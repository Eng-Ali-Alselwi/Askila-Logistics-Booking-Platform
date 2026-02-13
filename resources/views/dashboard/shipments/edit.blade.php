@extends('dashboard.layout.admin', ['title' => $shipment ? t('Update Shipment') : t('Add New Shipment')])

@section('css')
@endsection

@section('content')
    <x-dashboard.outer-card :title="t('Upsert Shipment')">
        <x-slot:header>
            <div class="flex px-4 border-b-1 border-b-gray-500 flex-col items-stretch justify-between py-4 space-y-3 md:flex-row md:items-center md:space-y-0">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">
                    {{$shipment ? t('Update Shipment') : t('Add New Shipment') }}
                </h2>
                <x-inputs.button-primary as="a" href="{{ route('dashboard.shipments.index') }}">
                    {{ t('Back to Shipments') }}
                    <x-heroicon-m-arrow-left class="h-5 w-5 {{app()->getLocale() === 'ar' ? 'ms-2' : 'me-2'}} inline" />
                </x-inputs.button-primary>
            </div>
        </x-slot:header>
        @livewire('upsert-shipment', ['shipmentId' => $shipment?->id])
    </x-dashboard.outer-card>
@endsection

@section('script')

@endsection
