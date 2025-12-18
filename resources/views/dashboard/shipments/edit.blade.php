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

            <div
                class="flex px-4 border-b-1 border-b-gray-500 flex-col items-stretch justify-between py-4 space-y-3 md:flex-row md:items-center md:space-y-0">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">
                    {{$shipment ? t('Update Shipment') : t('Add New Shipment') }}
                </h2>

                {{-- <div class="inline-flex flex-col rounded-md shadow-sm md:flex-row" role="group"><button type="button" class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-t-lg md:rounded-tr-none md:rounded-l-lg hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-2 focus:ring-primary-700 focus:text-primary-700 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-primary-500 dark:focus:text-white">Suspend all</button><button type="button" class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border-gray-200 border-x md:border-x-0 md:border-t md:border-b hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-2 focus:ring-primary-700 focus:text-primary-700 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-primary-500 dark:focus:text-white">Archive all</button><button type="button" class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-b-lg md:rounded-bl-none md:rounded-r-lg hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-2 focus:ring-primary-700 focus:text-primary-700 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-primary-500 dark:focus:text-white">Delete all</button></div> --}}
                {{-- <x-inputs.button-primary class="">
                    <x-heroicon-m-plus class="h-5 w-5 me-2 inline" />
                    Add New Shipment
                </x-inputs.button-primary> --}}
                {{-- <button type="button"
                class="flex items-center justify-center px-4 py-2 text-sm font-medium text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">

                <x-heroicon-m-plus class="h-5 w-5 me-2" />
                Add new user
            </button> --}}
            </div>

        </x-slot:header>
        @livewire('upsert-shipment', ['shipmentId' => $shipment?->id])


    </x-dashboard.outer-card>
@endsection

@section('script')

@endsection
