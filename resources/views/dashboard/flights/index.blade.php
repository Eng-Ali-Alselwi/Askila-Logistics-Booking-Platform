@extends('dashboard.layout.admin', ['title' => t('Show Flights')])

@section('css')
@endsection

@section('content')
<x-dashboard.confirm />
    <!-- Start Content-->
    @include('dashboard.layout.shared/page-title', ['subtitle' => 'Show Flights', 'title' => 'Dashboard'])

    <x-dashboard.outer-card :title="t('Flights')">
        <x-slot:header>

            <div
                class="flex px-4 border-b-1 border-b-gray-500 flex-col items-stretch justify-between py-4 space-y-3 md:flex-row md:items-center md:space-y-0">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">{{ t('Flights') }}</h2>

                {{-- <div class="inline-flex flex-col rounded-md shadow-sm md:flex-row" role="group"><button type="button" class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-t-lg md:rounded-tr-none md:rounded-l-lg hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-2 focus:ring-primary-700 focus:text-primary-700 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-primary-500 dark:focus:text-white">Suspend all</button><button type="button" class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border-gray-200 border-x md:border-x-0 md:border-t md:border-b hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-2 focus:ring-primary-700 focus:text-primary-700 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-primary-500 dark:focus:text-white">Archive all</button><button type="button" class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-b-lg md:rounded-bl-none md:rounded-r-lg hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-2 focus:ring-primary-700 focus:text-primary-700 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-primary-500 dark:focus:text-white">Delete all</button></div> --}}
                @can('manage flights')
                <x-inputs.button-primary as="a" href="{{ route('dashboard.flights.create') }}">
                    <x-heroicon-m-plus class="h-5 w-5 me-2 inline" />
                    {{ t('Add New Flight') }}
                </x-inputs.button-primary>
                @endcan

            </div>

        </x-slot:header>

        <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700">
            {{-- @include('dashboard.users.partials.table') --}}
            @livewire('flight-table')
                {{-- @livewire('users-roles-table') --}}
                 {{-- <livewire:users-roles-table> --}}
                {{-- @livewire('users-roles-table') --}}

        </div>

        <div class="mt-4">
            {{-- {{ $users->links() }} --}}
        </div>

    </x-dashboard.outer-card>

@endsection

@section('script')
<x-dashboard.confirm />
@endsection
