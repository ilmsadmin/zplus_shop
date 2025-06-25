<x-admin::layouts>
    <x-slot:title>
        ViPOS - Giao dịch
    </x-slot>

    <!-- Custom CSS -->
    @pushOnce('styles')
        <link rel="stylesheet" href="{{ asset('packages/Zplus/ViPOS/assets/css/transactions.css') }}">
    @endPushOnce

    <div class="vipos-transactions-page">
        <!-- Header Section -->
        <div class="transactions-header">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-white">
                        <svg class="w-8 h-8 inline-block mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                        </svg>
                        Giao dịch POS
                    </h1>
                    <p class="text-indigo-100">Quản lý và theo dõi tất cả giao dịch bán hàng</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('admin.vipos.index') }}" class="action-button">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/>
                        </svg>
                        Mở POS
                    </a>
                    <button onclick="exportTransactions()" class="action-button">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                        Xuất Excel
                    </button>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="transactions-stats">
            <div class="stat-card">
                <div class="stat-icon">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="stat-value">{{ number_format($transactions->where('status', 'completed')->sum('total_amount')) }}đ</div>
                <div class="stat-label">Tổng doanh thu</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="stat-value">{{ $transactions->where('status', 'completed')->count() }}</div>
                <div class="stat-label">Giao dịch hoàn thành</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="stat-value">{{ $transactions->filter(function($transaction) { return $transaction->created_at->isToday(); })->count() }}</div>
                <div class="stat-label">Giao dịch hôm nay</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4z"/>
                        <path d="M6 6a2 2 0 012-2h8a2 2 0 012 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2V6z"/>
                    </svg>
                </div>
                <div class="stat-value">{{ number_format($transactions->where('status', 'completed')->avg('total_amount') ?? 0) }}đ</div>
                <div class="stat-label">Giá trị trung bình</div>
            </div>
        </div>

        <!-- Transactions Table -->
        @if($transactions->count())
            <div class="transactions-table-container">
                <table class="transactions-table">
                    <thead>
                        <tr>
                            <th>Mã giao dịch</th>
                            <th>Người bán</th>
                            <th>Khách hàng</th>
                            <th>Phương thức TT</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Thời gian</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $transaction)
                            <tr>
                                <td>
                                    <span class="transaction-id">{{ $transaction->transaction_number }}</span>
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-semibold text-sm">
                                            {{ substr($transaction->user->name ?? 'N', 0, 1) }}
                                        </div>
                                        {{ $transaction->user->name ?? 'N/A' }}
                                    </div>
                                </td>
                                <td>
                                    @if($transaction->customer)
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center text-green-600 font-semibold text-sm">
                                                {{ substr($transaction->customer->first_name, 0, 1) }}
                                            </div>
                                            {{ $transaction->customer->first_name }} {{ $transaction->customer->last_name }}
                                        </div>
                                    @else
                                        <span class="text-gray-500 italic flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                            </svg>
                                            Khách lẻ
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @switch($transaction->payment_method)
                                        @case('cash')
                                            <span class="payment-badge cash">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4z"/>
                                                    <path d="M6 6a2 2 0 012-2h8a2 2 0 012 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2V6z"/>
                                                </svg>
                                                Tiền mặt
                                            </span>
                                            @break
                                        @case('card')
                                            <span class="payment-badge card">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4z"/>
                                                    <path d="M6 6a2 2 0 012-2h8a2 2 0 012 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2V6z"/>
                                                </svg>
                                                Thẻ
                                            </span>
                                            @break
                                        @case('bank_transfer')
                                            <span class="payment-badge bank">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm0 2v2h12V6H4zm0 4v6h12v-6H4z" clip-rule="evenodd"/>
                                                </svg>
                                                Chuyển khoản
                                            </span>
                                            @break
                                        @default
                                            <span class="payment-badge">{{ ucfirst($transaction->payment_method) }}</span>
                                    @endswitch
                                </td>
                                <td>
                                    <span class="amount-value">{{ number_format($transaction->total_amount) }}đ</span>
                                </td>
                                <td>
                                    @if($transaction->status === 'completed')
                                        <span class="status-badge completed">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            Hoàn thành
                                        </span>
                                    @elseif($transaction->status === 'pending')
                                        <span class="status-badge pending">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                            </svg>
                                            Đang xử lý
                                        </span>
                                    @else
                                        <span class="status-badge cancelled">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                            </svg>
                                            Đã hủy
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="text-sm">
                                        <div class="font-semibold">{{ $transaction->completed_at ? $transaction->completed_at->format('d/m/Y') : $transaction->created_at->format('d/m/Y') }}</div>
                                        <div class="text-gray-500">{{ $transaction->completed_at ? $transaction->completed_at->format('H:i') : $transaction->created_at->format('H:i') }}</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <button 
                                            onclick="showTransactionDetails({{ $transaction->id }})"
                                            class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm hover:bg-blue-200 transition-colors duration-200"
                                            title="Xem chi tiết"
                                        >
                                            <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                            </svg>
                                            Chi tiết
                                        </button>
                                        @if($transaction->status === 'completed')
                                            <button 
                                                onclick="printReceipt({{ $transaction->id }})"
                                                class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm hover:bg-green-200 transition-colors duration-200"
                                                title="In hóa đơn"
                                            >
                                                <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z" clip-rule="evenodd"/>
                                                </svg>
                                                In
                                            </button>
                                            <button 
                                                onclick="downloadReceipt({{ $transaction->id }})"
                                                class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-sm hover:bg-purple-200 transition-colors duration-200"
                                                title="Tải xuống hóa đơn"
                                            >
                                                <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                </svg>
                                                Tải
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                
                @if($transactions->hasPages())
                    <div class="pagination-wrapper">
                        {{ $transactions->links() }}
                    </div>
                @endif
            </div>
        @else
            <div class="empty-state">
                <div class="empty-state-icon">
                    <svg class="w-12 h-12 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <h2>Chưa có giao dịch nào</h2>
                <p>
                    Chưa có giao dịch POS nào được thực hiện. Hãy mở POS để bắt đầu bán hàng và tạo giao dịch đầu tiên.
                </p>
                <a href="{{ route('admin.vipos.index') }}" class="action-button">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/>
                    </svg>
                    Mở POS ngay
                </a>
            </div>
        @endif
    </div>

    <!-- Transaction Details Modal -->
    <div id="transaction-modal" class="transaction-modal hidden">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z" clip-rule="evenodd"/>
                    </svg>
                    Chi tiết giao dịch
                </h3>
                <button class="modal-close" onclick="closeTransactionModal()">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
            <div class="modal-body">
                <div id="transaction-details">
                    <!-- Details will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <script>
    function showTransactionDetails(transactionId) {
        const modal = document.getElementById('transaction-modal');
        const detailsContainer = document.getElementById('transaction-details');
        
        // Show modal
        modal.classList.remove('hidden');
        
        // Show loading state
        detailsContainer.innerHTML = `
            <div class="text-center py-8">
                <div class="animate-spin w-8 h-8 border-4 border-blue-500 border-t-transparent rounded-full mx-auto mb-4"></div>
                <p class="text-gray-600">Đang tải chi tiết giao dịch...</p>
            </div>
        `;
        
        // Fetch transaction details via AJAX
        fetch(`/admin/vipos/transactions/${transactionId}/details`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displayTransactionDetails(data.transaction);
                } else {
                    detailsContainer.innerHTML = `
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 text-red-400 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <p class="text-gray-600">Không thể tải chi tiết giao dịch</p>
                        </div>
                    `;
                }
            })
            .catch(error => {
                detailsContainer.innerHTML = `
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 text-red-400 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <p class="text-gray-600">Lỗi khi tải chi tiết giao dịch</p>
                    </div>
                `;
            });
    }

    function displayTransactionDetails(data) {
        const detailsContainer = document.getElementById('transaction-details');
        
        detailsContainer.innerHTML = `
            <!-- Transaction Info Grid -->
            <div class="transaction-detail-grid">
                <div class="detail-card">
                    <div class="detail-label">Mã giao dịch</div>
                    <div class="detail-value transaction-id">${data.transaction_number}</div>
                </div>
                <div class="detail-card">
                    <div class="detail-label">Người bán</div>
                    <div class="detail-value flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                        </svg>
                        ${data.user}
                    </div>
                </div>
                <div class="detail-card">
                    <div class="detail-label">Khách hàng</div>
                    <div class="detail-value flex items-center gap-2">
                        <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"/>
                        </svg>
                        ${data.customer}
                    </div>
                </div>
                <div class="detail-card">
                    <div class="detail-label">Phương thức thanh toán</div>
                    <div class="detail-value flex items-center gap-2">
                        ${data.payment_method === 'cash' ? 
                            '<svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4z"/><path d="M6 6a2 2 0 012-2h8a2 2 0 012 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2V6z"/></svg> Tiền mặt' : 
                          data.payment_method === 'card' ? 
                            '<svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4z"/><path d="M6 6a2 2 0 012-2h8a2 2 0 012 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2V6z"/></svg> Thẻ' : 
                            '<svg class="w-4 h-4 text-purple-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm0 2v2h12V6H4zm0 4v6h12v-6H4z" clip-rule="evenodd"/></svg> Chuyển khoản'}
                    </div>
                </div>
                <div class="detail-card">
                    <div class="detail-label">Trạng thái</div>
                    <div class="detail-value">
                        <span class="status-badge ${data.status} flex items-center gap-1">
                            ${data.status === 'completed' ? 
                                '<svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> Hoàn thành' : 
                              data.status === 'pending' ? 
                                '<svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg> Đang xử lý' : 
                                '<svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg> Đã hủy'}
                        </span>
                    </div>
                </div>
                <div class="detail-card">
                    <div class="detail-label">Thời gian</div>
                    <div class="detail-value flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                        </svg>
                        ${data.created_at}
                    </div>
                </div>
            </div>

            <!-- Items Table -->
            <div class="mt-6">
                <h4 class="text-lg font-semibold mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"/>
                    </svg>
                    Sản phẩm đã mua
                </h4>
                <table class="items-table">
                    <thead>
                        <tr>
                            <th>Sản phẩm</th>
                            <th>Số lượng</th>
                            <th>Đơn giá</th>
                            <th>Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${data.items.map(item => `
                            <tr>
                                <td>${item.name}</td>
                                <td class="text-center">${item.quantity}</td>
                                <td>${new Intl.NumberFormat('vi-VN').format(item.price)}đ</td>
                                <td class="amount-value">${new Intl.NumberFormat('vi-VN').format(item.total)}đ</td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            </div>

            <!-- Summary -->
            <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                <h4 class="text-lg font-semibold mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/>
                    </svg>
                    Tổng kết thanh toán
                </h4>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span>Tạm tính:</span>
                        <span>${new Intl.NumberFormat('vi-VN').format(data.summary.subtotal)}đ</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Giảm giá:</span>
                        <span>${new Intl.NumberFormat('vi-VN').format(data.summary.discount)}đ</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Thuế:</span>
                        <span>${new Intl.NumberFormat('vi-VN').format(data.summary.tax)}đ</span>
                    </div>
                    <hr class="my-2">
                    <div class="flex justify-between text-lg font-bold">
                        <span>Tổng cộng:</span>
                        <span class="amount-value">${new Intl.NumberFormat('vi-VN').format(data.summary.total)}đ</span>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-6 flex gap-3 justify-end">
                <button onclick="printReceipt(${data.id})" class="action-button">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z" clip-rule="evenodd"/>
                    </svg>
                    In hóa đơn
                </button>
                <button onclick="downloadReceipt(${data.id})" class="action-button">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                    Tải xuống
                </button>
            </div>
        `;
    }

    function closeTransactionModal() {
        document.getElementById('transaction-modal').classList.add('hidden');
    }

    function exportTransactions() {
        alert('Tính năng xuất Excel đang được phát triển');
    }

    function printReceipt(transactionId) {
        // Mở hóa đơn trong tab mới để in
        const printWindow = window.open(`/admin/vipos/transactions/${transactionId}/print`, '_blank');
        
        // Tự động in khi trang đã load xong
        printWindow.onload = function() {
            printWindow.print();
        };
    }

    function downloadReceipt(transactionId) {
        // Chuyển hướng để tải xuống PDF
        window.location.href = `/admin/vipos/transactions/${transactionId}/download`;
    }

    // Close modal when clicking outside
    document.getElementById('transaction-modal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeTransactionModal();
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeTransactionModal();
        }
    });
    </script>
</x-admin::layouts>
