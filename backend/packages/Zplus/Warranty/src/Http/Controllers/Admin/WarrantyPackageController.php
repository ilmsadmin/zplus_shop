<?php

namespace Zplus\Warranty\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Webkul\Admin\Http\Controllers\Controller;
use Zplus\Warranty\Models\WarrantyPackage;
use Illuminate\Support\Str;

class WarrantyPackageController extends Controller
{
    /**
     * Display a listing of the warranty packages.
     */
    public function index(): View
    {
        return view('warranty::admin.packages.index');
    }

    /**
     * Show the form for creating a new warranty package.
     */
    public function create(): View
    {
        return view('warranty::admin.packages.create');
    }

    /**
     * Store a newly created warranty package in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'duration_months' => 'required|integer|min:1|max:60',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $package = WarrantyPackage::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'duration_months' => $request->duration_months,
            'description' => $request->description,
            'price' => $request->price ?? 0,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return response()->json([
            'message' => 'Warranty package created successfully',
            'package' => $package
        ]);
    }

    /**
     * Display the specified warranty package.
     */
    public function show(int $id): View
    {
        $package = WarrantyPackage::with('warranties')->findOrFail($id);
        
        return view('warranty::admin.packages.show', compact('package'));
    }

    /**
     * Show the form for editing the specified warranty package.
     */
    public function edit(int $id): View
    {
        $package = WarrantyPackage::findOrFail($id);
        
        return view('warranty::admin.packages.edit', compact('package'));
    }

    /**
     * Update the specified warranty package in storage.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $package = WarrantyPackage::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'duration_months' => 'required|integer|min:1|max:60',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $package->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'duration_months' => $request->duration_months,
            'description' => $request->description,
            'price' => $request->price ?? 0,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return response()->json([
            'message' => 'Warranty package updated successfully',
            'package' => $package
        ]);
    }

    /**
     * Remove the specified warranty package from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $package = WarrantyPackage::findOrFail($id);
        
        // Check if package has any warranties
        if ($package->warranties()->count() > 0) {
            return response()->json([
                'message' => 'Cannot delete package that has warranties'
            ], 422);
        }
        
        $package->delete();

        return response()->json([
            'message' => 'Warranty package deleted successfully'
        ]);
    }

    /**
     * Get warranty packages data for DataGrid.
     */
    public function getPackages(Request $request): JsonResponse
    {
        $query = WarrantyPackage::query();

        // Search filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        $packages = $query->withCount('warranties')
            ->orderBy('duration_months')
            ->paginate($request->get('limit', 10));

        return response()->json($packages);
    }

    /**
     * Toggle package status.
     */
    public function toggleStatus(int $id): JsonResponse
    {
        $package = WarrantyPackage::findOrFail($id);
        $package->update(['is_active' => !$package->is_active]);

        return response()->json([
            'message' => 'Package status updated successfully',
            'package' => $package
        ]);
    }
}