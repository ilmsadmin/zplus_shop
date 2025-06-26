<x-admin::layouts>
    <!-- Page Title -->
    <x-slot:title>
        {{ trans('warranty::app.admin.packages.create.title') }}
    </x-slot>
    <div class="flex justify-between items-center">
        <div class="flex flex-col gap-2">
            <div class="text-xl text-gray-800 dark:text-white font-bold">
                {{ trans('warranty::app.admin.packages.create.title') }}
            </div>
        </div>

        <div class="flex gap-x-2.5 items-center">
            <a 
                href="{{ route('admin.warranty.packages.index') }}"
                class="secondary-button"
            >
                {{ trans('admin::app.datagrid.back') }}
            </a>
        </div>
    </div>

    <x-admin::form 
        action="{{ route('admin.warranty.packages.store') }}" 
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
                                Thông tin gói bảo hành
                            </p>
                        </div>
                    </x-slot:header>

                    <x-slot:content>
                        <!-- Package Name -->
                        <x-admin::form.control-group class="mb-2.5">
                            <x-admin::form.control-group.label class="required">
                                {{ trans('warranty::app.admin.packages.fields.name') }}
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="name"
                                rules="required"
                                :value="old('name')"
                                placeholder="Ví dụ: 6 tháng"
                            />

                            <x-admin::form.control-group.error control-name="name" />
                        </x-admin::form.control-group>

                        <!-- Duration -->
                        <x-admin::form.control-group class="mb-2.5">
                            <x-admin::form.control-group.label class="required">
                                {{ trans('warranty::app.admin.packages.fields.duration-months') }}
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="number"
                                name="duration_months"
                                rules="required|min:1|max:60"
                                :value="old('duration_months')"
                                placeholder="Nhập số tháng"
                                min="1"
                                max="60"
                            />

                            <x-admin::form.control-group.error control-name="duration_months" />
                        </x-admin::form.control-group>

                        <!-- Description -->
                        <x-admin::form.control-group class="mb-2.5">
                            <x-admin::form.control-group.label>
                                {{ trans('warranty::app.admin.packages.fields.description') }}
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="textarea"
                                name="description"
                                :value="old('description')"
                                placeholder="Mô tả gói bảo hành"
                                rows="3"
                            />

                            <x-admin::form.control-group.error control-name="description" />
                        </x-admin::form.control-group>

                        <!-- Price -->
                        <x-admin::form.control-group class="mb-2.5">
                            <x-admin::form.control-group.label>
                                {{ trans('warranty::app.admin.packages.fields.price') }}
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="number"
                                name="price"
                                :value="old('price', 0)"
                                placeholder="Giá gói bảo hành (0 = miễn phí)"
                                min="0"
                                step="1000"
                            />

                            <x-admin::form.control-group.error control-name="price" />
                            <x-admin::form.control-group.hint>
                                Để trống hoặc nhập 0 nếu gói bảo hành miễn phí
                            </x-admin::form.control-group.hint>
                        </x-admin::form.control-group>

                        <!-- Is Active -->
                        <x-admin::form.control-group class="mb-2.5">
                            <x-admin::form.control-group.label>
                                {{ trans('warranty::app.admin.packages.fields.is-active') }}
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="switch"
                                name="is_active"
                                :value="old('is_active', true)"
                            />

                            <x-admin::form.control-group.error control-name="is_active" />
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
                {{ trans('warranty::app.admin.packages.create.save-btn') }}
            </button>
        </div>
    </x-admin::form>
</x-admin::layouts>