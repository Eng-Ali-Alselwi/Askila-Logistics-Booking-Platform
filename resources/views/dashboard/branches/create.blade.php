@extends('dashboard.layout.admin')

@section('title', t('Add New Branch'))

@section('content')

    <x-dashboard.outer-card :title="t('Add New Branch')">
        <x-slot:header>
            <div class="flex px-4 border-b-1 border-b-gray-500 flex-col items-stretch justify-between py-4 space-y-3 md:flex-row md:items-center md:space-y-0">
                <h1 class="text-xl font-semibold">{{ t('Add New Branch') }}</h1>
                <div class="flex items-center gap-3">
                    <x-inputs.button-primary as="a" href="{{ route('dashboard.branches.index') }}" class="inline-flex gap-2 justify-center items-center">
                        {{ t('Back To Branches') }}
                        <x-heroicon-o-arrow-left class="w4 h-4"/> 
                    </x-inputs.button-primary>
                </div>
            </div>
        </x-slot:header>

        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800">
            <form action="{{ route('dashboard.branches.store') }}" method="POST" class="p-5 space-y-6">
                @csrf 
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="name" class="block text-sm mb-2">{{ t('Name') }} <span class="text-rose-500">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                class="w-full px-3 py-2 bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 @error('name') border-red-500 @enderror">
                        @error('name')<div class="text-xs text-rose-500 mt-1">{{ $message }}</div>@enderror
                    </div>
                        
                    <div>
                        <label for="code" class="block text-sm mb-2">{{ t('Code') }} <span class="text-rose-500">*</span></label>
                        <input type="text" id="code" name="code" value="{{ old('code') }}" required
                                class="w-full px-3 py-2 bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 @error('code') border-red-500 @enderror">
                        @error('code')<div class="text-xs text-rose-500 mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label for="manager_name" class="block text-sm mb-2">{{ t('Manager Name') }}</label>
                        <input type="text" id="manager_name" name="manager_name" value="{{ old('manager_name') }}"
                                class="w-full px-3 py-2 bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 @error('manager_name') border-red-500 @enderror">
                        @error('manager_name')<div class="text-xs text-rose-500 mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm mb-2">{{ t('Phone') }}</label>
                        <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                                class="w-full px-3 py-2 bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 @error('phone') border-red-500 @enderror">
                        @error('phone')<div class="text-xs text-rose-500 mt-1">{{ $message }}</div>@enderror
                    </div>
                        
                    <div>
                        <label for="email" class="block text-sm mb-2">{{ t('Email') }}</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                                class="w-full px-3 py-2 bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 @error('email') border-red-500 @enderror">
                        @error('email')<div class="text-xs text-rose-500 mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label for="address" class="block text-sm mb-2">{{ t('Address') }}</label>
                        <textarea id="address" name="address" rows="3"
                                    class="w-full px-3 py-2 bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 @error('address') border-red-500 @enderror">{{ old('address') }}</textarea>
                        @error('address')<div class="text-xs text-rose-500 mt-1">{{ $message }}</div>@enderror
                    </div>
                        
                    <div>
                        <label for="city" class="block text-sm mb-2">{{ t('City') }}</label>
                        <input type="text" id="city" name="city" value="{{ old('city') }}"
                                class="w-full px-3 py-2 bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 @error('city') border-red-500 @enderror">
                        @error('city')<div class="text-xs text-rose-500 mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label for="country" class="block text-sm mb-2">{{ t('Country') }}</label>
                        <select id="country" name="country"
                                class="w-full px-3 py-2 bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full dark:bg-gray-800 dark:border-gray-600 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 @error('country') border-red-500 @enderror">
                                    <option value="">{{ t('Select Country') }}</option>
                                    <option value="SA" {{ old('country') == 'SA' ? 'selected' : '' }}>{{ t('Saudi Arabia') }}</option>
                                    <option value="SD" {{ old('country') == 'SD' ? 'selected' : '' }}>{{ t('Sudan') }}</option>
                                </select>
                        @error('country')<div class="text-xs text-rose-500 mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">{{ t('Save Branch') }}</button>
                    <a href="{{ route('dashboard.branches.index') }}" class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700">{{ t('Back') }}</a>
                </div>
            </form>
        </div>
    </x-dashboard.outer-card>

@endsection