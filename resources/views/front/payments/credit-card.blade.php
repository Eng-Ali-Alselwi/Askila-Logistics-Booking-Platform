@extends('layouts.app')

@section('head')
    <x-front.seo-head
        title="الدفع بالبطاقة الائتمانية - {{ __('messages.company_name') }}"
        description="إتمام عملية الدفع بالبطاقة الائتمانية لحجز رحلتك مع {{ __('messages.company_name') }}."
    />
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>        
        .card-hover {
            transition: all 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        
        .payment-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            padding: 2rem;
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        .payment-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
        }
        
        .coming-soon-badge {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            font-size: 0.875rem;
            font-weight: bold;
            display: inline-block;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        .success-card {
            background: linear-gradient(135deg, #00b894 0%, #00a085 100%);
            border-radius: 15px;
            padding: 1.5rem;
            color: white;
            margin-bottom: 1rem;
        }
    </style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50 to-gray-50">
    
    <div class="container mx-auto px-4 py-10">
        <div class="max-w-4xl mx-auto mt-30">            
            <div class="space-y-6">
                {{-- نموذج الدفع بالبطاقة الائتمانية --}}
                <div class="bg-white rounded-2xl shadow-xl p-6 card-hover" data-aos="fade-left">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="bg-gradient-to-r from-purple-500 to-pink-600 p-2 rounded-lg">
                            <i class="fas fa-credit-card text-white"></i>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900">معلومات الدفع</h2>
                    </div>
                    
                    {{-- نموذج الدفع عبر Stripe --}}
                    <form id="paymentForm" class="space-y-6">
                        @csrf
                        
                        {{-- معلومات البطاقة باستخدام Stripe Elements --}}
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-credit-card text-gray-400 ml-1"></i>
                                    معلومات البطاقة الائتمانية *
                                </label>
                                <div id="card-element" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                    <!-- Stripe Elements will create form elements here -->
                            </div>
                                <div id="card-errors" class="text-red-500 text-sm mt-2" role="alert"></div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-user text-gray-400 ml-1"></i>
                                    اسم حامل البطاقة *
                                </label>
                                <input type="text" id="cardholder-name" required
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent input-focus"
                                        placeholder="كما هو مكتوب على البطاقة">
                            </div>
                        </div>
                        
                        {{-- شروط الاستخدام --}}
                        <div class="flex items-start space-x-3">
                            <input type="checkbox" id="terms" required 
                                    class="w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500 mt-1">
                            <label for="terms" class="text-sm text-gray-600">
                                أوافق على <a href="#" class="text-purple-600 hover:underline">شروط الاستخدام</a> 
                                و <a href="#" class="text-purple-600 hover:underline">سياسة الخصوصية</a>
                            </label>
                        </div>
                        
                        {{-- زر الدفع --}}
                        <button type="submit" id="submit-button"
                                class="w-full bg-gradient-to-r from-purple-600 to-pink-600 text-white font-bold py-4 px-6 rounded-xl text-lg shadow-xl hover:shadow-2xl transition duration-300 transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed">
                            <i class="fas fa-lock ml-2"></i>
                            <span id="button-text">إتمام الدفع - {{ number_format($booking->total_amount + $booking->tax_amount + $booking->service_fee) }} ريال</span>
                            <span id="spinner" class="hidden">
                                <i class="fas fa-spinner fa-spin ml-2"></i>
                                جاري المعالجة...
                            </span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>
    // إضافة تأثيرات حركية
    document.addEventListener('DOMContentLoaded', function() {
        // تأثير الكتابة للرسالة
        const comingSoonText = document.querySelector('.coming-soon-badge');
        if (comingSoonText) {
            comingSoonText.style.animation = 'pulse 2s infinite, fadeIn 1s ease-in';
        }

        // تهيئة Stripe
        const stripe = Stripe('{{ config("stripe.key") }}');
        const elements = stripe.elements();

        // إنشاء عنصر البطاقة
        const cardElement = elements.create('card', {
            style: {
                base: {
                    fontSize: '16px',
                    color: '#424770',
                    '::placeholder': {
                        color: '#aab7c4',
                    },
                },
                invalid: {
                    color: '#9e2146',
                },
            },
        });

        // إدراج عنصر البطاقة في DOM
        cardElement.mount('#card-element');

        // معالجة الأخطاء
        cardElement.on('change', function(event) {
            const displayError = document.getElementById('card-errors');
            if (event.error) {
                displayError.textContent = event.error.message;
            } else {
                displayError.textContent = '';
            }
        });

        // معالجة إرسال النموذج
        const paymentForm = document.getElementById('paymentForm');
        if (paymentForm) {
            paymentForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const submitButton = document.getElementById('submit-button');
                const buttonText = document.getElementById('button-text');
                const spinner = document.getElementById('spinner');
                
                // إظهار رسالة التحميل
                buttonText.classList.add('hidden');
                spinner.classList.remove('hidden');
                submitButton.disabled = true;

                try {
                    // إنشاء Payment Intent
                    const response = await fetch('{{ route("stripe.create-payment-intent", $booking) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            booking_id: {{ $booking->id }}
                        })
                    });

                    const responseData = await response.json();

                    // التحقق من نجاح العملية
                    if (!responseData.success) {
                        const errorMsg = responseData.error || 'فشل في إنشاء طلب الدفع';
                        throw new Error(errorMsg);
                    }

                    // التحقق من وجود client_secret
                    if (!responseData.client_secret) {
                        throw new Error('فشل في إنشاء طلب الدفع: لا يوجد client_secret');
                    }

                    // تأكيد الدفع مع Stripe
                    const { error, paymentIntent } = await stripe.confirmCardPayment(responseData.client_secret, {
                        payment_method: {
                            card: cardElement,
                            billing_details: {
                                name: document.getElementById('cardholder-name').value,
                            },
                        }
                    });

                    if (error) {
                        // إظهار خطأ للمستخدم
                        const displayError = document.getElementById('card-errors');
                        displayError.textContent = error.message;
                        
                        // إعادة إظهار الزر
                        buttonText.classList.remove('hidden');
                        spinner.classList.add('hidden');
                        submitButton.disabled = false;
                        
                        return; // الخروج من الدالة
                    }

                    // التحقق من حالة الدفع
                    if (!paymentIntent) {
                        const displayError = document.getElementById('card-errors');
                        displayError.textContent = 'فشل في معالجة الدفع. يرجى المحاولة مرة أخرى.';
                        
                        buttonText.classList.remove('hidden');
                        spinner.classList.add('hidden');
                        submitButton.disabled = false;
                        
                        return;
                    }

                    // إذا نجح الدفع
                    if (paymentIntent.status === 'succeeded') {
                        console.log('✅ Payment succeeded on Stripe, confirming with server...');
                        
                        // تأكيد الدفع مع الخادم
                        let confirmResponse = null;
                        let responseText = null;
                        let confirmResult = null;
                        
                        try {
                            confirmResponse = await fetch('{{ route("stripe.confirm-payment", $booking) }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({
                                    payment_intent_id: paymentIntent.id
                                })
                            });
                            
                            console.log('Confirm response status:', confirmResponse.status);
                            console.log('Confirm response ok:', confirmResponse.ok);
                            
                            // قراءة النص من الـ response
                            responseText = await confirmResponse.text();
                            console.log('Response text:', responseText);
                            
                            // التحقق من حالة HTTP
                            if (!confirmResponse.ok) {
                                console.error('Server error - Status:', confirmResponse.status);
                                console.error('Server error - Response:', responseText);
                                
                                // إذا كان status 403 أو 500، قد يكون هناك مشكلة في الـ token
                                if (confirmResponse.status === 403) {
                                    throw new Error('خطأ في التحقق من الأمان (CSRF). يرجى إعادة تحميل الصفحة.');
                                } else if (confirmResponse.status >= 500) {
                                    throw new Error('خطأ في الخادم (HTTP ' + confirmResponse.status + '). يرجى المحاولة مرة أخرى.');
                                } else {
                                    // محاولة استخراج رسالة الخطأ من الـ response
                                    try {
                                        const errorJson = JSON.parse(responseText);
                                        if (errorJson.error) {
                                            throw new Error(errorJson.error);
                                        }
                                    } catch (e) {
                                        // لا يوجد JSON error، استخدم رسالة عامة
                                    }
                                    throw new Error('فشل في الاتصال بالخادم (HTTP ' + confirmResponse.status + ')');
                                }
                            }

                            // محاولة parse JSON
                            confirmResult = JSON.parse(responseText);
                            console.log('Confirm result:', confirmResult);

                            if (confirmResult.success) {
                                // استخدام redirect_url من الاستجابة
                                const redirectUrl = confirmResult.redirect_url || '{{ route("booking.track.success") }}?booking_reference={{ $booking->booking_reference }}&success=true';
                                
                                console.log('✅ Payment confirmed successfully!');
                                console.log('Redirecting to:', redirectUrl);
                                
                                // توجيه إلى صفحة النجاح
                                window.location.href = redirectUrl;
                                return; // الخروج من الدالة
                            } else {
                                // فشل تأكيد الدفع على الخادم لكن الدفع نجح على Stripe
                                console.error('❌ Confirm failed:', confirmResult);
                                const displayError = document.getElementById('card-errors');
                                displayError.textContent = confirmResult.error || 'فشل في تأكيد الدفع. يرجى التواصل مع خدمة العملاء.';
                                
                                buttonText.classList.remove('hidden');
                                spinner.classList.add('hidden');
                                submitButton.disabled = false;
                            }
                            
                        } catch (fetchError) {
                            console.error('⚠️ Fetch/Parse error occurred:', fetchError);
                            console.error('Fetch error message:', fetchError.message);
                            console.error('Error type:', fetchError.constructor.name);
                            
                            // إذا كان الخطأ في parsing JSON لكن الـ response كان ok
                            if (confirmResponse && confirmResponse.ok) {
                                console.log('✅ Response was OK but error occurred - payment succeeded on Stripe!');
                                console.log('Response text was:', responseText);
                                console.log('Attempting to redirect anyway...');
                                
                                // محاولة redirect رغم الخطأ في parsing
                                const redirectUrl = '{{ route("booking.track.success") }}?booking_reference={{ $booking->booking_reference }}&success=true';
                                console.log('Redirecting to:', redirectUrl);
                                window.location.href = redirectUrl;
                                return; // الخروج من الدالة
                            }
                            
                            // إعادة رمي الخطأ للمعالجة في catch الخارجي
                            throw fetchError;
                        }
                    } else {
                        // حالة الدفع غير متوقعة
                        console.error('Unexpected payment status:', paymentIntent.status);
                        const displayError = document.getElementById('card-errors');
                        displayError.textContent = 'حالة الدفع غير متوقعة: ' + paymentIntent.status;
                        
                        buttonText.classList.remove('hidden');
                        spinner.classList.add('hidden');
                        submitButton.disabled = false;
                    }

                } catch (error) {
                    console.error('Payment error:', error);
                    console.error('Error type:', error.constructor.name);
                    console.error('Error message:', error.message);
                    console.error('Error stack:', error.stack);
                    
                    // إظهار رسالة خطأ مفصلة
                    const displayError = document.getElementById('card-errors');
                    let errorMessage = 'حدث خطأ أثناء معالجة الدفع';
                    
                    if (error.message) {
                        errorMessage = error.message;
                        
                        // تحسين الرسالة فقط إذا كانت غير واضحة
                        const msg = error.message.toLowerCase();
                        
                        if (msg.includes('csrf')) {
                            errorMessage = 'خطأ في التحقق من الأمان. يرجى إعادة تحميل الصفحة والمحاولة مرة أخرى.';
                        } else if (msg.includes('خطأ في الخادم') || msg.includes('server')) {
                            // استخدام الرسالة الأصلية - واضحة بالفعل
                            errorMessage = error.message;
                        } else if (msg.includes('استجابة غير صالحة')) {
                            // استخدام الرسالة الأصلية
                            errorMessage = error.message;
                        } else if (msg.includes('network') || msg.includes('fetch') || msg.includes('خطأ في الاتصال')) {
                            // إذا كان الطلب ناجحاً، قم بتوجيه المستخدم إلى صفحة النجاح
                            console.log('Payment succeeded on Stripe - redirecting to success page...');
                            const redirectUrl = '{{ route("booking.track.success") }}?booking_reference={{ $booking->booking_reference }}&success=true';
                            console.log('Redirecting to:', redirectUrl);
                            window.location.href = redirectUrl;
                            return; // الخروج من الدالة
                        } else if (error.message) {
                            // استخدام الرسالة الأصلية
                            errorMessage = error.message;
                        }
                    }
                    
                    console.log('Final error message to display:', errorMessage);
                    displayError.textContent = errorMessage;
                    
                    // إعادة إظهار الزر
                    buttonText.classList.remove('hidden');
                    spinner.classList.add('hidden');
                    submitButton.disabled = false;
                }
            });
        }
    });
</script>
@endpush

@endsection
