@extends('dashboard.layout.admin', ['title' => t('Customers Management')])

@section('content')


    <x-dashboard.outer-card :title="t('Customers')">
        <x-slot:header>
            <div class="px-4 border-b-1 border-b-gray-500 py-4 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">{{ t('Customers') }}</h2>
                @can('create customers')
                <x-inputs.button-primary as="a" href="{{ route('dashboard.customers.create') }}">
                    <x-heroicon-m-plus class="h-5 w-5 me-2 inline" />
                    {{ t('Add New Customer') }}
                </x-inputs.button-primary>
                @endcan
            </div>
        </x-slot:header>

        <!-- Filters -->
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
            <form method="GET" class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="{{ t('Search by name/phone/email/city') }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>
                <div>
                    <select name="city" class="px-8 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500">
                        <option value="">{{ t('All Cities') }}</option>
                        @foreach($cities as $city)
                            <option value="{{ $city }}" {{ request('city') === $city ? 'selected' : '' }}>
                                {{ $city }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <select name="status" class="px-8 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500">
                        <option value="">{{ t('All Statuses') }}</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>{{ t('Active') }}</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>{{ t('Inactive') }}</option>
                    </select>
                </div>
                <div>
                    <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700">
                        {{ t('Search') }}
                    </button>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800 text-sm">
                <thead class="bg-gray-50 dark:bg-gray-800/50">
                    <tr>
                        <th class="px-6 py-3 border border-x-1 border-gray-200 dark:border-gray-600">{{ t('Name') }}</th>
                        <th class="px-6 py-3 border border-x-1 border-gray-200 dark:border-gray-600">{{ t('Phone') }}</th>
                        <th class="px-6 py-3 border border-x-1 border-gray-200 dark:border-gray-600">{{ t('Email') }}</th>
                        <th class="px-6 py-3 border border-x-1 border-gray-200 dark:border-gray-600">{{ t('City') }}</th>
                        <th class="px-6 py-3 border border-x-1 border-gray-200 dark:border-gray-600">{{ t('Shipments') }}</th>
                        <th class="px-6 py-3 border border-x-1 border-gray-200 dark:border-gray-600">{{ t('Status') }}</th>
                        <th class="px-6 py-3 border border-x-1 border-gray-200 dark:border-gray-600">{{ t('Created At') }}</th>
                        <th class="px-6 py-3 border border-x-1 border-gray-200 dark:border-gray-600">{{ t('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    @forelse($customers as $customer)
                        <tr class="hover:bg-gray-50/60 dark:hover:bg-gray-800/50">
                            <td class="px-2 py-3 text-sm border border-x-1 border-gray-200 dark:border-gray-600 text-center text-gray-900 dark:text-gray-100">
                                {{ $customer->name }}
                            </td>
                            <td class="px-2 py-3 text-sm border border-x-1 border-gray-200 dark:border-gray-600 text-center text-gray-900 dark:text-gray-100">
                                {{ $customer->phone }}
                            </td>
                            <td class="px-2 py-3 text-sm border border-x-1 border-gray-200 dark:border-gray-600 text-center text-gray-900 dark:text-gray-100">
                                {{ $customer->email ?? '—' }}
                            </td>
                            <td class="px-2 py-3 text-sm border border-x-1 border-gray-200 dark:border-gray-600 text-center text-gray-900 dark:text-gray-100">
                                {{ $customer->city }}
                            </td>
                            <td class="px-2 py-3 text-sm border border-x-1 border-gray-200 dark:border-gray-600 text-center text-gray-900 dark:text-gray-100">
                                <span class="px-2 py-1 text-xs">
                                    {{ $customer->shipments_count }}
                                </span>
                            </td>
                            <td class="px-2 py-3 text-sm border border-x-1 border-gray-200 dark:border-gray-600 text-center text-gray-900 dark:text-gray-100">
                                <span class="px-2 py-1 text-xs rounded-xs {{ $customer->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $customer->is_active ? t('Active') : t('Inactive') }}
                                </span>
                            </td>
                            <td class="px-2 py-3 text-sm border border-x-1 border-gray-200 dark:border-gray-600 text-center text-gray-900 dark:text-gray-100">{{ $customer->created_at->format('Y-m-d') }}</td>
                            <td class="px-2 py-3 text-sm border border-x-1 border-gray-200 dark:border-gray-600 text-center text-gray-900 dark:text-gray-100">
                                <div class="flex justify-evenly items-center">
                                    @can('view customers')
                                    <a href="{{ route('dashboard.customers.show', $customer) }}" class="text-blue-600 hover:text-blue-800">
                                        <x-heroicon-o-eye class="h-4 w-4" />
                                    </a>
                                    @endcan
                                    @can('edit customers')
                                    <a href="{{ route('dashboard.customers.edit', $customer) }}" class="text-green-600 hover:text-green-800">
                                        <x-heroicon-o-pencil class="h-4 w-4" />
                                    </a>
                                    @endcan
                                    @can('activate customers')
                                    <form method="POST" action="{{ route('dashboard.customers.toggle-status', $customer) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-yellow-600 hover:text-yellow-800">
                                            <x-heroicon-o-{{ $customer->is_active ? 'x-mark' : 'check' }} class="h-4 w-4" />
                                        </button>
                                    </form>
                                    @endcan
                                    @can('delete customers')
                                    @php
                                        $confirmMessage = t('Are you sure you want to delete this customer?');
                                    @endphp
                                    <form method="POST" action="{{ route('dashboard.customers.destroy', $customer) }}" class="inline" 
                                          onsubmit="return confirm('{{ $confirmMessage }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800">
                                            <x-heroicon-o-trash class="h-4 w-4" />
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                {{ t('No customers found') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4">
            {{ $customers->links() }}
        </div>
    </x-dashboard.outer-card>
@endsection
