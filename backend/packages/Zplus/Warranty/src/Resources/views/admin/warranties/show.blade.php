<x-admin::layouts>
    <x-slot:title>
        Chi tiết bảo hành
    </x-slot>

    <div class="flex justify-between items-center mb-4">
        <div class="flex flex-col gap-2">
            <div class="text-xl text-gray-800 dark:text-white font-bold">
                Chi tiết bảo hành - {{ $warranty->warranty_number }}
            </div>
        </div>

        <div class="flex gap-x-2.5 items-center">
            <a 
                href="{{ route('admin.warranty.edit', $warranty->id) }}"
                class="primary-button"
            >
                Chỉnh sửa
            </a>
            <a 
                href="{{ route('admin.warranty.index') }}"
                class="secondary-button"
            >
                Quay lại
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Thông tin bảo hành -->
        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-lg font-semibold mb-4">Thông tin bảo hành</h3>
            
            <div class="space-y-3">
                <div class="flex justify-between py-2 border-b border-gray-200">
                    <span class="text-sm font-medium text-gray-600">Mã bảo hành:</span>
                    <span class="text-sm text-gray-900 font-mono">{{ $warranty->warranty_number }}</span>
                </div>

                <div class="flex justify-between py-2 border-b border-gray-200">
                    <span class="text-sm font-medium text-gray-600">Gói bảo hành:</span>
                    <span class="text-sm text-gray-900">
                        {{ $warranty->warrantyPackage->name ?? 'N/A' }} 
                        ({{ $warranty->warrantyPackage->duration_months ?? 'N/A' }} tháng)
                    </span>
                </div>

                <div class="flex justify-between py-2 border-b border-gray-200">
                    <span class="text-sm font-medium text-gray-600">Trạng thái:</span>
                    <span class="text-sm">
                        <span class="px-2 py-1 rounded text-xs
                            @switch($warranty->status)
                                @case('active') bg-green-200 text-green-800 @break
                                @case('expired') bg-red-200 text-red-800 @break
                                @case('claimed') bg-yellow-200 text-yellow-800 @break
                                @case('cancelled') bg-gray-200 text-gray-800 @break
                                @default bg-gray-200 text-gray-800
                            @endswitch">
                            @switch($warranty->status)
                                @case('active') Đang hiệu lực @break
                                @case('expired') Hết hạn @break
                                @case('claimed') Đã sử dụng @break
                                @case('cancelled') Đã hủy @break
                                @default {{ $warranty->status }}
                            @endswitch
                        </span>
                    </span>
                </div>

                <div class="flex justify-between py-2 border-b border-gray-200">
                    <span class="text-sm font-medium text-gray-600">Ngày bắt đầu:</span>
                    <span class="text-sm text-gray-900">{{ $warranty->start_date->format('d/m/Y') }}</span>
                </div>

                <div class="flex justify-between py-2 border-b border-gray-200">
                    <span class="text-sm font-medium text-gray-600">Ngày hết hạn:</span>
                    <span class="text-sm text-gray-900">{{ $warranty->end_date->format('d/m/Y') }}</span>
                </div>

                <div class="flex justify-between py-2">
                    <span class="text-sm font-medium text-gray-600">Ngày mua:</span>
                    <span class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($warranty->purchase_date)->format('d/m/Y') }}</span>
                </div>
            </div>
        </div>

        <!-- Thông tin sản phẩm -->
        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-lg font-semibold mb-4">Thông tin sản phẩm</h3>
            
            <div class="space-y-3">
                <div class="flex justify-between py-2 border-b border-gray-200">
                    <span class="text-sm font-medium text-gray-600">Tên sản phẩm:</span>
                    <span class="text-sm text-gray-900">{{ $warranty->product_name ?? 'N/A' }}</span>
                </div>

                <div class="flex justify-between py-2 border-b border-gray-200">
                    <span class="text-sm font-medium text-gray-600">SKU:</span>
                    <span class="text-sm text-gray-900 font-mono">{{ $warranty->product_sku ?? 'N/A' }}</span>
                </div>

                <div class="flex justify-between py-2 border-b border-gray-200">
                    <span class="text-sm font-medium text-gray-600">Serial:</span>
                    <span class="text-sm text-gray-900 font-mono">{{ $warranty->product_serial }}</span>
                </div>

                <div class="flex justify-between py-2">
                    <span class="text-sm font-medium text-gray-600">Số đơn hàng:</span>
                    <span class="text-sm text-gray-900">{{ $warranty->order_number ?? 'N/A' }}</span>
                </div>
            </div>
        </div>

        <!-- Thông tin khách hàng -->
        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-lg font-semibold mb-4">Thông tin khách hàng</h3>
            
            <div class="space-y-3">
                <div class="flex justify-between py-2 border-b border-gray-200">
                    <span class="text-sm font-medium text-gray-600">Tên khách hàng:</span>
                    <span class="text-sm text-gray-900">{{ $warranty->customer_name }}</span>
                </div>

                <div class="flex justify-between py-2 border-b border-gray-200">
                    <span class="text-sm font-medium text-gray-600">Email:</span>
                    <span class="text-sm text-gray-900">{{ $warranty->customer_email ?? 'N/A' }}</span>
                </div>

                <div class="flex justify-between py-2">
                    <span class="text-sm font-medium text-gray-600">Số điện thoại:</span>
                    <span class="text-sm text-gray-900">{{ $warranty->customer_phone ?? 'N/A' }}</span>
                </div>
            </div>
        </div>

        <!-- Ghi chú và thông tin khác -->
        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-lg font-semibold mb-4">Ghi chú & Thông tin khác</h3>
            
            <div class="space-y-3">
                <div class="flex justify-between py-2 border-b border-gray-200">
                    <span class="text-sm font-medium text-gray-600">Ghi chú:</span>
                    <span class="text-sm text-gray-900">{{ $warranty->notes ?? 'Không có ghi chú' }}</span>
                </div>

                <div class="flex justify-between py-2 border-b border-gray-200">
                    <span class="text-sm font-medium text-gray-600">Ngày tạo:</span>
                    <span class="text-sm text-gray-900">{{ $warranty->created_at->format('d/m/Y H:i:s') }}</span>
                </div>

                <div class="flex justify-between py-2">
                    <span class="text-sm font-medium text-gray-600">Cập nhật cuối:</span>
                    <span class="text-sm text-gray-900">{{ $warranty->updated_at->format('d/m/Y H:i:s') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Thống kê thời gian -->
    <div class="mt-6 bg-white p-4 rounded shadow">
        <h3 class="text-lg font-semibold mb-4">Thống kê thời gian bảo hành</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <div class="text-center p-4 bg-blue-50 rounded">
                <div class="text-2xl font-bold text-blue-600">
                    {{ $warranty->start_date->diffInDays(now()) }}
                </div>
                <div class="text-sm text-gray-600">Ngày đã qua</div>
            </div>
            
            <div class="text-center p-4 bg-green-50 rounded">
                <div class="text-2xl font-bold text-green-600">
                    {{ max(0, $warranty->end_date->diffInDays(now())) }}
                </div>
                <div class="text-sm text-gray-600">Ngày còn lại</div>
            </div>
            
            <div class="text-center p-4 bg-purple-50 rounded">
                <div class="text-2xl font-bold text-purple-600">
                    {{ $warranty->start_date->diffInDays($warranty->end_date) }}
                </div>
                <div class="text-sm text-gray-600">Tổng thời gian (ngày)</div>
            </div>
        </div>
        
        <!-- Progress bar -->
        <div>
            <div class="flex justify-between text-sm text-gray-600 mb-2">
                <span>Tiến độ bảo hành</span>
                <span>{{ round(($warranty->start_date->diffInDays(now()) / $warranty->start_date->diffInDays($warranty->end_date)) * 100, 1) }}%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-blue-600 h-2 rounded-full" 
                     style="width: {{ min(100, round(($warranty->start_date->diffInDays(now()) / $warranty->start_date->diffInDays($warranty->end_date)) * 100, 1)) }}%">
                </div>
            </div>
        </div>
    </div>
</x-admin::layouts>