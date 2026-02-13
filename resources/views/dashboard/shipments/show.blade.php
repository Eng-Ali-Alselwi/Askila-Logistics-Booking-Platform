@extends('dashboard.layout.admin', ['title' => t('Show Shipment').' | '.$shipment->tracking_number])

@section('css')
@endsection

@section('content')
    <x-dashboard.outer-card :title="t('Show Shipment')">
        <x-slot:header>
            <div class="flex px-4 border-b-1 border-b-gray-500 flex-col items-stretch justify-between py-4 space-y-3 md:flex-row md:items-center md:space-y-0">
                <h1 class="text-xl font-semibold"> {{t('Details of Shipment').': '.$shipment->tracking_number }}</h1>
                <div class="flex {{app()->getLocale() === 'ar' ? 'ms-auto' : 'me-auto'}} gap-3">
                    @can('edit shipments')
                        <x-inputs.button-secondary
                            as="a"
                            href="{{ route('dashboard.shipments.edit', $shipment) }}"
                            class="inline-flex gap-2 {{app()->getLocale() === 'ar' ? 'ms-2' : 'me-2'}} justify-center items-center" >
                            <x-heroicon-s-pencil-square class="w4 h-4"/>  {{ t('Edit') }}
                        </x-inputs.button-secondary>
                    @endcan

                    <x-inputs.button-primary
                        as="a"
                        href="{{ route('dashboard.shipments.index') }}"
                        class="inline-flex gap-2 {{app()->getLocale() === 'ar' ? 'ms-2' : 'me-2'}} justify-center items-center" >
                        <x-heroicon-s-arrow-up-right class="w4 h-4"/>  {{ t('Back To Shipments') }}
                    </x-inputs.button-primary>
                </div>
            </div>
        </x-slot:header>

        <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700">
            @livewire('show-shipment', ['shipmentId' => $shipment->id])
        </div>

        <div class="mt-4">
        </div>
    </x-dashboard.outer-card>
@endsection

@section('script')
@endsection