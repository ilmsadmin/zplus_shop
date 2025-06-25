<?php

namespace Zplus\ViPOS\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Webkul\Category\Models\Category;
use Illuminate\Support\ViewErrorBag;
use Zplus\ViPOS\Http\Controllers\Admin\PosDashboardController;

class PosController extends Controller
{
    /**
     * Display POS dashboard in fullscreen mode by default.
     */
    public function index()
    {
        // Ensure $errors is available in the view
        if (!session()->has('errors')) {
            session()->flash('errors', new ViewErrorBag());
        }
        
        $categories = Category::with('translations')
            ->where('status', 1)
            ->orderBy('position')
            ->get();
            
        return view('vipos::admin.pos.fullscreen', compact('categories'));
    }
    
    /**
     * Display POS dashboard.
     */
    public function dashboard()
    {
        // Use the dedicated dashboard controller
        $dashboardController = new PosDashboardController();
        return $dashboardController->index();
    }
    
    /**
     * Display POS fullscreen interface.
     */
    public function fullscreen()
    {
        // Ensure $errors is available in the view
        if (!session()->has('errors')) {
            session()->flash('errors', new ViewErrorBag());
        }
        
        $categories = Category::with('translations')
            ->where('status', 1)
            ->orderBy('position')
            ->get();
            
        return view('vipos::admin.pos.fullscreen', compact('categories'));
    }
}
