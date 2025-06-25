<x-admin::layouts>
    <x-slot:title>
        ViPOS - Bảng điều khiển
    </x-slot>

    <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
        <p class="text-xl font-bold text-gray-800 dark:text-white">
            ViPOS - Hệ thống bán hàng
        </p>
    </div>    <div class="mt-3.5">
        <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">
            Quản lý bán hàng tại điểm bán
        </p>
        <div class="grid gap-4 grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
            <!-- Quick Stats -->
            <div class="box-shadow rounded bg-white p-6 dark:bg-gray-900">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Phiên giao dịch hôm nay</h3>
                <p class="text-3xl font-bold text-blue-600">0</p>
            </div>

            <div class="box-shadow rounded bg-white p-6 dark:bg-gray-900">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Tổng doanh thu</h3>
                <p class="text-3xl font-bold text-green-600">0 VNĐ</p>
            </div>

            <div class="box-shadow rounded bg-white p-6 dark:bg-gray-900">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Giao dịch thành công</h3>
                <p class="text-3xl font-bold text-purple-600">0</p>
            </div>
        </div>        <!-- Quick Actions -->
        <div class="mt-8">            <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Thao tác nhanh</h2>            <div class="flex flex-wrap gap-4">
                <a href="{{ route('admin.vipos.index') }}" class="primary-button bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg shadow-md font-semibold">
                    <span class="flex items-center gap-2">
                        <i class="icon-expand-alt"></i> Mở POS toàn màn hình
                    </span>
                </a>
                <a href="{{ route('admin.vipos.sessions.index') }}" class="primary-button">
                    Quản lý phiên giao dịch
                </a>
                <a href="{{ route('admin.vipos.transactions.index') }}" class="secondary-button">
                    Xem giao dịch
                </a>
            </div>
        </div>
    </div>
</x-admin::layouts>
