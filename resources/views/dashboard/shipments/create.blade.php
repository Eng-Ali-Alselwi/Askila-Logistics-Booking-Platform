@extends('dashboard.layout.admin', ['title' => $shipment ? t('Update Shipment') : t('Add New Shipment')])

@section('css')
@endsection

@section('content')
    <!-- Start Content-->
    @include('dashboard.layout.shared/page-title',
    ['subtitle' => $shipment ? t('Update Shipment') : t('Add New Shipment'), 'title' => 'Dashboard']
    )

    <x-dashboard.outer-card :title="t('Upsert Shipment')">
        <x-slot:header>
            <div class="flex px-4 border-b-1 border-b-gray-500 flex-col items-stretch justify-between py-4 space-y-3 md:flex-row md:items-center md:space-y-0">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">
                    {{$shipment ? t('Update Shipment') : t('Add New Shipment') }}
                </h2>
            </div>
        </x-slot:header>
        
        @can('create shipments')
            @livewire('upsert-shipment', ['shipmentId' => $shipment?->id])
        @else
            <div class="p-4 bg-red-50 border border-red-200 rounded-lg text-red-700">
                <p>{{ t('You do not have permission to create shipments.') }}</p>
                <a href="{{ route('dashboard.shipments.index') }}" class="mt-2 inline-block text-blue-600 hover:underline">
                    {{ t('Back to Shipments') }}
                </a>
            </div>
        @endcan
    </x-dashboard.outer-card>
@endsection

@section('script')
@endsection