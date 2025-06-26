@extends('admin::layouts.content')

@section('page_title')
    {{ trans('warranty::app.admin.warranties.show.title') }}
@stop

@section('content')
    <div class="flex justify-between items-center">
        <div class="flex flex-col gap-2">
            <div class="text-xl text-gray-800 dark:text-white font-bold">
                {{ trans('warranty::app.admin.warranties.show.title') }} - {{ $warranty->warranty_number }}
            </div>
        </div>

        <div class="flex gap-x-2.5 items-center">
            <a 
                href="{{ route('admin.warranty.edit', $warranty->id) }}"
                class="primary-button"
            >
                {{ trans('warranty::app.admin.warranties.index.datagrid.edit') }}
            </a>
            <a 
                href="{{ route('admin.warranty.index') }}"
                class="secondary-button"
            >
                {{ trans('admin::app.datagrid.back') }}
            </a>
        </div>
    </div>

    <div class="flex gap-2.5 mt-3.5 max-xl:flex-wrap">
        <!-- Warranty Details -->
        <div class="flex flex-col gap-2 flex-1 max-xl:flex-auto">
            
            <x-admin::accordion>
                <x-slot:header>
                    <div class="flex items-center justify-between">
                        <p class="p-2.5 text-gray-800 dark:text-white text-base  font-semibold">
                            {{ trans('warranty::app.admin.warranties.show.warranty-details') }}
                        </p>
                    </div>
                </x-slot:header>

                <x-slot:content>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-gray-800 dark:text-white font-medium">
                                {{ trans('warranty::app.admin.warranties.fields.warranty-number') }}
                            </label>
                            <p class="text-gray-600 dark:text-gray-300">{{ $warranty->warranty_number }}</p>
                        </div>

                        <div>
                            <label class="text-gray-800 dark:text-white font-medium">
                                {{ trans('warranty::app.admin.warranties.fields.status') }}
                            </label>
                            <p class="text-gray-600 dark:text-gray-300">
                                <span class="badge {{ $warranty->status_badge_class }}">
                                    {{ $warranty->status_text }}
                                </span>
                            </p>
                        </div>

                        <div>
                            <label class="text-gray-800 dark:text-white font-medium">
                                {{ trans('warranty::app.admin.warranties.fields.warranty-package') }}
                            </label>
                            <p class="text-gray-600 dark:text-gray-300">{{ $warranty->warrantyPackage->name ?? 'N/A' }}</p>
                        </div>

                        <div>
                            <label class="text-gray-800 dark:text-white font-medium">
                                {{ trans('warranty::app.admin.warranties.fields.purchase-date') }}
                            </label>
                            <p class="text-gray-600 dark:text-gray-300">{{ $warranty->purchase_date->format('d/m/Y') }}</p>
                        </div>

                        <div>
                            <label class="text-gray-800 dark:text-white font-medium">
                                {{ trans('warranty::app.admin.warranties.fields.start-date') }}
                            </label>
                            <p class="text-gray-600 dark:text-gray-300">{{ $warranty->start_date->format('d/m/Y') }}</p>
                        </div>

                        <div>
                            <label class="text-gray-800 dark:text-white font-medium">
                                {{ trans('warranty::app.admin.warranties.fields.end-date') }}
                            </label>
                            <p class="text-gray-600 dark:text-gray-300">
                                {{ $warranty->end_date->format('d/m/Y') }}
                                @if($warranty->isActive())
                                    <span class="text-green-600">({{ $warranty->remaining_days }} ngày còn lại)</span>
                                @elseif($warranty->isExpired())
                                    <span class="text-red-600">(Đã hết hạn)</span>
                                @endif
                            </p>
                        </div>

                        @if($warranty->order_number)
                        <div>
                            <label class="text-gray-800 dark:text-white font-medium">
                                {{ trans('warranty::app.admin.warranties.fields.order-number') }}
                            </label>
                            <p class="text-gray-600 dark:text-gray-300">{{ $warranty->order_number }}</p>
                        </div>
                        @endif

                        @if($warranty->notes)
                        <div class="col-span-2">
                            <label class="text-gray-800 dark:text-white font-medium">
                                {{ trans('warranty::app.admin.warranties.fields.notes') }}
                            </label>
                            <p class="text-gray-600 dark:text-gray-300">{{ $warranty->notes }}</p>
                        </div>
                        @endif
                    </div>
                </x-slot:content>
            </x-admin::accordion>

            <!-- Product Information -->
            <x-admin::accordion>
                <x-slot:header>
                    <div class="flex items-center justify-between">
                        <p class="p-2.5 text-gray-800 dark:text-white text-base  font-semibold">
                            {{ trans('warranty::app.admin.warranties.show.product-details') }}
                        </p>
                    </div>
                </x-slot:header>

                <x-slot:content>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-gray-800 dark:text-white font-medium">
                                {{ trans('warranty::app.admin.warranties.fields.product') }}
                            </label>
                            <p class="text-gray-600 dark:text-gray-300">{{ $warranty->product_name }}</p>
                        </div>

                        <div>
                            <label class="text-gray-800 dark:text-white font-medium">SKU</label>
                            <p class="text-gray-600 dark:text-gray-300">{{ $warranty->product_sku }}</p>
                        </div>

                        <div>
                            <label class="text-gray-800 dark:text-white font-medium">
                                {{ trans('warranty::app.admin.warranties.fields.product-serial') }}
                            </label>
                            <p class="text-gray-600 dark:text-gray-300 font-bold text-lg">{{ $warranty->product_serial }}</p>
                        </div>

                        @if($warranty->product)
                        <div>
                            <label class="text-gray-800 dark:text-white font-medium">Liên kết sản phẩm</label>
                            <p class="text-gray-600 dark:text-gray-300">
                                <a href="{{ route('admin.catalog.products.edit', $warranty->product->id) }}" class="text-blue-600 hover:text-blue-800">
                                    Xem chi tiết sản phẩm
                                </a>
                            </p>
                        </div>
                        @endif
                    </div>
                </x-slot:content>
            </x-admin::accordion>

            <!-- Customer Information -->
            <x-admin::accordion>
                <x-slot:header>
                    <div class="flex items-center justify-between">
                        <p class="p-2.5 text-gray-800 dark:text-white text-base  font-semibold">
                            {{ trans('warranty::app.admin.warranties.show.customer-details') }}
                        </p>
                    </div>
                </x-slot:header>

                <x-slot:content>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-gray-800 dark:text-white font-medium">
                                {{ trans('warranty::app.admin.warranties.fields.customer') }}
                            </label>
                            <p class="text-gray-600 dark:text-gray-300">{{ $warranty->customer_name }}</p>
                        </div>

                        <div>
                            <label class="text-gray-800 dark:text-white font-medium">
                                {{ trans('warranty::app.admin.warranties.fields.customer-phone') }}
                            </label>
                            <p class="text-gray-600 dark:text-gray-300 font-bold">{{ $warranty->customer_phone }}</p>
                        </div>

                        @if($warranty->customer_email)
                        <div>
                            <label class="text-gray-800 dark:text-white font-medium">
                                {{ trans('warranty::app.admin.warranties.fields.customer-email') }}
                            </label>
                            <p class="text-gray-600 dark:text-gray-300">{{ $warranty->customer_email }}</p>
                        </div>
                        @endif

                        @if($warranty->customer)
                        <div>
                            <label class="text-gray-800 dark:text-white font-medium">Liên kết khách hàng</label>
                            <p class="text-gray-600 dark:text-gray-300">
                                <a href="{{ route('admin.customers.edit', $warranty->customer->id) }}" class="text-blue-600 hover:text-blue-800">
                                    Xem chi tiết khách hàng
                                </a>
                            </p>
                        </div>
                        @endif
                    </div>
                </x-slot:content>
            </x-admin::accordion>

            @if($warranty->posTransaction)
            <!-- POS Transaction Information -->
            <x-admin::accordion>
                <x-slot:header>
                    <div class="flex items-center justify-between">
                        <p class="p-2.5 text-gray-800 dark:text-white text-base  font-semibold">
                            Thông tin giao dịch POS
                        </p>
                    </div>
                </x-slot:header>

                <x-slot:content>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-gray-800 dark:text-white font-medium">Số giao dịch</label>
                            <p class="text-gray-600 dark:text-gray-300">{{ $warranty->posTransaction->transaction_number }}</p>
                        </div>

                        <div>
                            <label class="text-gray-800 dark:text-white font-medium">Tổng tiền</label>
                            <p class="text-gray-600 dark:text-gray-300">{{ number_format($warranty->posTransaction->total_amount, 0, ',', '.') }} ₫</p>
                        </div>

                        <div>
                            <label class="text-gray-800 dark:text-white font-medium">Phương thức thanh toán</label>
                            <p class="text-gray-600 dark:text-gray-300">{{ ucfirst($warranty->posTransaction->payment_method) }}</p>
                        </div>

                        <div>
                            <label class="text-gray-800 dark:text-white font-medium">Ngày giao dịch</label>
                            <p class="text-gray-600 dark:text-gray-300">{{ $warranty->posTransaction->created_at->format('d/m/Y H:i:s') }}</p>
                        </div>
                    </div>
                </x-slot:content>
            </x-admin::accordion>
            @endif

            <!-- Created By Information -->
            <x-admin::accordion>
                <x-slot:header>
                    <div class="flex items-center justify-between">
                        <p class="p-2.5 text-gray-800 dark:text-white text-base  font-semibold">
                            Thông tin tạo
                        </p>
                    </div>
                </x-slot:header>

                <x-slot:content>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-gray-800 dark:text-white font-medium">Người tạo</label>
                            <p class="text-gray-600 dark:text-gray-300">{{ $warranty->createdBy->name ?? 'N/A' }}</p>
                        </div>

                        <div>
                            <label class="text-gray-800 dark:text-white font-medium">Ngày tạo</label>
                            <p class="text-gray-600 dark:text-gray-300">{{ $warranty->created_at->format('d/m/Y H:i:s') }}</p>
                        </div>

                        <div>
                            <label class="text-gray-800 dark:text-white font-medium">Cập nhật cuối</label>
                            <p class="text-gray-600 dark:text-gray-300">{{ $warranty->updated_at->format('d/m/Y H:i:s') }}</p>
                        </div>
                    </div>
                </x-slot:content>
            </x-admin::accordion>

        </div>
    </div>

@stop