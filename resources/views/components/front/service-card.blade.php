@props([
    'title' => '',
    'description' => '',
    'image' => null,
    'icon' => null,
    'gradient' => 'from-blue-500 to-blue-600',
    'link' => '#',
    'delay' => 0
])

<div class="group bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden transform border border-gray-200 dark:border-gray-700 card-hover-scal" data-aos="fade-up" data-aos-delay="{{ $delay }}">
    <!-- Image Section -->
    <div class="relative h-48 overflow-hidden">
        @if($image)
            <img src="{{ $image }}" alt="{{ $title }}" class="w-full h-full object-cover">
        @else
            <div class="w-full h-full bg-gradient-to-br {{ $gradient }} flex items-center justify-center">
                @if($icon)
                    {!! $icon !!}
                @else
                    <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                @endif
            </div>
        @endif
        
        <!-- Overlay on hover -->
        <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
        
        <!-- Instagram-style badge (optional) -->
        <div class="absolute top-3 right-3 bg-white/90 backdrop-blur-sm rounded-full p-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
            <svg class="w-4 h-4 text-gray-700" viewBox="0 0 24 24" fill="currentColor">
                <path d="M7 2C4.2 2 2 4.2 2 7v10c0 2.8 2.2 5 5 5h10c2.8 0 5-2.2 5-5V7c0-2.8-2.2-5-5-5H7Zm10 2c1.7 0 3 1.3 3 3v10c0 1.7-1.3 3-3 3H7c-1.7 0-3-1.3-3-3V7c0-1.7 1.3-3 3-3h10Zm-5 3.5A5.5 5.5 0 1 0 17.5 13 5.5 5.5 0 0 0 12 7.5Zm0 2A3.5 3.5 0 1 1 8.5 13 3.5 3.5 0 0 1 12 9.5Zm5.8-2.6a1 1 0 1 0 1.4 1.4 1 1 0 0 0-1.4-1.4Z"/>
            </svg>
        </div>
    </div>
    
    <!-- Content Section -->
    <div class="p-6 text-right">
        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-300">
            {{ $title }}
        </h3>
        <p class="text-gray-600 dark:text-gray-300 mb-4 leading-relaxed text-sm">
            {{ $description }}
        </p>
        
        <!-- Action Button -->
        <a href="{{ $link }}" class="inline-flex items-center text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 font-medium transition-all duration-300 group-hover:translate-x-1">
            تعرف على المزيد
            <svg class="w-4 h-4 mr-2 transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
    </div>
</div>
