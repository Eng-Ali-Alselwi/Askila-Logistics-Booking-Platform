@extends('layouts.app')

@section('head')
    <x-front.seo-head
        :title="__('messages.page_title_track_shipment')"
        :description="__('messages.seo_contact_description')"
        :orgSchema="true"
        :webSiteSchema="true"
        ogImage="{{ asset('assets/images/logo.png') }}"
    />
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/aos.css') }}">
<style>
    .timeline-step {
        transition: all 0.3s ease;
    }

    .timeline-step:hover {
        transform: translateY(-2px);
    }

    .pulse-animation {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.5;
        }
    }

    .skeleton {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: loading 1.5s infinite;
    }

    .dark .skeleton {
        background: linear-gradient(90deg, #374151 25%, #4b5563 50%, #374151 75%);
        background-size: 200% 100%;
    }

    @keyframes loading {
        0% {
            background-position: 200% 0;
        }
        100% {
            background-position: -200% 0;
        }
    }

    .fade-in {
        animation: fadeIn 0.5s ease-in;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50 mt-30">
    <!-- Search Form - بدون بطاقة -->
    <div class="mb-4">
        <div class="container mx-auto p-4">
            <form id="trackingForm" action="{{ route('shipment.track') }}" method="GET" class="max-w-2xl mx-auto">
                <div class="flex flex-row gap-4">
                    <div class="flex-1">
                        <input type="text" name="tracking_number" id="tracking_number" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                               placeholder="{{ __('messages.enter_tracking_number') }}"
                               maxlength="40"
                               value="{{ old('tracking_number', $trackingNumber) }}" autocomplete="off">
                    </div>
                    <div class="flex items-end">
                        <button type="submit" id="trackButton"
                                class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition duration-300 transform hover:scale-105 flex items-center gap-2">
                            <span id="trackButtonText">{{ __('messages.track') }}</span>
                            <div id="trackButtonSpinner" class="hidden">
                                <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </div>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="px-10">
        @if($errors->any())
            <!-- Error Message -->
            <div class="max-w-2xl mx-auto mb-8">
                <div class="bg-red-50 border border-red-200 rounded-lg p-4" data-aos="fade-up">
                    <div class="flex items-center justify-center">
                        <div class="p-2 ml-3">
                            <i class="fas fa-exclamation-triangle text-red-600"></i>
                        </div>
                        <div class="mx-3">
                            @foreach($errors->all() as $error)
                                <p class="text-red-600 font-semibold">{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Results Section -->
        <div id="resultsSection" aria-live="polite" class="space-y-8">
            @if($trackingNumber)
                @if($error === 'not_found')
                    <!-- Not Found State -->
                    <div class="fade-in bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 text-center" data-aos="fade-up">
                        <div class="mb-6">
                            <svg class="mx-auto h-16 w-16 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.47-.881-6.08-2.33" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
                            {{ __('messages.couldnt_find_shipment') }}
                        </h2>
                        <p class="text-gray-600 dark:text-gray-300 mb-6">
                            {{ __('messages.check_tracking_number') }}
                        </p>
                        <button
                            onclick="resetForm()"
                            class="px-6 py-3 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-lg transition-colors duration-300"
                        >
                            {{ __('messages.try_again') }}
                        </button>
                    </div>
                @elseif($shipment)
                    <!-- Success State -->
                    <div class="fade-in bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8" data-aos="fade-up">
                        <!-- Header -->
                        <div class="mb-8 pb-6 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                <div>
                                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                                        {{ __('messages.tracking_number') }}: <span class="text-primary-600 dark:text-primary-400">{{ $shipment->tracking_number }}</span>
                                    </h2>
                                    <p class="text-gray-600 dark:text-gray-300">
                                        {{ __('messages.created') }}: {{ $shipment->created_at->format('M d, Y') }}
                                    </p>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ __('messages.status') }}:</span>
                                    <span class="px-4 py-2 bg-primary-100 dark:bg-primary-900 text-primary-800 dark:text-primary-200 rounded-full text-sm font-medium transition-all duration-300">
                                        {{ $shipment->current_status_label }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Timeline -->
                        <div class="mb-8">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">
                                {{ __('messages.shipment_progress') }}
                            </h3>
                            <div class="relative">
                                @php
                                    $timeline = $shipment->canonicalTimeline2();
                                @endphp

                                @foreach($timeline as $index => $step)
                                    <div class="timeline-step relative flex items-start mb-8 last:mb-0">
                                        @if($index < count($timeline) - 1)
                                            <div class="absolute top-9 start-4 w-0.5 h-full {{ $step['is_reached'] ? 'bg-primary-500' : 'bg-gray-200 dark:bg-gray-600' }} transition-colors duration-300"></div>
                                        @endif

                                        <div class="relative z-10 flex-shrink-0 w-8 h-8 rounded-full border-2 flex items-center justify-center me-4 mt-1
                                            @if($step['is_current'])
                                                border-primary-500 bg-primary-500 pulse-animation
                                            @elseif($step['is_reached'])
                                                border-primary-500 bg-primary-500
                                            @else
                                                border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-700
                                            @endif
                                            transition-all duration-300">
                                            @if($step['is_reached'])
                                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                </svg>
                                            @elseif($step['is_current'])
                                                <div class="w-2 h-2 bg-white rounded-full"></div>
                                            @endif
                                        </div>

                                        <div class="flex-1 min-w-0">
                                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                                                <div>
                                                    <h4 class="text-lg font-medium
                                                        @if($step['is_current'])
                                                            text-primary-600 dark:text-primary-400
                                                        @elseif($step['is_reached'])
                                                            text-gray-900 dark:text-white
                                                        @else
                                                            text-gray-500 dark:text-gray-400
                                                        @endif
                                                        transition-colors duration-300">
                                                        {{ $step['label'] }}
                                                    </h4>
                                                    <!-- <p class="text-sm text-gray-500 dark:text-gray-400">
                                                        {{ $step['status'] }}
                                                    </p> -->
                                                </div>
                                                @if($step['reached_at'])
                                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                                        {{ $step['is_reached']?$step['reached_at']->format('M d, Y g:i A'):'' }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // تهيئة AOS إذا كان موجوداً
    if (typeof AOS !== 'undefined') {
        AOS.init({
            duration: 1000,
            once: true
        });
    }

    const form = document.getElementById('trackingForm');
    const input = document.getElementById('tracking_number');
    const button = document.getElementById('trackButton');
    const buttonText = document.getElementById('trackButtonText');
    const buttonSpinner = document.getElementById('trackButtonSpinner');
    const resultsSection = document.getElementById('resultsSection');

    // تنسيق رقم التتبع
    if (input) {
        input.addEventListener('input', function(e) {
            let value = e.target.value.toUpperCase();
            // السماح بالأحرف والأرقام والشرطة الوسطى فقط للحفاظ على شكل رقم التتبع
            value = value.replace(/[^A-Z0-9-]/g, '');
            e.target.value = value;
        });

        // تأثير التركيز
        input.addEventListener('focus', function() {
            this.classList.add('ring-4', 'ring-blue-200');
        });

        input.addEventListener('blur', function() {
            this.classList.remove('ring-4', 'ring-blue-200');
        });
    }

    // Handle form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const trackingNumber = input.value.trim();
        if (!trackingNumber) return;

        // Show loading state
        setLoadingState(true);

        // Submit form
        form.submit();
    });

    // Handle Enter key
    input.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            form.dispatchEvent(new Event('submit'));
        }
    });

    // Focus management
    if (input.value) {
        // If there's a tracking number, focus on results
        const resultsHeading = resultsSection.querySelector('h2, h3');
        if (resultsHeading) {
            resultsHeading.focus();
        }
    } else {
        // Otherwise focus on input
        input.focus();
    }

    function setLoadingState(loading) {
        if (loading) {
            button.disabled = true;
            buttonText.classList.add('hidden');
            buttonSpinner.classList.remove('hidden');

            // Show skeleton loading
            resultsSection.innerHTML = `
                <div class="fade-in bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
                    <div class="animate-pulse">
                        <div class="h-8 bg-gray-200 dark:bg-gray-700 rounded mb-4"></div>
                        <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded mb-2"></div>
                        <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-3/4 mb-8"></div>

                        <div class="space-y-6">
                            ${Array(6).fill().map(() => `
                                <div class="flex items-start">
                                    <div class="w-8 h-8 bg-gray-200 dark:bg-gray-700 rounded-full mr-4"></div>
                                    <div class="flex-1">
                                        <div class="h-5 bg-gray-200 dark:bg-gray-700 rounded mb-2"></div>
                                        <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-1/2"></div>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                </div>
            `;
        } else {
            button.disabled = false;
            buttonText.classList.remove('hidden');
            buttonSpinner.classList.add('hidden');
        }
    }
});

function resetForm() {
    window.location.href = '{{ route("shipment.track") }}';
}
</script>
<script src="{{ asset('assets/js/aos.js') }}"></script>
@endpush
