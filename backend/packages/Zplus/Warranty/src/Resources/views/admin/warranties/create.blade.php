@extends('admin::layouts.content')

@section('page_title')
    {{ trans('warranty::app.admin.warranties.create.title') }}
@stop

@section('content')
    <div class="flex justify-between items-center">
        <div class="flex flex-col gap-2">
            <div class="text-xl text-gray-800 dark:text-white font-bold">
                {{ trans('warranty::app.admin.warranties.create.title') }}
            </div>
        </div>

        <div class="flex gap-x-2.5 items-center">
            <a 
                href="{{ route('admin.warranty.index') }}"
                class="secondary-button"
            >
                {{ trans('admin::app.datagrid.back') }}
            </a>
        </div>
    </div>

    <x-admin::form 
        action="{{ route('admin.warranty.store') }}" 
        method="POST"
        enctype="multipart/form-data"
    >
        @csrf

        <div class="flex gap-2.5 mt-3.5 max-xl:flex-wrap">
            <!-- General Information -->
            <div class="flex flex-col gap-2 flex-1 max-xl:flex-auto">
                
                <x-admin::accordion>
                    <x-slot:header>
                        <div class="flex items-center justify-between">
                            <p class="p-2.5 text-gray-800 dark:text-white text-base  font-semibold">
                                {{ trans('warranty::app.admin.warranties.create.general') }}
                            </p>
                        </div>
                    </x-slot:header>

                    <x-slot:content>
                        <!-- Warranty Package -->
                        <x-admin::form.control-group class="mb-2.5">
                            <x-admin::form.control-group.label class="required">
                                {{ trans('warranty::app.admin.warranties.fields.warranty-package') }}
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="select"
                                name="warranty_package_id"
                                rules="required"
                                :value="old('warranty_package_id')"
                            >
                                <option value="">{{ trans('admin::app.datagrid.select') }}</option>
                                @foreach ($warrantyPackages as $package)
                                    <option value="{{ $package->id }}">{{ $package->name }} ({{ $package->duration_text }})</option>
                                @endforeach
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error control-name="warranty_package_id" />
                        </x-admin::form.control-group>

                        <!-- Purchase Date -->
                        <x-admin::form.control-group class="mb-2.5">
                            <x-admin::form.control-group.label class="required">
                                {{ trans('warranty::app.admin.warranties.fields.purchase-date') }}
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="date"
                                name="purchase_date"
                                rules="required"
                                :value="old('purchase_date', now()->format('Y-m-d'))"
                            />

                            <x-admin::form.control-group.error control-name="purchase_date" />
                        </x-admin::form.control-group>

                        <!-- Order Number -->
                        <x-admin::form.control-group class="mb-2.5">
                            <x-admin::form.control-group.label>
                                {{ trans('warranty::app.admin.warranties.fields.order-number') }}
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="order_number"
                                :value="old('order_number')"
                                placeholder="Nhập số đơn hàng từ POS"
                            />

                            <x-admin::form.control-group.error control-name="order_number" />
                        </x-admin::form.control-group>

                        <!-- Notes -->
                        <x-admin::form.control-group class="mb-2.5">
                            <x-admin::form.control-group.label>
                                {{ trans('warranty::app.admin.warranties.fields.notes') }}
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="textarea"
                                name="notes"
                                :value="old('notes')"
                                placeholder="Ghi chú thêm về bảo hành"
                                rows="3"
                            />

                            <x-admin::form.control-group.error control-name="notes" />
                        </x-admin::form.control-group>
                    </x-slot:content>
                </x-admin::accordion>

                <!-- Product Information -->
                <x-admin::accordion>
                    <x-slot:header>
                        <div class="flex items-center justify-between">
                            <p class="p-2.5 text-gray-800 dark:text-white text-base  font-semibold">
                                {{ trans('warranty::app.admin.warranties.create.product-info') }}
                            </p>
                        </div>
                    </x-slot:header>

                    <x-slot:content>
                        <!-- Product -->
                        <x-admin::form.control-group class="mb-2.5">
                            <x-admin::form.control-group.label class="required">
                                {{ trans('warranty::app.admin.warranties.fields.product') }}
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="select"
                                name="product_id"
                                rules="required"
                                :value="old('product_id')"
                            >
                                <option value="">{{ trans('admin::app.datagrid.select') }}</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->sku }})</option>
                                @endforeach
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error control-name="product_id" />
                        </x-admin::form.control-group>

                        <!-- Product Serial -->
                        <x-admin::form.control-group class="mb-2.5">
                            <x-admin::form.control-group.label class="required">
                                {{ trans('warranty::app.admin.warranties.fields.product-serial') }}
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="product_serial"
                                rules="required"
                                :value="old('product_serial')"
                                placeholder="Nhập số serial sản phẩm"
                            />

                            <x-admin::form.control-group.error control-name="product_serial" />
                        </x-admin::form.control-group>
                    </x-slot:content>
                </x-admin::accordion>

                <!-- Customer Information -->
                <x-admin::accordion>
                    <x-slot:header>
                        <div class="flex items-center justify-between">
                            <p class="p-2.5 text-gray-800 dark:text-white text-base  font-semibold">
                                {{ trans('warranty::app.admin.warranties.create.customer-info') }}
                            </p>
                        </div>
                    </x-slot:header>

                    <x-slot:content>
                        <!-- Customer -->
                        <x-admin::form.control-group class="mb-2.5">
                            <x-admin::form.control-group.label class="required">
                                {{ trans('warranty::app.admin.warranties.fields.customer') }}
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="select"
                                name="customer_id"
                                rules="required"
                                :value="old('customer_id')"
                            >
                                <option value="">{{ trans('admin::app.datagrid.select') }}</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->first_name }} {{ $customer->last_name }} ({{ $customer->email }})</option>
                                @endforeach
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error control-name="customer_id" />
                        </x-admin::form.control-group>
                    </x-slot:content>
                </x-admin::accordion>

            </div>
        </div>

        <div class="flex gap-x-2.5 items-center mt-5">
            <button 
                type="submit" 
                class="primary-button"
            >
                {{ trans('warranty::app.admin.warranties.create.save-btn') }}
            </button>
        </div>
    </x-admin::form>

@stop