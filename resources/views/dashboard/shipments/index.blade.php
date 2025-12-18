@extends('dashboard.layout.admin', ['title' => t('Show Shipments')])

@section('css')
@endsection

@section('content')
<x-dashboard.confirm />
    <!-- Start Content-->
    @include('dashboard.layout.shared/page-title', ['subtitle' => 'Show Shipments', 'title' => 'Dashboard'])

    <x-dashboard.outer-card :title="t('Shipments')">
        <x-slot:header>
            <div class="flex px-4 border-b-1 border-b-gray-500 flex-col items-stretch justify-between py-4 space-y-3 md:flex-row md:items-center md:space-y-0">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">{{ t('Shipments') }}</h2>

                @can('create shipments')
                    <x-inputs.button-primary as="a" href="{{ route('dashboard.shipments.create') }}">
                        <x-heroicon-m-plus class="h-5 w-5 me-2 inline" />
                        {{ t('Add New Shipment') }}
                    </x-inputs.button-primary>
                @else
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        {{ t('You do not have permission to create shipments') }}
                    </div>
                @endcan
            </div>
        </x-slot:header>

        <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700">
            @livewire('shipment-table')
        </div>

        <div class="mt-4">
        </div>
    </x-dashboard.outer-card>
@endsection

@section('script')
<x-dashboard.confirm />
@endsection