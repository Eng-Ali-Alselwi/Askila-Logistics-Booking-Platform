@extends('layouts.app')
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/aos.css') }}">
@endpush
@section('head')
  @php
    $contactPageJson = [
      '@context' => 'https://schema.org',
      '@type'    => 'ContactPage',
      'name'     => 'تواصل معنا | مجموعة الأسكلة',
      'url'      => url()->current(),
      'inLanguage' => 'ar',
      'about'    => [
        '@type' => 'Organization',
        'name'  => 'مجموعة الأسكلة',
        'url'   => url('/'),
        'logo'  => asset('assets/images/logo.png'),
      ],
      'contactPoint' => [[
        '@type' => 'ContactPoint',
        'telephone' => '+966-5XXXXXXXX',
        'email' => 'support@askila.sa',
        'contactType' => 'خدمة العملاء',
        'areaServed' => ['SA','SD'],
        'availableLanguage' => ['ar'],
      ]],
    ];
  @endphp

  <x-front.seo-head
      :title="__('messages.page_title_contact')"
      :description="__('messages.seo_contact_description')"
      ogImage="{{ asset('assets/images/og/contact.jpg') }}"
      :orgSchema="true"
      :webSiteSchema="true"
      :jsonLd="[$contactPageJson]"
  />
@endsection 
@section('content')

    <!-- @include('front.contact.sections.hero') -->
    @include('front.contact.sections.form')
    <!-- Contact Section -->

    <!-- Quick Contact & Social -->
    <!-- <section class="py-8 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-primary-50/10 via-transparent to-secondary-50/10 dark:from-primary-900/5 dark:via-transparent dark:to-secondary-900/5"></div>
        <div class="absolute top-5 right-10 w-16 h-16 bg-primary-200/20 dark:bg-primary-800/20 rounded-full blur-xl"></div>
        <div class="absolute bottom-5 left-10 w-20 h-20 bg-secondary-200/20 dark:bg-secondary-800/20 rounded-full blur-xl"></div>
        
        <div class="max-w-5xl mx-auto px-4 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                <a href="https://wa.me/966501234567" target="_blank" rel="noopener" 
                   class="group flex items-center gap-4 p-4 rounded-xl bg-gradient-to-br from-green-500 to-green-600 
                   text-white shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105
                   border border-green-400/20 backdrop-blur-sm">
                    <div class="w-12 h-12 bg-white/20 backdrop-blur-sm border border-white/30 rounded-lg flex items-center justify-center
                                group-hover:scale-105 transition-all duration-300 shadow-md">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M20.5 3.5A11 11 0 1 0 3 20.9L2 23.9l3-.9A11 11 0 1 0 20.6 3.5Zm-8.5 18a9 9 0 1 1 0-18 9 9 0 0 1 0 18Zm5.1-6.6c-.3-.2-1.7-.8-1.9-.8-.3-.1-.5-.2-.7.2-.2.3-.8 1-.9 1.2-.2.2-.3.2-.6.1-.3-.2-1.2-.5-2.3-1.5-.9-.8-1.5-1.8-1.7-2-.2-.3 0-.4.1-.6.1-.1.3-.4.4-.5.1-.2.2-.3.3-.5.1-.2.1-.3 0-.5l-.8-2c-.2-.5-.4-.4-.6-.4h-.5c-.2 0-.5.1-.7.3-.3.3-1 1-1 2.4s1 2.7 1.1 2.9c.1.2 2 3.1 4.7 4.3 1.8.8 2.5.9 3 .8.5-.1 1.5-.6 1.7-1.2.2-.6.2-1.1.1-1.2-.1-.1-.2-.2-.5-.4Z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-lg font-bold mb-1">{{ __('messages.whatsapp_support') }}</div>
                        <div class="text-white/90 text-sm">{{ __('messages.fast_responses') }}</div>
                    </div>
                </a>

                <a href="mailto:support@askila.sa" 
                   class="group flex items-center gap-4 p-4 rounded-xl bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm 
                   border border-gray-200/50 dark:border-gray-700/50 shadow-lg hover:shadow-xl 
                   transition-all duration-300 hover:scale-105">
                    <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900/30 border border-primary-200 dark:border-primary-700/50 
                                rounded-lg flex items-center justify-center group-hover:scale-105 transition-all duration-300 shadow-md">
                        <svg class="w-6 h-6 text-primary-600 dark:text-primary-400" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M2 6a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v.2l-10 6.3L2 6.2V6Zm0 2.8 9.4 5.9c.4.3.8.3 1.2 0L22 8.8V18a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V8.8Z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-lg font-bold text-gray-900 dark:text-white mb-1">support@askila.sa</div>
                        <div class="text-gray-600 dark:text-gray-300 text-sm">{{ __('messages.email_us_anytime') }}</div>
                    </div>
                </a>

                <div class="flex flex-col items-center gap-4 p-4 rounded-xl bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm 
                            border border-gray-200/50 dark:border-gray-700/50 shadow-lg">
                    <div class="text-lg font-bold text-gray-900 dark:text-white mb-2">{{ __('messages.follow_us') }}</div>
                    <div class="flex items-center gap-3">
                        <a href="https://www.youtube.com/channel/UCmHHT15TMvSYD4iELO7xOJw" target="_blank" rel="noopener" 
                           class="w-10 h-10 inline-flex items-center justify-center rounded-lg bg-red-600 text-white 
                           hover:bg-red-700 hover:scale-105 hover:-translate-y-1 transition-all duration-300 shadow-md">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M23.5 6.2s-.2-1.6-.8-2.3c-.8-.8-1.8-.8-2.2-.9C17.9 2.7 12 2.7 12 2.7s-5.9 0-8.5.3c-.4 0-1.4.1-2.2.9-.6.7-.8 2.3-.8 2.3S0 8.1 0 10.1v1.7c0 2 .2 3.9.2 3.9s.2 1.6.8 2.3c.8.8 1.9.8 2.4.9 1.8.2 7.6.3 7.6.3s5.9 0 8.5-.3c.4 0 1.4-.1 2.2-.9.6-.7.8-2.3.8-2.3s.2-1.9.2-3.9v-1.7c0-2-.2-3.9-.2-3.9ZM9.6 14.6V7.9l6.4 3.4-6.4 3.3Z"/>
                            </svg>
                        </a>
                        <a href="https://www.instagram.com/askilagroup?igsh=ZDh1czJ1Mm9rbjkw&utm_source=qr" target="_blank" rel="noopener" 
                           class="w-10 h-10 inline-flex items-center justify-center rounded-lg bg-pink-600 text-white 
                           hover:bg-pink-700 hover:scale-105 hover:-translate-y-1 transition-all duration-300 shadow-md">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M7 2C4.2 2 2 4.2 2 7v10c0 2.8 2.2 5 5 5h10c2.8 0 5-2.2 5-5V7c0-2.8-2.2-5-5-5H7Zm10 2c1.7 0 3 1.3 3 3v10c0 1.7-1.3 3-3 3H7c-1.7 0-3-1.3-3-3V7c0-1.7 1.3-3 3-3h10Zm-5 3.5A5.5 5.5 0 1 0 17.5 13 5.5 5.5 0 0 0 12 7.5Zm0 2A3.5 3.5 0 1 1 8.5 13 3.5 3.5 0 0 1 12 9.5Zm5.8-2.6a1 1 0 1 0 1.4 1.4 1 1 0 0 0-1.4-1.4Z"/>
                            </svg>
                        </a>
                        <a href="https://www.tiktok.com/@alaskiila?_t=ZS-904r7B2T9aU&_r=1" target="_blank" rel="noopener" 
                           class="w-10 h-10 inline-flex items-center justify-center rounded-lg bg-black text-white 
                           hover:bg-gray-800 hover:scale-105 hover:-translate-y-1 transition-all duration-300 shadow-md">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M16.7 2c.5 2.2 2.2 3.9 4.4 4.4v3a8.4 8.4 0 0 1-4.3-1.2v6.7a6.9 6.9 0 1 1-6.9-6.9c.4 0 .8 0 1.2.1v3.1a3.9 3.9 0 0 0-1.2-.2 3.9 3.9 0 1 0 3.9 3.9V2h2.9Z"/>
                            </svg>
                        </a>
                        <a href="https://www.facebook.com/Asklagroup?locale=ar_AR" target="_blank" rel="noopener" 
                           class="w-10 h-10 inline-flex items-center justify-center rounded-lg bg-blue-600 text-white 
                           hover:bg-blue-700 hover:scale-105 hover:-translate-y-1 transition-all duration-300 shadow-md">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M13 22v-8h3l1-4h-4V7.5c0-1.2.3-2 2-2h2V2.1C16.6 2 15.2 2 14 2c-3 0-5 1.8-5 5.1V10H6v4h3v8h4Z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section> -->



    <!-- Enquiry Section -->
    {{-- <section class="relative max-w-7xl mx-auto px-4 py-16">
        <!-- Header Section -->
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                Enquire About Our <span class="text-red-600">Courses</span>
            </h1>
            <div class="w-20 h-1 bg-red-600 mx-auto mb-6"></div>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                We're here to help you begin your creative journey. Fill out the form below and our team will get back to
                you shortly.
            </p>
        </div>

        <!-- Form Container -->
        <div class="bg-white rounded-xl shadow-2xl overflow-hidden">
            <div class="md:flex">
                <!-- Left Side -->
                <div
                    class="md:w-1/3 bg-gradient-to-br from-red-600 to-red-800 p-10 text-white flex flex-col justify-between">
                    <div>
                        <h2 class="text-2xl font-bold mb-4">Why Enquire With Us?</h2>
                        <ul class="space-y-4">
                            <li class="flex items-start">
                                <svg class="h-6 w-6 text-red-200 mr-2" fill="none" stroke="currentColor"
                                    stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Expert guidance from industry professionals</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="h-6 w-6 text-red-200 mr-2" fill="none" stroke="currentColor"
                                    stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Personalized course recommendations</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="h-6 w-6 text-red-200 mr-2" fill="none" stroke="currentColor"
                                    stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Flexible learning options</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="h-6 w-6 text-red-200 mr-2" fill="none" stroke="currentColor"
                                    stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Quick response to all enquiries</span>
                            </li>
                        </ul>
                    </div>
                    <div class="mt-8">
                        <h3 class="text-lg font-semibold mb-2">Need immediate assistance?</h3>
                        <p class="text-red-100 mb-2">Call us at +91 7307xxxxxx</p>
                        <p class="text-sm text-red-200">Our team is available 10AM - 6PM, Monday to Saturday</p>
                    </div>
                </div>

                <!-- Right Side - Form -->
                <div class="md:w-2/3 p-10">
                    <form class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Full Name -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                                <input type="text" name="fullName" placeholder="John Doe"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500"
                                    required />
                            </div>

                            <!-- Email -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                                <input type="email" name="email" placeholder="john@example.com"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500"
                                    required />
                            </div>

                            <!-- Mobile Number -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Mobile Number</label>
                                <input type="tel" name="mobileNumber" placeholder="+91 9876543210"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500"
                                    required />
                            </div>

                            <!-- Course Selection -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Course Interested In</label>
                                <select name="courses"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 bg-white"
                                    required>
                                    <option value="">Select a Course</option>
                                    <option value="Graphic Design">Graphic Design</option>
                                    <option value="Web Development">Web Development</option>
                                    <option value="Animation">Animation</option>
                                    <option value="UI/UX Design">UI/UX Design</option>
                                </select>
                            </div>
                        </div>

                        <!-- Message -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Your Message</label>
                            <textarea name="message" rows="4" placeholder="Tell us about your interests and goals..."
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500"
                                required></textarea>
                        </div>

                        <!-- Submit -->
                        <div>
                            <button type="submit"
                                class="w-full py-3 px-6 bg-red-600 text-white font-medium rounded-lg shadow-sm hover:bg-red-700 transition duration-300">
                                Submit Enquiry
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section> --}}
@endsection
