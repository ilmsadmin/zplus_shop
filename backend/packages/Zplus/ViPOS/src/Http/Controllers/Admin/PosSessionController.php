<?php

namespace Zplus\ViPOS\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Zplus\ViPOS\Models\PosSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class PosSessionController extends Controller
{
    /**
     * Display POS sessions.
     */
    public function index()
    {
        $sessions = PosSession::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('vipos::admin.sessions.index', compact('sessions'));
    }

    /**
     * Open new POS session.
     */
    public function open(Request $request)
    {
        try {
            // Debug authentication
            if (!Auth::guard('admin')->check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Người dùng chưa được xác thực. Vui lòng đăng nhập lại.'
                ], 401);
            }

            $userId = Auth::guard('admin')->id();
            if (!$userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể lấy thông tin người dùng. Vui lòng đăng nhập lại.'
                ], 401);
            }

            $validator = Validator::make($request->all(), [
                'opening_balance' => 'required|numeric|min:0',
                'notes' => 'nullable|string|max:500'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Check if user already has an open session
            $existingSession = PosSession::where('user_id', $userId)
                ->where('status', 'open')
                ->first();

            if ($existingSession) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn đã có một phiên giao dịch đang mở. Vui lòng đóng phiên hiện tại trước khi mở phiên mới.'
                ], 400);
            }

            // Create new session
            $session = PosSession::create([
                'user_id' => $userId,
                'store_id' => 1, // Default store - should be configurable
                'opening_balance' => $request->opening_balance,
                'total_sales' => 0,
                'total_cash' => $request->opening_balance,
                'total_card' => 0,
                'total_other' => 0,
                'transaction_count' => 0,
                'opened_at' => Carbon::now(),
                'notes' => $request->notes,
                'status' => 'open',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Phiên giao dịch đã được mở thành công',
                'session' => $session
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi mở phiên giao dịch: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Close POS session.
     */
    public function close($id, Request $request)
    {
        try {
            if (!Auth::guard('admin')->check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Người dùng chưa được xác thực'
                ], 401);
            }

            $userId = Auth::guard('admin')->id();
            if (!$userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể lấy thông tin người dùng'
                ], 401);
            }

            $validator = Validator::make($request->all(), [
                'closing_balance' => 'required|numeric|min:0',
                'notes' => 'nullable|string|max:500'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $session = PosSession::where('id', $id)
                ->where('user_id', $userId)
                ->where('status', 'open')
                ->first();

            if (!$session) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy phiên giao dịch hoặc phiên đã được đóng'
                ], 404);
            }

            // Calculate totals from transactions
            $totalSales = $session->transactions()->sum('total_amount');
            $totalCash = $session->transactions()->where('payment_method', 'cash')->sum('total_amount');
            $totalCard = $session->transactions()->where('payment_method', 'card')->sum('total_amount');
            $totalOther = $session->transactions()->whereNotIn('payment_method', ['cash', 'card'])->sum('total_amount');
            $transactionCount = $session->transactions()->count();

            // Update session
            $session->update([
                'closing_balance' => $request->closing_balance,
                'total_sales' => $totalSales,
                'total_cash' => $totalCash + $session->opening_balance,
                'total_card' => $totalCard,
                'total_other' => $totalOther,
                'transaction_count' => $transactionCount,
                'closed_at' => Carbon::now(),
                'notes' => $request->notes,
                'status' => 'closed',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Phiên giao dịch đã được đóng thành công',
                'session' => $session
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi đóng phiên giao dịch: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get current active session.
     */
    public function getCurrent()
    {
        try {
            if (!Auth::guard('admin')->check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Người dùng chưa được xác thực'
                ], 401);
            }

            $userId = Auth::guard('admin')->id();
            if (!$userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể lấy thông tin người dùng'
                ], 401);
            }

            $session = PosSession::where('user_id', $userId)
                ->where('status', 'open')
                ->with('user')
                ->first();

            return response()->json([
                'success' => true,
                'session' => $session
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy thông tin phiên: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show session details.
     */
    public function show($id)
    {
        $session = PosSession::with(['user', 'transactions', 'cashMovements'])
            ->findOrFail($id);

        return view('vipos::admin.sessions.show', compact('session'));
    }
}
