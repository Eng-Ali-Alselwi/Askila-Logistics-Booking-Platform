@extends('layouts.app')
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/aos.css') }}">
@endpush
@section('head')
    @php
        // صفحة خدمات شاملة
        $servicesPageJson = [
            '@context'   => 'https://schema.org',
            '@type'      => 'CollectionPage',
            'name'       => 'خدماتنا | مجموعة الأسكلة',
            'url'        => url()->current(),
            'inLanguage' => 'ar',
            'about'      => [
                '@type' => 'Organization',
                'name'  => 'مجموعة الأسكلة',
                'url'   => url('/'),
                'logo'  => asset('assets/images/logo.png'),
            ],
            // عناصر الخدمات الأساسية
            'hasPart'    => [
                [
                    '@type'        => 'Service',
                    'name'         => 'الشحن البري',
                    'serviceType'  => 'Land Shipping',
                    'areaServed'   => ['SA','SD'],
                    'provider'     => ['@type'=>'Organization','name'=>'مجموعة الأسكلة'],
                    'url'          => url('/services#land'),
                ],
                [
                    '@type'        => 'Service',
                    'name'         => 'الشحن البحري',
                    'serviceType'  => 'Sea Shipping',
                    'areaServed'   => ['SA','SD'],
                    'provider'     => ['@type'=>'Organization','name'=>'مجموعة الأسكلة'],
                    'url'          => url('/services#sea'),
                ],
                [
                    '@type'        => 'Service',
                    'name'         => 'الشحن الجوي',
                    'serviceType'  => 'Air Shipping',
                    'areaServed'   => ['SA','SD'],
                    'provider'     => ['@type'=>'Organization','name'=>'مجموعة الأسكلة'],
                    'url'          => url('/services#air'),
                ],
                [
                    '@type'        => 'Service',
                    'name'         => 'تذاكر الطيران',
                    'serviceType'  => 'Flight Tickets',
                    'areaServed'   => ['SA','SD'],
                    'provider'     => ['@type'=>'Organization','name'=>'مجموعة الأسكلة'],
                    'url'          => url('/services#flight-tickets'),
                ],
                [
                    '@type'        => 'Service',
                    'name'         => 'تذاكر البحر',
                    'serviceType'  => 'Ferry Tickets',
                    'areaServed'   => ['SA','SD'],
                    'provider'     => ['@type'=>'Organization','name'=>'مجموعة الأسكلة'],
                    'url'          => url('/services#ferry-tickets'),
                ],
                [
                    '@type'        => 'Service',
                    'name'         => 'الخدمة الشاملة A2Z',
                    'serviceType'  => 'A2Z Full Service',
                    'areaServed'   => ['SA','SD'],
                    'provider'     => ['@type'=>'Organization','name'=>'مجموعة الأسكلة'],
                    'url'          => url('/services#a2z'),
                ],
            ],
        ];

        // مسار تنقل (Breadcrumbs)
        $breadcrumbJson = [
            '@context'        => 'https://schema.org',
            '@type'           => 'BreadcrumbList',
            'itemListElement' => [
                [
                    '@type'    => 'ListItem',
                    'position' => 1,
                    'name'     => 'الرئيسية',
                    'item'     => url('/'),
                ],
                [
                    '@type'    => 'ListItem',
                    'position' => 2,
                    'name'     => 'خدماتنا',
                    'item'     => url()->current(),
                ],
            ],
        ];
    @endphp

    <x-front.seo-head
        :title="__('messages.page_title_services')"
        :description="__('messages.seo_services_description')"
        ogImage="{{ asset('assets/images/og/services.jpg') }}"
        :orgSchema="true"
        :webSiteSchema="true"
        :jsonLd="[$servicesPageJson, $breadcrumbJson]"
    />
@endsection

@section('content')
    @include('front.services.sections.hero')
    
    <!-- Services Grid Section -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-screen-xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                
                <!-- Land Shipping -->
                <x-front.service-card
                    :title="__('messages.service_land')"
                    :description="__('messages.desc_land')"
                    :image="asset('assets/images/services/land_shipping.jpg')"
                    gradient="from-blue-500 to-blue-600"
                    :link="route('contact.index')"
                    :delay="0"
                />

                <!-- Sea Shipping -->
                <x-front.service-card
                    :title="__('messages.service_sea')"
                    :description="__('messages.desc_sea')"
                    :image="asset('assets/images/services/sea_shipping.jpg')"
                    gradient="from-cyan-500 to-cyan-600"
                    :link="route('contact.index')"
                    :delay="100"
                />

                <!-- Air Shipping -->
                <x-front.service-card
                    :title="__('messages.service_air')"
                    :description="__('messages.desc_air')"
                    :image="asset('assets/images/services/air_shipping.jpg')"
                    gradient="from-purple-500 to-purple-600"
                    :link="route('contact.index')"
                    :delay="200"
                />

                <!-- Flight Tickets -->
                <x-front.service-card
                    :title="__('messages.service_flight_tickets')"
                    :description="__('messages.desc_flight_tickets')"
                    :image="asset('assets/images/services/flight_tickets.jpg')"
                    gradient="from-green-500 to-green-600"
                    :link="route('flights.index')"
                    :delay="300"
                />

                <!-- Ferry Tickets -->
                <x-front.service-card
                    :title="__('messages.service_ferry_tickets')"
                    :description="__('messages.desc_ferry_tickets')"
                    :image="asset('assets/images/services/ferry_tickets.jpeg')"
                    gradient="from-orange-500 to-orange-600"
                    :link="route('contact.index')"
                    :delay="400"
                />

                <!-- A2Z Service -->
                <x-front.service-card
                    :title="__('messages.service_a2z')"
                    :description="__('messages.desc_a2z')"
                    :image="asset('assets/images/services/a2z.jpeg')"
                    gradient="from-indigo-500 to-indigo-600"
                    :link="route('contact.index')"
                    :delay="500"
                />

            </div>
        </div>
    </section>
    
    <!-- Services CTA Blocks -->
    <section class="py-16 bg-white">
        <div class="max-w-screen-xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <a href="{{ route('contact.index') }}" class="group rounded-2xl p-8 bg-gradient-to-br from-blue-600 to-blue-700 text-white shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105">
                    <div class="flex items-center justify-between">
                        <div class="text-right">
                            <h3 class="text-2xl font-bold mb-2">{{ __('messages.talk_to_experts') }}</h3>
                            <p class="text-white/90">{{ __('messages.get_custom_solution') }}</p>
                        </div>
                        <svg class="w-10 h-10 opacity-90 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </div>
                </a>
                <a href="https://www.youtube.com/channel/UCmHHT15TMvSYD4iELO7xOJw" target="_blank" rel="noopener noreferrer" class="group rounded-2xl p-8 bg-white border border-gray-200 shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105">
                    <div class="flex items-center justify-between">
                        <div class="text-right">
                            <h3 class="text-2xl font-bold mb-2 text-gray-900">{{ __('messages.watch_how_we_work') }}</h3>
                            <p class="text-gray-600">{{ __('messages.watch_videos') }}</p>
                        </div>
                        <svg class="w-10 h-10 text-red-600 group-hover:scale-110 transition-transform" viewBox="0 0 24 24" fill="currentColor"><path d="M23.5 6.2s-.2-1.6-.8-2.3c-.8-.8-1.8-.8-2.2-.9C17.9 2.7 12 2.7 12 2.7s-5.9 0-8.5.3c-.4 0-1.4.1-2.2.9-.6.7-.8 2.3-.8 2.3S0 8.1 0 10.1v1.7c0 2 .2 3.9.2 3.9s.2 1.6.8 2.3c.8.8 1.9.8 2.4.9 1.8.2 7.6.3 7.6.3s5.9 0 8.5-.3c.4 0 1.4-.1 2.2-.9.6-.7.8-2.3.8-2.3s.2-1.9.2-3.9v-1.7c0-2-.2-3.9-.2-3.9ZM9.6 14.6V7.9l6.4 3.4-6.4 3.3Z"/></svg>
                    </div>
                </a>
            </div>
        </div>
    </section>

@endsection
