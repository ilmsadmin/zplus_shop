<?php

namespace Zplus\Warranty\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Customer\Models\Customer;
use Webkul\Product\Models\Product;
use Zplus\Warranty\Models\Warranty;
use Zplus\Warranty\Models\WarrantyPackage;
use Zplus\ViPOS\Models\PosTransaction;

class WarrantyController extends Controller
{
    /**
     * Display a listing of the warranties.
     */
    public function index(): View
    {
        return view('warranty::admin.warranties.index');
    }

    /**
     * Show the form for creating a new warranty.
     */
    public function create(): View
    {
        $warrantyPackages = WarrantyPackage::active()->orderBy('duration_months')->get();
        
        // Get products with proper Bagisto relationship
        $products = Product::with('attribute_values.attribute')
                          ->orderBy('id')
                          ->get()
                          ->map(function($product) {
                              return [
                                  'id' => $product->id,
                                  'name' => $product->name ?? "Product #{$product->id}",
                                  'sku' => $product->sku,
                              ];
                          });
        
        $customers = Customer::orderBy('first_name')->get();
        
        return view('warranty::admin.warranties.create', compact('warrantyPackages', 'products', 'customers'))
               ->with('errors', session('errors', new \Illuminate\Support\MessageBag()));
    }

    /**
     * Store a newly created warranty in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'warranty_package_id' => 'required|exists:warranty_packages,id',
            'product_id' => 'required|exists:products,id',
            'product_serial' => 'required|string|max:255',
            'customer_id' => 'required|exists:customers,id',
            'purchase_date' => 'required|date',
            'order_number' => 'nullable|string|max:255',
            'pos_transaction_id' => 'nullable|exists:pos_transactions,id',
            'notes' => 'nullable|string',
        ]);

        $warrantyPackage = WarrantyPackage::findOrFail($request->warranty_package_id);
        $product = Product::findOrFail($request->product_id);
        $customer = Customer::findOrFail($request->customer_id);

        // Calculate warranty end date
        $startDate = now();
        $endDate = $startDate->copy()->addMonths($warrantyPackage->duration_months);

        $warranty = Warranty::create([
            'warranty_package_id' => $warrantyPackage->id,
            'product_id' => $product->id,
            'product_serial' => $request->product_serial,
            'product_name' => $product->name,
            'product_sku' => $product->sku,
            'order_number' => $request->order_number,
            'pos_transaction_id' => $request->pos_transaction_id,
            'customer_id' => $customer->id,
            'customer_name' => $customer->first_name . ' ' . $customer->last_name,
            'customer_phone' => $customer->phone ?? '',
            'customer_email' => $customer->email,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'purchase_date' => $request->purchase_date,
            'status' => 'active',
            'notes' => $request->notes,
            'created_by' => auth()->guard('admin')->id(),
        ]);

        // If AJAX request, return JSON
        if ($request->ajax()) {
            return response()->json([
                'message' => 'Warranty created successfully',
                'warranty' => $warranty
            ]);
        }

        // If normal form submission, redirect with success message
        return redirect()->route('admin.warranty.index')
                         ->with('success', 'Bảo hành đã được tạo thành công!');
    }

    /**
     * Display the specified warranty.
     */
    public function show(string $id): View
    {
        $warranty = Warranty::with(['warrantyPackage', 'product', 'customer', 'posTransaction', 'createdBy'])
            ->findOrFail($id);
            
        return view('warranty::admin.warranties.show', compact('warranty'));
    }

    /**
     * Show the form for editing the specified warranty.
     */
    public function edit(string $id): View
    {
        $warranty = Warranty::with(['warrantyPackage', 'product', 'customer', 'posTransaction', 'createdBy'])
            ->findOrFail($id);
        $warrantyPackages = WarrantyPackage::active()->orderBy('duration_months')->get();
        $products = Product::orderBy('id')->get();
        $customers = Customer::orderBy('first_name')->get();
        
        return view('warranty::admin.warranties.edit', compact('warranty', 'warrantyPackages', 'products', 'customers'));
    }

    /**
     * Update the specified warranty in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $warranty = Warranty::findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:active,expired,claimed,cancelled',
            'notes' => 'nullable|string',
        ]);

        $warranty->update([
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        return response()->json([
            'message' => 'Warranty updated successfully',
            'warranty' => $warranty
        ]);
    }

    /**
     * Remove the specified warranty from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $warranty = Warranty::findOrFail($id);
        $warranty->delete();

        return response()->json([
            'message' => 'Warranty deleted successfully'
        ]);
    }

    /**
     * Get warranties data for DataGrid.
     */
    public function getWarranties(Request $request): JsonResponse
    {
        $query = Warranty::with(['warrantyPackage', 'product', 'customer']);

        // Search filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('warranty_number', 'like', "%{$search}%")
                  ->orWhere('product_serial', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $warranties = $query->orderBy('created_at', 'desc')
            ->paginate($request->get('limit', 10));

        // Transform the data to ensure proper serialization
        $transformedWarranties = $warranties->getCollection()->transform(function ($warranty) {
            return [
                'id' => $warranty->id,
                'warranty_number' => $warranty->warranty_number,
                'product_serial' => $warranty->product_serial,
                'product_name' => $warranty->product_name,
                'product_sku' => $warranty->product_sku,
                'customer_name' => $warranty->customer_name,
                'customer_phone' => $warranty->customer_phone,
                'customer_email' => $warranty->customer_email,
                'warranty_package' => $warranty->warrantyPackage ? [
                    'id' => $warranty->warrantyPackage->id,
                    'name' => $warranty->warrantyPackage->name,
                    'duration_months' => $warranty->warrantyPackage->duration_months,
                ] : null,
                'start_date' => $warranty->start_date,
                'end_date' => $warranty->end_date,
                'purchase_date' => $warranty->purchase_date,
                'status' => $warranty->status,
                'notes' => $warranty->notes,
                'created_at' => $warranty->created_at,
                'updated_at' => $warranty->updated_at,
            ];
        });

        $warranties->setCollection($transformedWarranties);

        return response()->json($warranties);
    }

    /**
     * Search warranties by serial number or customer phone.
     */
    public function search(Request $request): JsonResponse
    {
        $request->validate([
            'query' => 'required|string|min:3',
        ]);

        $query = $request->query;
        
        $warranties = Warranty::with(['warrantyPackage', 'product', 'customer'])
            ->where(function($q) use ($query) {
                $q->where('product_serial', 'like', "%{$query}%")
                  ->orWhere('customer_phone', 'like', "%{$query}%")
                  ->orWhere('warranty_number', 'like', "%{$query}%");
            })
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        return response()->json($warranties);
    }
}