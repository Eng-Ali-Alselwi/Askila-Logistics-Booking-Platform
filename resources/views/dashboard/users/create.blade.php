@extends('dashboard.layout.admin', ['title' => t('Add New User')])

@section('content')
    @include('dashboard.layout.shared/page-title', ['subtitle' => 'Add New User', 'title' => 'Dashboard'])

    <x-dashboard.outer-card :title="t('Add New User')">
        <x-slot:header>
            <div class="flex px-4 border-b-1 border-b-gray-500 flex-col items-stretch justify-between py-4 space-y-3 md:flex-row md:items-center md:space-y-0">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">{{ t('Add New User') }}</h2>
            </div>
        </x-slot:header>
        <form method="POST" action="{{ route('dashboard.users.store') }}" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ t('Name') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                           class="form-input @error('name') !border-red-500 @enderror">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ t('Email') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                           class="form-input @error('email') !border-red-500 @enderror">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ t('Phone') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone') }}" required
                           class="form-input @error('phone') !border-red-500 @enderror">
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ t('Password') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="password" id="password" name="password" required
                           class="form-input @error('password') !border-red-500 @enderror">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ t($message) }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ t('Confirm Password') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                           class="form-input">
                </div>

                <div>
                    <label for="roles" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ t('Role') }}
                    </label>
                    <select id="roles" name="roles[]"
                            class="form-select">
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ in_array($role->id, old('roles', [])) ? 'selected' : '' }}>
                                {{ t($role->name) }}
                            </option>
                        @endforeach
                    </select>
                    @error('roles')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center">
                <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active') ? 'checked' : '' }}
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