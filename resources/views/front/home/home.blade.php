@extends('layouts.app')
@section('head')
  <x-front.seo-head
      :title="__('messages.page_title_home')"
      :description="__('messages.seo_home_description')"
      :orgSchema="true"
      :webSiteSchema="true"
      ogImage="{{ asset('assets/images/logo.png') }}"
  />
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/aos.css') }}">
@endpush
@section('content')
    @include('front.home.sections.hero')
    @include('front.home.sections.about')
    @include('front.home.sections.services')
    @include('front.home.sections.how-it-works')
    @include('front.home.sections.stats')
    @include('front.contact.sections.form')
@endsection
