@extends('dashboard.layout.admin', ['title' => t('Settings')])

@section('content')
    @include('dashboard.layout.shared/page-title', ['subtitle' => t('Settings'), 'title' => t('Dashboard')])

    <x-dashboard.outer-card :title="t('Application Settings')">
        <x-slot:header>
            <div class="flex px-4 border-b-1 border-b-gray-500 flex-col items-stretch justify-between py-4 space-y-3 md:flex-row md:items-center md:space-y-0">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">{{ t('Settings') }}</h2>
            </div>
        </x-slot:header>

        <div class="p-4 md:p-6">
            <form action="{{ route('dashboard.settings.update') }}" method="POST" class="space-y-8">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="app_name" class="block text-sm mb-2">{{ t('Application Name') }}</label>
                        <input type="text" id="app_name" name="app_name" class="form-input" value="{{ old('app_name', setting('app_name', 'Askila')) }}" />
                        @error('app_name')<div class="mt-1 text-xs text-rose-500">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label for="app_email" class="block text-sm mb-2">{{ t('Application Email') }}</label>
                        <input type="email" id="app_email" name="app_email" class="form-input" value="{{ old('app_email', setting('app_email', 'info@askila.com')) }}" />
                        @error('app_email')<div class="mt-1 text-xs text-rose-500">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label for="app_phone" class="block text-sm mb-2">{{ t('Application Phone') }}</label>
                        <input type="text" id="app_phone" name="app_phone" class="form-input" value="{{ old('app_phone', setting('app_phone', '+966123456789')) }}" />
                        @error('app_phone')<div class="mt-1 text-xs text-rose-500">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label for="app_address" class="block text-sm mb-2">{{ t('Application Address') }}</label>
                        <textarea id="app_address" name="app_address" rows="3" class="form-input">{{ old('app_address', setting('app_address', 'الرياض، المملكة العربية السعودية')) }}</textarea>
                        @error('app_address')<div class="mt-1 text-xs text-rose-500">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="border-t border-gray-200 dark:border-gray-800 pt-4">
                    <h5 class="mb-4 font-semibold">{{ t('SMS Settings') }}</h5>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="sms_enabled" class="block text-sm mb-2">{{ t('SMS Enabled') }}</label>
                            <select id="sms_enabled" name="sms_enabled" class="form-select">
                                <option value="1" {{ old('sms_enabled', setting('sms_enabled', '1')) == '1' ? 'selected' : '' }}>{{ t('Yes') }}</option>
                                <option value="0" {{ old('sms_enabled', setting('sms_enabled', '1')) == '0' ? 'selected' : '' }}>{{ t('No') }}</option>
                            </select>
                        </div>
                        <div>
                            <label for="sms_sender" class="block text-sm mb-2">{{ t('SMS Sender') }}</label>
                            <input type="text" id="sms_sender" name="sms_sender" class="form-input" value="{{ old('sms_sender', setting('sms_sender', 'ASKILA')) }}" />
                        </div>
                        <div>
                            <label for="sms_username" class="block text-sm mb-2">{{ t('SMS Username') }}</label>
                            <input type="text" id="sms_username" name="sms_username" class="form-input" value="{{ old('sms_username', setting('sms_username', '')) }}" />
                        </div>
                        <div>
                            <label for="sms_api_key" class="block text-sm mb-2">{{ t('SMS API Key') }}</label>
                            <input type="password" id="sms_api_key" name="sms_api_key" class="form-input" value="{{ old('sms_api_key', setting('sms_api_key', '')) }}" />
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-200 dark:border-gray-800 pt-4">
                    <h5 class="mb-4 font-semibold">{{ t('Email Settings') }}</h5>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="mail_from_address" class="block text-sm mb-2">{{ t('From Email') }}</label>
                            <input type="email" id="mail_from_address" name="mail_from_address" class="form-input" value="{{ old('mail_from_address', setting('mail_from_address', 'noreply@askila.com')) }}" />
                        </div>
                        <div>
                            <label for="mail_from_name" class="block text-sm mb-2">{{ t('From Name') }}</label>
                            <input type="text" id="mail_from_name" name="mail_from_name" class="form-input" value="{{ old('mail_from_name', setting('mail_from_name', 'Askila')) }}" />
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="btn btn-primary">{{ t('Save Settings') }}</button>
                </div>
            </form>
        </div>
    </x-dashboard.outer-card>
@endsection
