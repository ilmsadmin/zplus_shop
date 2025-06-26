<x-admin::layouts>
    <!-- Page Title -->
    <x-slot:title>
        {{ trans('warranty::app.admin.warranties.edit.title') }}
    </x-slot>
    <div class="flex justify-between items-center">
        <div class="flex flex-col gap-2">
            <div class="text-xl text-gray-800 dark:text-white font-bold">
                {{ trans('warranty::app.admin.warranties.edit.title') }} - {{ $warranty->warranty_number }}
            </div>
        </div>

        <div class="flex gap-x-2.5 items-center">
            <a 
                href="{{ route('admin.warranty.index') }}"
                class="secondary-button"
            >
                {{ trans('admin::app.components.datagrid.toolbar.back') }}
            </a>
        </div>
    </div>

    <x-admin::form 
        action="{{ route('admin.warranty.update', $warranty->id) }}" 
        method="PUT"
        enctype="multipart/form-data"
    >
        @csrf
        @method('PUT')

        <div class="flex gap-2.5 mt-3.5 max-xl:flex-wrap">
            <!-- General Information -->
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
                        <!-- Warranty Number (readonly) -->
                        <x-admin::form.control-group class="mb-2.5">
                            <x-admin::form.control-group.label>
                                {{ trans('warranty::app.admin.warranties.fields.warranty-number') }}
                            </x-admin::form.control-group.label>

                            <input 
                                type="text" 
                                value="{{ $warranty->warranty_number }}" 
                                readonly 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-700"
                            />
                        </x-admin::form.control-group>

                        <!-- Status -->
                        <x-admin::form.control-group class="mb-2.5">
                            <x-admin::form.control-group.label class="required">
                                {{ trans('warranty::app.admin.warranties.fields.status') }}
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="select"
                                name="status"
                                rules="required"
                                :value="old('status', $warranty->status)"
                            >
                                <option value="active" {{ $warranty->status == 'active' ? 'selected' : '' }}>
                                    {{ trans('warranty::app.admin.warranties.status.active') }}
                                </option>
                                <option value="expired" {{ $warranty->status == 'expired' ? 'selected' : '' }}>
                                    {{ trans('warranty::app.admin.warranties.status.expired') }}
                                </option>
                                <option value="claimed" {{ $warranty->status == 'claimed' ? 'selected' : '' }}>
                                    {{ trans('warranty::app.admin.warranties.status.claimed') }}
                                </option>
                                <option value="cancelled" {{ $warranty->status == 'cancelled' ? 'selected' : '' }}>
                                    {{ trans('warranty::app.admin.warranties.status.cancelled') }}
                                </option>
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error control-name="status" />
                        </x-admin::form.control-group>

                        <!-- Notes -->
                        <x-admin::form.control-group class="mb-2.5">
                            <x-admin::form.control-group.label>
                                {{ trans('warranty::app.admin.warranties.fields.notes') }}
                            </x-admin::form.control-group.label>

                            <textarea 
                                name="notes" 
                                placeholder="Ghi chú về bảo hành" 
                                rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                            >{{ old('notes', $warranty->notes) }}</textarea>

                            <x-admin::form.control-group.error control-name="notes" />
                        </x-admin::form.control-group>
                    </x-slot:content>
                </x-admin::accordion>

                <!-- Warranty Information (readonly) -->
                <x-admin::accordion>
                    <x-slot:header>
                        <div class="flex items-center justify-between">
                            <p class="p-2.5 text-gray-800 dark:text-white text-base  font-semibold">
                                {{ trans('warranty::app.admin.warranties.create.warranty-info') }}
                            </p>
                        </div>
                    </x-slot:header>

                    <x-slot:content>
                        <div class="grid grid-cols-2 gap-4">
                            <!-- Package -->
                            <x-admin::form.control-group class="mb-2.5">
                                <x-admin::form.control-group.label>
                                    {{ trans('warranty::app.admin.warranties.fields.warranty-package') }}
                                </x-admin::form.control-group.label>

                                <input 
                                    type="text" 
                                    value="{{ $warranty->warrantyPackage->name ?? 'N/A' }}" 
                                    readonly 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-700"
                                />
                            </x-admin::form.control-group>

                            <!-- Purchase Date -->
                            <x-admin::form.control-group class="mb-2.5">
                                <x-admin::form.control-group.label>
                                    {{ trans('warranty::app.admin.warranties.fields.purchase-date') }}
                                </x-admin::form.control-group.label>

                                <input 
                                    type="text" 
                                    value="{{ $warranty->purchase_date ? $warranty->purchase_date->format('d/m/Y') : 'N/A' }}" 
                                    readonly 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-700"
                                />
                            </x-admin::form.control-group>

                            <!-- Start Date -->
                            <x-admin::form.control-group class="mb-2.5">
                                <x-admin::form.control-group.label>
                                    {{ trans('warranty::app.admin.warranties.fields.start-date') }}
                                </x-admin::form.control-group.label>

                                <input 
                                    type="text" 
                                    value="{{ $warranty->start_date->format('d/m/Y') }}" 
                                    readonly 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-700"
                                />
                            </x-admin::form.control-group>

                            <!-- End Date -->
                            <x-admin::form.control-group class="mb-2.5">
                                <x-admin::form.control-group.label>
                                    {{ trans('warranty::app.admin.warranties.fields.end-date') }}
                                </x-admin::form.control-group.label>

                                <input 
                                    type="text" 
                                    value="{{ $warranty->end_date->format('d/m/Y') }}" 
                                    readonly 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-700"
                                />
                            </x-admin::form.control-group>
                        </div>
                    </x-slot:content>
                </x-admin::accordion>

                <!-- Product Information (readonly) -->
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
                            <!-- Product Name -->
                            <x-admin::form.control-group class="mb-2.5">
                                <x-admin::form.control-group.label>
                                    {{ trans('warranty::app.admin.warranties.fields.product') }}
                                </x-admin::form.control-group.label>

                                <input 
                                    type="text" 
                                    value="{{ $warranty->product_name }}" 
                                    readonly 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-700"
                                />
                            </x-admin::form.control-group>

                            <!-- Product Serial -->
                            <x-admin::form.control-group class="mb-2.5">
                                <x-admin::form.control-group.label>
                                    {{ trans('warranty::app.admin.warranties.fields.product-serial') }}
                                </x-admin::form.control-group.label>

                                <input 
                                    type="text" 
                                    value="{{ $warranty->product_serial }}" 
                                    readonly 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-700"
                                />
                            </x-admin::form.control-group>
                        </div>
                    </x-slot:content>
                </x-admin::accordion>

                <!-- Customer Information (readonly) -->
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
                            <!-- Customer Name -->
                            <x-admin::form.control-group class="mb-2.5">
                                <x-admin::form.control-group.label>
                                    {{ trans('warranty::app.admin.warranties.fields.customer') }}
                                </x-admin::form.control-group.label>

                                <input 
                                    type="text" 
                                    value="{{ $warranty->customer_name }}" 
                                    readonly 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-700"
                                />
                            </x-admin::form.control-group>

                            <!-- Customer Phone -->
                            <x-admin::form.control-group class="mb-2.5">
                                <x-admin::form.control-group.label>
                                    {{ trans('warranty::app.admin.warranties.fields.customer-phone') }}
                                </x-admin::form.control-group.label>

                                <input 
                                    type="text" 
                                    value="{{ $warranty->customer_phone }}" 
                                    readonly 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-700"
                                />
                            </x-admin::form.control-group>

                            <!-- Customer Email -->
                            <x-admin::form.control-group class="mb-2.5">
                                <x-admin::form.control-group.label>
                                    {{ trans('warranty::app.admin.warranties.fields.customer-email') }}
                                </x-admin::form.control-group.label>

                                <input 
                                    type="text" 
                                    value="{{ $warranty->customer_email ?? 'N/A' }}" 
                                    readonly 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-700"
                                />
                            </x-admin::form.control-group>
                        </div>
                    </x-slot:content>
                </x-admin::accordion>

            </div>
        </div>

        <div class="flex gap-x-2.5 items-center mt-5">
            <button 
                type="submit" 
                class="primary-button"
            >
                {{ trans('warranty::app.admin.warranties.edit.update-btn') }}
            </button>
        </div>
    </x-admin::form>
</x-admin::layouts>