<!-- Start How It Works Section -->
<section class="pt-16 pb-10">
    {{-- <x-home.svg-pattern/> --}}
    <div class="container mx-auto px-8 text-center ">
        <h3 class="text-4xl font-extrabold text-gray-800 dark:text-gray-200 mb-4">
            <span>{{ __('messages.how_it') }}</span>
            <span class="text-primary-300">{{ __('messages.works') }}</span>
        </h3>
        <p class="text-gray-600 mx-auto px-4 dark:text-gray-400 text-xl mb-8 max-w-sm">
            {{ __('messages.how_it_works_description') }}
        </p>
        {{-- <p class="text-center text-gray-600 max-w-3xl mx-auto mb-12">Simple steps to get your goods from Rwanda to Canada safely and efficiently.</p> --}}

        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">

            <x-home.step number="1" :title="__('messages.request_pickup')" :description="__('messages.request_pickup_description')"/>
            <x-home.step number="2" :title="__('messages.preparation_coordination')" :description="__('messages.preparation_description')"/>
            <x-home.step number="3" :title="__('messages.travel_shipping')" :description="__('messages.travel_description')"/>
            <x-home.step number="4" :title="__('messages.delivery_arrival')" :description="__('messages.delivery_description')"/>

        </div>
    </div>
</section>
<!-- End How It Works Section -->

<div class="max-w-screen-xl mx-auto px-4 mb-28">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

        <!-- Card 1: Talk to Experts (Primary CTA) -->
        <a href="{{ route('contact.index') }}" 
           class="group relative overflow-hidden rounded-2xl p-8 bg-gradient-to-br from-blue-600 to-blue-800 text-white shadow-2xl hover:shadow-blue-500/40 transition-all duration-500 transform hover:-translate-y-2">
            
            <!-- Background Shine Effect -->
            <div class="absolute -top-1/2 -left-1/2 w-[200%] h-[200%] bg-white opacity-10 transform-gpu rotate-45 group-hover:animate-shine"></div>

            <div class="relative z-10 flex flex-col h-full">
                <div class="flex-grow">
                    <h3 class="text-3xl text-white font-extrabold mb-2 tracking-tight">{{ __('messages.talk_to_experts') }}</h3>
                    <p class="text-white/80 max-w-md">{{ __('messages.get_custom_solution') }}</p>
                </div>
                <div class="mt-8 flex items-center font-semibold">
                    <span>تواصل معنا الآن</span>
                    <svg class="w-6 h-6 transition-transform duration-300 group-hover:translate-x-2 rtl:group-hover:-translate-x-2 mr-2 rtl:ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </div>
            </div>
        </a>

        <!-- Card 2: Watch on YouTube (Secondary CTA) -->
        <a href="https://www.youtube.com/channel/UCmHHT15TMvSYD4iELO7xOJw" 
           target="_blank" rel="noopener noreferrer" 
           class="group relative overflow-hidden rounded-2xl p-8 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2">
            
            <div class="relative z-10 flex flex-col h-full">
                <div class="flex-grow">
                    <h3 class="text-3xl font-extrabold mb-2 text-gray-900 dark:text-white tracking-tight">{{ __('messages.watch_how_we_work' ) }}</h3>
                    <p class="text-gray-600 dark:text-gray-300 max-w-md">{{ __('messages.watch_videos') }}</p>
                </div>
                <div class="mt-8 flex items-center justify-between">
                    <span class="font-semibold text-gray-700 dark:text-gray-200">شاهد على يوتيوب</span>
                    <svg class="w-16 h-16 text-red-600 group-hover:scale-110 transition-transform duration-300" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M23.5 6.2s-.2-1.6-.8-2.3c-.8-.8-1.8-.8-2.2-.9C17.9 2.7 12 2.7 12 2.7s-5.9 0-8.5.3c-.4 0-1.4.1-2.2.9-.6.7-.8 2.3-.8 2.3S0 8.1 0 10.1v1.7c0 2 .2 3.9.2 3.9s.2 1.6.8 2.3c.8.8 1.9.8 2.4.9 1.8.2 7.6.3 7.6.3s5.9 0 8.5-.3c.4 0 1.4-.1 2.2-.9.6-.7.8-2.3.8-2.3s.2-1.9.2-3.9v-1.7c0-2-.2-3.9-.2-3.9ZM9.6 14.6V7.9l6.4 3.4-6.4 3.3Z"/>
                    </svg>
                </div>
            </div>
        </a>
    </div>
</div>

<!-- Add this animation to your global CSS or in a <style> tag in your main layout -->
<style>
    @keyframes shine {
        0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
        80% { transform: translateX(100%) translateY(100%) rotate(45deg); }
        100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
    }
    .group:hover .animate-shine {
        animation: shine 1.2s cubic-bezier(0.2, 0.8, 0.2, 1);
    }
</style>
