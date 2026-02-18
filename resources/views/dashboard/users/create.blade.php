@extends('dashboard.layout.admin', ['title' => t('Add New User')])

@section('content')
    <!-- @include('dashboard.layout.shared/page-title', ['subtitle' => 'Add New User', 'title' => 'Dashboard']) -->

    <x-dashboard.outer-card :title="t('Add New User')">
        <x-slot:header>
            <div class="flex px-4 border-b-1 border-b-gray-500 flex-col items-stretch justify-between py-4 space-y-3 md:flex-row md:items-center md:space-y-0">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">{{ t('Add New User') }}</h2>
                @can('view users')
                    @if(app()->getLocale() === 'ar')
                        <x-inputs.button-primary as="a" href="{{ route('dashboard.users.index') }}">
                            {{ t('Back') }}
                            <x-heroicon-m-arrow-left class="h-4 w-4 ms-4 inline" />
                        </x-inputs.button-primary>
                    @else
                        <x-inputs.button-primary as="a" href="{{ route('dashboard.users.index') }}">
                            {{ t('Back') }}
                            <x-heroicon-m-arrow-right class="h-4 w-4 ms-4 inline" />
                        </x-inputs.button-primary>
                    @endif
                @endcan
            </div>
        </x-slot:header>
        <form method="POST" action="{{ route('dashboard.users.store') }}" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-white mb-2">
                        {{ t('Name') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}"
                           class="form-input @error('name') !border-red-500 @enderror">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ t($message) }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ t('Email') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                           class="form-input @error('email') !border-red-500 @enderror">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ t($message) }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ t('Phone') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone') }}" 
                           class="form-input @error('phone') !border-red-500 @enderror">
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ t($message) }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ t('Password') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="password" id="password" name="password" 
                           class="form-input @error('password') !border-red-500 @enderror">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ t($message) }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ t('Confirm Password') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                           class="form-input">
                </div>

                <div>
                    <label for="roles" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ t('Role') }}
                    </label>
                    <select id="roles" name="roles"
                            class="form-select">
                        <option value="">{{ t('Select Role') }}</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" 
                                    data-name="{{ $role->name }}"
                                    {{ old('roles') == $role->id ? 'selected' : '' }}>
                                {{ t($role->name) }}
                            </option>
                        @endforeach
                    </select>
                    @error('roles')
                        <p class="mt-1 text-sm text-red-600">{{ t($message) }}</p>
                    @enderror
                </div>

                <div id="branch-container" style="display: none;">
                    <label for="branch_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ t('Branch') }} <span class="text-red-500">*</span>
                    </label>
                    <select id="branch_id" name="branch_id"
                            class="form-select">
                        <option value="">{{ t('Select Branch') }}</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                {{ $branch->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('branch_id')
                        <p class="mt-1 text-sm text-red-600">{{ t($message) }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center">
                <input type="checkbox" id="is_active" name="is_active" checked value="1" {{ old('is_active') ? 'checked' : '' }}
                       class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                <label for="is_active" class="mx-2 block text-sm text-gray-700 dark:text-gray-300">
                    {{ t('Activate the account directly') }}
                </label>
            </div>

            <div class="flex justify-end space-x-4">
                <a href="{{ route('dashboard.users.index') }}" 
                   class="btn btn-outline-secondary">
                    {{ t('Cancel') }}
                </a>
                <button type="submit" 
                        class="btn btn-primary">
                    {{ t('Save') }}
                </button>
            </div>
        </form>
    </x-dashboard.outer-card>
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const roleSelect = document.getElementById('roles');
    const branchContainer = document.getElementById('branch-container');
    const branchSelect = document.getElementById('branch_id');

    function toggleBranchField() {
        if (!roleSelect) return;
        const selectedOption = roleSelect.options[roleSelect.selectedIndex];
        const roleName = selectedOption ? selectedOption.getAttribute('data-name') : '';
        
        // roles that manage all branches (super_admin, admin)
        const globalRoles = ['super_admin', 'manager', 'المشرف الاعلى', 'مدير'];
        
        if (roleName && !globalRoles.includes(roleName)) {
            branchContainer.style.display = 'block';
            branchSelect.setAttribute('required', 'required');
        } else {
            branchContainer.style.display = 'none';
            branchSelect.removeAttribute('required');
            branchSelect.value = '';
        }
    }

    if (roleSelect) {
        roleSelect.addEventListener('change', toggleBranchField);
        // Initial check
        toggleBranchField();
    }
});
</script>
@endsection