@extends('layouts.app')

@section('head')
    <x-front.seo-head
        title="{{ __('messages.flight_details') }} {{ $flight->flight_number }} - {{ __('messages.company_name') }}"
        description="{{ __('messages.flight_details') }} {{ $flight->departure_city }} {{ __('messages.to') }} {{ $flight->arrival_city }} {{ __('messages.and') }} {{ __('messages.company_name') }}."
    />
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/aos.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/payment-styles.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        /* إخفاء زر الإدخال الأصلي */
        .custom-file-input {
            opacity: 0;
            position: absolute;
            width: 100%;
            height: 100%;
            cursor: pointer;
            top: 0;
            right: 0;
        }

        /* تنسيق الحاوية لتكون قابلة للنقر */
        .file-upload-container {
            position: relative;
            overflow: hidden;
        }

        /* تحسينات مخصصة للتصميم */
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .gradient-bg-air {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        
        .gradient-bg-land {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        }
        
        .gradient-bg-sea {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        
        .card-hover {
            transition: all 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        
        .route-line {
            position: relative;
            flex: 1;
            height: 3px;
            background: linear-gradient(to right, #e5e7eb 0%, #3b82f6 50%, #e5e7eb 100%);
        }
        
        .route-icon {
            animation: float 3s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        .tab-button {
            transition: all 0.3s ease;
        }
        
        .tab-button.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .input-focus {
            transition: all 0.3s ease;
        }
        
        .input-focus:focus {
            transform: scale(1.02);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        .price-badge {
            position: relative;
            overflow: hidden;
        }
        
        .price-badge::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s;
        }
        
        .price-badge:hover::before {
            left: 100%;
        }

        .plan-from {
            transform: skew(180deg);
        }

        @media (max-width: 480px){
            .hidden-400 {
                display: none;
            }
        }

    </style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50 to-gray-50">
    
    {{-- عرض رسائل الخطأ --}}
    @if($errors->any())
        <div class="container mx-auto px-4 pt-20 mt-32">
            <div class="max-w-4xl mx-auto mb-6">
                <div class="bg-red-50 border border-red-200 rounded-lg p-4" data-aos="fade-up">
                    <div class="flex items-center justify-center">
                        <div class="p-2 ml-3">
                            <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                        </div>
                        <div class="mx-3">
                            @foreach($errors->all() as $error)
                                <p class="text-red-600 font-semibold">{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    
    {{-- ============================================ --}}
    {{-- Header Section - معلومات الرحلة الرئيسية --}}
    {{-- ============================================ --}}
    
    @php
        $tripTypeConfig = match($flight->trip_type) {
            'air' => [
                'color' => 'blue', 
                'bg' => 'bg-blue-100', 
                'text' => 'text-blue-800',
                'gradient' => 'from-blue-500 to-blue-600',
                'icon' => 'fa-plane'
            ],
            'land' => [
                'color' => 'green', 
                'bg' => 'bg-green-100', 
                'text' => 'text-green-800',
                'gradient' => 'from-green-500 to-green-600',
                'icon' => 'fa-bus'
            ],
            'sea' => [
                'color' => 'cyan', 
                'bg' => 'bg-cyan-100', 
                'text' => 'text-cyan-800',
                'gradient' => 'from-cyan-500 to-cyan-600',
                'icon' => 'fa-ship'
            ],
            default => [
                'color' => 'blue', 
                'bg' => 'bg-blue-100', 
                'text' => 'text-blue-800',
                'gradient' => 'from-blue-500 to-blue-600',
                'icon' => 'fa-plane'
            ]
        };
    @endphp


    <div class="container mx-auto px-4 py-10 mt-30">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- ============================================ --}}
            {{-- القسم الأيسر - تفاصيل الرحلة --}}
            {{-- ============================================ --}}
            
            <div class="lg:col-span-2 space-y-6">
                
                {{-- بطاقة تفاصيل الرحلة --}}
                <div class="bg-white rounded-2xl shadow-xl px-8 pt-8 pb-10 card-hover" data-aos="fade-up">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="bg-gradient-to-r {{ $tripTypeConfig['gradient'] }} p-2 rounded-xl">
                            <i class="fas fa-route text-white"></i>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900">{{ __('messages.flight_details') }}</h2>
                    </div>
                    
                    {{-- مسار الرحلة --}}
                    <div class="bg-gradient-to-br from-gray-50 to-blue-50 rounded-2xl p-4 mb-8">
                        <div class="flex  md:flex-row items-center justify-between gap-6">
                            
                            <!-- نقطة المغادرة -->
                            <div class="text-center flex-shrink-0">
                                <div class="bg-white rounded-xl py-4 md:px-4 px-2 shadow-md mb-3">
                                    <i class="fas fa-plane-departure md:text-3xl text-xl {{ $tripTypeConfig['text'] }} mb-2 plan-from"></i>
                                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">من</p>
                                    <p class="text-xl font-bold text-gray-900">{{ $flight->departure_city }}</p>
                                    @if($flight->departure_terminal)
                                        <p class="text-xs text-gray-500 mt-1">{{ $flight->departure_terminal }}</p>
                                    @endif
                                </div>
                                <div class="bg-white rounded-lg px-4 py-2 shadow-sm">
                                    <p class="text-xl font-bold {{ $tripTypeConfig['text'] }}">{{ $flight->departure_time->format('H:i') }}</p>
                                    <p class="text-sm text-gray-500">{{ $flight->departure_time->format('Y-m-d') }}</p>
                                </div>
                            </div>
                            
                            <!-- خط المسار -->
                            <div class="md:flex-1 md:block md:w-auto w-full mt-6 hidden-400">
                                <div class="flex items-center justify-center relative">
                                    <div class="w-full h-1 bg-gradient-to-r {{ $tripTypeConfig['gradient'] }} rounded-full"></div>
                                    <div class="absolute bg-white px-4 py-2 rounded-full shadow-lg route-icon">
                                        <i class="fas {{ $tripTypeConfig['icon'] }} md:text-3xl text-xl {{ $tripTypeConfig['text'] }} plan-icon-rotate"></i>
                                    </div>
                                </div>
                                <div class="text-center mt-12">
                                    <p class="text-xs text-gray-500">مدة الرحلة</p>
                                    <p class="text-sm font-semibold text-gray-700">{{ $flight->duration_formatted }}</p>
                                </div>
                            </div>
                            
                            <!-- نقطة الوصول -->
                            <div class="text-center flex-shrink-0">
                                <div class="bg-white rounded-xl py-4 md:px-4 px-2 shadow-md mb-3">
                                    <i class="fas fa-plane-arrival md:text-3xl text-xl {{ $tripTypeConfig['text'] }} mb-2"></i>
                                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">إلى</p>
                                    <p class="text-xl font-bold text-gray-900">{{ $flight->arrival_city }}</p>
                                    @if($flight->arrival_terminal)
                                        <p class="text-xs text-gray-500 mt-1">{{ $flight->arrival_terminal }}</p>
                                    @endif
                                </div>
                                <div class="bg-white rounded-lg px-4 py-2 shadow-sm">
                                    <p class="text-xl font-bold {{ $tripTypeConfig['text'] }}">{{ $flight->arrival_time->format('H:i') }}</p>
                                    <p class="text-sm text-gray-500">{{ $flight->arrival_time->format('Y-m-d') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- معلومات الرحلة والفئات --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <!-- معلومات الرحلة -->
                        <div class="bg-gray-50 rounded-xl p-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                                <i class="fas fa-info-circle {{ $tripTypeConfig['text'] }}"></i>
                                معلومات الرحلة
                            </h3>
                            <div class="space-y-3">
                                <div class="flex justify-between items-center py-2 border-b border-gray-200">
                                    <span class="text-gray-600 flex items-center gap-2">
                                        <i class="fas fa-tag text-gray-400 text-sm"></i>
                                        نوع المركبة
                                    </span>
                                    <span class="font-semibold text-gray-900">{{ $flight->vehicle_type_label }}</span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-gray-200">
                                    <span class="text-gray-600 flex items-center gap-2">
                                        <i class="fas fa-map-marker-alt text-gray-400 text-sm"></i>
                                        محطة المغادرة
                                    </span>
                                    <span class="font-semibold text-gray-900">{{ $flight->departure_airport }}</span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-gray-200">
                                    <span class="text-gray-600 flex items-center gap-2">
                                        <i class="fas fa-map-marker-alt text-gray-400 text-sm"></i>
                                        محطة الوصول
                                    </span>
                                    <span class="font-semibold text-gray-900">{{ $flight->arrival_airport }}</span>
                                </div>
                                @if($flight->departure_terminal)
                                <div class="flex justify-between items-center py-2 border-b border-gray-200">
                                    <span class="text-gray-600 flex items-center gap-2">
                                        <i class="fas fa-door-open text-gray-400 text-sm"></i>
                                        محطة المغادرة
                                    </span>
                                    <span class="font-semibold text-gray-900">{{ $flight->departure_terminal }}</span>
                                </div>
                                @endif
                                @if($flight->arrival_terminal)
                                <div class="flex justify-between items-center py-2 border-b border-gray-200">
                                    <span class="text-gray-600 flex items-center gap-2">
                                        <i class="fas fa-door-open text-gray-400 text-sm"></i>
                                        محطة الوصول
                                    </span>
                                    <span class="font-semibold text-gray-900">{{ $flight->arrival_terminal }}</span>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- فئات المقاعد -->
                        <div class="bg-gray-50 rounded-xl p-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                                <i class="fas fa-layer-group {{ $tripTypeConfig['text'] }}"></i>
                                @if($flight->trip_type === 'sea')
                                    فئات الكابينات
                                @else
                                    فئات المقاعد
                                @endif
                            </h3>
                            <div class="space-y-3">
                                @if($flight->seat_classes)
                                    @foreach($flight->seat_classes as $class)
                                        <div class="flex justify-between items-center p-3 bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border-2 border-transparent hover:border-{{ $tripTypeConfig['color'] }}-200">
                                            <div class="flex items-center gap-3">
                                                <div class="bg-gradient-to-r {{ $tripTypeConfig['gradient'] }} p-1 rounded-xl">
                                                    <i class="fas fa-check text-white text-sm"></i>
                                                </div>
                                                <span class="font-semibold text-gray-900 capitalize">{{ t(ucfirst($class)) }}</span>
                                            </div>
                                            <span class="{{ $tripTypeConfig['text'] }} font-bold text-lg">
                                                {{ number_format($flight->getPriceForClass($class)) }} ريال
                                            </span>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="flex justify-between items-center p-4 bg-white rounded-xl shadow-sm">
                                        <div class="flex items-center gap-3">
                                            <div class="bg-gradient-to-r {{ $tripTypeConfig['gradient'] }} p-2 rounded-lg">
                                                <i class="fas fa-check text-white text-sm"></i>
                                            </div>
                                            <span class="font-semibold text-gray-900">
                                                @if($flight->trip_type === 'sea')
                                                    كابينة عادية
                                                @else
                                                    درجة اقتصادية
                                                @endif
                                            </span>
                                        </div>
                                        <span class="{{ $tripTypeConfig['text'] }} font-bold text-lg">
                                            {{ number_format($flight->base_price) }} ريال
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ============================================ --}}
            {{-- القسم الأيمن - Sidebar --}}
            {{-- ============================================ --}}
            
            <div class="lg:col-span-1 space-y-6">
                
                {{-- ملخص السعر --}}
                <div class="bg-white rounded-2xl shadow-xl p-6 card-hover" data-aos="fade-up" data-aos-delay="300">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="bg-gradient-to-r from-green-500 to-emerald-600 p-2 rounded-xl">
                            <i class="fas fa-dollar-sign text-white"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">ملخص السعر</h3>
                    </div>
                    
                    <div class="space-y-1">
                        <div class="flex justify-between items-center py-3 border-b border-gray-100">
                            <span class="text-gray-600 flex items-center gap-2">
                                <i class="fas fa-tag text-gray-400 text-sm"></i>
                                السعر الأساسي
                            </span>
                            <span class="font-semibold text-gray-900">{{ number_format($flight->base_price) }} ريال</span>
                        </div>
                        <div class="flex justify-between items-center py-3 border-b border-gray-100">
                            <span class="text-gray-600 flex items-center gap-2">
                                <i class="fas fa-percent text-gray-400 text-sm"></i>
                                ضريبة القيمة المضافة (15%)
                            </span>
                            <span class="font-semibold text-gray-900">{{ number_format($flight->base_price * 0.15) }} ريال</span>
                        </div>
                        <div class="flex justify-between items-center py-3 border-b border-gray-100">
                            <span class="text-gray-600 flex items-center gap-2">
                                <i class="fas fa-cog text-gray-400 text-sm"></i>
                                رسوم الخدمة
                            </span>
                            <span class="font-semibold text-gray-900">50 ريال</span>
                        </div>
                        
                        <div class="bg-gradient-to-r {{ $tripTypeConfig['gradient'] }} rounded-xl p-3 mt-4">
                            <div class="flex justify-between items-center">
                                <span class="text-white font-bold text-lg">المجموع الكلي</span>
                                <span class="text-white font-bold text-2xl">{{ number_format($flight->base_price * 1.15 + 50) }} ريال</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- حالة الرحلة --}}
                <div class="bg-white rounded-2xl shadow-xl p-4 card-hover" data-aos="fade-up" data-aos-delay="400">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 p-3 rounded-lg">
                            <i class="fas fa-info-circle text-white"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">حالة الرحلة</h3>
                    </div>
                    
                    <div class="space-y-2">
                        <div class="flex items-center justify-between py-2 border-b border-gray-100">
                            <span class="text-gray-600 flex items-center gap-2">
                                <i class="fas fa-circle-check text-gray-400 text-sm"></i>
                                الحالة
                            </span>
                            <span class="px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-full text-sm font-bold shadow-md">
                                متاحة
                            </span>
                        </div>
                        <div class="flex items-center justify-between py-3 border-b border-gray-100">
                            <span class="text-gray-600 flex items-center gap-2">
                                <i class="fas fa-chair text-gray-400 text-sm"></i>
                                المقاعد المتاحة
                            </span>
                            <span class="font-bold text-green-600 text-xl">{{ $flight->available_seats }}</span>
                        </div>
                        <div class="flex items-center justify-between py-3">
                            <span class="text-gray-600 flex items-center gap-2">
                                <i class="fas fa-plane text-gray-400 text-sm"></i>
                                نوع الرحلة
                            </span>
                            <span class="font-semibold {{ $tripTypeConfig['text'] }}">{{ $flight->trip_type_label }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-10">
        <div class="bg-white rounded-2xl shadow-xl p-8 card-hover" data-aos="fade-up" data-aos-delay="200">
            <div class="flex items-center gap-3 mb-6">
                <div class="bg-gradient-to-r {{ $tripTypeConfig['gradient'] }} p-2 rounded-lg">
                    <i class="fas fa-ticket-alt text-white"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-900">{{ __('messages.book_flight') }}</h2>
            </div>
            <form action="{{ route('flights.choosePayment', $flight) }}" method="POST" class="space-y-8" enctype="multipart/form-data">
                @csrf
                <div class="tab-content" id="passenger-info">
                    <div class="bg-gradient-to-br from-gray-50 to-blue-50 rounded-xl p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                            <i class="fas fa-user-circle {{ $tripTypeConfig['text'] }}"></i>
                            معلومات المسافر
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-id-card text-gray-400 ml-1"></i>
                                    الاسم الكامل (كما هو في الجواز) *
                                </label>
                                <input type="text" name="passenger_name" value="{{ old('passenger_name') }}" required 
                                    class="w-full px-4 py-3 border {{ $errors->has('passenger_name') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent input-focus">
                                @error('passenger_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-envelope text-gray-400 ml-1"></i>
                                    البريد الإلكتروني *
                                </label>
                                <input type="email" name="passenger_email" value="{{ old('passenger_email') }}" required 
                                    class="w-full px-4 py-3 border {{ $errors->has('passenger_email') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent input-focus">
                                @error('passenger_email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-flag text-gray-400 ml-1"></i>
                                    الجنسية
                                </label>
                                <input type="text" name="nationality" value="{{ old('nationality') }}"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent input-focus">
                            </div>

                            <div class="file-upload-container">
                                <label for="file-upload" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-id-card text-gray-400 mx-2"></i>
                                    صورة جواز السفر أو الإقامة
                                </label>

                                <!-- الحاوية المخصصة لزر الإرفاق -->
                                <div id="custom-button" class="flex items-center justify-between p-3 border-2 border-dashed border-gray-300 rounded-lg bg-gray-50 hover:bg-gray-100 transition duration-150 ease-in-out cursor-pointer text-gray-600">
                                    <span id="file-name" class="truncate text-sm font-medium">
                                        <i class="fas fa-upload text-indigo-500 mx-2"></i>
                                        انقر هنا لاختيار ملف   
                                    </span>
                                    <span class="text-xs font-semibold text-indigo-600 bg-indigo-100 px-3 py-1 rounded-full shadow-sm">
                                        اختيار
                                    </span>
                                </div>

                                <!-- زر الإدخال الأصلي المخفي (تحويله إلى input فعلي) -->
                                <input type="file" id="file-upload" name="image" accept="image/*"
                                    class="custom-file-input" onchange="updateFileName(this)">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-hashtag text-gray-400 ml-1"></i>
                                    رقم جواز السفر
                                </label>
                                <input type="text" name="passport_number" value="{{ old('passport_number') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent input-focus">
                                @error('passenger_email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror    
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-calendar-plus text-gray-400 ml-1"></i>
                                    تاريخ إصدار الجواز
                                </label>
                                <input type="date" name="passport_issue_date" value="{{ old('passport_issue_date') }}"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent input-focus">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-calendar-times text-gray-400 ml-1"></i>
                                    تاريخ انتهاء الجواز
                                </label>
                                <input type="date" name="passport_expiry_date" value="{{ old('passport_expiry_date') }}"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent input-focus">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-id-badge text-gray-400 ml-1"></i>
                                    رقم الهوية الوطنية أو الإقامة
                                </label>
                                <input type="text" name="passenger_id_number" value="{{ old('passenger_id_number') }}"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent input-focus">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-calendar text-gray-400 ml-1"></i>
                                    تاريخ الميلاد
                                </label>
                                <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent input-focus">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-home text-gray-400 ml-1"></i>
                                    بلد الإقامة الحالية
                                </label>
                                <input type="text" name="current_residence_country" value="{{ old('current_residence_country') }}"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent input-focus">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">بلد وجهة الوصول</label>
                                <input type="text" name="destination_country" value="{{ old('destination_country') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-phone text-gray-400 ml-1"></i>
                                    رقم الهاتف في السعودية *
                                </label>
                                <input type="tel" name="passenger_phone" value="{{ old('passenger_phone') }}" required 
                                    class="w-full px-4 py-3 border {{ $errors->has('passenger_phone') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent input-focus">
                                @error('passenger_phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-phone-alt text-gray-400 ml-1"></i>
                                    رقم الهاتف في السودان
                                </label>
                                <input type="tel" name="phone_sudan" value="{{ old('phone_sudan') }}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent input-focus">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-users text-gray-400 ml-1"></i>
                                    عدد الركاب *
                                </label>
                                <select name="number_of_passengers" required 
                                        class="w-full px-4 py-3 border-2 {{ $errors->has('number_of_passengers') ? 'border-red-500' : 'border-gray-200' }} rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent input-focus">
                                    @for($i = 1; $i <= min(9, $flight->available_seats); $i++)
                                        <option value="{{ $i }}" {{ old('number_of_passengers') == $i ? 'selected' : '' }}>{{ $i }} {{ $i == 1 ? 'راكب' : 'ركاب' }}</option>
                                    @endfor
                                </select>
                                @error('number_of_passengers')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-exchange-alt text-gray-400 ml-1"></i>
                                    نوع التذكرة *
                                </label>
                                <select name="ticket_type" required 
                                        class="w-full px-4 py-3 border-2 {{ $errors->has('ticket_type') ? 'border-red-500' : 'border-gray-200' }} rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent input-focus">
                                    <option value="one_way" {{ old('ticket_type') == 'one_way' ? 'selected' : '' }}>ذهاب فقط</option>
                                    <option value="round_trip" {{ old('ticket_type') == 'round_trip' ? 'selected' : '' }}>ذهاب وعودة</option>
                                </select>
                                @error('ticket_type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-chair text-gray-400 ml-1"></i>
                                    @if($flight->trip_type === 'sea')
                                        فئة الكابينة *
                                    @else
                                        فئة المقعد *
                                    @endif
                                </label>
                                <select name="seat_class" required 
                                        class="w-full px-4 py-3 border-2 {{ $errors->has('seat_class') ? 'border-red-500' : 'border-gray-200' }} rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent input-focus">
                                    @if($flight->seat_classes)
                                        @foreach($flight->seat_classes as $class)
                                            <option value="{{ $class }}" {{ old('seat_class') == $class ? 'selected' : '' }}>
                                                {{ ucfirst($class) }} - {{ number_format($flight->getPriceForClass($class)) }} ريال
                                            </option>
                                        @endforeach
                                    @else
                                        <option value="economy" {{ old('seat_class') == 'economy' ? 'selected' : '' }}>
                                            @if($flight->trip_type === 'sea')
                                                كابينة عادية
                                            @else
                                                درجة اقتصادية
                                            @endif
                                            - {{ number_format($flight->base_price) }} ريال
                                        </option>
                                    @endif
                                </select>
                                @error('seat_class')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            @if($flight->trip_type === 'sea')
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-bed text-gray-400 ml-1"></i>
                                    نوع الكابينة
                                </label>
                                <input type="text" name="cabin_type" value="{{ old('cabin_type') }}"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent input-focus"
                                        placeholder="مثل: كابينة داخلية، كابينة خارجية">
                            </div>
                            @endif

                            {{-- حقل طريقة الدفع --}}
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-4">
                                    <i class="fas fa-credit-card text-gray-400 ml-1"></i>
                                    طريقة الدفع *
                                </label>
                                
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    {{-- PayPal Option --}}
                                    <div class="payment-method-card bg-white rounded-xl p-4 border-2 {{ $errors->has('payment_method') ? 'border-red-500' : 'border-gray-200' }} cursor-pointer" 
                                            onclick="selectPaymentMethod('paypal')">
                                        <input type="radio" name="payment_method" value="paypal" id="paypal" class="hidden" required {{ old('payment_method') == 'paypal' ? 'checked' : '' }}>
                                        <label for="paypal" class="cursor-pointer">
                                            <div class="payment-icon paypal-icon">
                                                <i class="fab fa-paypal"></i>
                                            </div>
                                            <h3 class="font-bold text-gray-900 text-center mb-2">PayPal</h3>
                                            <p class="text-sm text-gray-600 text-center">الدفع الآمن عبر PayPal</p>
                                        </label>
                                    </div>
                                    
                                    {{-- Credit Card Option --}}
                                    <div class="payment-method-card bg-white rounded-xl p-4 border-2 {{ $errors->has('payment_method') ? 'border-red-500' : 'border-gray-200' }} cursor-pointer" 
                                            onclick="selectPaymentMethod('credit_card')">
                                        <input type="radio" name="payment_method" value="credit_card" id="credit_card" class="hidden" required {{ old('payment_method') == 'credit_card' ? 'checked' : '' }}>
                                        <label for="credit_card" class="cursor-pointer">
                                            <div class="payment-icon credit-card-icon">
                                                <i class="fas fa-credit-card"></i>
                                            </div>
                                            <h3 class="font-bold text-gray-900 text-center mb-2">البطاقة الائتمانية</h3>
                                            <p class="text-sm text-gray-600 text-center">الدفع الآمن بالبطاقة</p>
                                        </label>
                                    </div>

                                    {{-- WhatsApp Option --}}
                                    <div class="payment-method-card bg-white rounded-xl p-4 border-2 {{ $errors->has('payment_method') ? 'border-red-500' : 'border-gray-200' }} cursor-pointer" 
                                            onclick="selectPaymentMethod('whatsapp')">
                                        <input type="radio" name="payment_method" value="whatsapp" id="whatsapp" class="hidden" required {{ old('payment_method') == 'whatsapp' ? 'checked' : '' }}>
                                        <label for="whatsapp" class="cursor-pointer">
                                            <div class="payment-icon whatsapp-icon">
                                                <i class="fab fa-whatsapp"></i>
                                            </div>
                                            <h3 class="font-bold text-gray-900 text-center mb-2">الواتساب</h3>
                                            <p class="text-sm text-gray-600 text-center">إتمام الحجز عبر الواتساب</p>
                                        </label>
                                    </div>
                                </div>
                                @error('payment_method')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>
                    </div>
                </div>

                {{-- زر الإرسال --}}
                <div class="text-center pt-4">
                    <button type="submit" 
                            class="cursor-pointer bg-gradient-to-r {{ $tripTypeConfig['gradient'] }} text-white font-bold py-4 px-12 rounded-xl text-lg shadow-xl hover:shadow-2xl">
                        <i class="fas fa-check-circle ml-2"></i>
                        احجز الآن
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('assets/js/aos.js') }}"></script>
<script src="{{ asset('assets/js/payment-methods.js') }}"></script>
<script>
    // تفعيل AOS للتأثيرات الحركية
    AOS.init({
        duration: 800,
        once: true,
        offset: 100
    });

    // ============================================
    // نظام Tabs
    // ============================================
    document.addEventListener('DOMContentLoaded', function() {
        const tabButtons = document.querySelectorAll('.tab-button');
        const tabContents = document.querySelectorAll('.tab-content');
        
        tabButtons.forEach(button => {
            button.addEventListener('click', function() {
                const targetTab = this.getAttribute('data-tab');
                
                // إزالة الـ active من جميع الأزرار
                tabButtons.forEach(btn => {
                    btn.classList.remove('active');
                    btn.classList.add('text-gray-600', 'hover:bg-gray-200');
                });
                
                // إضافة active للزر المضغوط
                this.classList.add('active');
                this.classList.remove('text-gray-600', 'hover:bg-gray-200');
                
                // إخفاء جميع المحتويات
                tabContents.forEach(content => {
                    content.classList.add('hidden');
                });
                
                // إظهار المحتوى المطلوب
                document.getElementById(targetTab).classList.remove('hidden');
            });
        });
    });

    // ============================================
    // تحديث السعر ديناميكياً
    // ============================================
    document.querySelector('select[name="seat_class"]').addEventListener('change', updatePrice);
    document.querySelector('select[name="number_of_passengers"]').addEventListener('change', updatePrice);
    document.querySelector('select[name="ticket_type"]').addEventListener('change', updatePrice);

    function updatePrice() {
        const seatClass = document.querySelector('select[name="seat_class"]').value;
        const passengers = parseInt(document.querySelector('select[name="number_of_passengers"]').value);
        const ticketType = document.querySelector('select[name="ticket_type"]').value;
        
        const basePrice = {{ $flight->base_price }};
        let totalPrice = basePrice * passengers;
        
        // إذا كان ذهاب وعودة، ضاعف السعر
        if (ticketType === 'round_trip') {
            totalPrice = totalPrice * 2;
        }
        
        const tax = totalPrice * 0.15;
        const serviceFee = 50 * passengers;
        const finalTotal = totalPrice + tax + serviceFee;

        // تحديث عرض السعر في الـ Sidebar
        const totalElements = document.querySelectorAll('.text-2xl.font-bold.text-white');
        totalElements.forEach(element => {
            if (element.textContent.includes('ريال')) {
                element.textContent = finalTotal.toLocaleString() + ' ريال';
            }
        });
    }

    // ============================================
    // تعيين تاريخ السفر الافتراضي
    // ============================================
    document.addEventListener('DOMContentLoaded', function() {
        const travelDateInput = document.querySelector('input[name="travel_date"]');
        if (travelDateInput && !travelDateInput.value) {
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            travelDateInput.value = tomorrow.toISOString().split('T')[0];
        }
    });

    function updateFileName(input) {
        const fileNameDisplay = document.getElementById("file-name");
        const customButton = document.getElementById("custom-button");

        if (input.files && input.files.length > 0) {
            const fileName = input.files[0].name;
            // عرض اسم الملف مع أيقونة النجاح
            fileNameDisplay.innerHTML = `<i class=\"fas fa-check-circle text-green-500 ml-2\"></i> ${fileName}`;
            // تغيير تنسيق الزر للإشارة إلى النجاح
            customButton.classList.remove("border-gray-300", "bg-gray-50", "text-gray-600");
            customButton.classList.add("border-green-400", "bg-green-50", "text-green-700");
        } else {
            // إعادة التنسيق للحالة الافتراضية
            fileNameDisplay.innerHTML = `<i class=\"fas fa-upload text-gray-700 mx-2\"></i> انقر هنا لاختيار ملف أو اسحبه وأفلته`;
            customButton.classList.remove("border-green-400", "bg-green-50", "text-green-700");
            customButton.classList.add("border-gray-300", "bg-gray-50", "text-gray-600");
        }
    }
</script>
@endpush
@endsection