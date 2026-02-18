@extends('dashboard.layout.admin', ['title' => t('User Details')])

@section('content')
    <x-dashboard.outer-card :title="t('User Details')">
        <x-slot:header>
            <div class="flex px-4 border-b-1 border-b-gray-500 flex-col items-stretch justify-between py-4 space-y-3 md:flex-row md:items-center md:space-y-0">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">{{ t('User Details') }}</h2>
                <div class="flex gap-2">
                    <x-inputs.button-primary as="a" href="{{ route('dashboard.users.edit', $user) }}">
                        {{ t('Edit User') }}
                        <x-heroicon-m-pencil class="h-5 w-5 ms-4 inline" />
                    </x-inputs.button-primary>
                    @if(app()->getLocale() === 'ar')
                        <x-inputs.button-primary as="a" href="{{ route('dashboard.users.index') }}">
                            {{ t('Back to Users') }}
                            <x-heroicon-m-arrow-left class="h-5 w-5 ms-4 inline" />
                        </x-inputs.button-primary>
                    @else
                        <x-inputs.button-primary as="a" href="{{ route('dashboard.users.index') }}">
                            {{ t('Back to Users') }}
                            <x-heroicon-m-arrow-right class="h-5 w-5 ms-4 inline" />
                        </x-inputs.button-primary>
                    @endif
                </div>
            </div>
        </x-slot:header>

        <div class="px-6">
            <!-- User Profile Section -->
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800 p-6 mb-6">
                <div class="flex items-center space-x-6">
                    <div class="flex-shrink-0">
                        <img class="h-20 w-20 rounded-full object-cover" src="{{ $user->image_url }}" alt="{{ $user->name }}">
                    </div>
                    <div class="flex-1">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $user->name }}</h3>
                        <p class="text-gray-600 dark:text-gray-400 py-3">{{ $user->email }}</p>
                        <div class="mt-2 flex items-center space-x-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                       {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                <span class="w-2 h-2 mx-2 rounded-full {{ $user->is_active ? 'bg-green-400' : 'bg-red-400' }}"></span>
                                {{ $user->is_active ? t('Active') : t('Inactive') }}
                            </span>
                            @if($user->email_verified_at)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    <x-heroicon-m-check-badge class="w-4 h-4 mx-2" />
                                    {{ t('Verified') }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                    <x-heroicon-m-exclamation-triangle class="w-4 h-4 mx-2" />
                                    {{ t('Unverified') }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Information Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                <!-- Contact Information -->
                <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800 p-6">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ t('Contact Information') }}</h4>
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <x-heroicon-m-envelope class="h-5 w-5 text-gray-400 mx-3" />
                            <span class="text-sm text-gray-600 dark:text-gray-400">{{ $user->email }}</span>
                        </div>
                        @if($user->phone)
                            <div class="flex items-center">
                                <x-heroicon-m-phone class="h-5 w-5 text-gray-400 mx-3" />
                                <span class="text-sm text-gray-600 dark:text-gray-400">{{ $user->phone }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                <div class="md:col-span-1 lg:col-span-2 bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800 p-6">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ t('Actions') }}</h4>
                    <div class="flex flex-wrap gap-3">
                        <x-inputs.button-primary as="a" href="{{ route('dashboard.users.edit', $user) }}">
                            <x-heroicon-m-pencil class="h-4 w-4 mx-2" />
                            {{ t('Edit User') }}
                        </x-inputs.button-primary>

                        @if(!$user->hasRole('super_admin'))
                            <!-- Toggle Status -->
                            <form method="POST" action="{{ route('dashboard.users.toggle-status', $user) }}" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" 
                                        class="inline-flex items-center px-4 py-4  text-white 
                                            {{ $user->is_active ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-green-600 hover:bg-green-700' }} 
                                            border border-transparent rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 
                                            {{ $user->is_active ? 'focus:ring-yellow-500' : 'focus:ring-green-500' }} transition-colors duration-200">
                                    @if($user->is_active)
                                        <x-heroicon-m-pause class="h-4 w-4 mx-2" />
                                        {{ t('Deactivate') }}
                                    @else
                                        <x-heroicon-m-play class="h-4 w-4 mx-2" />
                                        {{ t('Activate') }}
                                    @endif
                                </button>
                            </form>

                            <!-- Delete User -->
                            <form method="POST" action="{{ route('dashboard.users.destroy', $user) }}" 
                                x-data
                                @submit.prevent="Alpine.store('confirm').ask(() => $el.submit(), '{{ t('Delete User') }}', '{{ t('Are you sure you want to delete this user? This action cannot be undone.') }}')"
                                class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="inline-flex items-center px-4 py-4 text-white bg-red-600 hover:bg-red-700 border border-transparent rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                                    <x-heroicon-m-trash class="h-4 w-4 mx-2" />
                                    {{ t('Delete User') }}
                                </button>
                            </form>
                        @else
                            <div class="inline-flex items-center px-4 py-4 text-gray-500 bg-gray-100 border border-gray-300 rounded-lg">
                                <x-heroicon-m-shield-check class="h-4 w-4 mx-2" />
                                {{ t('Super Admin - Protected') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- User Roles and Permissions -->
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800 p-6 mb-6">
                <!-- <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">{{ t('Roles and Permissions') }}</h4> -->
                
                @if($user->roles->count() > 0)
                    <div class="mb-6 ">
                        <h5 class="text-md font-medium text-gray-700 dark:text-gray-300 mb-3">{{ t('Assigned Roles') }}</h5>
                        <div class="flex flex-wrap gap-2">
                            @foreach($user->roles as $role)
                                <span class="inline-flex items-center px-3 py-1 rounded-xs text-sm font-medium bg-primary-100 text-primary-800">
                                    <x-heroicon-m-shield-check class="w-4 h-4 mx-1" />
                                    {{ t($role->name) }}
                                </span>
                            @endforeach
                        </div>
                    </div>

                    <!-- Permissions from Roles -->
                    <div>
                        <h5 class="text-md font-medium text-gray-700 dark:text-gray-300 mb-3">{{ t('Permissions') }}</h5>
                        @php
                            $permissions = $user->getAllPermissions();
                        @endphp
                        @if($permissions->count() > 0)
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-2">
                                @foreach($permissions as $permission)
                                    <span class="inline-flex items-center px-2 py-2 rounded text-sm font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                        <x-heroicon-m-key class="w-3 h-3 mx-1" />
                                        {{ t($permission->name) }}
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ t('No permissions assigned') }}</p>
                        @endif
                    </div>
                @else
                    <div class="text-center py-8">
                        <x-heroicon-o-shield-exclamation class="mx-auto h-12 w-12 text-gray-400" />
                        <h5 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">{{ t('No Roles Assigned') }}</h5>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ t('This user has no roles assigned and therefore no permissions.') }}</p>
                        <div class="mt-4">
                            <x-inputs.button-primary as="a" href="{{ route('dashboard.users.edit', $user) }}">
                                {{ t('Assign Roles') }}
                            </x-inputs.button-primary>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </x-dashboard.outer-card>
@endsection