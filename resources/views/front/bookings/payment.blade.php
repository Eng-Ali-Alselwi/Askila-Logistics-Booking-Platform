@extends('layouts.app')

@section('head')
    <x-front.seo-head
        title="{{ __('messages.complete_payment') }} {{ $booking->booking_reference }} - {{ __('messages.company_name') }}"
        description="{{ __('messages.complete_payment') }} {{ __('messages.and') }} {{ __('messages.company_name') }}."
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
                    <h1 class="text-2xl font-bold text-gray-800">{{ __('messages.complete_payment') }}</h1>
                    <p class="text-gray-600">{{ __('messages.booking_reference') }}: {{ $booking->booking_reference }}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-600">{{ __('messages.status') }}</p>
                    <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-medium">
                        {{ __('messages.pending') }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Payment Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md p-6" data-aos="fade-up">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6">{{ __('messages.payment_info') }}</h2>
                    
                    <form action="{{ route('booking.payment.process', $booking) }}" method="POST" id="paymentForm" class="space-y-6">
                        @csrf
                        
                        <!-- Payment Method Selection -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('messages.payment_method_selection') }}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <label class="relative">
                                    <input type="radio" name="payment_method" value="mada" class="sr-only" checked>
                                    <div class="border-2 border-gray-300 rounded-lg p-4 cursor-pointer hover:border-blue-500 transition duration-300 payment-method-card">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <i class="fas fa-credit-card text-2xl text-blue-600 ml-3"></i>
                                                <span class="font-medium">{{ __('messages.mada') }}</span>
                                            </div>
                                            <div class="w-4 h-4 border-2 border-gray-300 rounded-full payment-radio"></div>
                                        </div>
                                    </div>
                                </label>

                                <label class="relative">
                                    <input type="radio" name="payment_method" value="visa" class="sr-only">
                                    <div class="border-2 border-gray-300 rounded-lg p-4 cursor-pointer hover:border-blue-500 transition duration-300 payment-method-card">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <i class="fab fa-cc-visa text-2xl text-blue-600 ml-3"></i>
                                                <span class="font-medium">{{ __('messages.visa') }}</span>
                                            </div>
                                            <div class="w-4 h-4 border-2 border-gray-300 rounded-full payment-radio"></div>
                                        </div>
                                    </div>
                                </label>

                                <label class="relative">
                                    <input type="radio" name="payment_method" value="mastercard" class="sr-only">
                                    <div class="border-2 border-gray-300 rounded-lg p-4 cursor-pointer hover:border-blue-500 transition duration-300 payment-method-card">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <i class="fab fa-cc-mastercard text-2xl text-red-600 ml-3"></i>
                                                <span class="font-medium">{{ __('messages.mastercard') }}</span>
                                            </div>
                                            <div class="w-4 h-4 border-2 border-gray-300 rounded-full payment-radio"></div>
                                        </div>
                                    </div>
                                </label>

                                <label class="relative">
                                    <input type="radio" name="payment_method" value="apple_pay" class="sr-only">
                                    <div class="border-2 border-gray-300 rounded-lg p-4 cursor-pointer hover:border-blue-500 transition duration-300 payment-method-card">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <i class="fab fa-apple-pay text-2xl text-gray-800 ml-3"></i>
                                                <span class="font-medium">{{ __('messages.apple_pay') }}</span>
                                            </div>
                                            <div class="w-4 h-4 border-2 border-gray-300 rounded-full payment-radio"></div>
                                        </div>
                                    </div>
                                </label>

                                <label class="relative">
                                    <input type="radio" name="payment_method" value="paypal" class="sr-only">
                                    <div class="border-2 border-gray-300 rounded-lg p-4 cursor-pointer hover:border-blue-500 transition duration-300 payment-method-card">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <i class="fab fa-paypal text-2xl text-blue-600 ml-3"></i>
                                                <span class="font-medium">PayPal</span>
                                            </div>
                                            <div class="w-4 h-4 border-2 border-gray-300 rounded-full payment-radio"></div>
                                        </div>
                                    </div>
                                </label>

                                <label class="relative">
                                    <input type="radio" name="payment_method" value="manual_whatsapp" class="sr-only">
                                    <div class="border-2 border-gray-300 rounded-lg p-4 cursor-pointer hover:border-green-500 transition duration-300 payment-method-card">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <i class="fab fa-whatsapp text-2xl text-green-600 ml-3"></i>
                                                <span class="font-medium">الدفع بطريقة أخرى (عبر التواصل مع الفرع)</span>
                                            </div>
                                            <div class="w-4 h-4 border-2 border-gray-300 rounded-full payment-radio"></div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Card Details (for card payments) -->
                        <div id="cardDetails" class="space-y-4">
                            <h3 class="text-lg font-semibold text-gray-800">{{ __('messages.card_details') }}</h3>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.card_number') }} *</label>
                                <input type="text" name="card_number" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="{{ __('messages.card_number_placeholder') }}">
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.expiry_date') }} *</label>
                                    <input type="text" name="expiry_date" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                           placeholder="{{ __('messages.expiry_date_placeholder') }}">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.cvv') }} *</label>
                                    <input type="text" name="cvv" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                           placeholder="{{ __('messages.cvv_placeholder') }}">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.cardholder_name') }} *</label>
                                <input type="text" name="cardholder_name" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="{{ __('messages.cardholder_name_placeholder') }}">
                            </div>
                        </div>

                        <!-- WhatsApp Payment Details -->
                        <div id="whatsappDetails" class="space-y-4" style="display: none;">
                            <h3 class="text-lg font-semibold text-gray-800">تفاصيل التواصل مع الفرع</h3>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">رقم الحجز *</label>
                                <input type="text" value="{{ $booking->booking_reference }}" readonly
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-600">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">رقم الرحلة *</label>
                                <input type="text" value="{{ $booking->flight->flight_number }}" readonly
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-600">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">اختر الفرع *</label>
                                <select name="branch_id" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                    <option value="">اختر الفرع</option>
                                    @foreach(\App\Models\Branch::active()->get() as $branch)
                                        <option value="{{ $branch->id }}" data-whatsapp="{{ $branch->phone }}">
                                            {{ $branch->name }} - {{ $branch->city }} ({{ $branch->phone }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                <div class="flex items-start">
                                    <i class="fab fa-whatsapp text-green-600 text-xl ml-3 mt-1"></i>
                                    <div>
                                        <h4 class="font-semibold text-green-800">الدفع عبر واتساب</h4>
                                        <p class="text-green-700 text-sm mt-1">
                                            سيتم توجيهك إلى واتساب الفرع المختار لإتمام عملية الدفع
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Confirm Send Button -->
                            <div class="text-center">
                                <button type="button" id="confirmSendWhatsApp"
                                        class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300 transform hover:scale-105">
                                    <i class="fab fa-whatsapp ml-2"></i>
                                    تأكيد إرسال الطلب
                                </button>
                            </div>
                        </div>

                        <!-- Security Notice -->
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <div class="flex items-start">
                                <i class="fas fa-shield-alt text-green-600 text-xl ml-3 mt-1"></i>
                                <div>
                                    <h4 class="font-semibold text-green-800">{{ __('messages.secure_payment') }}</h4>
                                    <p class="text-green-700 text-sm mt-1">
                                        {{ __('messages.ssl_encryption') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Terms and Conditions -->
                        <div class="flex items-start space-x-3">
                            <input type="checkbox" id="payment_terms" required 
                                   class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 mt-1">
                            <label for="payment_terms" class="text-sm text-gray-600">
                                {{ __('messages.agree_terms') }} <a href="#" class="text-blue-600 hover:underline">{{ __('messages.payment_terms') }}</a> 
                                {{ __('messages.and') }} <a href="#" class="text-blue-600 hover:underline">{{ __('messages.cancellation_refund_policy') }}</a>
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <div class="text-center">
                            <button type="submit" id="payButton"
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-8 rounded-lg text-lg transition duration-300 transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed">
                                <i class="fas fa-lock ml-2"></i>
                                <span id="payButtonText">{{ __('messages.pay_amount') }} {{ number_format($booking->total_amount + $booking->tax_amount + $booking->service_fee) }} {{ __('messages.currency') }}</span>
                            </button>
                        </div>

                        <!-- WhatsApp Confirmation Modal -->
                        <div id="whatsappConfirmationModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
                            <div class="bg-white rounded-lg p-6 max-w-md mx-4">
                                <div class="text-center">
                                    <div class="mb-4">
                                        <i class="fab fa-whatsapp text-green-600 text-4xl"></i>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-800 mb-2">تأكيد التوجيه للواتساب</h3>
                                    <p class="text-gray-600 mb-6">
                                        سيتم الآن توجيهك لخدمات العملاء لإتمام عملية الدفع
                                    </p>
                                    <div class="flex gap-3 justify-center">
                                        <button type="button" id="confirmWhatsAppRedirect"
                                                class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-6 rounded-lg transition duration-300">
                                            نعم أوافق
                                        </button>
                                        <button type="button" id="cancelWhatsAppRedirect"
                                                class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-6 rounded-lg transition duration-300">
                                            لا أوافق
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Booking Summary -->
            <div class="lg:col-span-1">
                <!-- Flight Details -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6 sticky top-4" data-aos="fade-up" data-aos-delay="200">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('messages.booking_summary') }}</h3>
                    
                    <!-- Flight Info -->
                    <div class="mb-6">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-gray-600">{{ __('messages.flight') }}</span>
                            <span class="font-medium">{{ $booking->flight->flight_number }}</span>
                        </div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-gray-600">{{ __('messages.company') }}</span>
                            <span class="font-medium">{{ $booking->flight->airline }}</span>
                        </div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-gray-600">{{ __('messages.destination') }}</span>
                            <span class="font-medium">{{ $booking->flight->departure_city }} - {{ $booking->flight->arrival_city }}</span>
                        </div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-gray-600">{{ __('messages.date') }}</span>
                            <span class="font-medium">{{ $booking->flight->departure_time->format('Y-m-d') }}</span>
                        </div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-gray-600">{{ __('messages.time') }}</span>
                            <span class="font-medium">{{ $booking->flight->departure_time->format('H:i') }}</span>
                        </div>
                    </div>

                    <!-- Passenger Info -->
                    <div class="mb-6">
                        <h4 class="font-semibold text-gray-800 mb-3">{{ __('messages.passenger_info') }}</h4>
                        <div class="space-y-2">
                            <p class="text-sm"><span class="text-gray-600">{{ __('messages.passenger_name') }}:</span> {{ $booking->passenger_name }}</p>
                            <p class="text-sm"><span class="text-gray-600">{{ __('messages.passenger_email') }}:</span> {{ $booking->passenger_email }}</p>
                            <p class="text-sm"><span class="text-gray-600">{{ __('messages.passenger_phone') }}:</span> {{ $booking->passenger_phone }}</p>
                            <p class="text-sm"><span class="text-gray-600">{{ __('messages.seat_class') }}:</span> {{ ucfirst($booking->seat_class) }}</p>
                            <p class="text-sm"><span class="text-gray-600">{{ __('messages.number_of_passengers') }}:</span> {{ $booking->number_of_passengers }}</p>
                        </div>
                    </div>

                    <!-- Price Breakdown -->
                    <div>
                        <h4 class="font-semibold text-gray-800 mb-3">{{ __('messages.price_breakdown') }}</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">{{ __('messages.base_price') }}:</span>
                                <span>{{ number_format($booking->total_amount) }} {{ __('messages.currency') }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">{{ __('messages.tax_15_percent') }}:</span>
                                <span>{{ number_format($booking->tax_amount) }} {{ __('messages.currency') }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">{{ __('messages.service_fee') }}:</span>
                                <span>{{ number_format($booking->service_fee) }} {{ __('messages.currency') }}</span>
                            </div>
                            <hr class="my-2">
                            <div class="flex justify-between font-bold text-lg">
                                <span>{{ __('messages.total') }}:</span>
                                <span class="text-blue-600">{{ number_format($booking->total_amount + $booking->tax_amount + $booking->service_fee) }} {{ __('messages.currency') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Security Features -->
                <div class="bg-green-50 rounded-lg p-6" data-aos="fade-up" data-aos-delay="300">
                    <h3 class="text-lg font-semibold text-green-800 mb-4">{{ __('messages.security_features') }}</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex items-center">
                            <i class="fas fa-shield-alt text-green-600 ml-2"></i>
                            <span>{{ __('messages.ssl_256_bit') }}</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-lock text-green-600 ml-2"></i>
                            <span>{{ __('messages.pci_dss_protection') }}</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-user-shield text-green-600 ml-2"></i>
                            <span>{{ __('messages.no_card_storage') }}</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-green-600 ml-2"></i>
                            <span>{{ __('messages.money_back_guarantee') }}</span>
                        </div>
                    </div>
                </div>
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

    // إدارة اختيار طريقة الدفع
    const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
    const cardDetails = document.getElementById('cardDetails');
    const whatsappDetails = document.getElementById('whatsappDetails');
    const payButton = document.getElementById('payButton');
    const payButtonText = document.getElementById('payButtonText');

    paymentMethods.forEach(method => {
        method.addEventListener('change', function() {
            // إزالة التحديد من جميع البطاقات
            document.querySelectorAll('.payment-method-card').forEach(card => {
                card.classList.remove('border-blue-500', 'bg-blue-50', 'border-green-500', 'bg-green-50');
                card.classList.add('border-gray-300');
            });

            // إزالة التحديد من جميع الراديو
            document.querySelectorAll('.payment-radio').forEach(radio => {
                radio.classList.remove('border-blue-500', 'bg-blue-500', 'border-green-500', 'bg-green-500');
                radio.classList.add('border-gray-300');
            });

            // تحديد البطاقة المختارة
            const selectedCard = this.closest('label').querySelector('.payment-method-card');
            const selectedRadio = this.closest('label').querySelector('.payment-radio');
            
            if (this.value === 'manual_whatsapp') {
                selectedCard.classList.add('border-green-500', 'bg-green-50');
                selectedCard.classList.remove('border-gray-300');
                selectedRadio.classList.add('border-green-500', 'bg-green-500');
                selectedRadio.classList.remove('border-gray-300');
            } else {
                selectedCard.classList.add('border-blue-500', 'bg-blue-50');
                selectedCard.classList.remove('border-gray-300');
                selectedRadio.classList.add('border-blue-500', 'bg-blue-500');
                selectedRadio.classList.remove('border-gray-300');
            }

            // إظهار/إخفاء تفاصيل البطاقة أو الواتساب
            if (['mada', 'visa', 'mastercard'].includes(this.value)) {
                cardDetails.style.display = 'block';
                whatsappDetails.style.display = 'none';
                payButton.disabled = false;
                // جعل الحقول مطلوبة
                document.querySelectorAll('#cardDetails input').forEach(input => {
                    input.required = true;
                });
                document.querySelectorAll('#whatsappDetails select').forEach(select => {
                    select.required = false;
                });
            } else if (this.value === 'paypal') {
                cardDetails.style.display = 'none';
                whatsappDetails.style.display = 'none';
                payButton.disabled = false;
                // إزالة المطلوب من الحقول
                document.querySelectorAll('#cardDetails input').forEach(input => {
                    input.required = false;
                });
                document.querySelectorAll('#whatsappDetails select').forEach(select => {
                    select.required = false;
                });
            } else if (this.value === 'manual_whatsapp') {
                cardDetails.style.display = 'none';
                whatsappDetails.style.display = 'block';
                payButton.disabled = true; // تعطيل زر الدفع الأصلي
                // إزالة المطلوب من الحقول
                document.querySelectorAll('#cardDetails input').forEach(input => {
                    input.required = false;
                });
                document.querySelectorAll('#whatsappDetails select').forEach(select => {
                    select.required = true;
                });
            } else {
                cardDetails.style.display = 'none';
                whatsappDetails.style.display = 'none';
                payButton.disabled = false;
                // إزالة المطلوب من الحقول
                document.querySelectorAll('#cardDetails input').forEach(input => {
                    input.required = false;
                });
                document.querySelectorAll('#whatsappDetails select').forEach(select => {
                    select.required = false;
                });
            }
        });
    });

    // تحديث نص زر الدفع
    function updatePayButtonText() {
        const selectedMethod = document.querySelector('input[name="payment_method"]:checked');
        if (selectedMethod) {
            const methodNames = {
                'mada': 'مدى',
                'visa': 'فيزا',
                'mastercard': 'ماستركارد',
                'apple_pay': 'آبل باي',
                'paypal': 'PayPal',
                'manual_whatsapp': 'تأكيد الطلب'
            };
            const currency = 'ريال';
            const amount = '{{ number_format($booking->total_amount + $booking->tax_amount + $booking->service_fee) }}';
            
            if (selectedMethod.value === 'manual_whatsapp') {
                payButtonText.textContent = 'تأكيد الطلب';
            } else {
                payButtonText.textContent = methodNames[selectedMethod.value] + ' - ' + amount + ' ' + currency;
            }
        }
    }

    paymentMethods.forEach(method => {
        method.addEventListener('change', updatePayButtonText);
    });

    // تحديث نص زر الدفع عند التحميل
    updatePayButtonText();

    // معالجة إرسال النموذج
    document.getElementById('paymentForm').addEventListener('submit', function(e) {
        payButton.disabled = true;
        payButtonText.textContent = 'جاري المعالجة...';
        
        // إضافة تأثير تحميل
        payButton.innerHTML = '<i class="fas fa-spinner fa-spin ml-2"></i> جاري المعالجة...';
    });

    // تنسيق رقم البطاقة
    document.querySelector('input[name="card_number"]').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\s/g, '').replace(/[^0-9]/gi, '');
        let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
        e.target.value = formattedValue;
    });

    // تنسيق تاريخ الانتهاء
    document.querySelector('input[name="expiry_date"]').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length >= 2) {
            value = value.substring(0, 2) + '/' + value.substring(2, 4);
        }
        e.target.value = value;
    });

    // تنسيق CVV
    document.querySelector('input[name="cvv"]').addEventListener('input', function(e) {
        e.target.value = e.target.value.replace(/\D/g, '').substring(0, 4);
    });

    // التعامل مع زر تأكيد إرسال الطلب للواتساب
    document.getElementById('confirmSendWhatsApp').addEventListener('click', function() {
        const branchSelect = document.querySelector('select[name="branch_id"]');
        if (!branchSelect.value) {
            alert('يرجى اختيار الفرع أولاً');
            return;
        }
        
        // التحقق من وجود رقم الواتساب للفرع المختار
        const selectedOption = branchSelect.options[branchSelect.selectedIndex];
        const whatsappPhone = selectedOption.getAttribute('data-whatsapp');
        if (!whatsappPhone) {
            alert('رقم الواتساب غير متوفر للفرع المختار. يرجى اختيار فرع آخر.');
            return;
        }
        
        // إظهار نافذة التأكيد
        document.getElementById('whatsappConfirmationModal').classList.remove('hidden');
    });

    // التعامل مع زر "نعم أوافق"
    document.getElementById('confirmWhatsAppRedirect').addEventListener('click', function() {
        const branchSelect = document.querySelector('select[name="branch_id"]');
        if (!branchSelect.value) {
            alert('يرجى اختيار الفرع أولاً');
            return;
        }

        const confirmButton = this;
        const cancelButton = document.getElementById('cancelWhatsAppRedirect');
        confirmButton.disabled = true;
        cancelButton.disabled = true;
        confirmButton.innerHTML = '<i class="fas fa-spinner fa-spin ml-2"></i> جاري المعالجة...';

        const form = document.getElementById('paymentForm');
        const formData = new FormData(form);
        formData.append('payment_method', 'manual_whatsapp');

        fetch(`/bookings/{{ $booking->id }}/whatsapp-request`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            confirmButton.disabled = false;
            cancelButton.disabled = false;
            confirmButton.innerHTML = 'نعم أوافق';

            if (data.success && data.whatsapp_url) {
                window.location.href = data.whatsapp_url; // توجيه مباشر للواتساب
            } else {
                alert(data.message || 'حدث خطأ أثناء إرسال الطلب.');
            }
        })
        .catch(error => {
            confirmButton.disabled = false;
            cancelButton.disabled = false;
            confirmButton.innerHTML = 'نعم أوافق';
            alert('حدث خطأ أثناء الاتصال بالخادم. يرجى المحاولة مرة أخرى.');
            console.error(error);
        });
    });

    // التعامل مع زر "لا أوافق"
    document.getElementById('cancelWhatsAppRedirect').addEventListener('click', function() {
        // إعادة تفعيل الأزرار
        const confirmButton = document.getElementById('confirmWhatsAppRedirect');
        const cancelButton = this;
        confirmButton.disabled = false;
        cancelButton.disabled = false;
        confirmButton.innerHTML = 'نعم أوافق';
        
        document.getElementById('whatsappConfirmationModal').classList.add('hidden');
    });

    // إغلاق النافذة عند النقر خارجها
    document.getElementById('whatsappConfirmationModal').addEventListener('click', function(e) {
        if (e.target === this) {
            // إعادة تفعيل الأزرار
            const confirmButton = document.getElementById('confirmWhatsAppRedirect');
            const cancelButton = document.getElementById('cancelWhatsAppRedirect');
            confirmButton.disabled = false;
            cancelButton.disabled = false;
            confirmButton.innerHTML = 'نعم أوافق';
            
            this.classList.add('hidden');
        }
    });
</script>
@endpush
@endsection
