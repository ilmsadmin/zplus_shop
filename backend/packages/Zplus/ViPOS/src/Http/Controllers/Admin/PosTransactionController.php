<?php

namespace Zplus\ViPOS\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Webkul\Product\Models\Product;
use Webkul\Customer\Models\Customer;
use Webkul\Customer\Models\CustomerGroup;
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
use Carbon\Carbon;

class PosTransactionController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        protected ChannelRepository $channelRepository
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

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Giao dịch đã được hoàn thành thành công',
                'transaction' => $transaction
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
            $validator = Validator::make($request->all(), [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|unique:customers,email',
                'phone' => 'nullable|string|max:20',
                'gender' => 'required|in:Male,Female,Other',
                'date_of_birth' => 'nullable|date'
            ], [
                'first_name.required' => 'Tên là bắt buộc',
                'last_name.required' => 'Họ là bắt buộc',
                'email.required' => 'Email là bắt buộc',
                'email.email' => 'Email không đúng định dạng',
                'email.unique' => 'Email này đã được sử dụng',
                'gender.required' => 'Giới tính là bắt buộc'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dữ liệu không hợp lệ',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Get default customer group
            $defaultGroup = CustomerGroup::where('code', 'general')->first();
            if (!$defaultGroup) {
                $defaultGroup = CustomerGroup::first();
            }

            // Create customer without password (system will handle password generation)
            $customer = Customer::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'gender' => $request->gender,
                'date_of_birth' => $request->date_of_birth,
                'password' => Hash::make(str()->random(10)), // Auto-generate random password
                'customer_group_id' => $defaultGroup->id ?? 1,
                'channel_id' => $this->channelRepository->getCurrentChannelId(),
                'is_verified' => 1
            ]);

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

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create customer: ' . $e->getMessage()
            ], 500);
        }
    }
}
