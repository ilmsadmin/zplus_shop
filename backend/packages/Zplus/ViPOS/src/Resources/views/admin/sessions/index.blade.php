<x-admin::layouts>
    <x-slot:title>
        ViPOS - Phiên giao dịch
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
                        Phiên giao dịch POS
                    </h1>
                    <p class="text-green-100">Quản lý và theo dõi tất cả phiên giao dịch bán hàng</p>
                </div>
                <div class="flex gap-3">
                    <button onclick="openSessionModal()" class="action-button">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                        </svg>
                        Mở phiên mới
                    </button>
                    <a href="{{ route('admin.vipos.index') }}" class="action-button secondary">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/>
                        </svg>
                        Mở POS
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="sessions-stats">
            <div class="stat-card">
                <div class="stat-icon">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="stat-value">{{ $sessions->where('status', 'open')->count() }}</div>
                <div class="stat-label">Phiên đang mở</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="stat-value">{{ $sessions->where('status', 'closed')->count() }}</div>
                <div class="stat-label">Phiên đã đóng</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="stat-value">{{ number_format($sessions->where('status', 'closed')->sum('total_sales')) }}đ</div>
                <div class="stat-label">Tổng doanh thu</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="stat-value">{{ $sessions->filter(function($s) { return $s->opened_at && $s->opened_at->isToday(); })->count() }}</div>
                <div class="stat-label">Phiên hôm nay</div>
            </div>
        </div>
        <!-- Sessions Table -->
        @if($sessions->count())
            <div class="sessions-table-container">
                <table class="sessions-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Người dùng</th>
                            <th>Trạng thái</th>
                            <th>Số dư mở</th>
                            <th>Số dư đóng</th>
                            <th>Tổng bán</th>
                            <th>Số giao dịch</th>
                            <th>Thời gian mở</th>
                            <th>Thời gian đóng</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sessions as $session)
                            <tr>
                                <td>
                                    <span class="session-id">{{ $session->id }}</span>
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center text-green-600 font-semibold text-sm">
                                            {{ substr($session->user->name ?? 'N', 0, 1) }}
                                        </div>
                                        {{ $session->user->name ?? 'N/A' }}
                                    </div>
                                </td>
                                <td>
                                    @if($session->status === 'open')
                                        <span class="status-badge open">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            Đang mở
                                        </span>
                                    @else
                                        <span class="status-badge closed">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                            </svg>
                                            Đã đóng
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <span class="amount-value">{{ number_format($session->opening_balance) }}đ</span>
                                </td>
                                <td>
                                    @if($session->closing_balance)
                                        <span class="amount-value">{{ number_format($session->closing_balance) }}đ</span>
                                    @else
                                        <span class="text-gray-500">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="amount-value">{{ number_format($session->total_sales) }}đ</span>
                                </td>
                                <td>
                                    <span class="font-semibold text-gray-700">{{ $session->transaction_count }}</span>
                                </td>
                                <td>
                                    <div class="text-sm">
                                        @if($session->opened_at)
                                            <div class="font-semibold">{{ $session->opened_at->format('d/m/Y') }}</div>
                                            <div class="text-gray-500">{{ $session->opened_at->format('H:i') }}</div>
                                        @else
                                            <span class="text-gray-500">-</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="text-sm">
                                        @if($session->closed_at)
                                            <div class="font-semibold">{{ $session->closed_at->format('d/m/Y') }}</div>
                                            <div class="text-gray-500">{{ $session->closed_at->format('H:i') }}</div>
                                        @else
                                            <span class="text-gray-500">-</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('admin.vipos.sessions.show', $session->id) }}" class="action-button">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                        </svg>
                                        Xem chi tiết
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                
                @if($sessions->hasPages())
                    <div class="pagination-wrapper">
                        {{ $sessions->links() }}
                    </div>
                @endif
            </div>
        @else
            <div class="empty-state">
                <div class="empty-state-icon">
                    <svg class="w-12 h-12 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <h2>Chưa có phiên giao dịch nào</h2>
                <p>
                    Chưa có phiên giao dịch nào được tạo. Hãy mở POS để bắt đầu phiên giao dịch mới.
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

    <!-- Open Session Modal -->
    <div id="openSessionModal" class="session-modal hidden">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                    </svg>
                    Mở phiên giao dịch mới
                </h3>
                <button class="modal-close" onclick="closeSessionModal()">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
            <div class="modal-body">
                <form id="openSessionForm">
                    <div class="form-group">
                        <label for="opening_balance" class="form-label">
                            <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/>
                            </svg>
                            Số dư ban đầu (VND)
                        </label>
                        <input type="number" 
                               id="opening_balance" 
                               name="opening_balance" 
                               min="0" 
                               step="1000"
                               value="1000000"
                               class="form-input"
                               placeholder="Nhập số dư ban đầu..."
                               required>
                    </div>
                    <div class="form-group">
                        <label for="session_notes" class="form-label">
                            <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 0v12h8V4H6z" clip-rule="evenodd"/>
                            </svg>
                            Ghi chú (tùy chọn)
                        </label>
                        <textarea id="session_notes" 
                                  name="notes" 
                                  rows="3"
                                  class="form-input form-textarea"
                                  placeholder="Ghi chú về phiên giao dịch..."></textarea>
                    </div>
                    <div class="modal-actions">
                        <button type="button" onclick="closeSessionModal()" class="action-button secondary">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                            Hủy
                        </button>
                        <button type="submit" class="action-button">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                            </svg>
                            Mở phiên
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openSessionModal() {
            document.getElementById('openSessionModal').classList.remove('hidden');
        }

        function closeSessionModal() {
            document.getElementById('openSessionModal').classList.add('hidden');
        }

        document.getElementById('openSessionForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const data = {};
            formData.forEach((value, key) => {
                data[key] = value;
            });

            fetch('{{ route("admin.vipos.sessions.open") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    alert('Phiên giao dịch đã được mở thành công!');
                    window.location.reload();
                } else {
                    alert('Lỗi: ' + result.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi mở phiên giao dịch!');
            });
        });

        // Close modal when clicking outside
        document.getElementById('openSessionModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeSessionModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeSessionModal();
            }
        });
    </script>
</x-admin::layouts>
