@extends('dashboard.layout.admin', ['title' => t('Update Customer')])

@section('css')
@endsection

@section('content')
    <x-dashboard.outer-card :title="t('Update Customer Data')">
        <x-slot:header>
            <div class="flex px-4 border-b-1 border-b-gray-500 flex-col items-stretch justify-between py-4 space-y-3 md:flex-row md:items-center md:space-y-0">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">{{ t('Update Customer Data') }}</h2>
                <div class="flex items-center gap-2">
                    <x-inputs.button-primary as="a" href="{{ route('dashboard.customers.index') }}">
                        {{ t('Back to Customers') }}
                        <x-heroicon-s-arrow-left class="h-4 w-4 {{app()->getLocale() == 'ar' ? 'mr-2' : 'ml-2'}} inline" />
                    </x-inputs.button-primary>
                </div>
            </div>
        </x-slot:header>

        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800">
            <form action="{{ route('dashboard.customers.update', $customer) }}" method="POST" class="p-5 space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="name" class="block text-sm mb-2">{{ t('Name') }} <span class="text-rose-500">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name', $customer->name) }}" required
                               class="w-full px-3 py-2 bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 @error('name') border-red-500 @enderror">
                        @error('name')<div class="text-xs text-rose-500 mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm mb-2">{{ t('Email') }}</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $customer->email) }}"
                               class="w-full px-3 py-2 bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 @error('email') border-red-500 @enderror">
                        @error('email')<div class="text-xs text-rose-500 mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm mb-2">{{ t('Phone') }} <span class="text-rose-500">*</span></label>
                        <input type="text" id="phone" name="phone" value="{{ old('phone', $customer->phone) }}" required
                               class="w-full px-3 py-2 bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 @error('phone') border-red-500 @enderror">
                        @error('phone')<div class="text-xs text-rose-500 mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label for="is_active" class="block text-sm mb-2">{{ t('Status') }}</label>
                        <select id="is_active" name="is_active"
                                class="w-full px-3 py-2 bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full dark:bg-gray-800 dark:border-gray-600 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 @error('is_active') border-red-500 @enderror">
                            <option value="1" {{ old('is_active', $customer->is_active ? '1' : '0') == '1' ? 'selected' : '' }}>{{ t('Active') }}</option>
                            <option value="0" {{ old('is_active', $customer->is_active ? '1' : '0') == '0' ? 'selected' : '' }}>{{ t('Inactive') }}</option>
                        </select>
                        @error('is_active')<div class="text-xs text-rose-500 mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label for="city" class="block text-sm mb-2">{{ t('City') }}</label>
                        <input type="text" id="city" name="city" value="{{ old('city', $customer->city) }}"
                               class="w-full px-3 py-2 bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 @error('city') border-red-500 @enderror">
                        @error('city')<div class="text-xs text-rose-500 mt-1">{{ $message }}</div>@enderror
                    </div>

                    
                    <div>
                        <label for="country" class="block text-sm mb-2">{{ t('Country') }}</label>
                        <select id="country" name="country"
                                class="w-full px-3 py-2 bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full dark:bg-gray-800 dark:border-gray-600 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 @error('country') border-red-500 @enderror">
                            <option value="">{{ t('Select Country') }}</option>
                            <option value="SA" {{ old('country', $customer->country) == 'SA' ? 'selected' : '' }}>{{ t('Saudi Arabia') }}</option>
                            <option value="SD" {{ old('country', $customer->country) == 'SD' ? 'selected' : '' }}>{{ t('Sudan') }}</option>
                        </select>
                        @error('country')<div class="text-xs text-rose-500 mt-1">{{ $message }}</div>@enderror
                    </div>

                </div>

                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label for="address" class="block text-sm mb-2">{{ t('Address') }}</label>
                        <textarea id="address" name="address" rows="3"
                                  class="w-full px-3 py-2 bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 @error('address') border-red-500 @enderror">{{ old('address', $customer->address) }}</textarea>
                        @error('address')<div class="text-xs text-rose-500 mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">{{ t('Save Changes') }}</button>
                    <a href="{{ route('dashboard.customers.show', $customer) }}" class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700">{{ t('Cancel') }}</a>
                </div>
            </form>
        </div>

    </x-dashboard.outer-card>
@endsection

@section('script')
@endsection


