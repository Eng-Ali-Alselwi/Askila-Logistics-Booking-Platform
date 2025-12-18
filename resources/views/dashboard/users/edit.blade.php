@extends('dashboard.layout.admin', ['title' => t('Edit User')])

@section('content')
    @include('dashboard.layout.shared/page-title', ['subtitle' => 'Edit User', 'title' => 'Dashboard'])

    <x-dashboard.outer-card :title="t('Edit User')">
        <x-slot:header>
            <div class="flex px-4 border-b-1 border-b-gray-500 flex-col items-stretch justify-between py-4 space-y-3 md:flex-row md:items-center md:space-y-0">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">{{ t('Edit User') }}</h2>
                <div class="flex gap-2">
                    <x-inputs.button-outlined as="a" href="{{ route('dashboard.users.index') }}">
                        <x-heroicon-m-arrow-left class="h-5 w-5 me-2 inline" />
                        {{ t('Back to Users') }}
                    </x-inputs.button-outlined>
                </div>
            </div>
        </x-slot:header>

        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800">
            <form method="POST" action="{{ route('dashboard.users.update', $user) }}" class="p-6 space-y-6">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ t('Name') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                               class="form-input @error('name') !border-red-500 @enderror">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ t('Email') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                               class="form-input @error('email') !border-red-500 @enderror">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ t('Phone') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" required
                               class="form-input @error('phone') !border-red-500 @enderror">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ t('New Password') }} <span class="text-gray-500 text-xs">({{ t('Leave empty to keep current password') }})</span>
                        </label>
                        <input type="password" id="password" name="password"
                               class="form-input @error('password') !border-red-500 @enderror">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Confirmation -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ t('Confirm New Password') }}
                        </label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                               class="form-input @error('password_confirmation') !border-red-500 @enderror">
                        @error('password_confirmation')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Is Active -->
                    <div class="flex items-center cursor-pointer">
                        <input type="checkbox" id="is_active" name="is_active" value="1" 
                                class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded"
                               {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                               @if($user->hasRole('super_admin')) disabled @endif>
                        <label for="is_active" class="mx-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{ t('Active User') }}
                            @if($user->hasRole('super_admin'))
                                <span class="text-xs text-gray-500">({{ t('Super admin cannot be deactivated') }})</span>
                            @endif
                        </label>
                        @error('is_active')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Roles Section -->
                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">{{ t('User Roles') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($roles as $role)
                            <div class="flex items-center">
                                <input type="checkbox" id="role_{{ $role->id }}" name="roles[]" value="{{ $role->id }}"
                                       {{ in_array($role->id, old('roles', $user->roles->pluck('id')->toArray())) ? 'checked' : '' }}
                                       class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                <label for="role_{{ $role->id }}" class="mx-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ t($role->name) }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                    @error('roles')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- User Info Display -->
                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">{{ t('User Information') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-600 dark:text-gray-400">
                        <div>
                            <span class="font-medium">{{ t('Created At') }}:</span>
                            <span>{{ $user->created_at->format('Y-m-d H:i') }}</span>
                        </div>
                        <div>
                            <span class="font-medium">{{ t('Updated At') }}:</span>
                            <span>{{ $user->updated_at->format('Y-m-d H:i') }}</span>
                        </div>
                        <div>
                            <span class="font-medium">{{ t('Email Verified') }}:</span>
                            <span class="{{ $user->email_verified_at ? 'text-green-600' : 'text-red-600' }}">
                                {{ $user->email_verified_at ? t('Yes') : t('No') }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-between items-center pt-6 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex gap-3">
                        <x-inputs.button-primary type="submit">
                            <x-heroicon-m-check class="h-5 w-5 me-2 inline" />
                            {{ t('Update User') }}
                        </x-inputs.button-primary>
                        
                        <x-inputs.button-outlined as="a" href="{{ route('dashboard.users.index') }}">
                            {{ t('Cancel') }}
                        </x-inputs.button-outlined>
                    </div>

                    <!-- Danger Zone -->
                    @if(!$user->hasRole('super_admin'))
                        <div class="flex gap-2">
                            <!-- Delete User -->
                            <button type="button" id="delete-user-button"
                                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 border border-transparent rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                                <x-heroicon-m-trash class="h-4 w-4 me-2" />
                                {{ t('Delete User') }}
                            </button>
                        </div>
                    @endif
                </div>
            </form>
            
            <!-- Hidden delete form -->
            @if(!$user->hasRole('super_admin'))
            <form id="delete-user-form" method="POST" action="{{ route('dashboard.users.destroy', $user) }}" class="hidden">
                @csrf
                @method('DELETE')
            </form>
            @endif
        </div>
    </x-dashboard.outer-card>
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Password confirmation validation
    const password = document.getElementById('password');
    const passwordConfirmation = document.getElementById('password_confirmation');
    
    function validatePassword() {
        if (password.value && passwordConfirmation.value) {
            if (password.value !== passwordConfirmation.value) {
                passwordConfirmation.setCustomValidity('{{ t("Passwords do not match") }}');
            } else {
                passwordConfirmation.setCustomValidity('');
            }
        } else {
            passwordConfirmation.setCustomValidity('');
        }
    }
    
    password.addEventListener('input', validatePassword);
    passwordConfirmation.addEventListener('input', validatePassword);
    
    // Form submission confirmation - target the main form specifically
    const mainForm = document.querySelector('form[method="POST"][action*="users"]:not(#delete-user-form)');
    if (mainForm) {
        mainForm.addEventListener('submit', function(e) {
            // Check if at least one role is selected
            const roleCheckboxes = mainForm.querySelectorAll('input[name="roles[]"]');
            const hasSelectedRole = Array.from(roleCheckboxes).some(checkbox => checkbox.checked);
            
            if (!hasSelectedRole) {
                if (!confirm('{{ t("No roles selected. The user will have no permissions. Continue?") }}')) {
                    e.preventDefault();
                }
            }
        });
    }
    
    // Delete confirmation
    const deleteButton = document.getElementById('delete-user-button');
    const deleteForm = document.getElementById('delete-user-form');
    
    if (deleteButton && deleteForm) {
        deleteButton.addEventListener('click', function(e) {
            e.preventDefault();
            if (confirm('{{ t('Are you sure you want to delete this user? This action cannot be undone.') }}')) {
                deleteForm.submit();
            }
        });
    }
});
</script>
@endsection
