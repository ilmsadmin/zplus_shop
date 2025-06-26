<?php

namespace Zplus\Warranty\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use App\Http\Controllers\Controller;
use Zplus\Warranty\Models\Warranty;

class WarrantySearchController extends Controller
{
    /**
     * Display the warranty search page.
     */
    public function index(): View
    {
        return view('warranty::frontend.search.index');
    }

    /**
     * Search warranties by serial number or customer phone.
     */
    public function search(Request $request): JsonResponse
    {
        $request->validate([
            'query' => 'required|string|min:3',
        ]);

        $query = trim($request->query);
        
        $warranties = Warranty::with(['warrantyPackage', 'product', 'customer'])
            ->where(function($q) use ($query) {
                $q->where('product_serial', 'like', "%{$query}%")
                  ->orWhere('customer_phone', 'like', "%{$query}%")
                  ->orWhere('warranty_number', 'like', "%{$query}%");
            })
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($warranty) {
                return [
                    'id' => $warranty->id,
                    'warranty_number' => $warranty->warranty_number,
                    'product_name' => $warranty->product_name,
                    'product_serial' => $warranty->product_serial,
                    'customer_name' => $warranty->customer_name,
                    'customer_phone' => $warranty->customer_phone,
                    'package_name' => $warranty->warrantyPackage->name ?? 'N/A',
                    'start_date' => $warranty->start_date->format('d/m/Y'),
                    'end_date' => $warranty->end_date->format('d/m/Y'),
                    'status' => $warranty->status,
                    'status_text' => $warranty->status_text,
                    'is_active' => $warranty->isActive(),
                    'is_expired' => $warranty->isExpired(),
                    'remaining_days' => $warranty->remaining_days,
                    'purchase_date' => $warranty->purchase_date->format('d/m/Y'),
                ];
            });

        return response()->json([
            'success' => true,
            'warranties' => $warranties,
            'total' => $warranties->count()
        ]);
    }
}