<?php

namespace Zplus\ViPOS\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Webkul\Customer\Models\Customer;
use Webkul\Product\Models\Product;
use Zplus\ViPOS\Models\PosSession;
use Zplus\ViPOS\Models\PosTransaction;

class PosDashboardController extends Controller
{
    /**
     * Display ViPOS dashboard with statistics.
     */
    public function index()
    {
        // Get dashboard statistics
        $stats = [
            'today' => $this->getTodayStats(),
            'week' => $this->getWeekStats(),
            'month' => $this->getMonthStats(),
            'current_session' => $this->getCurrentSessionStats(),
            'recent_transactions' => $this->getRecentTransactions(),
            'top_products' => $this->getTopProducts(),
            'sales_chart' => $this->getSalesChartData(),
        ];

        return view('vipos::admin.index', compact('stats'));
    }

    /**
     * Get today's statistics.
     */
    protected function getTodayStats(): array
    {
        $today = Carbon::today();
        
        $todayTransactions = PosTransaction::whereDate('created_at', $today)
            ->where('status', 'completed');

        $todaySales = $todayTransactions->sum('total_amount');
        $todayCount = $todayTransactions->count();
        $todayCustomers = $todayTransactions->distinct('customer_id')->count('customer_id');

        // Compare with yesterday
        $yesterday = Carbon::yesterday();
        $yesterdayTransactions = PosTransaction::whereDate('created_at', $yesterday)
            ->where('status', 'completed');
        
        $yesterdayCount = $yesterdayTransactions->count();
        $yesterdaySales = $yesterdayTransactions->sum('total_amount');

        return [
            'sales' => [
                'amount' => $todaySales,
                'formatted' => number_format($todaySales, 0, ',', '.') . ' VND',
                'change' => $yesterdaySales > 0 ? (($todaySales - $yesterdaySales) / $yesterdaySales) * 100 : 0,
            ],
            'transactions' => [
                'count' => $todayCount,
                'change' => $yesterdayCount > 0 ? (($todayCount - $yesterdayCount) / $yesterdayCount) * 100 : 0,
            ],
            'customers' => [
                'count' => $todayCustomers,
                'change' => 0, // Can implement comparison if needed
            ],
            'avg_order' => [
                'amount' => $todayCount > 0 ? $todaySales / $todayCount : 0,
                'formatted' => $todayCount > 0 ? number_format($todaySales / $todayCount, 0, ',', '.') . ' VND' : '0 VND',
            ],
        ];
    }

    /**
     * Get this week's statistics.
     */
    protected function getWeekStats(): array
    {
        $weekStart = Carbon::now()->startOfWeek();
        $weekEnd = Carbon::now()->endOfWeek();
        
        $weekTransactions = PosTransaction::whereBetween('created_at', [$weekStart, $weekEnd])
            ->where('status', 'completed');

        $weekSales = $weekTransactions->sum('total_amount');
        $weekCount = $weekTransactions->count();

        return [
            'sales' => [
                'amount' => $weekSales,
                'formatted' => number_format($weekSales, 0, ',', '.') . ' VND',
            ],
            'transactions' => [
                'count' => $weekCount,
            ],
        ];
    }

    /**
     * Get this month's statistics.
     */
    protected function getMonthStats(): array
    {
        $monthStart = Carbon::now()->startOfMonth();
        $monthEnd = Carbon::now()->endOfMonth();
        
        $monthTransactions = PosTransaction::whereBetween('created_at', [$monthStart, $monthEnd])
            ->where('status', 'completed');

        $monthSales = $monthTransactions->sum('total_amount');
        $monthCount = $monthTransactions->count();

        return [
            'sales' => [
                'amount' => $monthSales,
                'formatted' => number_format($monthSales, 0, ',', '.') . ' VND',
            ],
            'transactions' => [
                'count' => $monthCount,
            ],
        ];
    }

    /**
     * Get current session statistics.
     */
    protected function getCurrentSessionStats(): ?array
    {
        $currentSession = PosSession::where('user_id', Auth::guard('admin')->id())
            ->where('status', 'open')
            ->latest()
            ->first();

        if (!$currentSession) {
            return null;
        }

        $sessionTransactions = $currentSession->transactions()
            ->where('status', 'completed');

        $sessionSales = $sessionTransactions->sum('total_amount');
        $sessionCount = $sessionTransactions->count();
        $cashSales = $sessionTransactions->where('payment_method', 'cash')->sum('total_amount');

        return [
            'session' => $currentSession,
            'sales' => [
                'amount' => $sessionSales,
                'formatted' => number_format($sessionSales, 0, ',', '.') . ' VND',
            ],
            'transactions' => [
                'count' => $sessionCount,
            ],
            'cash_on_hand' => [
                'amount' => $currentSession->opening_balance + $cashSales,
                'formatted' => number_format($currentSession->opening_balance + $cashSales, 0, ',', '.') . ' VND',
            ],
            'duration' => $currentSession->opened_at->diffForHumans(),
        ];
    }

    /**
     * Get recent transactions.
     */
    protected function getRecentTransactions(): array
    {
        $transactions = PosTransaction::with(['customer', 'user'])
            ->where('status', 'completed')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return $transactions->map(function ($transaction) {
            return [
                'id' => $transaction->id,
                'transaction_number' => $transaction->transaction_number,
                'customer_name' => $transaction->customer ? $transaction->customer->name : 'Khách vãng lai',
                'total_amount' => $transaction->total_amount,
                'formatted_total' => number_format($transaction->total_amount, 0, ',', '.') . ' VND',
                'payment_method' => $transaction->payment_method,
                'created_at' => $transaction->created_at->format('d/m/Y H:i'),
                'time_ago' => $transaction->created_at->diffForHumans(),
            ];
        })->toArray();
    }

    /**
     * Get top selling products.
     */
    protected function getTopProducts(): array
    {
        $weekStart = Carbon::now()->startOfWeek();
        
        // Get top products from completed transactions this week
        $topProducts = DB::table('pos_transactions')
            ->join('pos_transaction_items', 'pos_transactions.id', '=', 'pos_transaction_items.pos_transaction_id')
            ->join('products', 'pos_transaction_items.product_id', '=', 'products.id')
            ->join('product_flat', function($join) {
                $join->on('products.id', '=', 'product_flat.product_id')
                     ->where('product_flat.locale', 'en')
                     ->where('product_flat.channel', 'default');
            })
            ->where('pos_transactions.status', 'completed')
            ->where('pos_transactions.created_at', '>=', $weekStart)
            ->select(
                'products.id',
                'product_flat.name',
                'products.sku',
                DB::raw('SUM(pos_transaction_items.quantity) as total_quantity'),
                DB::raw('SUM(pos_transaction_items.total) as total_revenue')
            )
            ->groupBy('products.id', 'product_flat.name', 'products.sku')
            ->orderByDesc('total_quantity')
            ->limit(5)
            ->get();

        return $topProducts->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name ?: $product->sku,
                'sku' => $product->sku,
                'quantity' => $product->total_quantity,
                'revenue' => $product->total_revenue,
                'formatted_revenue' => number_format($product->total_revenue, 0, ',', '.') . ' VND',
            ];
        })->toArray();
    }

    /**
     * Get sales chart data for the last 7 days.
     */
    protected function getSalesChartData(): array
    {
        $days = [];
        $sales = [];
        $transactions = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dayTransactions = PosTransaction::whereDate('created_at', $date)
                ->where('status', 'completed');

            $days[] = $date->format('d/m');
            $sales[] = $dayTransactions->sum('total_amount');
            $transactions[] = $dayTransactions->count();
        }

        return [
            'labels' => $days,
            'sales' => $sales,
            'transactions' => $transactions,
        ];
    }

    /**
     * Get general statistics for dashboard cards.
     */
    protected function getGeneralStats(): array
    {
        return [
            'total_products' => Product::where('status', 1)->count(),
            'total_customers' => Customer::count(),
            'active_sessions' => PosSession::where('status', 'open')->count(),
            'total_sessions_today' => PosSession::whereDate('opened_at', Carbon::today())->count(),
        ];
    }

    /**
     * API endpoint to get dashboard stats.
     */
    public function getStats(Request $request)
    {
        $type = $request->get('type', 'overview');

        switch ($type) {
            case 'today':
                return response()->json($this->getTodayStats());
            case 'week':
                return response()->json($this->getWeekStats());
            case 'month':
                return response()->json($this->getMonthStats());
            case 'current_session':
                return response()->json($this->getCurrentSessionStats());
            case 'chart':
                return response()->json($this->getSalesChartData());
            case 'overview':
            default:
                return response()->json([
                    'today' => $this->getTodayStats(),
                    'week' => $this->getWeekStats(),
                    'current_session' => $this->getCurrentSessionStats(),
                    'general' => $this->getGeneralStats(),
                    'chart' => $this->getSalesChartData(),
                ]);
        }
    }
}
