@extends('dashboard.layout.admin', ['title' => $flight ? t('Update Flight') : t('Add New Flight')])

@section('content')
    @include('dashboard.layout.shared/page-title', ['subtitle' => $flight ? t('Update Flight') : t('Add New Flight'), 'title' => 'Dashboard'])

    <x-dashboard.outer-card :title="$flight ? t('Update Flight') : t('Add New Flight')">
        <x-slot:header>
            <div class="flex px-4 border-b-1 border-b-gray-500 flex-col items-stretch justify-between py-4 space-y-3 md:flex-row md:items-center md:space-y-0">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">
                    {{ $flight ? t('Update Flight') : t('Add New Flight') }}
                </h2>
            </div>
        </x-slot:header>
        
        @livewire('upsert-flight', ['flightId' => $flight?->id])
    </x-dashboard.outer-card>
@endsection