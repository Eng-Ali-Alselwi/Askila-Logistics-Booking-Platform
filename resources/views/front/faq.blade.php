@extends('layouts.app')

@section('head')
    <x-front.seo-head
        title="الأسئلة الشائعة - مجموعة الأسكلة"
        description="إجابات على الأسئلة الشائعة حول خدمات الشحن وحجز التذاكر مع مجموعة الأسكلة."
    />
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/aos.css') }}">
@endpush

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-16">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl font-bold mb-6" data-aos="fade-up">الأسئلة الشائعة</h1>
            <p class="text-xl" data-aos="fade-up" data-aos-delay="200">
                إجابات على أكثر الأسئلة شيوعاً حول خدماتنا
            </p>
        </div>
    </div>

    <div class="container mx-auto px-4 py-16">
        <div class="max-w-4xl mx-auto">
            <!-- FAQ Accordion -->
            <div class="space-y-4">
                <!-- Shipping FAQ -->
                <div class="bg-white rounded-lg shadow-md" data-aos="fade-up">
                    <h2 class="text-2xl font-bold text-gray-800 p-6 border-b border-gray-200">
                        <i class="fas fa-shipping-fast text-blue-600 ml-2"></i>
                        أسئلة الشحن
                    </h2>
                    
                    <div class="accordion" id="shippingAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#shipping1">
                                    كم تستغرق عملية الشحن؟
                                </button>
                            </h2>
                            <div id="shipping1" class="accordion-collapse collapse show" data-bs-parent="#shippingAccordion">
                                <div class="accordion-body">
                                    <p>تختلف مدة الشحن حسب نوع الخدمة:</p>
                                    <ul>
                                        <li><strong>الشحن البري:</strong> 3-7 أيام عمل</li>
                                        <li><strong>الشحن البحري:</strong> 7-14 يوم عمل</li>
                                        <li><strong>الشحن الجوي:</strong> 1-3 أيام عمل</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#shipping2">
                                    كيف يمكنني تتبع شحنتي؟
                                </button>
                            </h2>
                            <div id="shipping2" class="accordion-collapse collapse" data-bs-parent="#shippingAccordion">
                                <div class="accordion-body">
                                    <p>يمكنك تتبع شحنتك بسهولة من خلال:</p>
                                    <ul>
                                        <li>إدخال رقم التتبع في صفحة التتبع</li>
                                        <li>استلام تحديثات عبر SMS</li>
                                        <li>الاستفسار عبر خدمة العملاء</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#shipping3">
                                    ما هي الأغراض المحظورة؟
                                </button>
                            </h2>
                            <div id="shipping3" class="accordion-collapse collapse" data-bs-parent="#shippingAccordion">
                                <div class="accordion-body">
                                    <p>نحن لا نتعامل مع:</p>
                                    <ul>
                                        <li>المواد الخطرة والكيميائية</li>
                                        <li>الأسلحة والأدوات الحادة</li>
                                        <li>المواد الغذائية القابلة للتلف</li>
                                        <li>الأدوية والمستحضرات الطبية</li>
                                        <li>النقود والمجوهرات الثمينة</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Flight FAQ -->
                <div class="bg-white rounded-lg shadow-md" data-aos="fade-up" data-aos-delay="200">
                    <h2 class="text-2xl font-bold text-gray-800 p-6 border-b border-gray-200">
                        <i class="fas fa-plane text-green-600 ml-2"></i>
                        أسئلة التذاكر
                    </h2>
                    
                    <div class="accordion" id="flightAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#flight1">
                                    كيف يمكنني حجز تذكرة طيران؟
                                </button>
                            </h2>
                            <div id="flight1" class="accordion-collapse collapse show" data-bs-parent="#flightAccordion">
                                <div class="accordion-body">
                                    <p>يمكنك حجز تذكرة طيران بسهولة:</p>
                                    <ol>
                                        <li>اختر وجهة السفر وتاريخ المغادرة</li>
                                        <li>اختر الرحلة المناسبة</li>
                                        <li>أدخل بيانات الراكب</li>
                                        <li>أكمل عملية الدفع</li>
                                        <li>احصل على التذكرة الإلكترونية</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flight2">
                                    هل يمكنني إلغاء أو تعديل الحجز؟
                                </button>
                            </h2>
                            <div id="flight2" class="accordion-collapse collapse" data-bs-parent="#flightAccordion">
                                <div class="accordion-body">
                                    <p>نعم، يمكنك إلغاء أو تعديل الحجز حسب الشروط التالية:</p>
                                    <ul>
                                        <li><strong>قبل 24 ساعة:</strong> إلغاء مجاني</li>
                                        <li><strong>قبل 12 ساعة:</strong> رسوم إلغاء 50 ريال</li>
                                        <li><strong>أقل من 12 ساعة:</strong> لا يمكن الإلغاء</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flight3">
                                    ما هي طرق الدفع المتاحة؟
                                </button>
                            </h2>
                            <div id="flight3" class="accordion-collapse collapse" data-bs-parent="#flightAccordion">
                                <div class="accordion-body">
                                    <p>نقبل جميع طرق الدفع الآمنة:</p>
                                    <ul>
                                        <li>مدى</li>
                                        <li>فيزا</li>
                                        <li>ماستركارد</li>
                                        <li>Apple Pay</li>
                                        <li>التحويل البنكي</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- General FAQ -->
                <div class="bg-white rounded-lg shadow-md" data-aos="fade-up" data-aos-delay="400">
                    <h2 class="text-2xl font-bold text-gray-800 p-6 border-b border-gray-200">
                        <i class="fas fa-question-circle text-purple-600 ml-2"></i>
                        أسئلة عامة
                    </h2>
                    
                    <div class="accordion" id="generalAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#general1">
                                    ما هي ساعات العمل؟
                                </button>
                            </h2>
                            <div id="general1" class="accordion-collapse collapse show" data-bs-parent="#generalAccordion">
                                <div class="accordion-body">
                                    <p>نحن متاحون لخدمتكم:</p>
                                    <ul>
                                        <li><strong>الأحد - الخميس:</strong> 8:00 ص - 6:00 م</li>
                                        <li><strong>الجمعة:</strong> 2:00 م - 6:00 م</li>
                                        <li><strong>السبت:</strong> 9:00 ص - 2:00 م</li>
                                        <li><strong>خدمة العملاء:</strong> 24/7</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#general2">
                                    كيف يمكنني التواصل معكم؟
                                </button>
                            </h2>
                            <div id="general2" class="accordion-collapse collapse" data-bs-parent="#generalAccordion">
                                <div class="accordion-body">
                                    <p>يمكنك التواصل معنا عبر:</p>
                                    <ul>
                                        <li><strong>الهاتف:</strong> +966 11 123 4567</li>
                                        <li><strong>البريد الإلكتروني:</strong> info@askila.com</li>
                                        <li><strong>واتساب:</strong> +966 50 123 4567</li>
                                        <li><strong>الموقع الإلكتروني:</strong> نموذج التواصل</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#general3">
                                    هل تقدمون خدمة التوصيل للمنازل؟
                                </button>
                            </h2>
                            <div id="general3" class="accordion-collapse collapse" data-bs-parent="#generalAccordion">
                                <div class="accordion-body">
                                    <p>نعم، نقدم خدمة التوصيل للمنازل في:</p>
                                    <ul>
                                        <li>جميع مدن المملكة العربية السعودية</li>
                                        <li>جميع مدن السودان</li>
                                        <li>المناطق النائية (برسوم إضافية)</li>
                                    </ul>
                                    <p class="mt-3"><strong>ملاحظة:</strong> يرجى التأكد من صحة العنوان قبل التوصيل.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact CTA -->
            <div class="text-center mt-12" data-aos="fade-up" data-aos-delay="600">
                <div class="bg-blue-50 rounded-lg p-8">
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">لم تجد إجابة لسؤالك؟</h3>
                    <p class="text-gray-600 mb-6">فريقنا متاح لمساعدتك في أي وقت</p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('contact.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg transition duration-300">
                            <i class="fas fa-envelope ml-2"></i>
                            اتصل بنا
                        </a>
                        <a href="tel:+966111234567" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded-lg transition duration-300">
                            <i class="fas fa-phone ml-2"></i>
                            اتصل الآن
                        </a>
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
</script>
@endpush
@endsection