<x-admin::layouts>
    <x-slot:title>
        Quản lý bảo hành
    </x-slot>

    <div class="flex justify-between items-center mb-4">
        <div class="flex flex-col gap-2">
            <div class="text-xl text-gray-800 dark:text-white font-bold">
                Quản lý bảo hành
            </div>
        </div>

        <div class="flex gap-x-2.5 items-center">
            <a 
                href="{{ route('admin.warranty.create') }}"
                class="primary-button"
            >
                Tạo bảo hành mới
            </a>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white p-4 rounded shadow">
        <h3 class="text-lg font-semibold mb-4">Danh sách bảo hành</h3>
        
        <!-- Search Bar -->
        <div class="mb-4">
            <input 
                type="text" 
                id="search-input" 
                placeholder="Tìm kiếm theo mã bảo hành, serial, tên khách hàng..."
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border-collapse border border-gray-300">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="border border-gray-300 px-4 py-2">Mã bảo hành</th>
                        <th class="border border-gray-300 px-4 py-2">Serial sản phẩm</th>
                        <th class="border border-gray-300 px-4 py-2">Tên sản phẩm</th>
                        <th class="border border-gray-300 px-4 py-2">Khách hàng</th>
                        <th class="border border-gray-300 px-4 py-2">Gói bảo hành</th>
                        <th class="border border-gray-300 px-4 py-2">Ngày bắt đầu</th>
                        <th class="border border-gray-300 px-4 py-2">Ngày hết hạn</th>
                        <th class="border border-gray-300 px-4 py-2">Trạng thái</th>
                        <th class="border border-gray-300 px-4 py-2">Thao tác</th>
                    </tr>
                </thead>
                <tbody id="warranties-table-body">
                    <tr>
                        <td colspan="9" class="border border-gray-300 px-4 py-2 text-center">Đang tải dữ liệu...</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div id="pagination" class="mt-4 flex justify-between items-center">
            <div id="pagination-info" class="text-sm text-gray-700"></div>
            <div id="pagination-links" class="flex gap-2"></div>
        </div>
    </div>

    <script>
    let currentPage = 1;
    let searchQuery = '';

    document.addEventListener('DOMContentLoaded', function() {
        loadWarranties();
        
        // Search functionality
        const searchInput = document.getElementById('search-input');
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                searchQuery = this.value;
                currentPage = 1;
                loadWarranties();
            }, 500);
        });
    });

    function loadWarranties() {
        const url = new URL('{{ route("admin.warranty.api.warranties") }}');
        if (searchQuery) url.searchParams.append('search', searchQuery);
        url.searchParams.append('page', currentPage);
        
        fetch(url)
            .then(response => response.json())
            .then(data => {
                console.log('API Response:', data);
                renderWarrantiesTable(data);
                renderPagination(data);
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('warranties-table-body').innerHTML = 
                    '<tr><td colspan="9" class="border border-gray-300 px-4 py-2 text-center text-red-600">Lỗi tải dữ liệu: ' + error.message + '</td></tr>';
            });
    }

    function renderWarrantiesTable(data) {
        const tbody = document.getElementById('warranties-table-body');
        
        if (data.data && data.data.length > 0) {
            tbody.innerHTML = data.data.map(warranty => `
                <tr class="hover:bg-gray-50">
                    <td class="border border-gray-300 px-4 py-2 font-mono">${warranty.warranty_number}</td>
                    <td class="border border-gray-300 px-4 py-2">${warranty.product_serial || 'N/A'}</td>
                    <td class="border border-gray-300 px-4 py-2">${warranty.product_name || 'N/A'}</td>
                    <td class="border border-gray-300 px-4 py-2">${warranty.customer_name || 'N/A'}</td>
                    <td class="border border-gray-300 px-4 py-2">${warranty.warranty_package ? warranty.warranty_package.name : 'N/A'}</td>
                    <td class="border border-gray-300 px-4 py-2">${formatDate(warranty.start_date)}</td>
                    <td class="border border-gray-300 px-4 py-2">${formatDate(warranty.end_date)}</td>
                    <td class="border border-gray-300 px-4 py-2">
                        <span class="px-2 py-1 rounded text-xs ${getStatusClass(warranty.status)}">
                            ${getStatusText(warranty.status)}
                        </span>
                    </td>
                    <td class="border border-gray-300 px-4 py-2">
                        <div class="flex gap-2">
                            <a href="/admin/warranty/${warranty.id}" class="text-blue-600 hover:underline">Xem</a>
                            <a href="/admin/warranty/${warranty.id}/edit" class="text-green-600 hover:underline">Sửa</a>
                        </div>
                    </td>
                </tr>
            `).join('');
        } else {
            tbody.innerHTML = '<tr><td colspan="9" class="border border-gray-300 px-4 py-2 text-center">Không có dữ liệu</td></tr>';
        }
    }

    function renderPagination(data) {
        const info = document.getElementById('pagination-info');
        const links = document.getElementById('pagination-links');
        
        if (data.total) {
            info.innerHTML = `Hiển thị ${data.from || 0} đến ${data.to || 0} trong tổng số ${data.total} bản ghi`;
            
            let paginationHtml = '';
            
            if (data.prev_page_url) {
                paginationHtml += `<button onclick="changePage(${data.current_page - 1})" class="px-3 py-1 border rounded hover:bg-gray-100">Trước</button>`;
            }
            
            for (let i = Math.max(1, data.current_page - 2); i <= Math.min(data.last_page, data.current_page + 2); i++) {
                paginationHtml += `<button onclick="changePage(${i})" class="px-3 py-1 border rounded ${i === data.current_page ? 'bg-blue-500 text-white' : 'hover:bg-gray-100'}">${i}</button>`;
            }
            
            if (data.next_page_url) {
                paginationHtml += `<button onclick="changePage(${data.current_page + 1})" class="px-3 py-1 border rounded hover:bg-gray-100">Sau</button>`;
            }
            
            links.innerHTML = paginationHtml;
        }
    }

    function changePage(page) {
        currentPage = page;
        loadWarranties();
    }

    function formatDate(dateString) {
        if (!dateString) return 'N/A';
        const date = new Date(dateString);
        return date.toLocaleDateString('vi-VN');
    }

    function getStatusClass(status) {
        switch(status) {
            case 'active': return 'bg-green-200 text-green-800';
            case 'expired': return 'bg-red-200 text-red-800';
            case 'claimed': return 'bg-yellow-200 text-yellow-800';
            case 'cancelled': return 'bg-gray-200 text-gray-800';
            default: return 'bg-gray-200 text-gray-800';
        }
    }

    function getStatusText(status) {
        switch(status) {
            case 'active': return 'Đang hiệu lực';
            case 'expired': return 'Hết hạn';
            case 'claimed': return 'Đã sử dụng';
            case 'cancelled': return 'Đã hủy';
            default: return status;
        }
    }
    </script>
</x-admin::layouts>