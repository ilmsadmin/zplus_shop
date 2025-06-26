<?php

namespace Zplus\ViPOS\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Product\Models\Product;
use Webkul\Customer\Models\Customer;
use Webkul\Customer\Models\CustomerGroup;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\Customer\Repositories\CustomerGroupRepository;
use Webkul\Core\Repositories\ChannelRepository;
use Webkul\Sales\Models\Order;
use Webkul\Category\Models\Category;
use Zplus\ViPOS\Models\PosSession;
use Zplus\ViPOS\Models\PosTransaction;
use Zplus\ViPOS\Models\PosTransactionItem;
use Zplus\ViPOS\Models\PosCashMovement;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Webkul\Core\Rules\PhoneNumber;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Zplus\ViPOS\Services\BagistoOrderService;

class PosTransactionController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        protected ChannelRepository $channelRepository,
        protected CustomerRepository $customerRepository,
        protected CustomerGroupRepository $customerGroupRepository,
        protected BagistoOrderService $bagistoOrderService
    ) {}

    /**
     * Display POS transactions.
     */
    public function index()
    {
        $transactions = PosTransaction::with(['user', 'customer', 'session'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('vipos::admin.transactions.index', compact('transactions'));
    }

    /**
     * Process checkout.
     */
    public function checkout(Request $request)
    {
        try {
            $request->validate([
                'items' => 'required|array|min:1',
                'items.*.product_id' => 'required|exists:products,id',
                'items.*.quantity' => 'required|integer|min:1',
                'items.*.price' => 'required|numeric|min:0',
                'items.*.name' => 'required|string|max:255',
                'customer_id' => 'nullable|exists:customers,id',
                'payment_method' => 'required|string|in:cash,card,bank_transfer,other',
                'subtotal' => 'required|numeric|min:0',
                'discount_amount' => 'nullable|numeric|min:0',
                'discount_percentage' => 'nullable|numeric|min:0|max:100',
                'tax_amount' => 'nullable|numeric|min:0',
                'total' => 'required|numeric|min:0',
                'paid_amount' => 'required|numeric|min:0',
                'change_amount' => 'nullable|numeric|min:0',
                'notes' => 'nullable|string|max:500'
            ]);

            // Check if user has active session
            $session = PosSession::where('user_id', Auth::guard('admin')->id())
                ->where('status', 'open')
                ->first();

            if (!$session) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy phiên giao dịch đang mở. Vui lòng mở phiên giao dịch trước khi thực hiện thanh toán.'
                ], 400);
            }

            DB::beginTransaction();

            // Create POS transaction
            $transaction = PosTransaction::create([
                'pos_session_id' => $session->id,
                'user_id' => Auth::guard('admin')->id(),
                'customer_id' => $request->customer_id,
                'payment_method' => $request->payment_method,
                'subtotal_amount' => $request->subtotal,
                'discount_amount' => $request->discount_amount ?? 0,
                'discount_percentage' => $request->discount_percentage ?? 0,
                'tax_amount' => $request->tax_amount ?? 0,
                'total_amount' => $request->total,
                'paid_amount' => $request->paid_amount,
                'change_amount' => $request->change_amount ?? 0,
                'status' => 'completed',
                'completed_at' => Carbon::now(),
                'notes' => $request->notes,
                'items' => $request->items
            ]);

            // Create transaction items
            foreach ($request->items as $item) {
                // Get product to retrieve SKU
                $product = Product::find($item['product_id']);
                
                PosTransactionItem::create([
                    'pos_transaction_id' => $transaction->id,
                    'product_id' => $item['product_id'],
                    'product_sku' => $product->sku ?? '',
                    'product_name' => $item['name'] ?? '',
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['quantity'] * $item['price']
                ]);
            }

            // Create cash movement for the transaction
            PosCashMovement::create([
                'pos_session_id' => $session->id,
                'user_id' => Auth::guard('admin')->id(),
                'pos_transaction_id' => $transaction->id,
                'amount' => $request->total,
                'type' => 'sale',
                'reference' => $transaction->transaction_number,
                'description' => 'Bán hàng POS',
                'movement_at' => Carbon::now()
            ]);

            // Create corresponding order in Bagisto
            try {
                $bagistoOrder = $this->bagistoOrderService->createOrderFromPosTransaction($transaction);
                
                if ($bagistoOrder) {
                    Log::info('Bagisto order created successfully', [
                        'pos_transaction_id' => $transaction->id,
                        'bagisto_order_id' => $bagistoOrder->id,
                        'order_increment_id' => $bagistoOrder->increment_id
                    ]);
                } else {
                    Log::warning('Failed to create Bagisto order', [
                        'pos_transaction_id' => $transaction->id
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Error creating Bagisto order', [
                    'pos_transaction_id' => $transaction->id,
                    'error' => $e->getMessage()
                ]);
                // Continue with POS transaction success even if Bagisto order creation fails
            }

            DB::commit();

            // Reload transaction to get the updated bagisto_order_id
            $transaction->refresh();

            $message = 'Giao dịch đã được hoàn thành thành công';
            if ($transaction->bagisto_order_id) {
                $message .= '. Đã tạo đơn hàng trong hệ thống Bagisto.';
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'transaction' => [
                    'id' => $transaction->id,
                    'transaction_number' => $transaction->transaction_number,
                    'total_amount' => $transaction->total_amount,
                    'bagisto_order_id' => $transaction->bagisto_order_id,
                    'print_url' => route('admin.vipos.transactions.print', $transaction->id),
                    'download_url' => route('admin.vipos.transactions.download', $transaction->id)
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi xử lý thanh toán: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get products for POS.
     */
    public function getProducts(Request $request)
    {
        try {
            // Lấy tất cả sản phẩm đang hoạt động
            $query = Product::query();

            // Search functionality
            if ($request->has('search') && !empty($request->search)) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('sku', 'like', '%' . $search . '%');
                });
            }

            // Category filter - tạm thời bỏ qua do chưa có relationship
            // if ($request->has('category_id') && !empty($request->category_id)) {
            //     $query->whereHas('categories', function($c) use ($request) {
            //         $c->where('category_id', $request->category_id);
            //     });
            // }

            $products = $query->paginate($request->get('limit', 20));

            // Format products for POS interface
            $formattedProducts = $products->map(function($product) {
                try {
                    $typeInstance = $product->getTypeInstance();
                    
                    return [
                        'id' => $product->id,
                        'sku' => $product->sku,
                        'name' => $product->name ?? $product->sku,
                        'price' => $typeInstance ? $typeInstance->getMinimalPrice() : 0,
                        'image' => $product->base_image_url ?? null,
                        'quantity' => $typeInstance ? $typeInstance->totalQuantity() : 0,
                        'is_saleable' => $typeInstance ? $typeInstance->isSaleable() : false
                    ];
                } catch (\Exception $e) {
                    // Fallback nếu có lỗi với product instance
                    return [
                        'id' => $product->id,
                        'sku' => $product->sku,
                        'name' => $product->sku,
                        'price' => 0,
                        'image' => null,
                        'quantity' => 0,
                        'is_saleable' => false
                    ];
                }
            });

            return response()->json([
                'success' => true,
                'products' => $formattedProducts,
                'pagination' => [
                    'current_page' => $products->currentPage(),
                    'last_page' => $products->lastPage(),
                    'total' => $products->total()
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch products: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get categories for POS.
     */
    public function getCategories()
    {
        try {
            $categories = Category::with('translations')
                ->where('status', 1)
                ->orderBy('position')
                ->get()
                ->map(function($category) {
                    return [
                        'id' => $category->id,
                        'name' => $category->name ?? 'N/A',
                        'slug' => $category->slug ?? '',
                    ];
                });

            return response()->json([
                'success' => true,
                'categories' => $categories
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch categories: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search customers.
     */
    public function searchCustomers(Request $request)
    {
        try {
            $query = Customer::query();

            if ($request->has('search') && !empty($request->search)) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('first_name', 'like', '%' . $search . '%')
                      ->orWhere('last_name', 'like', '%' . $search . '%')
                      ->orWhere('email', 'like', '%' . $search . '%')
                      ->orWhere('phone', 'like', '%' . $search . '%');
                });
            }

            $customers = $query->limit(20)->get();

            $formattedCustomers = $customers->map(function($customer) {
                return [
                    'id' => $customer->id,
                    'name' => $customer->first_name . ' ' . $customer->last_name,
                    'email' => $customer->email,
                    'phone' => $customer->phone ?? 'N/A'
                ];
            });

            return response()->json([
                'success' => true,
                'customers' => $formattedCustomers
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to search customers: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Quick create customer.
     */
    public function quickCreateCustomer(Request $request)
    {
        try {
            // Use same validation rules as Bagisto admin customer creation
            $this->validate($request, [
                'first_name'    => 'string|required',
                'last_name'     => 'string|required',
                'gender'        => 'required',
                'email'         => 'required|unique:customers,email',
                'date_of_birth' => 'nullable|date|before:today',
                'phone'         => ['nullable', 'unique:customers,phone', new PhoneNumber],
            ]);

            $password = rand(100000, 10000000);

            Event::dispatch('customer.registration.before');

            $data = array_merge([
                'password'    => bcrypt($password),
                'is_verified' => 1,
                'channel_id'  => core()->getCurrentChannel()->id,
            ], $request->only([
                'first_name',
                'last_name',
                'gender',
                'email',
                'date_of_birth',
                'phone',
                'customer_group_id',
            ]));

            if (empty($data['phone'])) {
                $data['phone'] = null;
            }

            Event::dispatch('customer.create.before');

            $customer = $this->customerRepository->create($data);

            Event::dispatch('customer.create.after', $customer);

            return response()->json([
                'success' => true,
                'message' => 'Customer created successfully',
                'customer' => [
                    'id' => $customer->id,
                    'name' => $customer->first_name . ' ' . $customer->last_name,
                    'email' => $customer->email,
                    'phone' => $customer->phone ?? 'N/A'
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create customer: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Print receipt for a transaction.
     */
    public function printReceipt($id)
    {
        $transaction = PosTransaction::with(['user', 'customer', 'session', 'items'])
            ->findOrFail($id);

        return view('vipos::admin.transactions.receipt', compact('transaction'));
    }

    /**
     * Download receipt PDF for a transaction.
     */
    public function downloadReceipt($id)
    {
        $transaction = PosTransaction::with(['user', 'customer', 'session', 'items'])
            ->findOrFail($id);

        $pdf = Pdf::loadView('vipos::admin.transactions.receipt', compact('transaction'));
        $pdf->setPaper('a4', 'portrait');

        $fileName = 'receipt-' . $transaction->transaction_number . '.pdf';
        
        return $pdf->download($fileName);
    }

    /**
     * Get transaction details for AJAX.
     */
    public function getTransactionDetails($id)
    {
        try {
            $transaction = PosTransaction::with(['user', 'customer', 'session', 'items', 'bagistoOrder'])
                ->findOrFail($id);

            // Get items from relationship, fallback to stored items array
            $transactionItems = $transaction->items()->get();
            if ($transactionItems->isEmpty() && is_array($transaction->items)) {
                // Use stored items array if relationship is empty
                $itemsData = collect($transaction->items)->map(function($item) {
                    return [
                        'name' => $item['name'] ?? 'N/A',
                        'quantity' => $item['quantity'] ?? 0,
                        'price' => $item['price'] ?? 0,
                        'total' => ($item['quantity'] ?? 0) * ($item['price'] ?? 0)
                    ];
                });
            } else {
                // Use relationship items
                $itemsData = $transactionItems->map(function($item) {
                    return [
                        'name' => $item->product_name,
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                        'total' => $item->total
                    ];
                });
            }

            return response()->json([
                'success' => true,
                'transaction' => [
                    'id' => $transaction->id,
                    'transaction_number' => $transaction->transaction_number,
                    'user' => $transaction->user->name ?? 'N/A',
                    'customer' => $transaction->customer ? 
                        $transaction->customer->first_name . ' ' . $transaction->customer->last_name : 
                        'Khách lẻ',
                    'payment_method' => $transaction->payment_method,
                    'total_amount' => $transaction->total_amount,
                    'status' => $transaction->status,
                    'created_at' => $transaction->created_at->format('d/m/Y H:i'),
                    'bagisto_order_id' => $transaction->bagisto_order_id,
                    'bagisto_order' => $transaction->bagistoOrder ? [
                        'id' => $transaction->bagistoOrder->id,
                        'increment_id' => $transaction->bagistoOrder->increment_id,
                        'status' => $transaction->bagistoOrder->status,
                        'grand_total' => $transaction->bagistoOrder->grand_total
                    ] : null,
                    'items' => $itemsData,
                    'summary' => [
                        'subtotal' => $transaction->subtotal_amount ?? $transaction->subtotal,
                        'discount' => $transaction->discount_amount,
                        'tax' => $transaction->tax_amount,
                        'total' => $transaction->total_amount,
                        'paid_amount' => $transaction->paid_amount,
                        'change_amount' => $transaction->change_amount
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy giao dịch: ' . $e->getMessage()
            ], 404);
        }
    }
}
