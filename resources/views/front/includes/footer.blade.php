<!-- Modern Footer -->
<footer class="bg-gray-900 text-white border-t border-gray-200">
    <!-- Main Footer Content -->
    <div class="max-w-screen-xl mx-auto px-4 py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            
            <!-- Column 1: Company Info -->
            <div class="lg:col-span-1">
                <div class="mb-6">
                    <img src="{{ asset('assets/images/logo/light.png') }}" alt="شعار مجموعة الأسكلة" class="h-12 mb-4">
                </div>
                <p class="text-gray-300 leading-relaxed mb-6">
                    {{ __('messages.company_short') }}
                </p>
                
                <!-- Social Media Links -->
                <div class="flex items-center gap-3">
                    <a href="https://www.facebook.com/Asklagroup?locale=ar_AR" target="_blank" rel="noopener noreferrer" 
                       class="group w-10 h-10 bg-gray-800 hover:bg-blue-600 rounded-full flex items-center justify-center transition-all duration-300 hover:scale-110">
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-white transition-colors duration-300" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M13 22v-8h3l1-4h-4V7.5c0-1.2.3-2 2-2h2V2.1C16.6 2 15.2 2 14 2c-3 0-5 1.8-5 5.1V10H6v4h3v8h4Z"/>
                        </svg>
                    </a>
                    
                    <a href="https://www.instagram.com/askilagroup?igsh=ZDh1czJ1Mm9rbjkw&utm_source=qr" target="_blank" rel="noopener noreferrer" 
                       class="group w-10 h-10 bg-gray-800 hover:bg-gradient-to-r hover:from-pink-500 hover:to-purple-600 rounded-full flex items-center justify-center transition-all duration-300 hover:scale-110">
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-white transition-colors duration-300" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M7 2C4.2 2 2 4.2 2 7v10c0 2.8 2.2 5 5 5h10c2.8 0 5-2.2 5-5V7c0-2.8-2.2-5-5-5H7Zm10 2c1.7 0 3 1.3 3 3v10c0 1.7-1.3 3-3 3H7c-1.7 0-3-1.3-3-3V7c0-1.7 1.3-3 3-3h10Zm-5 3.5A5.5 5.5 0 1 0 17.5 13 5.5 5.5 0 0 0 12 7.5Zm0 2A3.5 3.5 0 1 1 8.5 13 3.5 3.5 0 0 1 12 9.5Zm5.8-2.6a1 1 0 1 0 1.4 1.4 1 1 0 0 0-1.4-1.4Z"/>
                        </svg>
                    </a>
                    
                    <a href="https://www.tiktok.com/@alaskiila?_t=ZS-904r7B2T9aU&_r=1" target="_blank" rel="noopener noreferrer" 
                       class="group w-10 h-10 bg-gray-800 hover:bg-black rounded-full flex items-center justify-center transition-all duration-300 hover:scale-110">
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-white transition-colors duration-300" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M16.7 2c.5 2.2 2.2 3.9 4.4 4.4v3a8.4 8.4 0 0 1-4.3-1.2v6.7a6.9 6.9 0 1 1-6.9-6.9c.4 0 .8 0 1.2.1v3.1a3.9 3.9 0 0 0-1.2-.2 3.9 3.9 0 1 0 3.9 3.9V2h2.9Z"/>
                        </svg>
                    </a>
                    
                    <a href="https://www.youtube.com/channel/UCmHHT15TMvSYD4iELO7xOJw" target="_blank" rel="noopener noreferrer" 
                       class="group w-10 h-10 bg-gray-800 hover:bg-red-600 rounded-full flex items-center justify-center transition-all duration-300 hover:scale-110">
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-white transition-colors duration-300" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M23.5 6.2s-.2-1.6-.8-2.3c-.8-.8-1.8-.8-2.2-.9C17.9 2.7 12 2.7 12 2.7s-5.9 0-8.5.3c-.4 0-1.4.1-2.2.9-.6.7-.8 2.3-.8 2.3S0 8.1 0 10.1v1.7c0 2 .2 3.9.2 3.9s.2 1.6.8 2.3c.8.8 1.9.8 2.4.9 1.8.2 7.6.3 7.6.3s5.9 0 8.5-.3c.4 0 1.4-.1 2.2-.9.6-.7.8-2.3.8-2.3s.2-1.9.2-3.9v-1.7c0-2-.2-3.9-.2-3.9ZM9.6 14.6V7.9l6.4 3.4-6.4 3.3Z"/>
                        </svg>
                    </a>
                </div>
            </div>
            
            <!-- Column 2: Quick Links -->
            <div>
                <h3 class="text-lg font-bold text-white mb-6">{{ __('messages.quick_links') }}</h3>
                <ul class="space-y-3">
                    <li>
                        <a href="{{ route('home') }}" class="text-gray-300 hover:text-blue-400 transition-colors duration-300 flex items-center group">
                            <svg class="w-4 h-4 mx-2 text-gray-500 group-hover:text-blue-400 transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            {{ __('messages.home') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('services.index') }}" class="text-gray-300 hover:text-blue-400 transition-colors duration-300 flex items-center group">
                            <svg class="w-4 h-4 mx-2 text-gray-500 group-hover:text-blue-400 transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 9.586V5L8 4z"/>
                            </svg>
                            {{ __('messages.services') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('shipment.track') }}" class="text-gray-300 hover:text-blue-400 transition-colors duration-300 flex items-center group">
                            <svg class="w-4 h-4 mx-2 text-gray-500 group-hover:text-blue-400 transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            {{ __('messages.track_shipment') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('booking.track') }}" class="text-gray-300 hover:text-blue-400 transition-colors duration-300 flex items-center group">
                            <svg class="w-4 h-4 mx-2 text-gray-500 group-hover:text-blue-400 transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                            </svg>
                            {{ __('messages.track_booking') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('contact.index') }}" class="text-gray-300 hover:text-blue-400 transition-colors duration-300 flex items-center group">
                            <svg class="w-4 h-4 mx-2 text-gray-500 group-hover:text-blue-400 transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            {{ __('messages.contact') }}
                        </a>
                    </li>
                </ul>
            </div>
            
            <!-- Column 3: Contact Information -->
            <div>
                <h3 class="text-lg font-bold text-white mb-6">{{ __('messages.contact_info') }}</h3>
                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="w-5 h-5 mt-1 text-blue-400 flex-shrink-0">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div class="text-gray-300">
                            <p class="font-medium">{{ __('messages.address') }}</p>
                            <p class="text-sm">{{ __('messages.address_value') }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start gap-3">
                        <div class="w-5 h-5 mt-1 text-blue-400 flex-shrink-0">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </div>
                        <div class="text-gray-300">
                            <p class="font-medium">{{ __('messages.phone') }}</p>
                            <p class="text-sm">{{ __('messages.phone_value') }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start gap-3">
                        <div class="w-5 h-5 mt-1 text-blue-400 flex-shrink-0">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
            </div>
                        <div class="text-gray-300">
                            <p class="font-medium">{{ __('messages.email') }}</p>
                            <p class="text-sm">{{ __('messages.email_value') }}</p>
                </div>
                </div>
                </div>
            </div>
            
            <!-- Column 4: Services -->
            <div>
                <h3 class="text-lg font-bold text-white mb-6">{{ __('messages.our_services') }}</h3>
                <ul class="space-y-3">
                    <li>
                        <a href="{{ route('services.index') }}" class="text-gray-300 hover:text-blue-400 transition-colors duration-300 flex items-center group">
                            <svg class="w-4 h-4 mx-2 text-gray-500 group-hover:text-blue-400 transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                            </svg>
                            {{ __('messages.service_land') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('services.index') }}" class="text-gray-300 hover:text-blue-400 transition-colors duration-300 flex items-center group">
                            <svg class="w-4 h-4 mx-2 text-gray-500 group-hover:text-blue-400 transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"/>
                            </svg>
                            {{ __('messages.service_sea') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('services.index') }}" class="text-gray-300 hover:text-blue-400 transition-colors duration-300 flex items-center group">
                            <svg class="w-4 h-4 mx-2 text-gray-500 group-hover:text-blue-400 transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                            {{ __('messages.service_air') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('flights.index') }}" class="text-gray-300 hover:text-blue-400 transition-colors duration-300 flex items-center group">
                            <svg class="w-4 h-4 mx-2 text-gray-500 group-hover:text-blue-400 transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                            </svg>
                            {{ __('messages.service_flight_tickets') }}
                        </a>
                    </li>
                </ul>
            </div>
        </div>
            </div>
    
    <!-- Separator Line -->
    <div class="border-t border-gray-800"></div>
    
    <!-- Copyright Section -->
    <div class="max-w-screen-xl mx-auto px-4 py-6">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <p class="text-gray-400 text-sm text-center md:text-right">
                {{ __('messages.copyright', ['year' => date('Y')]) }}
            </p>
            <div class="flex items-center gap-6 text-sm">
                <a href="#" class="text-gray-400 hover:text-blue-400 transition-colors duration-300">{{ __('messages.privacy_policy') }}</a>
                <a href="#" class="text-gray-400 hover:text-blue-400 transition-colors duration-300">{{ __('messages.terms') }}</a>
            </div>
        </div>
    </div>
</footer>