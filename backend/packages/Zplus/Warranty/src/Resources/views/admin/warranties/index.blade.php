@extends('admin::layouts.content')

@section('page_title')
    {{ trans('warranty::app.admin.warranties.index.title') }}
@stop

@section('content')
    <div class="flex justify-between items-center">
        <div class="flex flex-col gap-2">
            <div class="text-xl text-gray-800 dark:text-white font-bold">
                {{ trans('warranty::app.admin.warranties.index.title') }}
            </div>
        </div>

        <div class="flex gap-x-2.5 items-center">
            <a 
                href="{{ route('admin.warranty.create') }}"
                class="primary-button"
            >
                {{ trans('warranty::app.admin.warranties.index.create-btn') }}
            </a>
        </div>
    </div>

    {!! view_render_event('bagisto.admin.warranty.warranties.list.before') !!}

    <x-admin::datagrid src="{{ route('admin.warranty.api.warranties') }}">
        <!-- Search Bar -->
        <template #header>
            <div class="flex items-center gap-x-2.5">
                <x-admin::form.control-group>
                    <x-admin::form.control-group.control
                        type="text"
                        id="search"
                        name="search"
                        placeholder="{{ trans('warranty::app.admin.warranties.index.search-placeholder') }}"
                        v-model="applied.search.value"
                        @keyup.enter="searchData"
                    />
                </x-admin::form.control-group>

                <button 
                    type="button"
                    class="primary-button" 
                    @click="searchData"
                >
                    {{ trans('admin::app.datagrid.search') }}
                </button>
            </div>
        </template>

        <!-- Table Structure -->
        <template #table>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th class="px-6 py-3">{{ trans('warranty::app.admin.warranties.index.datagrid.warranty-number') }}</th>
                            <th class="px-6 py-3">{{ trans('warranty::app.admin.warranties.index.datagrid.product-name') }}</th>
                            <th class="px-6 py-3">{{ trans('warranty::app.admin.warranties.index.datagrid.product-serial') }}</th>
                            <th class="px-6 py-3">{{ trans('warranty::app.admin.warranties.index.datagrid.customer-name') }}</th>
                            <th class="px-6 py-3">{{ trans('warranty::app.admin.warranties.index.datagrid.customer-phone') }}</th>
                            <th class="px-6 py-3">{{ trans('warranty::app.admin.warranties.index.datagrid.package') }}</th>
                            <th class="px-6 py-3">{{ trans('warranty::app.admin.warranties.index.datagrid.end-date') }}</th>
                            <th class="px-6 py-3">{{ trans('warranty::app.admin.warranties.index.datagrid.status') }}</th>
                            <th class="px-6 py-3">{{ trans('warranty::app.admin.warranties.index.datagrid.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="record in records" :key="record.id" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                @{{ record.warranty_number }}
                            </td>
                            <td class="px-6 py-4">
                                @{{ record.product_name }}
                            </td>
                            <td class="px-6 py-4">
                                @{{ record.product_serial }}
                            </td>
                            <td class="px-6 py-4">
                                @{{ record.customer_name }}
                            </td>
                            <td class="px-6 py-4">
                                @{{ record.customer_phone }}
                            </td>
                            <td class="px-6 py-4">
                                @{{ record.warranty_package?.name }}
                            </td>
                            <td class="px-6 py-4">
                                @{{ record.end_date }}
                            </td>
                            <td class="px-6 py-4">
                                <span :class="'badge ' + (record.status === 'active' ? 'badge-success' : record.status === 'expired' ? 'badge-danger' : 'badge-warning')">
                                    @{{ record.status }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-x-2">
                                    <a 
                                        :href="'{{ route('admin.warranty.show', '') }}/' + record.id"
                                        class="text-blue-600 hover:text-blue-900"
                                    >
                                        {{ trans('warranty::app.admin.warranties.index.datagrid.view') }}
                                    </a>
                                    <a 
                                        :href="'{{ route('admin.warranty.edit', '') }}/' + record.id"
                                        class="text-green-600 hover:text-green-900"
                                    >
                                        {{ trans('warranty::app.admin.warranties.index.datagrid.edit') }}
                                    </a>
                                    <button 
                                        @click="deleteWarranty(record.id)"
                                        class="text-red-600 hover:text-red-900"
                                    >
                                        {{ trans('warranty::app.admin.warranties.index.datagrid.delete') }}
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </template>
    </x-admin::datagrid>

    {!! view_render_event('bagisto.admin.warranty.warranties.list.after') !!}

@push('scripts')
    <script>
        function deleteWarranty(id) {
            if (confirm('{{ trans('admin::app.datagrid.delete-confirm') }}')) {
                fetch(`{{ route('admin.warranty.destroy', '') }}/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.message) {
                        location.reload();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            }
        }
    </script>
@endpush

@stop