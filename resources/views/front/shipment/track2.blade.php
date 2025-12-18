@extends('layouts.app')
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/aos.css') }}">
@endpush
@section('head')

@endsection

@section('content')
    @include('front.shipment.sections.hero')
    {{-- @include('front.home.sections.services') --}}
    <div class="container mx-auto px-4 py-10">
        @livewire('track-shipment', ['prefill' => $prefill])
    </div>
@endsection
