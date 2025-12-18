@extends('layouts.app')

@section('head')
    <x-front.seo-head
        title="تأكيد الحجز عبر الواتساب - {{ $booking->booking_reference }} - {{ __('messages.company_name') }}"
        description="تأكيد الحجز عبر الواتساب - {{ __('messages.company_name') }}."
    />
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/aos.css') }}">
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 to-blue-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="container mx-auto px-4 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">تأكيد الحجز عبر الواتساب</h1>
                    <p class="text-gray-600">رقم الحجز: {{ $booking->booking_reference }}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-600">الحالة</p>
                    <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-medium">
                        في انتظار التأكيد
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <!-- Success Message -->
            <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-8" data-aos="fade-up">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                    </div>
                    <div class="mr-4">
                        <h3 class="text-lg font-semibold text-green-800 mb-2">تم إنشاء الحجز بنجاح!</h3>
                        <p class="text-green-700">
                            تم إنشاء حجزك بنجاح. الآن يمكنك التواصل معنا عبر الواتساب لإتمام عملية الدفع وتأكيد الحجز.
                        </p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Booking Details -->
                <div class="bg-white rounded-lg shadow-md p-6" data-aos="fade-up" data-aos-delay="100">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6">تفاصيل الحجز</h2>
                    
                    <!-- Flight Info -->
                    <div class="mb-6">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-gray-600">رقم الرحلة</span>
                            <span class="font-medium">{{ $booking->flight->flight_number }}</span>
                        </div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-gray-600">الشركة</span>
                            <span class="font-medium">{{ $booking->flight->airline }}</span>
                        </div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-gray-600">الوجهة</span>
                            <span class="font-medium">{{ $booking->flight->departure_city }} - {{ $booking->flight->arrival_city }}</span>
                        </div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-gray-600">التاريخ</span>
                            <span class="font-medium">{{ $booking->flight->departure_time->format('Y-m-d') }}</span>
                        </div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-gray-600">الوقت</span>
                            <span class="font-medium">{{ $booking->flight->departure_time->format('H:i') }}</span>
                        </div>
                    </div>

                    <!-- Passenger Info -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">معلومات المسافر</h3>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-gray-600">الاسم</span>
                            <span class="font-medium">{{ $booking->passenger_name }}</span>
                        </div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-gray-600">البريد الإلكتروني</span>
                            <span class="font-medium">{{ $booking->passenger_email }}</span>
                        </div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-gray-600">رقم الهاتف</span>
                            <span class="font-medium">{{ $booking->passenger_phone }}</span>
                        </div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-gray-600">عدد الركاب</span>
                            <span class="font-medium">{{ $booking->number_of_passengers }}</span>
                        </div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-gray-600">فئة المقعد</span>
                            <span class="font-medium">{{ ucfirst($booking->seat_class) }}</span>
                        </div>
                    </div>

                    <!-- Payment Summary -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">ملخص الدفع</h3>
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">السعر الأساسي</span>
                                <span class="font-medium">{{ number_format($booking->total_amount) }} ريال</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">الضريبة (15%)</span>
                                <span class="font-medium">{{ number_format($booking->tax_amount) }} ريال</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">رسوم الخدمة</span>
                                <span class="font-medium">{{ number_format($booking->service_fee) }} ريال</span>
                            </div>
                            <hr class="my-2">
                            <div class="flex items-center justify-between text-lg font-bold">
                                <span>المجموع</span>
                                <span class="text-green-600">{{ number_format($booking->total_amount + $booking->tax_amount + $booking->service_fee) }} ريال</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- WhatsApp Contact -->
                <div class="bg-white rounded-lg shadow-md p-6" data-aos="fade-up" data-aos-delay="200">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6">التواصل عبر الواتساب</h2>
                    
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                        <div class="flex items-start">
                            <i class="fab fa-whatsapp text-green-600 text-2xl ml-3 mt-1"></i>
                            <div>
                                <h3 class="font-semibold text-green-800 mb-2">إتمام الحجز عبر الواتساب</h3>
                                <p class="text-green-700 text-sm">
                                    اضغط على الزر أدناه للتواصل معنا عبر الواتساب لإتمام عملية الدفع وتأكيد الحجز.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- WhatsApp Button -->
                    <div class="text-center mb-6">
                        <a href="https://wa.me/967772734012?text={{ urlencode('مرحباً، أنا العميل ' . $booking->passenger_name . "\nرقم الرحلة: " . $booking->flight->flight_number . "\nرقم الحجز: " . $booking->booking_reference . "\nأريد تأكيد الحجز والدفع.") }}" 
                           target="_blank"
                           class="inline-flex items-center justify-center bg-green-600 hover:bg-green-700 text-white font-bold py-4 px-8 rounded-lg text-lg transition duration-300 transform hover:scale-105 shadow-lg">
                            <i class="fab fa-whatsapp text-2xl ml-2"></i>
                            التواصل عبر الواتساب
                        </a>
                    </div>

                    <!-- Instructions -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h4 class="font-semibold text-blue-800 mb-2">تعليمات مهمة:</h4>
                        <ul class="text-blue-700 text-sm space-y-1">
                            <li>• سيتم إرسال رسالة تلقائية تحتوي على تفاصيل حجزك</li>
                            <li>• سيقوم فريقنا بالرد عليك خلال 24 ساعة</li>
                            <li>• سيتم تأكيد الحجز بعد إتمام عملية الدفع</li>
                            <li>• احتفظ برقم الحجز: <strong>{{ $booking->booking_reference }}</strong></li>
                        </ul>
                    </div>

                    <!-- Alternative Contact -->
                    <div class="mt-6 text-center">
                        <p class="text-gray-600 text-sm mb-2">أو يمكنك التواصل معنا عبر:</p>
                        <div class="flex justify-center space-x-4">
                            <a href="tel:+967772734012" class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-phone"></i> +967772734012
                            </a>
                            <a href="mailto:info@askila.com" class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-envelope"></i> info@askila.com
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Important Notice -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mt-8" data-aos="fade-up" data-aos-delay="300">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-triangle text-yellow-600 text-xl ml-3 mt-1"></i>
                    <div>
                        <h3 class="font-semibold text-yellow-800 mb-2">تنبيه مهم</h3>
                        <p class="text-yellow-700 text-sm">
                            هذا الحجز في حالة انتظار التأكيد. لن يتم تأكيد الحجز نهائياً إلا بعد التواصل معنا عبر الواتساب وإتمام عملية الدفع. 
                            يرجى التواصل معنا خلال 24 ساعة من إنشاء الحجز.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('assets/js/aos.js') }}"></script>
<script>
    // تفعيل AOS للتأثيرات الحركية
    AOS.init({
        duration: 800,
        once: true,
        offset: 100
    });
</script>
@endpush
@endsection
