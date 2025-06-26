<x-admin::layouts>
    <x-slot:title>
        Tạo bảo hành mới
    </x-slot>

    <div class="flex justify-between items-center mb-4">
        <div class="flex flex-col gap-2">
            <div class="text-xl text-gray-800 dark:text-white font-bold">
                Tạo bảo hành mới
            </div>
        </div>

        <div class="flex gap-x-2.5 items-center">
            <a 
                href="{{ route('admin.warranty.index') }}"
                class="secondary-button"
            >
                Quay lại
            </a>
        </div>
    </div>

    <form action="{{ route('admin.warranty.store') }}" method="POST" class="bg-white p-6 rounded shadow">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Warranty Package -->
            <div>
                <label for="warranty_package_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Gói bảo hành <span class="text-red-500">*</span>
                </label>
                <select 
                    name="warranty_package_id" 
                    id="warranty_package_id" 
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    <option value="">Chọn gói bảo hành</option>
                    @foreach($warrantyPackages as $package)
                        <option value="{{ $package->id }}" {{ old('warranty_package_id') == $package->id ? 'selected' : '' }}>
                            {{ $package->name }} ({{ $package->duration_months }} tháng)
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Product -->
            <div>
                <label for="product_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Sản phẩm <span class="text-red-500">*</span>
                </label>
                <select 
                    name="product_id" 
                    id="product_id" 
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    <option value="">Chọn sản phẩm</option>
                    @foreach($products as $product)
                        <option value="{{ $product['id'] }}" {{ old('product_id') == $product['id'] ? 'selected' : '' }}>
                            {{ $product['name'] }} ({{ $product['sku'] }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Product Serial -->
            <div>
                <label for="product_serial" class="block text-sm font-medium text-gray-700 mb-2">
                    Serial sản phẩm <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="product_serial" 
                    id="product_serial" 
                    value="{{ old('product_serial') }}"
                    required
                    placeholder="Nhập serial sản phẩm"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
            </div>

            <!-- Customer -->
            <div>
                <label for="customer_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Khách hàng <span class="text-red-500">*</span>
                </label>
                <select 
                    name="customer_id" 
                    id="customer_id" 
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    <option value="">Chọn khách hàng</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                            {{ $customer->first_name }} {{ $customer->last_name }} ({{ $customer->email }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Purchase Date -->
            <div>
                <label for="purchase_date" class="block text-sm font-medium text-gray-700 mb-2">
                    Ngày mua <span class="text-red-500">*</span>
                </label>
                <input 
                    type="date" 
                    name="purchase_date" 
                    id="purchase_date" 
                    value="{{ old('purchase_date', date('Y-m-d')) }}"
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
            </div>

            <!-- Order Number -->
            <div>
                <label for="order_number" class="block text-sm font-medium text-gray-700 mb-2">
                    Số đơn hàng
                </label>
                <input 
                    type="text" 
                    name="order_number" 
                    id="order_number" 
                    value="{{ old('order_number') }}"
                    placeholder="Nhập số đơn hàng (nếu có)"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
            </div>
        </div>

        <!-- Notes -->
        <div class="mt-6">
            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                Ghi chú
            </label>
            <textarea 
                name="notes" 
                id="notes" 
                rows="4"
                placeholder="Nhập ghi chú thêm (nếu có)"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            >{{ old('notes') }}</textarea>
        </div>

        <!-- Submit Buttons -->
        <div class="flex justify-end gap-3 mt-6">
            <a 
                href="{{ route('admin.warranty.index') }}"
                class="secondary-button"
            >
                Hủy bỏ
            </a>
            <button 
                type="submit"
                class="primary-button"
            >
                Tạo bảo hành
            </button>
        </div>
    </form>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-calculate warranty dates when package is selected
        const packageSelect = document.getElementById('warranty_package_id');
        const purchaseDateInput = document.getElementById('purchase_date');
        
        function updateWarrantyInfo() {
            // This could be enhanced to show warranty end date preview
            console.log('Package selected:', packageSelect.value);
        }
        
        packageSelect.addEventListener('change', updateWarrantyInfo);
        purchaseDateInput.addEventListener('change', updateWarrantyInfo);
    });
    </script>
</x-admin::layouts>