@extends('dashboard.layout.admin')

@section('title', t('Add New Customer'))
@section('styles')

@section('content')
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">{{ t('Add New Customer') }}</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">{{ t('Dashboard') }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.customers.index') }}">{{ t('Customers') }}</a></li>
                        <li class="breadcrumb-item active">{{ t('Add New Customer') }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800">
                <form action="{{ route('dashboard.customers.store') }}" method="POST" class="p-5 space-y-6">
                    @csrf

                    {{-- Basic Information --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block text-sm mb-2">
                                {{ t('Name') }} <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" 
                                   class="w-full px-3 py-2 bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 @error('name') border-red-500 @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" 
                                   placeholder="{{ t('Enter full name') }}" required>
                            @error('name')
                                <div class="text-xs text-rose-500 mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm mb-2">{{ t('Email') }}</label>
                            <input type="email" 
                                   class="w-full px-3 py-2 bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 @error('email') border-red-500 @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" 
                                   placeholder="name@example.com">
                            @error('email')
                                <div class="text-xs text-rose-500 mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label for="phone" class="block text-sm mb-2">
                                {{ t('Phone') }} <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" 
                                   class="w-full px-3 py-2 bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 @error('phone') border-red-500 @enderror" 
                                   id="phone" name="phone" value="{{ old('phone') }}" 
                                   placeholder="{{ t('Enter phone number') }}" required>
                            @error('phone')
                                <div class="text-xs text-rose-500 mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label for="is_active" class="block text-sm mb-2">{{ t('Status') }}</label>
                            <select class="w-full px-3 py-2 bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full dark:bg-gray-800 dark:border-gray-600 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 @error('is_active') border-red-500 @enderror" 
                                    id="is_active" name="is_active">
                                <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>{{ t('Active') }}</option>
                                <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>{{ t('Inactive') }}</option>
                            </select>
                            @error('is_active')
                                <div class="text-xs text-rose-500 mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Address Information --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-t border-gray-200 dark:border-gray-800 pt-4">
                        <div>
                            <label for="address" class="block text-sm mb-2">{{ t('Address') }}</label>
                            <textarea class="w-full px-3 py-2 bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 @error('address') border-red-500 @enderror" 
                                      id="address" name="address" rows="3" 
                                      placeholder="{{ t('Street, building, apartment') }}">{{ old('address') }}</textarea>
                            @error('address')
                                <div class="text-xs text-rose-500 mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label for="city" class="block text-sm mb-2">{{ t('City') }}</label>
                            <input type="text" 
                                   class="w-full px-3 py-2 bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 @error('city') border-red-500 @enderror" 
                                   id="city" name="city" value="{{ old('city') }}" 
                                   placeholder="{{ t('Enter city') }}">
                            @error('city')
                                <div class="text-xs text-rose-500 mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label for="country" class="block text-sm mb-2">{{ t('Country') }}</label>
                            <select class="w-full px-3 py-2 bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full dark:bg-gray-800 dark:border-gray-600 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 @error('country') border-red-500 @enderror" 
                                    id="country" name="country">
                                <option value="">{{ t('Select Country') }}</option>
                                <option value="SA" {{ old('country') == 'SA' ? 'selected' : '' }}>{{ t('Saudi Arabia') }}</option>
                                <option value="SD" {{ old('country') == 'SD' ? 'selected' : '' }}>{{ t('Sudan') }}</option>
                            </select>
                            @error('country')
                                <div class="text-xs text-rose-500 mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit" 
                                class="px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">
                            {{ t('Create Customer') }}
                        </button>

                        <a href="{{ route('dashboard.customers.index') }}" 
                           class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700">
                            {{ t('Back to List') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
