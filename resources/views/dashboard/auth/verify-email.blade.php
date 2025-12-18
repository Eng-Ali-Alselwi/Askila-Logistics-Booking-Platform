@extends('dashboard.layout.guest', ['title' => t('Verify Email Address')])

@section('content')
    <div class="flex flex-col items-center justify-center px-6 pt-8 mx-auto md:h-screen pt:mt-0 dark:bg-gray-900">
        <!-- Professional Email Verification Card -->
        <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl dark:bg-gray-800 border border-gray-200 dark:border-gray-700 overflow-hidden">
            
            <!-- Header with Logo -->
            <div class="px-8 py-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-center">
                    <img src="{{ asset('assets/images/logo/dark.png') }}" class="h-12" alt="Askila Logo">
                </div>
            </div>

            <!-- Email Verification Content -->
            <div class="px-8 py-8 text-center">
                <div class="mb-6">
                    <!-- Email Icon -->
                    <div class="mx-auto w-16 h-16 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>

                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
                    {{ t('Verify Your Email Address') }}
                </h3>

                <p class="text-sm text-gray-600 dark:text-gray-300 mb-6">
                    {{ t('Thanks for signing up! Before getting started, please verify your email address by clicking the link we just emailed to you. If you didn\'t receive the email, we will gladly send you another.') }}
                </p>

                @if (session('message'))
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                        <p class="text-green-600 text-sm font-medium">{{ session('message') }}</p>
                    </div>
                @endif

                <div class="space-y-4">
                    <!-- Resend Verification Email Button -->
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit" 
                                class="w-full bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 
                                       text-white font-semibold py-3 px-4 rounded-lg shadow-lg hover:shadow-xl 
                                       transform hover:scale-105 transition-all duration-300 
                                       focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            {{ t('Resend Verification Email') }}
                        </button>
                    </form>

                    <!-- Log Out Button -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" 
                                class="w-full bg-gray-500 hover:bg-gray-600 text-white font-semibold py-3 px-4 rounded-lg 
                                       transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            {{ t('Log Out') }}
                        </button>
                    </form>
                </div>

                <!-- Back to Home Link -->
                <div class="mt-6 text-center">
                    <a href="{{ route('home') }}" 
                        class="inline-flex items-center text-sm text-gray-600 hover:text-primary-600 transition-colors duration-300">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m0 7h18" />
                        </svg>
                        {{ t('Back to Website') }}
                    </a>
                </div>
            </div>

            <!-- Footer -->
            <div class="px-8 py-4 bg-gray-50 dark:bg-gray-700 text-center border-t border-gray-200 dark:border-gray-600">
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    &copy; {{ date('Y') }} Askila Company. {{ t('All rights reserved') }}.
                </p>
            </div>
        </div>
    </div>
@endsection
