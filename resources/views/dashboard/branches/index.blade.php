@extends('dashboard.layout.admin', ['title' => t('Branches Management')])

@section('content')


    <x-dashboard.outer-card :title="t('Branches')">
        <x-slot:header>
            <div class="px-4 border-b-1 border-b-gray-500 py-4 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">{{ t('Branches') }}</h2>
                @can('create branches')
                <x-inputs.button-primary as="a" href="{{ route('dashboard.branches.create') }}">
                    <x-heroicon-m-plus class="h-5 w-5 me-2 inline" />
                    {{ t('Add New Branch') }}
                </x-inputs.button-primary>
                @endcan
            </div>
        </x-slot:header>

        <!-- Filters -->
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
            <form method="GET" class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="{{ t('Search by name/code/city/manager') }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>
                <div>
                    <select name="city" class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500">
                        <option value="">{{ t('All Cities') }}</option>
                        @foreach($cities as $city)
                            <option value="{{ $city }}" {{ request('city') === $city ? 'selected' : '' }}>
                                {{ $city }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <select name="status" class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500">
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
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th class="px-6 py-3">{{ t('Name') }}</th>
                        <th class="px-6 py-3">{{ t('Code') }}</th>
                        <th class="px-6 py-3">{{ t('City') }}</th>
                        <th class="px-6 py-3">{{ t('Manager') }}</th>
                        <th class="px-6 py-3">{{ t('Users') }}</th>
                        <th class="px-6 py-3">{{ t('Shipments') }}</th>
                        <th class="px-6 py-3">{{ t('Status') }}</th>
                        <th class="px-6 py-3">{{ t('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($branches as $branch)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                {{ $branch->name }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs bg-gray-100 text-gray-800 rounded-full">
                                    {{ $branch->code }}
                                </span>
                            </td>
                            <td class="px-6 py-4">{{ $branch->city }}</td>
                            <td class="px-6 py-4">
                                <div>
                                    <div class="font-medium">{{ $branch->manager_name ?? '—' }}</div>
                                    @if($branch->manager_phone)
                                        <div class="text-sm text-gray-500">{{ $branch->manager_phone }}</div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded-full">
                                    {{ $branch->users_count }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">
                                    {{ $branch->shipments_count }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs rounded-full {{ $branch->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $branch->is_active ? t('Active') : t('Inactive') }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex space-x-2">
                                    @can('view branches')
                                    <a href="{{ route('dashboard.branches.show', $branch) }}" class="text-blue-600 hover:text-blue-800">
                                        <x-heroicon-o-eye class="h-4 w-4" />
                                    </a>
                                    @endcan
                                    @can('edit branches')
                                    <a href="{{ route('dashboard.branches.edit', $branch) }}" class="text-green-600 hover:text-green-800">
                                        <x-heroicon-o-pencil class="h-4 w-4" />
                                    </a>
                                    @endcan
                                    @can('activate branches')
                                    <form method="POST" action="{{ route('dashboard.branches.toggle-status', $branch) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-yellow-600 hover:text-yellow-800">
                                            <x-heroicon-o-{{ $branch->is_active ? 'x-mark' : 'check' }} class="h-4 w-4" />
                                        </button>
                                    </form>
                                    @endcan
                                    @can('delete branches')
                                    @php
                                        $confirmMessage = t('Are you sure you want to delete this branch?');
                                    @endphp
                                    <form method="POST" action="{{ route('dashboard.branches.destroy', $branch) }}" class="inline"
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
                                {{ t('No branches found') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4">
            {{ $branches->links() }}
        </div>
    </x-dashboard.outer-card>
@endsection
