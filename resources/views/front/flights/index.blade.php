@extends('layouts.app')

@section('head')
    <x-front.seo-head
        title="{{ __('messages.flights_page_title') }} - {{ __('messages.company_name') }}"
        description="{{ __('messages.flights_page_description') }}"
    />
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/aos.css') }}">
@endpush

@section('content')
<div class="min-h-screen bg-gray-50 mt-34">
    <!-- نموذج البحث والفلاتر -->
    <div class="">
        <div class="container mx-auto px-4 py-4 md:py-6">

            <!-- نموذج البحث -->
            <form action="{{ route('flights.search') }}" method="POST" class="space-y-3" data-aos="fade-up" data-aos-delay="100">
                @csrf
                
                <!-- صف الفلاتر مع زر البحث -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3">
                    <!-- من -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1 uppercase tracking-wide">
                            <i class="fas fa-plane-departure text-blue-600 mx-1"></i>
                            من
                        </label>
                        <select name="departure" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                            <option value="">اختر المدينة</option>
                            <option value="الرياض">الرياض</option>
                            <option value="جدة">جدة</option>
                            <option value="الدمام">الدمام</option>
                            <option value="مكة المكرمة">مكة المكرمة</option>
                            <option value="المدينة المنورة">المدينة المنورة</option>
                            <option value="الطائف">الطائف</option>
                            <option value="الخبر">الخبر</option>
                            <option value="الظهران">الظهران</option>
                            <option value="تبوك">تبوك</option>
                            <option value="بريدة">بريدة</option>
                            <option value="حائل">حائل</option>
                            <option value="نجران">نجران</option>
                            <option value="جازان">جازان</option>
                            <option value="الباحة">الباحة</option>
                            <option value="عرعر">عرعر</option>
                            <option value="سكاكا">سكاكا</option>
                        </select>
                    </div>

                    <!-- إلى -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1 uppercase tracking-wide">
                            <i class="fas fa-plane-arrival text-blue-600 mx-1"></i>
                            إلى
                        </label>
                        <select name="arrival" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                            <option value="">اختر المدينة</option>
                            <option value="الخرطوم">الخرطوم</option>
                            <option value="بورتسودان">بورتسودان</option>
                            <option value="كسلا">كسلا</option>
                            <option value="نيالا">نيالا</option>
                            <option value="الفاشر">الفاشر</option>
                            <option value="القضارف">القضارف</option>
                            <option value="سنار">سنار</option>
                            <option value="كادقلي">كادقلي</option>
                            <option value="الجنينة">الجنينة</option>
                            <option value="الدمازين">الدمازين</option>
                            <option value="المناقل">المناقل</option>
                            <option value="الرهد">الرهد</option>
                            <option value="الضعين">الضعين</option>
                            <option value="الطينة">الطينة</option>
                            <option value="الجنيد">الجنيد</option>
                            <option value="الروصيرص">الروصيرص</option>
                        </select>
                    </div>

                    <!-- تاريخ السفر -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1 uppercase tracking-wide">
                            <i class="fas fa-calendar-alt text-blue-600 mx-1"></i>
                            التاريخ
                        </label>
                        <input type="date" name="departure_date" 
                               min="{{ date('Y-m-d') }}" 
                               class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    </div>

                    <!-- عدد الركاب -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1 uppercase tracking-wide">
                            <i class="fas fa-user-friends text-blue-600 mx-1"></i>
                            الركاب
                        </label>
                        <select name="passengers" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                            <option value="1">1 راكب</option>
                            <option value="2">2 راكب</option>
                            <option value="3">3 ركاب</option>
                            <option value="4">4 ركاب</option>
                            <option value="5">5 ركاب</option>
                            <option value="6">6 ركاب</option>
                            <option value="7">7 ركاب</option>
                            <option value="8">8 ركاب</option>
                            <option value="9">9 ركاب</option>
                        </select>
                    </div>

                    <!-- نوع الرحلة -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1 uppercase tracking-wide">
                            <i class="fas fa-layer-group text-blue-600 mx-1"></i>
                            النوع
                        </label>
                        <select name="trip_type" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                            <option value="">جميع الأنواع</option>
                            <option value="air">رحلات جوية</option>
                            <option value="land">رحلات برية</option>
                            <option value="sea">رحلات بحرية</option>
                        </select>
                    </div>

                    <!-- زر البحث -->
                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-2 px-4 rounded-lg transition-all duration-500 ease-in-out transform hover:scale-105 active:scale-95 shadow-md inline-flex items-center justify-center gap-2">
                            <i class="fas fa-search-plus"></i>
                            بحث
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>


        <!-- شبكة الرحلات -->
    <div class="container mx-auto px-4 py-4 md:py-6">
        <div id="flights-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" data-aos="fade-up" data-aos-delay="200">
            @forelse($flights as $flight)
                <div class="flight-card bg-white rounded-xl shadow-sm hover:shadow-xl border border-gray-200 overflow-hidden transition-all duration-500 ease-in-out hover:-translate-y-1 flex flex-col group" 
                        data-trip-type="{{ $flight->trip_type }}"> 
                <!-- رأس البطاقة -->
                    <div class="flex items-center justify-between gap-3 px-4 py-4 border-b border-gray-200 flex items-center justify-between">
                        @php
                            $icons = [
                                'air' => 'fas fa-plane',
                                'land' => 'fas fa-bus',
                                'sea' => 'fas fa-ship',
                            ];
                            $labels = [
                                'air' => 'جوية',
                                'land' => 'برية',
                                'sea' => 'بحرية',
                            ];
                            $colors = [
                                'air' => 'text-blue-600 bg-blue-100',
                                'land' => 'text-green-600 bg-green-100',
                                'sea' => 'text-cyan-600 bg-cyan-100',
                            ];
                        @endphp
                        <div>
                            <span class="text-xl font-bold block">{{ $labels[$flight->trip_type] }}</span>
                            <!-- <span class="text-xs text-gray-600">نوع الرحلة</span> -->
                        </div>
                        <div class="w-10 h-10 rounded-full {{ $colors[$flight->trip_type] ?? 'text-blue-600 bg-blue-100' }} flex items-center justify-center">
                            <i class="{{ $icons[$flight->trip_type] ?? 'fas fa-globe' }} text-2xl"></i>
                        </div>
                        <!-- @if(isset($flight->rating))
                            <div class="flex items-center gap-1 text-xs font-bold text-amber-700 bg-amber-100 px-3 py-1.5 rounded-full">
                                <i class="fas fa-star text-amber-500"></i>
                                {{ $flight->rating }}
                            </div>
                        @endif -->
                    </div>

                    <!-- جسم البطاقة -->
                    <div class="p-4 flex-1 flex flex-col">
                        <!-- المسار الرئيسي -->
                        <div class="mb-4 pb-4 border-b border-gray-200">
                            <div class="grid grid-cols-3 gap-1 items-center">
                                <!-- المدينة الأولى -->
                                <div class="text-center">
                                    <!-- <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center mx-auto mb-2">
                                        <i class="fas fa-plane-departure text-blue-600 text-lg"></i>
                                    </div> -->
                                    <div class="text-sm font-bold text-gray-900">{{ $flight->departure_city }}</div>
                                    <div class="text-xs text-gray-600 font-medium mt-2">{{ $flight->departure_time }}</div>
                                </div>
                                
                                <!-- السهم والمدة -->
                                <div class="flex flex-col items-center">
                                    <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center mb-2">
                                        <i class="fas fa-arrow-left text-gray-500 text-sm"></i>
                                    </div>
                                    <div class="text-xs text-gray-500 font-medium bg-gray-100 px-2 py-1 rounded-full">{{ $flight->duration_minutes }}</div>
                                </div>
                                
                                <!-- المدينة الثانية -->
                                <div class="text-center">
                                    <!-- <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center mx-auto mb-2">
                                        <i class="fas fa-plane-arrival text-green-600 text-lg"></i>
                                    </div> -->
                                    <div class="text-sm font-bold text-gray-900">{{ $flight->arrival_city }}</div>
                                    <div class="text-xs text-gray-600 font-medium mt-1">{{ $flight->arrival_time  }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- تفاصيل الرحلة -->
                        <div class="space-y-3 mb-4">
                            <!-- المقاعد المتاحة -->
                            <div class="flex items-center justify-between p-2 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                        <i class="fas fa-chair text-blue-600 text-sm"></i>
                                    </div>
                                    <div>
                                        <span class="text-sm text-gray-700 font-medium block">المقاعد المتاحة</span>
                                        <!-- <span class="text-xs text-gray-500">عدد المقاعد</span> -->
                                    </div>
                                </div>
                                <span class="text-lg font-bold text-blue-600">{{ $flight->available_seats }}</span>
                            </div>

                            <!-- الأمتعة -->
                            <div class="flex items-center justify-between p-2 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                                        <i class="fas fa-dollar-sign text-green-600 text-sm"></i>
                                    </div>
                                    <div>
                                        <span class="text-sm text-gray-700 font-medium block"> تكلفة الرحلة</span>
                                        <!-- <span class="text-xs text-gray-500">الوزن المسموح</span> -->
                                    </div>
                                </div>
                                <span class="text-lg font-bold text-green-600">{{ $flight->base_price }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- تذييل البطاقة -->
                    <div class="px-4 py-4 border-t border-gray-200">
                    <div class="px-4 py-4 border-t border-gray-200">
                        <a href=" {{ route('flights.show' , $flight) }} " class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-3 rounded-xl transition-all duration-500 ease-in-out transform hover:scale-[1.02] active:scale-95 text-sm flex items-center justify-center gap-2 hover:shadow-lg group">
                            <i class="fas fa-shopping-cart transition-transform group-hover:scale-110"></i>
                            احجز الآن
                            <i class="fas fa-arrow-left text-xs group-hover:translate-x-1 transition-transform"></i>
                        </a>
                    </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-20">
                    <div class="w-24 h-24 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-plane-slash text-4xl text-gray-400"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-700 mb-2">لا توجد رحلات متاحة حالياً</h3>
                    <p class="text-gray-500 text-sm mb-6">حاول تغيير معايير البحث أو التاريخ</p>
                    <button onclick="document.querySelector('form').scrollIntoView({behavior: 'smooth'})" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors inline-flex items-center gap-2">
                        <i class="fas fa-edit"></i>
                        تعديل البحث
                    </button>
                </div>
            @endforelse
        </div>
    </div>
    </div>
</div>

@push('scripts')
<script>
    // فلترة الرحلات حسب النوع من نموذج البحث
    document.querySelector('select[name="trip_type"]').addEventListener('change', function() {
        const selectedType = this.value;
        
        // فلترة البطاقات
        document.querySelectorAll('.flight-card').forEach(card => {
            const cardType = card.getAttribute('data-trip-type');
            if (selectedType === '' || cardType === selectedType) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    });

    // تعيين تاريخ العودة ليكون بعد تاريخ السفر
    document.querySelector('input[name="departure_date"]').addEventListener('change', function() {
        const returnDateInput = document.querySelector('input[name="return_date"]');
        if (returnDateInput) {
            const departureDate = new Date(this.value);
            const minReturnDate = new Date(departureDate);
            minReturnDate.setDate(minReturnDate.getDate() + 1);
            
            returnDateInput.min = minReturnDate.toISOString().split('T')[0];
        }
    });



    // تحسين تجربة الفلترة مع تأثيرات بصرية
    document.addEventListener('DOMContentLoaded', function() {
        const filterSelect = document.querySelector('select[name="trip_type"]');
        if (filterSelect) {
            filterSelect.addEventListener('change', function() {
                const cards = document.querySelectorAll('.flight-card');
                cards.forEach((card, index) => {
                    card.style.opacity = '0.5';
                    card.style.transform = 'scale(0.95)';
                    
                    setTimeout(() => {
                        const cardType = card.getAttribute('data-trip-type');
                        const selectedType = this.value;
                        
                        if (selectedType === '' || cardType === selectedType) {
                            card.style.display = '';
                            card.style.opacity = '1';
                            card.style.transform = 'scale(1)';
                        } else {
                            card.style.display = 'none';
                        }
                    }, index * 50);
                });
            });
        }
    });
</script>
@endpush
@endsection