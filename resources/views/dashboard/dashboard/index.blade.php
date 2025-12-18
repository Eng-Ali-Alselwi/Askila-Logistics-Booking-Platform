@extends('dashboard.layout.admin', ['title' => t('Analytics')])

@section('css')
@endsection

@section('content')
    <!-- Start Content-->
    @include('dashboard.layout.shared/page-title', ['subtitle' => t('Dashtrap'), 'title' => t('Dashboard')])

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-2xl p-6 border border-gray-200 dark:border-gray-700">
            {{-- Header --}}
            <div class="flex items-center justify-between mb-6">

                <!-- <h2 class="text-xl font-semibold text-gray-800 dark:text-white">
                    {{ t('Page Title') }}
                </h2> -->

                {{-- Optional Button / Actions --}}
                <div>
                    {{-- Add buttons like "Add New", filters, etc. --}}
                    {{-- Example: --}}
                    {{-- <a href="#" class="btn-primary">+ {{ t('Add New') }}</a> --}}
                </div>

            </div>

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
    </div>
    {{-- <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
        <div class="border-2 border-dashed border-gray-300 rounded-lg dark:border-gray-600 h-32 md:h-64">
        </div>
        <div class="border-2 border-dashed rounded-lg border-gray-300 dark:border-gray-600 h-32 md:h-64">
        </div>
        <div class="border-2 border-dashed rounded-lg border-gray-300 dark:border-gray-600 h-32 md:h-64">
        </div>
        <div class="border-2 border-dashed rounded-lg border-gray-300 dark:border-gray-600 h-32 md:h-64">
        </div>
    </div>
    <div class="border-2 border-dashed rounded-lg border-gray-300 dark:border-gray-600 h-96 mb-4"></div>
    <div class="grid grid-cols-2 gap-4 mb-4">
        <div class="border-2 border-dashed rounded-lg border-gray-300 dark:border-gray-600 h-48 md:h-72">
        </div>
        <div class="border-2 border-dashed rounded-lg border-gray-300 dark:border-gray-600 h-48 md:h-72">
        </div>
        <div class="border-2 border-dashed rounded-lg border-gray-300 dark:border-gray-600 h-48 md:h-72">
        </div>
        <div class="border-2 border-dashed rounded-lg border-gray-300 dark:border-gray-600 h-48 md:h-72">
        </div>
    </div>
    <div class="border-2 border-dashed rounded-lg border-gray-300 dark:border-gray-600 h-96 mb-4"></div>
    <div class="grid grid-cols-2 gap-4">
        <div class="border-2 border-dashed rounded-lg border-gray-300 dark:border-gray-600 h-48 md:h-72">
        </div>
        <div class="border-2 border-dashed rounded-lg border-gray-300 dark:border-gray-600 h-48 md:h-72">
        </div>
        <div class="border-2 border-dashed rounded-lg border-gray-300 dark:border-gray-600 h-48 md:h-72">
        </div>
        <div class="border-2 border-dashed rounded-lg border-gray-300 dark:border-gray-600 h-48 md:h-72">
        </div>
    </div> --}}
@endsection

@section('script')
    {{-- @vite(['resources/js/pages/dashboard.js']) --}}
@endsection
