@extends('layouts.app')

@section('head')
    <x-front.seo-head
        title="نتائج البحث عن الرحلات - مجموعة الأسكلة"
        description="نتائج البحث عن تذاكر الطيران مع أفضل الأسعار والعروض."
    />
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/aos.css') }}">
@endpush

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">نتائج البحث</h1>
                    <p class="text-gray-600">
                        {{ $departureFlights->count() }} رحلة متاحة
                        @if($returnFlights->count() > 0)
                            | {{ $returnFlights->count() }} رحلة عودة
                        @endif
                    </p>
                </div>
                <a href="{{ route('flights.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition duration-300">
                    <i class="fas fa-search ml-2"></i>
                    بحث جديد
                </a>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Filters Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">فلترة النتائج</h3>
                    
                    <!-- Trip Type Filter -->
                    <div class="mb-6">
                        <h4 class="text-sm font-medium text-gray-700 mb-3">نوع الرحلة</h4>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="checkbox" class="trip-type-filter w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" data-type="air">
                                <i class="fas fa-plane ml-2 text-blue-600"></i>
                                <span class="mr-2 text-sm text-gray-600">رحلات جوية</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="trip-type-filter w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500" data-type="land">
                                <i class="fas fa-bus ml-2 text-green-600"></i>
                                <span class="mr-2 text-sm text-gray-600">رحلات برية</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="trip-type-filter w-4 h-4 text-cyan-600 border-gray-300 rounded focus:ring-cyan-500" data-type="sea">
                                <i class="fas fa-ship ml-2 text-cyan-600"></i>
                                <span class="mr-2 text-sm text-gray-600">رحلات بحرية</span>
                            </label>
                        </div>
                    </div>

                    <!-- Company Filter -->
                    <div class="mb-6">
                        <h4 class="text-sm font-medium text-gray-700 mb-3">الشركة</h4>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <span class="mr-2 text-sm text-gray-600">الأسكلة للطيران</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <span class="mr-2 text-sm text-gray-600">السودان للطيران</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <span class="mr-2 text-sm text-gray-600">الخطوط السعودية</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                                <span class="mr-2 text-sm text-gray-600">شركة النقل البري</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="w-4 h-4 text-cyan-600 border-gray-300 rounded focus:ring-cyan-500">
                                <span class="mr-2 text-sm text-gray-600">شركة النقل البحري</span>
                            </label>
                        </div>
                    </div>

                    <!-- Price Range -->
                    <div class="mb-6">
                        <h4 class="text-sm font-medium text-gray-700 mb-3">نطاق السعر</h4>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="radio" name="price_range" class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                <span class="mr-2 text-sm text-gray-600">أقل من 500 ريال</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="price_range" class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                <span class="mr-2 text-sm text-gray-600">500 - 1000 ريال</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="price_range" class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                <span class="mr-2 text-sm text-gray-600">1000 - 1500 ريال</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="price_range" class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                <span class="mr-2 text-sm text-gray-600">أكثر من 1500 ريال</span>
                            </label>
                        </div>
                    </div>

                    <!-- Departure Time -->
                    <div class="mb-6">
                        <h4 class="text-sm font-medium text-gray-700 mb-3">وقت الإقلاع</h4>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <span class="mr-2 text-sm text-gray-600">صباحاً (6:00 - 12:00)</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <span class="mr-2 text-sm text-gray-600">بعد الظهر (12:00 - 18:00)</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <span class="mr-2 text-sm text-gray-600">مساءً (18:00 - 24:00)</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Flight Results -->
            <div class="lg:col-span-2">
                @if($departureFlights->count() > 0)
                    <!-- Departure Flights -->
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">رحلات الذهاب</h2>
                        <div class="space-y-4">
                            @foreach($departureFlights as $flight)
                                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition duration-300 flight-card" 
                                     data-trip-type="{{ $flight->trip_type }}" data-aos="fade-up">
                                    <div class="p-6">
                                        <!-- Trip Type Badge -->
                                        <div class="flex items-center justify-between mb-4">
                                            <div class="flex items-center space-x-4">
                                                @php
                                                    $tripTypeConfig = match($flight->trip_type) {
                                                        'air' => ['icon' => 'fa-plane', 'color' => 'blue', 'bg' => 'bg-blue-100', 'text' => 'text-blue-600'],
                                                        'land' => ['icon' => 'fa-bus', 'color' => 'green', 'bg' => 'bg-green-100', 'text' => 'text-green-600'],
                                                        'sea' => ['icon' => 'fa-ship', 'color' => 'cyan', 'bg' => 'bg-cyan-100', 'text' => 'text-cyan-600'],
                                                        default => ['icon' => 'fa-plane', 'color' => 'blue', 'bg' => 'bg-blue-100', 'text' => 'text-blue-600']
                                                    };
                                                @endphp
                                                <div class="{{ $tripTypeConfig['bg'] }} p-3 rounded-full">
                                                    <i class="fas {{ $tripTypeConfig['icon'] }} {{ $tripTypeConfig['text'] }} text-xl"></i>
                                                </div>
                                                <div>
                                                    <div class="flex items-center gap-2 mb-1">
                                                        <h3 class="text-lg font-semibold text-gray-800">{{ $flight->operator_name ?: $flight->airline }}</h3>
                                                        <span class="px-2 py-1 text-xs rounded-full 
                                                            @if($flight->trip_type === 'air') bg-blue-100 text-blue-800
                                                            @elseif($flight->trip_type === 'land') bg-green-100 text-green-800
                                                            @elseif($flight->trip_type === 'sea') bg-cyan-100 text-cyan-800
                                                            @endif">
                                                            {{ $flight->trip_type_label }}
                                                        </span>
                                                    </div>
                                                    <p class="text-sm text-gray-600">{{ $flight->flight_number }}</p>
                                                    @if($flight->vehicle_type)
                                                        <p class="text-xs text-gray-500">{{ $flight->vehicle_type }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-2xl font-bold {{ $tripTypeConfig['text'] }}">{{ number_format($flight->base_price) }} ريال</p>
                                                <p class="text-sm text-gray-600">لكل راكب</p>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                            <div class="text-center">
                                                <p class="text-sm text-gray-600">من</p>
                                                <p class="font-semibold text-gray-800">{{ $flight->departure_city }}</p>
                                                <p class="text-sm text-gray-600">{{ $flight->departure_airport }}</p>
                                                @if($flight->departure_terminal)
                                                    <p class="text-xs text-gray-500">{{ $flight->departure_terminal }}</p>
                                                @endif
                                                <p class="text-sm text-gray-600 font-medium">{{ $flight->departure_time->format('H:i') }}</p>
                                            </div>
                                            <div class="text-center">
                                                <div class="flex items-center justify-center">
                                                    <div class="w-8 h-px bg-gray-300"></div>
                                                    <i class="fas {{ $tripTypeConfig['icon'] }} mx-2 {{ $tripTypeConfig['text'] }}"></i>
                                                    <div class="w-8 h-px bg-gray-300"></div>
                                                </div>
                                                <p class="text-xs text-gray-600 mt-1">{{ $flight->duration_formatted }}</p>
                                            </div>
                                            <div class="text-center">
                                                <p class="text-sm text-gray-600">إلى</p>
                                                <p class="font-semibold text-gray-800">{{ $flight->arrival_city }}</p>
                                                <p class="text-sm text-gray-600">{{ $flight->arrival_airport }}</p>
                                                @if($flight->arrival_terminal)
                                                    <p class="text-xs text-gray-500">{{ $flight->arrival_terminal }}</p>
                                                @endif
                                                <p class="text-sm text-gray-600 font-medium">{{ $flight->arrival_time->format('H:i') }}</p>
                                            </div>
                                        </div>

                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-4 text-sm text-gray-600">
                                                <span><i class="fas fa-users ml-1"></i> {{ $flight->available_seats }} مقعد متاح</span>
                                                <span><i class="fas fa-clock ml-1"></i> {{ $flight->departure_time->format('Y-m-d') }}</span>
                                            </div>
                                            <a href="{{ route('flights.show', $flight) }}" 
                                               class="px-6 py-2 rounded-lg transition duration-300 text-white font-medium
                                               @if($flight->trip_type === 'air') bg-blue-600 hover:bg-blue-700
                                               @elseif($flight->trip_type === 'land') bg-green-600 hover:bg-green-700
                                               @elseif($flight->trip_type === 'sea') bg-cyan-600 hover:bg-cyan-700
                                               @else bg-blue-600 hover:bg-blue-700 @endif">
                                                اختر الرحلة
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Return Flights -->
                    @if($returnFlights->count() > 0)
                        <div>
                            <h2 class="text-xl font-semibold text-gray-800 mb-4">رحلات العودة</h2>
                            <div class="space-y-4">
                                @foreach($returnFlights as $flight)
                                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition duration-300 flight-card" 
                                         data-trip-type="{{ $flight->trip_type }}" data-aos="fade-up">
                                        <div class="p-6">
                                            <div class="flex items-center justify-between mb-4">
                                                <div class="flex items-center space-x-4">
                                                    @php
                                                        $tripTypeConfig = match($flight->trip_type) {
                                                            'air' => ['icon' => 'fa-plane', 'color' => 'blue', 'bg' => 'bg-blue-100', 'text' => 'text-blue-600'],
                                                            'land' => ['icon' => 'fa-bus', 'color' => 'green', 'bg' => 'bg-green-100', 'text' => 'text-green-600'],
                                                            'sea' => ['icon' => 'fa-ship', 'color' => 'cyan', 'bg' => 'bg-cyan-100', 'text' => 'text-cyan-600'],
                                                            default => ['icon' => 'fa-plane', 'color' => 'blue', 'bg' => 'bg-blue-100', 'text' => 'text-blue-600']
                                                        };
                                                    @endphp
                                                    <div class="{{ $tripTypeConfig['bg'] }} p-3 rounded-full">
                                                        <i class="fas {{ $tripTypeConfig['icon'] }} {{ $tripTypeConfig['text'] }} text-xl"></i>
                                                    </div>
                                                    <div>
                                                        <div class="flex items-center gap-2 mb-1">
                                                            <h3 class="text-lg font-semibold text-gray-800">{{ $flight->operator_name ?: $flight->airline }}</h3>
                                                            <span class="px-2 py-1 text-xs rounded-full 
                                                                @if($flight->trip_type === 'air') bg-blue-100 text-blue-800
                                                                @elseif($flight->trip_type === 'land') bg-green-100 text-green-800
                                                                @elseif($flight->trip_type === 'sea') bg-cyan-100 text-cyan-800
                                                                @endif">
                                                                {{ $flight->trip_type_label }}
                                                            </span>
                                                        </div>
                                                        <p class="text-sm text-gray-600">{{ $flight->flight_number }}</p>
                                                        @if($flight->vehicle_type)
                                                            <p class="text-xs text-gray-500">{{ $flight->vehicle_type }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="text-right">
                                                    <p class="text-2xl font-bold {{ $tripTypeConfig['text'] }}">{{ number_format($flight->base_price) }} ريال</p>
                                                    <p class="text-sm text-gray-600">لكل راكب</p>
                                                </div>
                                            </div>

                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                                <div class="text-center">
                                                    <p class="text-sm text-gray-600">من</p>
                                                    <p class="font-semibold text-gray-800">{{ $flight->departure_city }}</p>
                                                    <p class="text-sm text-gray-600">{{ $flight->departure_airport }}</p>
                                                    @if($flight->departure_terminal)
                                                        <p class="text-xs text-gray-500">{{ $flight->departure_terminal }}</p>
                                                    @endif
                                                    <p class="text-sm text-gray-600 font-medium">{{ $flight->departure_time->format('H:i') }}</p>
                                                </div>
                                                <div class="text-center">
                                                    <div class="flex items-center justify-center">
                                                        <div class="w-8 h-px bg-gray-300"></div>
                                                        <i class="fas {{ $tripTypeConfig['icon'] }} mx-2 {{ $tripTypeConfig['text'] }}"></i>
                                                        <div class="w-8 h-px bg-gray-300"></div>
                                                    </div>
                                                    <p class="text-xs text-gray-600 mt-1">{{ $flight->duration_formatted }}</p>
                                                </div>
                                                <div class="text-center">
                                                    <p class="text-sm text-gray-600">إلى</p>
                                                    <p class="font-semibold text-gray-800">{{ $flight->arrival_city }}</p>
                                                    <p class="text-sm text-gray-600">{{ $flight->arrival_airport }}</p>
                                                    @if($flight->arrival_terminal)
                                                        <p class="text-xs text-gray-500">{{ $flight->arrival_terminal }}</p>
                                                    @endif
                                                    <p class="text-sm text-gray-600 font-medium">{{ $flight->arrival_time->format('H:i') }}</p>
                                                </div>
                                            </div>

                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center space-x-4 text-sm text-gray-600">
                                                    <span><i class="fas fa-users ml-1"></i> {{ $flight->available_seats }} مقعد متاح</span>
                                                    <span><i class="fas fa-clock ml-1"></i> {{ $flight->departure_time->format('Y-m-d') }}</span>
                                                </div>
                                                <a href="{{ route('flights.show', $flight) }}" 
                                                   class="px-6 py-2 rounded-lg transition duration-300 text-white font-medium
                                                   @if($flight->trip_type === 'air') bg-blue-600 hover:bg-blue-700
                                                   @elseif($flight->trip_type === 'land') bg-green-600 hover:bg-green-700
                                                   @elseif($flight->trip_type === 'sea') bg-cyan-600 hover:bg-cyan-700
                                                   @else bg-green-600 hover:bg-green-700 @endif">
                                                    اختر الرحلة
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @else
                    <!-- No Results -->
                    <div class="text-center py-16">
                        <div class="bg-gray-100 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-search text-gray-400 text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">لم نجد رحلات متاحة</h3>
                        <p class="text-gray-600 mb-6">جرب تغيير معايير البحث أو التاريخ</p>
                        <a href="{{ route('flights.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition duration-300">
                            بحث جديد
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('assets/js/aos.js') }}"></script>
<script>
    AOS.init({
        duration: 1000,
        once: true
    });

    // فلترة النتائج حسب نوع الرحلة
    document.querySelectorAll('.trip-type-filter').forEach(filter => {
        filter.addEventListener('change', function() {
            const selectedTypes = Array.from(document.querySelectorAll('.trip-type-filter:checked'))
                .map(cb => cb.dataset.type);
            
            const flightCards = document.querySelectorAll('.flight-card');
            
            flightCards.forEach(card => {
                const tripType = card.dataset.tripType;
                
                if (selectedTypes.length === 0 || selectedTypes.includes(tripType)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
            
            // تحديث عدد النتائج المعروضة
            updateResultsCount();
        });
    });

    function updateResultsCount() {
        const visibleCards = document.querySelectorAll('.flight-card[style*="block"], .flight-card:not([style*="none"])');
        const countElement = document.querySelector('.container .text-gray-600');
        
        if (countElement && visibleCards.length > 0) {
            const departureCount = document.querySelectorAll('.flight-card[data-trip-type]:not([style*="none"])').length;
            countElement.textContent = `${departureCount} رحلة متاحة`;
        }
    }

    // فلترة حسب الشركة
    document.querySelectorAll('input[type="checkbox"]:not(.trip-type-filter)').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            // يمكن إضافة منطق فلترة إضافي هنا
            updateResultsCount();
        });
    });
</script>
@endpush
@endsection
