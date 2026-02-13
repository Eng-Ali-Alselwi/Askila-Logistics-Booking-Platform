@extends('dashboard.layout.admin', ['title' => t('Show Bookings')])

@section('css')
@endsection

@section('content')
<x-dashboard.confirm />

    <x-dashboard.outer-card :title="t('Bookings')">
        <x-slot:header>
            <div class="px-4 border-b-1 border-b-gray-500 py-4 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">{{ t('Bookings') }}</h2>
                @can('manage bookings')
                <x-inputs.button-primary as="a" href="{{ route('dashboard.bookings.create') }}">
                    <x-heroicon-m-plus class="h-5 w-5 me-2 inline" />
                    {{ t('Add New Booking') }}
                </x-inputs.button-primary>
                @endcan
            </div>
        </x-slot:header>

        <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700">
            @livewire('booking-table')
        </div>
    </x-dashboard.outer-card>

@endsection

@section('script')
<x-dashboard.confirm />
@endsection
