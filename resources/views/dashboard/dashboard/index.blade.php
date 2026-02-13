@extends('dashboard.layout.admin', ['title' => t('Analytics')])

@section('css')
@endsection

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-2xl p-6 border border-gray-200 dark:border-gray-700">
            {{-- Main content area --}}
            <div class="space-y-6">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
                        <div class="text-sm text-gray-500">{{ t('Shipments') }}</div>
                        <div class="text-2xl font-semibold text-gray-800 dark:text-gray-100">{{ $shipmentsTotal ?? 0 }}</div>
                    </div>
                    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
                        <div class="text-sm text-gray-500">{{ t('Flights') }}</div>
                        <div class="text-2xl font-semibold text-gray-800 dark:text-gray-100">{{ $flightsTotal ?? 0 }}</div>
                    </div>
                    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
                        <div class="text-sm text-gray-500">{{ t('Bookings') }}</div>
                        <div class="text-2xl font-semibold text-gray-800 dark:text-gray-100">{{ $bookingsTotal ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>
@endsection

@section('script')
    {{-- @vite(['resources/js/pages/dashboard.js']) --}}
@endsection
