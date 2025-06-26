@extends('admin::layouts.content')

@section('page_title')
    {{ trans('warranty::app.admin.packages.index.title') }}
@stop

@section('content')
    <div class="flex justify-between items-center">
        <div class="flex flex-col gap-2">
            <div class="text-xl text-gray-800 dark:text-white font-bold">
                {{ trans('warranty::app.admin.packages.index.title') }}
            </div>
        </div>

        <div class="flex gap-x-2.5 items-center">
            <a 
                href="{{ route('admin.warranty.packages.create') }}"
                class="primary-button"
            >
                {{ trans('warranty::app.admin.packages.index.create-btn') }}
            </a>
        </div>
    </div>

    {!! view_render_event('bagisto.admin.warranty.packages.list.before') !!}

    <x-admin::datagrid src="{{ route('admin.warranty.packages.api.packages') }}">
        <!-- Table Structure -->
        <template #table>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th class="px-6 py-3">{{ trans('warranty::app.admin.packages.index.datagrid.name') }}</th>
                            <th class="px-6 py-3">{{ trans('warranty::app.admin.packages.index.datagrid.duration') }}</th>
                            <th class="px-6 py-3">{{ trans('warranty::app.admin.packages.index.datagrid.price') }}</th>
                            <th class="px-6 py-3">{{ trans('warranty::app.admin.packages.index.datagrid.warranties-count') }}</th>
                            <th class="px-6 py-3">{{ trans('warranty::app.admin.packages.index.datagrid.status') }}</th>
                            <th class="px-6 py-3">{{ trans('warranty::app.admin.packages.index.datagrid.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="record in records" :key="record.id" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                @{{ record.name }}
                            </td>
                            <td class="px-6 py-4">
                                @{{ record.duration_months }} tháng
                            </td>
                            <td class="px-6 py-4">
                                @{{ record.price > 0 ? formatPrice(record.price) : 'Miễn phí' }}
                            </td>
                            <td class="px-6 py-4">
                                @{{ record.warranties_count || 0 }}
                            </td>
                            <td class="px-6 py-4">
                                <span :class="'badge ' + (record.is_active ? 'badge-success' : 'badge-secondary')">
                                    @{{ record.is_active ? '{{ trans('warranty::app.admin.packages.index.datagrid.active') }}' : '{{ trans('warranty::app.admin.packages.index.datagrid.inactive') }}' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-x-2">
                                    <a 
                                        :href="'{{ route('admin.warranty.packages.show', '') }}/' + record.id"
                                        class="text-blue-600 hover:text-blue-900"
                                    >
                                        {{ trans('warranty::app.admin.warranties.index.datagrid.view') }}
                                    </a>
                                    <a 
                                        :href="'{{ route('admin.warranty.packages.edit', '') }}/' + record.id"
                                        class="text-green-600 hover:text-green-900"
                                    >
                                        {{ trans('warranty::app.admin.warranties.index.datagrid.edit') }}
                                    </a>
                                    <button 
                                        @click="toggleStatus(record.id)"
                                        :class="record.is_active ? 'text-orange-600 hover:text-orange-900' : 'text-green-600 hover:text-green-900'"
                                    >
                                        @{{ record.is_active ? 'Tạm ngưng' : 'Kích hoạt' }}
                                    </button>
                                    <button 
                                        @click="deletePackage(record.id)"
                                        class="text-red-600 hover:text-red-900"
                                        v-if="record.warranties_count == 0"
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

    {!! view_render_event('bagisto.admin.warranty.packages.list.after') !!}

@push('scripts')
    <script>
        function formatPrice(price) {
            return new Intl.NumberFormat('vi-VN', { 
                style: 'currency', 
                currency: 'VND' 
            }).format(price);
        }

        function toggleStatus(id) {
            if (confirm('{{ trans('admin::app.datagrid.mass-action.confirm') }}')) {
                fetch(`{{ route('admin.warranty.packages.toggle-status', '') }}/${id}`, {
                    method: 'POST',
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

        function deletePackage(id) {
            if (confirm('{{ trans('admin::app.datagrid.delete-confirm') }}')) {
                fetch(`{{ route('admin.warranty.packages.destroy', '') }}/${id}`, {
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