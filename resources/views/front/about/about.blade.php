@extends('layouts.app')
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/aos.css') }}">
@endpush
@section('head')
    @php
        $aboutPageJson = [
            '@context'   => 'https://schema.org',
            '@type'      => 'AboutPage',
            'name'       => 'عن الأسكلة | مجموعة الأسكلة',
            'url'        => url()->current(),
            'inLanguage' => 'ar',
            'about'      => [
                '@type' => 'Organization',
                'name'  => 'مجموعة الأسكلة',
                'url'   => url('/'),
                'logo'  => asset('assets/images/logo.png'),
            ],
        ];

        // اختياري: يساعد في الـ rich results لمسار التنقل
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
                    'name'     => 'عن الأسكلة',
                    'item'     => url()->current(),
                ],
            ],
        ];
    @endphp

    <x-front.seo-head
        title="عن الأسكلة | مجموعة الأسكلة"
        description="تعرّف على حكاية الأسكلة وقيمنا وخدماتنا من الباب للباب بين السعودية والسودان — شحن بري وبحري وجوي وتذاكر طيران وعبّارات بدعم موثوق وأسعار واضحة."
        ogImage="{{ asset('assets/images/og/about.jpg') }}"
        :orgSchema="true"
        :webSiteSchema="true"
        :jsonLd="[$aboutPageJson, $breadcrumbJson]"
    />
@endsection
@section('content')
    @include('front.about.sections.hero')

    {{-- <div class="bg-gradient-to-br from-gray-50 to-gray-100 font-sans antialiased dark:from-gray-900 dark:to-gray-800"> --}}

        <main class="relative z-10 mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
            <!-- About Askilah -->
            @include('front.about.sections.about')

            <!-- Stats Section -->
            @include('front.about.sections.stats')

            <!-- Values Section -->
            @include('front.about.sections.values')

            <!-- CTA Section -->
            @include('front.about.sections.cta')

            {{-- <section class="relative overflow-hidden rounded-3xl bg-white py-5 shadow-xl dark:bg-gray-800">
                <!-- Decorative elements -->
                <div class="absolute -top-20 -right-20 h-64 w-64 rounded-full bg-primary-100/30 dark:bg-primary-900/30">
                </div>
                <div class="absolute -bottom-10 -left-10 h-32 w-32 rounded-full bg-primary-100/30 dark:bg-primary-900/30">
                </div>

                <div class="relative z-10 mx-auto max-w-4xl px-4 text-center sm:px-6 lg:px-8">
                    <h2 class="mb-6 text-3xl font-bold text-gray-800 md:text-4xl dark:text-gray-200">Ready to <span
                            class="text-primary-600 dark:text-primary-300">work with us</span>?</h2>
                    <p class="mx-auto mb-8 max-w-2xl text-xl text-gray-600 dark:text-gray-400">Let's discuss how we can
                        help your business grow and succeed in the digital landscape.</p>
                    <div class="flex flex-col space-y-4 sm:flex-row sm:justify-center sm:space-y-0 sm:space-x-4">

                        <a type="submit"
                            class="flex w-full md:w-fit items-center justify-center gap-2 bg-gradient-to-br from-primary-500 to-primary-400 text-white font-semibold py-2 px-6 rounded-full hover:scale-105 hover:shadow-lg transition duration-300">
                            <x-icons icon="paper-plane" />
                            {{ t('Send Message') }}
                        </a>

                        <a href="#"
                            class="flex  w-full md:w-fit items-center justify-center gap-2 border border-gray-500 text-gray-600
                        dark:border-gray-300 dark:text-gray-400
                        font-semibold py-2 px-6 rounded-full hover:scale-105 hover:shadow-lg transition
                        duration-300">
                            <x-icons icon="whatsapp" />
                            {{ t('Contact On Whatsapp') }}
                        </a>
                    </div>
                </div>
            </section> --}}



        </main>
    {{-- </div> --}}

    {{-- @include('front.contact.sections.form') --}}
    <!-- Contact Section -->
@endsection
