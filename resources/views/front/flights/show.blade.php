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
    <div class="pb-6 bg-gradient-to-br from-gray-50 via-blue-50 to-gray-50">
        
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

        <div class="container mx-auto px-4 pt-10 mt-30">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-3 space-y-6">
                    
                    {{-- بطاقة تفاصيل الرحلة --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 px-8 pt-4 pb-6" data-aos="fade-up">
                        <div class="flex items-center justify-between mb-4 border-b border-gray-100 pb-2">
                            <div class="flex items-center gap-3">
                                <div class="bg-gray-100 p-2.5 rounded-lg">
                                    <i class="fas fa-plane-departure text-gray-700"></i>
                                </div>
                                <h2 class="text-xl font-bold text-gray-900">{{ __('messages.flight_details') }}</h2>
                            </div>
                            <div class="text-sm text-gray-500 font-medium bg-gray-50 px-3 py-1 rounded-full">
                                {{ $flight->flight_number ?? 'FL-' . $flight->id }}
                            </div>
                        </div>
                        
                        {{-- مسار الرحلة - تصميم رسمي --}}
                        <div class="bg-white border border-gray-200 rounded-xl p-4 mb-8 shadow-[0_2px_8px_rgba(0,0,0,0.04)]">
                            <div class="flex flex-col md:flex-row items-center justify-between gap-8 md:gap-4">
                                
                                <!-- نقطة المغادرة -->
                                <div class="text-center w-full md:w-1/4">
                                    <p class="text-sm text-gray-500 font-medium mb-1">المغادرة</p>
                                    <p class="text-2xl font-bold text-gray-900 mb-1">{{ $flight->departure_city }}</p>
                                    <div class="inline-flex items-center px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-sm font-bold mt-2">
                                        {{ $flight->departure_time->format('H:i') }}
                                    </div>
                                    <p class="text-xs text-gray-400 mt-2">{{ $flight->departure_time->format('Y-m-d') }}</p>
                                    @if($flight->departure_terminal)
                                        <p class="text-xs text-gray-500 mt-1 font-medium bg-gray-50 inline-block px-2 py-0.5 rounded">{{ $flight->departure_terminal }}</p>
                                    @endif
                                </div>
                                
                                <!-- خط المسار -->
                                <div class="md:flex-1 w-full flex flex-col items-center justify-center px-4">
                                    <div class="flex items-center gap-2 mb-4">
                                        <i class="far fa-clock text-gray-400 text-xs"></i>
                                        <span class="text-xs text-gray-500 font-medium">{{ $flight->duration_formatted }}</span>
                                    </div>
                                    <div class="w-full flex items-center relative">
                                        <div class="h-2 w-2 rounded-full bg-gray-300"></div>
                                        <div class="flex-1 h-[2px] bg-gray-200 relative">
                                            <!-- أيقونة الطائرة في المنتصف -->
                                            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white px-2">
                                                <i class="fas {{ $tripTypeConfig['icon'] }} text-gray-400 transform {{ $flight->trip_type == 'air' ? 'rotate-180' : '' }}"></i>
                                            </div>
                                        </div>
                                        <div class="h-2 w-2 rounded-full bg-gray-300"></div>
                                    </div>
                                    <div class="mt-2 text-xs text-gray-400 font-medium">{{ $flight->trip_type_label }}</div>
                                </div>
                                
                                <!-- نقطة الوصول -->
                                <div class="text-center w-full md:w-1/4">
                                    <p class="text-sm text-gray-500 font-medium mb-1">الوصول</p>
                                    <p class="text-2xl font-bold text-gray-900 mb-1">{{ $flight->arrival_city }}</p>
                                    <div class="inline-flex items-center px-3 py-1 rounded-full bg-gray-100 text-gray-700 text-sm font-bold mt-2">
                                        {{ $flight->arrival_time->format('H:i') }}
                                    </div>
                                    <p class="text-xs text-gray-400 mt-2">{{ $flight->arrival_time->format('Y-m-d') }}</p>
                                    @if($flight->arrival_terminal)
                                        <p class="text-xs text-gray-500 mt-1 font-medium bg-gray-50 inline-block px-2 py-0.5 rounded">{{ $flight->arrival_terminal }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- معلومات الرحلة والفئات --}}
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                            
                            <!-- معلومات الرحلة - شبكة رسمية (ثلثي المساحة) -->
                            <div class="col-span-1 lg:col-span-2">
                                <h3 class="text-sm uppercase tracking-wider text-gray-500 font-bold mb-4 flex items-center gap-2">
                                    <span class="w-1 h-4 bg-blue-600 rounded-full inline-block"></span>
                                    البيانات الأساسية
                                </h3>
                                
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                                    <!-- نوع المركبة -->
                                    <div class="p-4 border border-gray-100 rounded-xl bg-gray-50/50 hover:bg-white hover:shadow-md hover:border-blue-100 transition-all duration-300 group">
                                        <div class="flex flex-col h-full justify-between">
                                            <div class="w-8 h-8 rounded-lg bg-white border border-gray-200 flex items-center justify-center text-gray-500 group-hover:text-blue-600 group-hover:border-blue-200 transition-colors mb-3">
                                                <i class="fas fa-shuttle-van text-sm"></i>
                                            </div>
                                            <div>
                                                <p class="text-[11px] text-gray-500 font-semibold uppercase mb-1">المركبة</p>
                                                <p class="font-bold text-gray-800 text-sm">{{ $flight->vehicle_type_label }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- محطة المغادرة -->
                                    <div class="p-4 border border-gray-100 rounded-xl bg-gray-50/50 hover:bg-white hover:shadow-md hover:border-blue-100 transition-all duration-300 group">
                                        <div class="flex flex-col h-full justify-between">
                                            <div class="w-8 h-8 rounded-lg bg-white border border-gray-200 flex items-center justify-center text-gray-500 group-hover:text-blue-600 group-hover:border-blue-200 transition-colors mb-3">
                                                <i class="fas fa-plane-departure text-sm"></i>
                                            </div>
                                            <div>
                                                <p class="text-[11px] text-gray-500 font-semibold uppercase mb-1">المطار/المحطة</p>
                                                <p class="font-bold text-gray-800 text-sm truncate" title="{{ $flight->departure_airport }}">{{ $flight->departure_airport }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- الحالة -->
                                    <div class="p-4 border border-gray-100 rounded-xl bg-gray-50/50 hover:bg-white hover:shadow-md hover:border-blue-100 transition-all duration-300 group">
                                        <div class="flex flex-col h-full justify-between">
                                            <div class="w-8 h-8 rounded-lg bg-white border border-gray-200 flex items-center justify-center text-gray-500 group-hover:text-blue-600 group-hover:border-blue-200 transition-colors mb-3">
                                                <i class="fas fa-info-circle text-sm"></i>
                                            </div>
                                            <div>
                                                <p class="text-[11px] text-gray-500 font-semibold uppercase mb-1">حالة الحجز</p>
                                                <div class="flex items-center gap-1.5">
                                                    <span class="w-2 h-2 rounded-full {{ $flight->is_active ? 'bg-green-500' : 'bg-red-500' }}"></span>
                                                    <p class="font-bold {{ $flight->is_active ? 'text-green-700' : 'text-red-700' }} text-sm">
                                                        {{ $flight->is_active ? 'متاح' : 'مغلق' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- المقاعد -->
                                    <div class="p-4 border border-gray-100 rounded-xl bg-gray-50/50 hover:bg-white hover:shadow-md hover:border-blue-100 transition-all duration-300 group">
                                        <div class="flex flex-col h-full justify-between">
                                            <div class="w-8 h-8 rounded-lg bg-white border border-gray-200 flex items-center justify-center text-gray-500 group-hover:text-blue-600 group-hover:border-blue-200 transition-colors mb-3">
                                                <i class="fas fa-chair text-sm"></i>
                                            </div>
                                            <div>
                                                <p class="text-[11px] text-gray-500 font-semibold uppercase mb-1">المقاعد الشاغرة</p>
                                                <p class="font-bold text-gray-800 text-sm font-mono">{{ $flight->available_seats }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- فئات المقاعد / الكابينات - تصميم رسمي (ثلث المساحة) -->
                            <div class="col-span-1">
                                <h3 class="text-sm uppercase tracking-wider text-gray-500 font-bold mb-4 flex items-center gap-2">
                                    <span class="w-1 h-4 bg-gray-400 rounded-full inline-block"></span>
                                    @if($flight->trip_type === 'sea')
                                        خيارات الكابينات
                                    @else
                                        خيارات الحجز
                                    @endif
                                </h3>
                                
                                <div class="grid grid-cols-1 gap-4">
                                    @if($flight->seat_classes)
                                        @foreach($flight->seat_classes as $class)
                                            <div class="flex items-center justify-between p-4 border border-gray-100 rounded-xl bg-white hover:border-blue-200 hover:shadow-sm transition-all duration-200 group cursor-default">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 group-hover:bg-blue-50 group-hover:text-blue-600 transition-colors">
                                                        <i class="fas fa-check"></i>
                                                    </div>
                                                    <div>
                                                        <p class="font-bold text-gray-900 text-sm capitalize">{{ t(ucfirst($class)) }}</p>
                                                        <p class="text-xs text-gray-500">متوفر للحجز الفوري</p>
                                                    </div>
                                                </div>
                                                <div class="text-right">
                                                    <p class="text-lg font-bold text-blue-700">{{ number_format($flight->getPriceForClass($class)) }} <span class="text-xs font-normal text-gray-500">ريال</span></p>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="flex items-center justify-between p-4 border border-gray-100 rounded-xl bg-white hover:border-blue-200 hover:shadow-sm transition-all duration-200 group">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 group-hover:bg-blue-50 group-hover:text-blue-600 transition-colors">
                                                    <i class="fas fa-check"></i>
                                                </div>
                                                <div>
                                                    <p class="font-bold text-gray-900 text-sm">
                                                        @if($flight->trip_type === 'sea')
                                                            كابينة قياسية
                                                        @else
                                                            درجة اقتصادية
                                                        @endif
                                                    </p>
                                                    <p class="text-xs text-gray-500">السعر الأساسي للرحلة</p>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-lg font-bold text-blue-700">{{ number_format($flight->base_price) }} <span class="text-xs font-normal text-gray-500">ريال</span></p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container mx-auto px-4 py-8">
            <div class="bg-white rounded-2xl shadow-xl p-6" data-aos="fade-up" data-aos-delay="200">
                <div class="flex items-center gap-3 ">
                    <div class="bg-gradient-to-r {{ $tripTypeConfig['gradient'] }} p-2 rounded-lg">
                        <i class="fas fa-ticket-alt text-white"></i>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900">{{ __('messages.book_flight') }}</h2>
                </div>
                <form action="{{ route('flights.choosePayment', $flight) }}" method="POST" class="space-y-8" enctype="multipart/form-data">
                    @csrf
                    <div class="tab-content" id="passenger-info">
                        <div class="rounded-xl p-6">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-user text-gray-400 ml-1"></i>
                                        الاسم الكامل *
                                    </label>
                                    <input type="text" name="passenger_name" value="{{ old('passenger_name') }}" 
                                        class="w-full px-4 py-3 border {{ $errors->has('passenger_name') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent input-focus">
                                    @error('passenger_name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div >
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-envelope text-gray-400 ml-1"></i>
                                        البريد الإلكتروني *
                                    </label>
                                    <input type="email" name="passenger_email" value="{{ old('passenger_email') }}"  
                                        class="w-full px-4 py-3 border {{ $errors->has('passenger_email') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent input-focus">
                                    @error('passenger_email')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="file-upload-container">
                                    <label for="file-upload" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-id-card text-gray-400 mx-2"></i>
                                        صورة جواز السفر
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
                                        <i class="fas fa-id-badge text-gray-400 ml-1"></i>
                                        رقم الهوية الوطنية أو الإقامة
                                    </label>
                                    <input type="text" name="passenger_id_number" value="{{ old('passenger_id_number') }}"
                                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent input-focus">
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-phone text-gray-400 ml-1"></i>
                                        رقم الهاتف في السعودية *
                                    </label>
                                    <input type="tel" name="passenger_phone" value="{{ old('passenger_phone') }}" 
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
                                    <select name="number_of_passengers"  
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
                                    <select name="ticket_type"  
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
                                    <select name="seat_class"  
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
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-credit-card text-gray-400 ml-1"></i>
                                        طريقة الدفع *
                                    </label>
                                    <select name="payment_method"  
                                            class="w-full px-4 py-3 border-2 {{ $errors->has('payment_method') ? 'border-red-500' : 'border-gray-200' }} rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent input-focus">
                                        <option value="">اختر طريقة الدفع</option>
                                        <option value="on_arrival" {{ old('payment_method') == 'on_arrival' ? 'selected' : '' }}>
                                            الدفع عند الحضور
                                        </option>
                                        <option value="whatsapp" {{ old('payment_method') == 'whatsapp' ? 'selected' : '' }}>
                                            الدفع عبر الواتساب
                                        </option>
                                        <option value="tap_payment" disabled {{ old('payment_method') == 'tap_payment' ? 'selected' : '' }}>
                                            Tap Payment - قريباً (غير متاح حالياً)
                                        </option>
                                    </select>
                                    @error('payment_method')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
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