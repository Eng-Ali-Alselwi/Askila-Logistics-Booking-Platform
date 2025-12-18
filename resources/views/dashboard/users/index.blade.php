@extends('dashboard.layout.admin', ['title' => t('Users Management')])

@section('content')
<x-dashboard.confirm />
    <!-- Start Content-->
    @include('dashboard.layout.shared/page-title', ['subtitle' => 'Users Management', 'title' => 'Dashboard'])

    <x-dashboard.outer-card :title="t('Users')">
        <x-slot:header>
            <div class="flex px-4 border-b-1 border-b-gray-500 flex-col items-stretch justify-between py-4 space-y-3 md:flex-row md:items-center md:space-y-0">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">{{ t('Users') }}</h2>
                @can('create users')
                <x-inputs.button-primary as="a" href="{{ route('dashboard.users.create') }}">
                    <x-heroicon-m-plus class="h-5 w-5 me-2 inline" />
                    {{ t('Add New User') }}
                </x-inputs.button-primary>
                @endcan
            </div>
        </x-slot:header>

        <!-- Filters -->
        <div class="py-4 border-b border-gray-200 dark:border-gray-700">
            <form method="GET" class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="{{ t('Search by name/email/mobile') }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>
                <div>
                    <select name="role" class="w-48 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500">
                        <option value="">{{ t('All roles') }}</option>
                        @foreach(\Spatie\Permission\Models\Role::all() as $role)
                            <option value="{{ $role->name }}" {{ request('role') === $role->name ? 'selected' : '' }}>
                                {{ t($role->name) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <select name="status" class="w-48 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500">
                        <option value="">{{ t('All statuses') }}</option>
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
            <table class="w-full text-sm text-gray-500 dark:text-gray-400 border border-gray-200 dark:border-gray-700">
                <thead class="text-xs font-bold text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400 border-b border-gray-200 dark:border-gray-700">
                    <tr>
                        <th class="py-3 border border-gray-200 dark:border-gray-700">{{ t('Name') }}</th>
                        <th class="py-3 border border-gray-200 dark:border-gray-700">{{ t('Email') }}</th>
                        <th class="py-3 border border-gray-200 dark:border-gray-700">{{ t('Phone') }}</th>
                        <th class="py-3 border border-gray-200 dark:border-gray-700">{{ t('Role') }}</th>
                        <th class="py-3 px-8 border border-gray-200 dark:border-gray-700">{{ t('Status') }}</th>
                        <th class="py-3 border border-gray-200 dark:border-gray-700">{{ t('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 text-center">
                            <td class="px-2 py-4 font-medium text-gray-900 dark:text-white border border-gray-200 dark:border-gray-700">
                                {{ $user->name }}
                            </td>
                            <td class="px-2 py-4 border border-gray-200 dark:border-gray-700">{{ $user->email }}</td>
                            <td class="px-2 py-4 border border-gray-200 dark:border-gray-700">{{ $user->phone }}</td>
                            <td class="px-2 py-4 border border-gray-200 dark:border-gray-700">
                                @foreach($user->roles as $role)
                                    <span class="px-2 py-1 text-xs text-primary-800 rounded-full">
                                        {{ t($role->name) }}
                                    </span>
                                @endforeach
                            </td>
                            <td class="px-2 py-4 border border-gray-200 dark:border-gray-700">
                                <span class="px-2 py-1 text-xs rounded-xs {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $user->is_active ? t('Active') : t('Inactive') }}
                                </span>
                            </td>
                            <td class="px-2 py-4 border border-gray-200 dark:border-gray-700">
                                <div class="flex items-center justify-center px-2 space-x-2">
                                    @can('view users')
                                        <a href="{{ route('dashboard.users.show', $user) }}" class="text-blue-600 hover:text-blue-800">
                                            <x-heroicon-o-eye class="h-4 w-4" />
                                        </a>
                                    @endcan
                                    @can('edit users')
                                        <a href="{{ route('dashboard.users.edit', $user) }}" class="text-green-600 hover:text-green-800">
                                            <x-heroicon-o-pencil class="h-4 w-4" />
                                        </a>
                                    @endcan
                                    @can('activate users')
                                    @if(!$user->hasRole('super_admin'))
                                        <form method="POST" action="{{ route('dashboard.users.toggle-status', $user) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-yellow-600 hover:text-yellow-800 pt-2">
                                                <x-heroicon-o-{{ $user->is_active ? 'x-mark' : 'check' }} class="h-4 w-4" />
                                            </button>
                                        </form>
                                    @endif
                                    @endcan
                                    @can('delete users')
                                    @if(!$user->hasRole('super_admin'))
                                        @php
                                            $confirmMessage = t('Are you sure you want to delete this user?');
                                        @endphp
                                        <form method="POST" action="{{ route('dashboard.users.destroy', $user) }}" class="inline" 
                                              onsubmit="return confirm('{{ $confirmMessage }}');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 pt-2">
                                                <x-heroicon-o-trash class="h-4 w-4" />
                                            </button>
                                        </form>
                                    @endif
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                {{ t('No users found') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="py-4">
            {{ $users->links() }}
        </div>
    </x-dashboard.outer-card>
@endsection