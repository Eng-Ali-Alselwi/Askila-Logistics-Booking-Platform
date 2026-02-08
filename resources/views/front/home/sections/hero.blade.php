<section class="relative flex items-center justify-center overflow-hidden hero-section-height">
        <!-- Background with Modern Gradient Overlay -->
        <!-- min-h-screen -->
        <div class="absolute inset-0 z-0">
            <div class="absolute inset-0 bg-cover bg-center bg-fixed"
                 style="background-image: url({{ asset('assets/images/hero/hero1.webp') }})"></div>
            <!-- Modern gradient overlay with professional opacity -->
            <div class="absolute inset-0 bg-gradient-to-br from-primary-900/90 via-primary-800/85 to-secondary-900/90"></div>
            <!-- Animated background pattern -->
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-10 left-10 w-72 h-72 bg-white rounded-full mix-blend-overlay filter blur-xl animate-float"></div>
                <div class="absolute top-1/3 right-10 w-96 h-96 bg-primary-300 rounded-full mix-blend-overlay filter blur-xl animate-float animate-delay-200"></div>
                <div class="absolute bottom-10 left-1/3 w-80 h-80 bg-secondary-300 rounded-full mix-blend-overlay filter blur-xl animate-float animate-delay-400"></div>
            </div>
        </div>

        <!-- Hero Content -->
        <div class="relative z-10 text-center px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
            <!-- Main Headline (Arabic only) -->
            <div class="mb-4 animate-fade-in-up animate-delay-200">
                <h1 class="text-xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-white leading-tight mb-6 text-center">
                        <span class="text-gradient-primary bg-gradient-to-r from-white via-blue-100 to-primary-200 bg-clip-text text-transparent">
                        {{ __('messages.hero_title') }}
                    </span>
                </h1>
            </div>

            <!-- Subtitle -->
            <div class="mb-12 animate-fade-in-up animate-delay-300">
                <p class="text-xl sm:text-2xl text-blue-100 font-light leading-relaxed max-w-4xl mx-auto text-center">
                    {{ __('messages.hero_subtitle') }}
                </p>
            </div>

            <!-- Call-to-Action Buttons (exactly two) -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center animate-fade-in-up animate-delay-400">
                <a href="{{ route('shipment.track') }}" class="py-3 px-8 text-lg font-semibold text-white border-2 border-white/60 hover:border-white/80 bg-blue-600 hover:bg-blue-700 rounded-2xl shadow-xl transition">{{ __('messages.btn_track_shipment') }}</a>
                <a href="{{ route('flights.index') }}" class="py-3 px-8 text-lg font-semibold text-white/90 border-2 border-white/60 hover:border-white/80 rounded-2xl bg-white/10 hover:bg-white/20 backdrop-blur-md transition">{{ __('messages.btn_book_flight') }}</a>
            </div>

            <!-- Whitespace after CTA for readability -->
            <div class="mt-16 animate-fade-in-up animate-delay-500">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 max-w-4xl mx-auto">
                    <div class="text-center group">
                        <div class="text-3xl md:text-4xl font-bold text-white mb-2 transition-transform duration-300">
                            25+
                        </div>
                        <div class="text-sm text-blue-200 uppercase tracking-wide">
                            {{ __('messages.years_experience') }}
                        </div>
                    </div>
                    <div class="text-center group">
                        <div class="text-3xl md:text-4xl font-bold text-white mb-2 transition-transform duration-300">
                            20+
                        </div>
                        <div class="text-sm text-blue-200 uppercase tracking-wide">
                            {{ __('messages.branches') }}
                        </div>
                    </div>
                    <div class="text-center group">
                        <div class="text-3xl md:text-4xl font-bold text-white mb-2 transition-transform duration-300">
                            24/7
                        </div>
                        <div class="text-sm text-blue-200 uppercase tracking-wide">
                            {{ __('messages.support') }}
                        </div>
                    </div>
                    <div class="text-center group">
                        <div class="text-3xl md:text-4xl font-bold text-white mb-2 transition-transform duration-300">
                            100%
                        </div>
                        <div class="text-sm text-blue-200 uppercase tracking-wide">
                            {{ __('messages.reliability') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scroll Indicator -->
        <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 animate-pulse">
            <div class="flex flex-col items-center text-white/70 hover:text-white transition-colors duration-300 cursor-pointer"
                 onclick="document.querySelector('#services').scrollIntoView({behavior: 'smooth'})">
                <span class="text-sm mb-2">{{ __('messages.explore_services') }}</span>
                <svg class="w-6 h-6 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                </svg>
            </div>
        </div>
    </section>