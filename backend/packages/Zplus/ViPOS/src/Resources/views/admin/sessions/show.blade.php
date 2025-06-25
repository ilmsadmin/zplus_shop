<x-admin::layouts>
    <x-slot:title>
        ViPOS - Chi tiết phiên {{ $session->id }}
    </x-slot>

    <!-- Custom CSS -->
    @pushOnce('styles')
        <link rel="stylesheet" href="{{ asset('packages/Zplus/ViPOS/assets/css/sessions.css') }}">
    @endPushOnce

    <div class="vipos-sessions-page">
        <!-- Header Section -->
        <div class="sessions-header">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-white">
                        <svg class="w-8 h-8 inline-block mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Chi tiết phiên #{{ $session->id }}
                    </h1>
                    <p class="text-green-100">
                        <svg class="w-4 h-4 inline-block mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                        </svg>
                        {{ $session->opened_at ? $session->opened_at->format('d/m/Y H:i') : 'N/A' }} - 
                        {{ $session->closed_at ? $session->closed_at->format('d/m/Y H:i') : 'Đang hoạt động' }}
                    </p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('admin.vipos.sessions.index') }}" class="action-button secondary">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                        </svg>
                        Quay lại
                    </a>
                    
                    @if($session->status === 'open')
                        <button onclick="closeSession()" class="action-button danger">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                            Đóng phiên
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Session Overview Stats -->
        <div class="sessions-stats">
            <div class="stat-card">
                <div class="stat-icon">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="stat-value">{{ $session->transaction_count }}</div>
                <div class="stat-label">Tổng giao dịch</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="stat-value">{{ number_format($session->total_sales) }}đ</div>
                <div class="stat-label">Tổng doanh thu</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4z"/>
                        <path d="M6 8a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2V8z"/>
                    </svg>
                </div>
                <div class="stat-value">{{ number_format($session->total_cash) }}đ</div>
                <div class="stat-label">Tiền mặt</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    @if($session->status === 'open')
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    @else
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    @endif
                </div>
                <div class="stat-value">
                    @if($session->status === 'open')
                        <span class="status-badge open">Đang mở</span>
                    @else
                        <span class="status-badge closed">Đã đóng</span>
                    @endif
                </div>
                <div class="stat-label">Trạng thái</div>
            </div>
        </div>

        <!-- Session Details Grid -->
        <div class="sessions-table-container">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Session Information -->
                <div class="session-details-card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            Thông tin phiên
                        </h3>
                    </div>
                    <div class="card-content">
                        <div class="detail-row">
                            <div class="detail-label">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                </svg>
                                Người vận hành
                            </div>
                            <div class="detail-value">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center text-green-600 font-semibold text-xs">
                                        {{ substr($session->user->name ?? 'N', 0, 1) }}
                                    </div>
                                    {{ $session->user->name ?? 'N/A' }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="detail-row">
                            <div class="detail-label">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/>
                                </svg>
                                Số dư mở phiên
                            </div>
                            <div class="detail-value amount-value">{{ number_format($session->opening_balance) }}đ</div>
                        </div>
                        
                        <div class="detail-row">
                            <div class="detail-label">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                </svg>
                                Số dư đóng phiên
                            </div>
                            <div class="detail-value">
                                @if($session->closing_balance)
                                    <span class="amount-value">{{ number_format($session->closing_balance) }}đ</span>
                                @else
                                    <span class="text-gray-500">Chưa đóng</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="detail-row">
                            <div class="detail-label">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                </svg>
                                Thời gian mở
                            </div>
                            <div class="detail-value">
                                @if($session->opened_at)
                                    <div class="text-sm">
                                        <div class="font-semibold">{{ $session->opened_at->format('d/m/Y') }}</div>
                                        <div class="text-gray-500">{{ $session->opened_at->format('H:i') }}</div>
                                    </div>
                                @else
                                    <span class="text-gray-500">N/A</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="detail-row">
                            <div class="detail-label">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                </svg>
                                Thời gian đóng
                            </div>
                            <div class="detail-value">
                                @if($session->closed_at)
                                    <div class="text-sm">
                                        <div class="font-semibold">{{ $session->closed_at->format('d/m/Y') }}</div>
                                        <div class="text-gray-500">{{ $session->closed_at->format('H:i') }}</div>
                                    </div>
                                @else
                                    <span class="text-gray-500">Đang hoạt động</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="detail-row">
                            <div class="detail-label">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                </svg>
                                Thời gian hoạt động
                            </div>
                            <div class="detail-value">
                                @if($session->closed_at)
                                    {{ $session->opened_at->diffForHumans($session->closed_at, true) }}
                                @else
                                    {{ $session->opened_at->diffForHumans() }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Summary -->
                <div class="session-details-card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4z"/>
                                <path d="M6 8a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2V8z"/>
                            </svg>
                            Thanh toán
                        </h3>
                    </div>
                    <div class="card-content">
                        <div class="detail-row">
                            <div class="detail-label">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4z"/>
                                    <path d="M6 8a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2V8z"/>
                                </svg>
                                Tiền mặt
                            </div>
                            <div class="detail-value amount-value">{{ number_format($session->total_cash) }}đ</div>
                        </div>
                        
                        <div class="detail-row">
                            <div class="detail-label">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M4 4a2 2 0 011-1.732V2a1 1 0 112 0v.268A2 2 0 018 4h8a2 2 0 012 2v8a2 2 0 01-2 2H8a2 2 0 01-2-2V6a2 2 0 01-2-2z"/>
                                    <path d="M6 8a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2V8z"/>
                                </svg>
                                Thẻ
                            </div>
                            <div class="detail-value amount-value">{{ number_format($session->total_card ?? 0) }}đ</div>
                        </div>
                        
                        <div class="detail-row">
                            <div class="detail-label">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm0 2v8h12V6H4z" clip-rule="evenodd"/>
                                </svg>
                                Chuyển khoản
                            </div>
                            <div class="detail-value amount-value">{{ number_format($session->total_transfer ?? 0) }}đ</div>
                        </div>
                        
                        <div class="detail-row border-t pt-4">
                            <div class="detail-label font-semibold">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/>
                                </svg>
                                Tổng doanh thu
                            </div>
                            <div class="detail-value amount-value font-bold text-lg">{{ number_format($session->total_sales) }}đ</div>
                        </div>
                    </div>
                </div>
            </div>
            
            @if($session->notes)
                <div class="session-notes">
                    <div class="notes-header">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z"/>
                            <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z"/>
                        </svg>
                        Ghi chú
                    </div>
                    <div class="notes-content">{{ $session->notes }}</div>
                </div>
            @endif
        </div>
                        </span>
                        <span class="pos-stat-value pos-amount-positive">{{ number_format($session->total_card) }}đ</span>
                    </div>
                    
                    <div class="pos-stat-item">
                        <span class="pos-stat-label flex items-center gap-2">
                            🏦 Khác:
                        </span>
                        <span class="pos-stat-value pos-amount-positive">{{ number_format($session->total_other) }}đ</span>
                    </div>
                    
                    <hr class="border-gray-200">
                    
                    <div class="pos-stat-item">
                        <span class="pos-stat-label font-bold text-lg">💰 Tổng cộng:</span>
                        <span class="pos-stat-value text-xl pos-amount-positive">{{ number_format($session->total_sales) }}đ</span>
                    </div>
                </div>
                
                <!-- Payment Chart would go here -->
                <div class="mt-6 p-4 bg-gradient-to-br from-green-50 to-blue-50 rounded-lg">
                    <div class="text-center">
                        <div class="text-sm text-gray-600 mb-2">Tỷ lệ thanh toán</div>
                        <div class="grid grid-cols-3 gap-2 text-xs">
                            <div class="bg-green-100 p-2 rounded">
                                <div class="font-bold text-green-800">{{ $session->total_sales > 0 ? round(($session->total_cash / $session->total_sales) * 100) : 0 }}%</div>
                                <div class="text-green-600">Tiền mặt</div>
                            </div>
                            <div class="bg-blue-100 p-2 rounded">
                                <div class="font-bold text-blue-800">{{ $session->total_sales > 0 ? round(($session->total_card / $session->total_sales) * 100) : 0 }}%</div>
                                <div class="text-blue-600">Thẻ</div>
                            </div>
                            <div class="bg-purple-100 p-2 rounded">
                                <div class="font-bold text-purple-800">{{ $session->total_sales > 0 ? round(($session->total_other / $session->total_sales) * 100) : 0 }}%</div>
                                <div class="text-purple-600">Khác</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Transactions List -->
    @if($session->transactions->count())
        <div class="mt-8">
            <div class="pos-table">
                <div class="pos-table-header">
                    <h3 class="pos-section-title">🧾 Danh sách giao dịch ({{ $session->transactions->count() }})</h3>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="pos-table">
                                <th class="text-left">Mã giao dịch</th>
                                <th class="text-left">Khách hàng</th>
                                <th class="text-left">Phương thức TT</th>
                                <th class="text-left">Tổng tiền</th>
                                <th class="text-left">Thời gian</th>
                                <th class="text-left">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody class="pos-table">
                            @foreach($session->transactions as $transaction)
                                <tr>
                                    <td class="font-mono font-semibold text-blue-600">{{ $transaction->transaction_number }}</td>
                                    <td>
                                        @if($transaction->customer)
                                            <div class="flex items-center gap-2">
                                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                                    <i class="icon-customer text-blue-600"></i>
                                                </div>
                                                <div>
                                                    <div class="font-semibold">{{ $transaction->customer->first_name }} {{ $transaction->customer->last_name }}</div>
                                                    <div class="text-sm text-gray-500">{{ $transaction->customer->phone ?? $transaction->customer->email }}</div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="flex items-center gap-2">
                                                <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                                                    👤
                                                </div>
                                                <span class="text-gray-500 italic">Khách lẻ</span>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        @switch($transaction->payment_method)
                                            @case('cash')
                                                <span class="pos-badge pos-badge-success">
                                                    💵 Tiền mặt
                                                </span>
                                                @break
                                            @case('card')
                                                <span class="pos-badge pos-badge-info">
                                                    💳 Thẻ
                                                </span>
                                                @break
                                            @case('bank_transfer')
                                                <span class="pos-badge pos-badge-warning">
                                                    🏦 Chuyển khoản
                                                </span>
                                                @break
                                            @default
                                                <span class="pos-badge pos-badge-secondary">
                                                    {{ ucfirst($transaction->payment_method) }}
                                                </span>
                                        @endswitch
                                    </td>
                                    <td class="font-bold text-lg pos-amount-positive">{{ number_format($transaction->total_amount) }}đ</td>
                                    <td>
                                        <div class="text-sm">
                                            <div class="font-semibold">{{ $transaction->completed_at ? $transaction->completed_at->format('H:i:s') : $transaction->created_at->format('H:i:s') }}</div>
                                            <div class="text-gray-500">{{ $transaction->created_at->format('d/m/Y') }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="flex gap-2">
                                            <button class="text-blue-600 hover:text-blue-800" title="Xem chi tiết">
                                                <i class="icon-view"></i>
                                            </button>
                                            <button class="text-green-600 hover:text-green-800" title="In hóa đơn">
                                                <i class="icon-printer"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <div class="mt-8">
            <div class="pos-stats-card text-center py-12">
                <div class="text-6xl mb-4">📋</div>
                <h3 class="text-xl font-semibold text-gray-600 mb-2">Chưa có giao dịch</h3>
                <p class="text-gray-500">Phiên này chưa có giao dịch nào được thực hiện.</p>
            </div>
        </div>
    @endif

    <!-- Cash Movements -->
    @if($session->cashMovements->count())
        <div class="mt-8">
            <div class="pos-table">
                <div class="pos-table-header">
                    <h3 class="pos-section-title">💰 Biến động tiền mặt ({{ $session->cashMovements->count() }})</h3>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="pos-table">
                                <th class="text-left">Thời gian</th>
                                <th class="text-left">Loại giao dịch</th>
                                <th class="text-left">Mô tả</th>
                                <th class="text-left">Số tiền</th>
                                <th class="text-left">Tham chiếu</th>
                            </tr>
                        </thead>
                        <tbody class="pos-table">
                            @foreach($session->cashMovements as $movement)
                                <tr>
                                    <td>
                                        <div class="text-sm">
                                            <div class="font-semibold">{{ $movement->movement_at->format('H:i:s') }}</div>
                                            <div class="text-gray-500">{{ $movement->movement_at->format('d/m/Y') }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        @switch($movement->type)
                                            @case('sale')
                                                <span class="pos-badge pos-badge-success">
                                                    🛒 Bán hàng
                                                </span>
                                                @break
                                            @case('cash_in')
                                                <span class="pos-badge pos-badge-info">
                                                    ⬇️ Tiền vào
                                                </span>
                                                @break
                                            @case('cash_out')
                                                <span class="pos-badge pos-badge-danger">
                                                    ⬆️ Tiền ra
                                                </span>
                                                @break
                                            @default
                                                <span class="pos-badge pos-badge-secondary">
                                                    {{ ucfirst($movement->type) }}
                                                </span>
                                        @endswitch
                                    </td>
                                    <td>
                                        <div class="max-w-xs">
                                            <div class="font-medium">{{ $movement->description }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="font-bold text-lg {{ $movement->type === 'cash_out' ? 'pos-amount-negative' : 'pos-amount-positive' }}">
                                            {{ $movement->type === 'cash_out' ? '-' : '+' }}{{ number_format($movement->amount) }}đ
                                        </span>
                                    </td>
                                    <td>
                                        <code class="bg-gray-100 px-2 py-1 rounded text-sm">{{ $movement->reference }}</code>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
    
    @pushOnce('scripts')
        <script>
            function closeSession() {
                if (confirm('Bạn có chắc chắn muốn đóng phiên này không?')) {
                    // Add close session logic here
                    console.log('Closing session...');
                }
            }
        </script>
    @endPushOnce
</x-admin::layouts>
