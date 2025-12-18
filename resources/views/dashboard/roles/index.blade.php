@extends('dashboard.layout.admin', ['title' => t('Roles Management')])

@section('content')
    @include('dashboard.layout.shared/page-title', ['subtitle' => 'Roles Management', 'title' => 'Dashboard'])

    <x-dashboard.outer-card :title="t('Roles')">
        <x-slot:header>
            <div class="flex px-4 border-b-1 border-b-gray-500 flex-col items-stretch justify-between py-4 space-y-3 md:flex-row md:items-center md:space-y-0">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">{{ t('Roles') }}</h2>
                <x-inputs.button-primary as="a" href="{{ route('dashboard.roles.create') }}">
                    <x-heroicon-m-plus class="h-5 w-5 me-2 inline" />
                    {{ t('Add New Role') }}
                </x-inputs.button-primary>
            </div>
        </x-slot:header>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th class="px-6 py-3">{{ t('Name') }}</th>
                        <th class="px-6 py-3">{{ t('Permissions') }}</th>
                        <th class="px-6 py-3">{{ t('Users Count') }}</th>
                        <th class="px-6 py-3">{{ t('Created At') }}</th>
                        <th class="px-6 py-3">{{ t('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($roles as $role)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                <span class="px-2 py-1 text-xs bg-primary-100 text-primary-800 rounded-full">
                                    {{ ucfirst($role->name) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1">
                                    @foreach($role->permissions->take(3) as $permission)
                                        <span class="px-2 py-1 text-xs bg-gray-100 text-gray-800 rounded">
                                            {{ $permission->name }}
                                        </span>
                                    @endforeach
                                    @if($role->permissions->count() > 3)
                                        <span class="px-2 py-1 text-xs bg-gray-100 text-gray-800 rounded">
                                            +{{ $role->permissions->count() - 3 }} more
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">{{ $role->users_count ?? 0 }}</td>
                            <td class="px-6 py-4">{{ $role->created_at->format('Y-m-d') }}</td>
                            <td class="px-6 py-4">
                                <div class="flex space-x-2">
                                    <a href="{{ route('dashboard.roles.show', $role) }}" class="text-blue-600 hover:text-blue-800">
                                        <x-heroicon-o-eye class="h-4 w-4" />
                                    </a>
                                    @if($role->name !== 'super_admin')
                                        <a href="{{ route('dashboard.roles.edit', $role) }}" class="text-green-600 hover:text-green-800">
                                            <x-heroicon-o-pencil class="h-4 w-4" />
                                        </a>
                                        <form method="POST" action="{{ route('dashboard.roles.destroy', $role) }}" class="inline" 
                                              onsubmit="return confirm('{{ t('Are you sure you want to delete this role?') }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800">
                                                <x-heroicon-o-trash class="h-4 w-4" />
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                {{ t('No roles found') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4">
            {{ $roles->links() }}
        </div>
    </x-dashboard.outer-card>
@endsection
