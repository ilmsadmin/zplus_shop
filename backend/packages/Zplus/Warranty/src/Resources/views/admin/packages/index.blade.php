<x-admin::layouts>
    <x-slot:title>
        Quản lý gói bảo hành
    </x-slot>

    <div class="flex justify-between items-center mb-4">
        <div class="flex flex-col gap-2">
            <div class="text-xl text-gray-800 dark:text-white font-bold">
                Quản lý gói bảo hành
            </div>
        </div>

        <div class="flex gap-x-2.5 items-center">
            <a 
                href="{{ route('admin.warranty.packages.create') }}"
                class="primary-button"
            >
                Tạo gói bảo hành mới
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
        <h3 class="text-lg font-semibold mb-4">Danh sách gói bảo hành</h3>
        
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border-collapse border border-gray-300">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="border border-gray-300 px-4 py-2">Tên gói</th>
                        <th class="border border-gray-300 px-4 py-2">Thời hạn (tháng)</th>
                        <th class="border border-gray-300 px-4 py-2">Giá</th>
                        <th class="border border-gray-300 px-4 py-2">Số bảo hành</th>
                        <th class="border border-gray-300 px-4 py-2">Trạng thái</th>
                        <th class="border border-gray-300 px-4 py-2">Thao tác</th>
                    </tr>
                </thead>
                <tbody id="packages-table-body">
                    <tr>
                        <td colspan="6" class="border border-gray-300 px-4 py-2 text-center">Đang tải dữ liệu...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        fetch('{{ route("admin.warranty.packages.api.packages") }}')
            .then(response => response.json())
            .then(data => {
                console.log('API Response:', data);
                const tbody = document.getElementById('packages-table-body');
                if (data.data && data.data.length > 0) {
                    tbody.innerHTML = data.data.map(package => `
                        <tr class="hover:bg-gray-50">
                            <td class="border border-gray-300 px-4 py-2">${package.name}</td>
                            <td class="border border-gray-300 px-4 py-2">${package.duration_months}</td>
                            <td class="border border-gray-300 px-4 py-2">${package.price > 0 ? package.price.toLocaleString() + ' VND' : 'Miễn phí'}</td>
                            <td class="border border-gray-300 px-4 py-2">${package.warranties_count || 0}</td>
                            <td class="border border-gray-300 px-4 py-2">
                                <span class="px-2 py-1 rounded text-xs ${package.is_active ? 'bg-green-200 text-green-800' : 'bg-gray-200 text-gray-800'}">
                                    ${package.is_active ? 'Hoạt động' : 'Tạm ngưng'}
                                </span>
                            </td>
                            <td class="border border-gray-300 px-4 py-2">
                                <div class="flex gap-2">
                                    <a href="/admin/warranty/packages/${package.id}" class="text-blue-600 hover:underline">Xem</a>
                                    <a href="/admin/warranty/packages/${package.id}/edit" class="text-green-600 hover:underline">Sửa</a>
                                    <button onclick="toggleStatus(${package.id})" class="text-orange-600 hover:underline">
                                        ${package.is_active ? 'Tạm ngưng' : 'Kích hoạt'}
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `).join('');
                } else {
                    tbody.innerHTML = '<tr><td colspan="6" class="border border-gray-300 px-4 py-2 text-center">Không có dữ liệu</td></tr>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('packages-table-body').innerHTML = 
                    '<tr><td colspan="6" class="border border-gray-300 px-4 py-2 text-center text-red-600">Lỗi tải dữ liệu: ' + error.message + '</td></tr>';
            });
    });

    function toggleStatus(id) {
        if (confirm('Bạn có chắc chắn muốn thay đổi trạng thái gói bảo hành này?')) {
            fetch(`{{ url('admin/warranty/packages') }}/${id}/toggle-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert('Có lỗi xảy ra');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra: ' + error.message);
            });
        }
    }
    </script>
</x-admin::layouts>