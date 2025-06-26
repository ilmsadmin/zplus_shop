<x-admin::layouts>
    <!-- Page Title -->
    <x-slot:title>
        {{ trans('warranty::app.admin.packages.show.title') }}
    </x-slot>

    <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
        <p class="py-3 text-xl font-bold text-gray-800 dark:text-white">
            {{ trans('warranty::app.admin.packages.show.title') }}
        </p>

        <div class="flex items-center gap-x-2.5">
            <!-- Back Button -->
            <a
                href="{{ route('admin.warranty.packages.index') }}"
                class="transparent-button hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800"
            >
                @lang('admin::app.account.edit.back-btn')
            </a>

            <!-- Edit Button -->
            <a
                href="{{ route('admin.warranty.packages.edit', $package->id) }}"
                class="primary-button"
            >
                @lang('admin::app.account.edit.edit-btn')
            </a>
        </div>
    </div>

    <!-- Package Details -->
    <div class="flex gap-2.5 mt-3.5 max-xl:flex-wrap">
        <!-- Left Panel -->
        <div class="flex flex-col gap-2 flex-1 max-xl:flex-auto">
            <!-- General Information -->
            <div class="p-4 bg-white dark:bg-gray-900 rounded box-shadow">
                <p class="mb-4 text-base text-gray-800 dark:text-white font-semibold">
                    {{ trans('warranty::app.admin.packages.show.general-info') }}
                </p>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-gray-600 dark:text-gray-300 font-medium">
                            {{ trans('warranty::app.admin.packages.show.name') }}
                        </label>
                        <p class="text-gray-800 dark:text-white">{{ $package->name }}</p>
                    </div>

                    <div>
                        <label class="text-gray-600 dark:text-gray-300 font-medium">
                            {{ trans('warranty::app.admin.packages.show.duration') }}
                        </label>
                        <p class="text-gray-800 dark:text-white">{{ $package->duration_months }} {{ trans('warranty::app.admin.packages.show.months') }}</p>
                    </div>

                    <div>
                        <label class="text-gray-600 dark:text-gray-300 font-medium">
                            {{ trans('warranty::app.admin.packages.show.price') }}
                        </label>
                        <p class="text-gray-800 dark:text-white">{{ core()->formatPrice($package->price) }}</p>
                    </div>

                    <div>
                        <label class="text-gray-600 dark:text-gray-300 font-medium">
                            {{ trans('warranty::app.admin.packages.show.status') }}
                        </label>
                        <span class="badge {{ $package->is_active ? 'badge-md-success' : 'badge-md-danger' }}">
                            {{ $package->is_active ? trans('warranty::app.admin.packages.show.active') : trans('warranty::app.admin.packages.show.inactive') }}
                        </span>
                    </div>

                    @if($package->description)
                    <div class="col-span-2">
                        <label class="text-gray-600 dark:text-gray-300 font-medium">
                            {{ trans('warranty::app.admin.packages.show.description') }}
                        </label>
                        <p class="text-gray-800 dark:text-white">{{ $package->description }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Panel -->
        <div class="flex flex-col gap-2 w-[360px] max-w-full max-sm:w-full">
            <!-- Statistics -->
            <div class="p-4 bg-white dark:bg-gray-900 rounded box-shadow">
                <p class="mb-4 text-base text-gray-800 dark:text-white font-semibold">
                    {{ trans('warranty::app.admin.packages.show.statistics') }}
                </p>

                <div class="grid gap-4">
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-300">{{ trans('warranty::app.admin.packages.show.total-warranties') }}</span>
                        <span class="font-semibold text-gray-800 dark:text-white">{{ $package->warranties_count ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin::layouts>
