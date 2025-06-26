<x-admin::layouts>
    <!-- Page Title -->
    <x-slot:title>
        {{ trans('warranty::app.admin.packages.edit.title') }}
    </x-slot>

    <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
        <p class="py-3 text-xl font-bold text-gray-800 dark:text-white">
            {{ trans('warranty::app.admin.packages.edit.title') }}
        </p>

        <div class="flex items-center gap-x-2.5">
            <!-- Back Button -->
            <a
                href="{{ route('admin.warranty.packages.index') }}"
                class="transparent-button hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800"
            >
                @lang('admin::app.account.edit.back-btn')
            </a>
        </div>
    </div>

    <!-- Edit Form -->
    <x-admin::form
        :action="route('admin.warranty.packages.update', $package->id)"
        method="PUT"
        enctype="multipart/form-data"
    >
        <div class="flex gap-2.5 mt-3.5 max-xl:flex-wrap">
            <!-- Left Panel -->
            <div class="flex flex-col gap-2 flex-1 max-xl:flex-auto">
                <!-- General Information -->
                <div class="p-4 bg-white dark:bg-gray-900 rounded box-shadow">
                    <p class="mb-4 text-base text-gray-800 dark:text-white font-semibold">
                        {{ trans('warranty::app.admin.packages.edit.general-info') }}
                    </p>

                    <!-- Name -->
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label class="required">
                            @lang('warranty::app.admin.packages.edit.name')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="text"
                            name="name"
                            :value="old('name') ?: $package->name"
                            id="name"
                            rules="required"
                            :label="trans('warranty::app.admin.packages.edit.name')"
                            :placeholder="trans('warranty::app.admin.packages.edit.name')"
                        />

                        <x-admin::form.control-group.error control-name="name" />
                    </x-admin::form.control-group>

                    <!-- Duration -->
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label class="required">
                            @lang('warranty::app.admin.packages.edit.duration-months')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="number"
                            name="duration_months"
                            :value="old('duration_months') ?: $package->duration_months"
                            id="duration_months"
                            rules="required|min_value:1"
                            :label="trans('warranty::app.admin.packages.edit.duration-months')"
                            :placeholder="trans('warranty::app.admin.packages.edit.duration-months')"
                        />

                        <x-admin::form.control-group.error control-name="duration_months" />
                    </x-admin::form.control-group>

                    <!-- Price -->
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label>
                            @lang('warranty::app.admin.packages.edit.price')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="number"
                            name="price"
                            :value="old('price') ?: $package->price"
                            id="price"
                            step="0.01"
                            :label="trans('warranty::app.admin.packages.edit.price')"
                            :placeholder="trans('warranty::app.admin.packages.edit.price')"
                        />

                        <x-admin::form.control-group.error control-name="price" />
                    </x-admin::form.control-group>

                    <!-- Description -->
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label>
                            @lang('warranty::app.admin.packages.edit.description')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="textarea"
                            name="description"
                            :value="old('description') ?: $package->description"
                            id="description"
                            :label="trans('warranty::app.admin.packages.edit.description')"
                            :placeholder="trans('warranty::app.admin.packages.edit.description')"
                        />

                        <x-admin::form.control-group.error control-name="description" />
                    </x-admin::form.control-group>
                </div>
            </div>

            <!-- Right Panel -->
            <div class="flex flex-col gap-2 w-[360px] max-w-full max-sm:w-full">
                <!-- Settings -->
                <x-admin::accordion>
                    <x-slot:header>
                        <div class="flex items-center justify-between">
                            <p class="p-2.5 text-base font-semibold text-gray-800 dark:text-white">
                                @lang('warranty::app.admin.packages.edit.settings')
                            </p>
                        </div>
                    </x-slot>

                    <x-slot:content>
                        <!-- Status -->
                        <x-admin::form.control-group class="!mb-0">
                            <x-admin::form.control-group.label>
                                @lang('warranty::app.admin.packages.edit.status')
                            </x-admin::form.control-group.label>

                            <label class="flex items-center cursor-pointer">
                                <input 
                                    type="hidden" 
                                    name="is_active" 
                                    value="0"
                                />
                                <input 
                                    type="checkbox" 
                                    name="is_active" 
                                    value="1"
                                    {{ old('is_active', $package->is_active) ? 'checked' : '' }}
                                    class="mr-2 rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                />
                                <span class="text-sm text-gray-700 dark:text-gray-300">Kích hoạt gói bảo hành</span>
                            </label>

                            <x-admin::form.control-group.error control-name="is_active" />
                        </x-admin::form.control-group>
                    </x-slot>
                </x-admin::accordion>
            </div>
        </div>

        <!-- Save Button -->
        <div class="flex gap-2.5 items-center justify-end mt-3.5">
            <button
                type="submit"
                class="primary-button"
            >
                @lang('warranty::app.admin.packages.edit.save-btn')
            </button>
        </div>
    </x-admin::form>
</x-admin::layouts>
