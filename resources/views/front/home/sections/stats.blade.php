<!-- Features Section -->
<div class="bg-gray-100 py-16">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-800 mb-4" data-aos="fade-up">
                لماذا تختار أسكلة؟
            </h2>
            <p class="text-gray-600 text-lg" data-aos="fade-up" data-aos-delay="200">
                أفضل الخدمات والموثوقية في السفر
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="text-center" data-aos="fade-up" data-aos-delay="100">
            <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-shield-alt text-blue-600 text-2xl"></i>
            </div>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">حجز آمن</h3>
                <p class="text-gray-600">نضمن لك حجز آمن ومحمي لجميع رحلاتك</p>
        </div>

        <div class="text-center" data-aos="fade-up" data-aos-delay="200">
            <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-clock text-green-600 text-2xl"></i>
            </div>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">تأكيد فوري</h3>
                <p class="text-gray-600">احصل على تأكيد فوري لحجزك مع جميع التفاصيل</p>
        </div>

        <div class="text-center" data-aos="fade-up" data-aos-delay="300">
            <div class="bg-purple-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-headset text-purple-600 text-2xl"></i>
            </div>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">دعم 24/7</h3>
                <p class="text-gray-600">فريق الدعم متاح على مدار الساعة لمساعدتك</p>
            </div>
        </div>
    </div>
</div>


<!-- Popular Destinations -->
<div class="bg-gray-100 py-16">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-800 mb-4" data-aos="fade-up">
                {{ __('messages.popular_destinations') }}
            </h2>
            <p class="text-gray-600 text-lg" data-aos="fade-up" data-aos-delay="200">
                {{ __('messages.discover_popular_destinations') }}
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- رحلة جوية -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-300" data-aos="fade-up" data-aos-delay="100">
                <div class="h-48 bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center relative">
                    <i class="fas fa-plane text-white text-4xl"></i>
                    <div class="absolute top-3 right-3 bg-blue-500 text-white px-2 py-1 rounded-full text-2xs font-bold">
                        رحلة جوية
                    </div>
                </div>
                <div class="p-4">
                    <h3 class="font-bold text-2xl mb-4 text-gray-800">الرياض → الخرطوم</h3>
                    <p class="text-gray-600 text-sm">من 850 ريال</p>
                    <p class="text-blue-600 text-xs mt-1">3 ساعات</p>
                </div>
            </div>

            <!-- رحلة برية -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-300" data-aos="fade-up" data-aos-delay="200">
                <div class="h-48 bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center relative">
                    <i class="fas fa-bus text-white text-4xl"></i>
                    <div class="absolute top-3 right-3 bg-green-500 text-white px-2 py-1 rounded-full text-2xs font-bold">
                        رحلة برية
                    </div>
                </div>
                <div class="p-4">
                    <h3 class="font-semibold text-2xl mb-4 text-gray-800">الرياض → الخرطوم</h3>
                    <p class="text-gray-600 text-sm">من 500 ريال</p>
                    <p class="text-green-600 text-xs mt-1">12 ساعة</p>
                </div>
            </div>

            <!-- رحلة بحرية -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-300" data-aos="fade-up" data-aos-delay="300">
                <div class="h-48 bg-gradient-to-br from-cyan-400 to-cyan-600 flex items-center justify-center relative">
                    <i class="fas fa-ship text-white text-4xl"></i>
                    <div class="absolute top-3 right-3 bg-cyan-500 text-white px-2 py-1 rounded-full text-2xs font-bold">
                        رحلة بحرية
                    </div>
                </div>
                <div class="p-4">
                    <h3 class="font-semibold text-2xl mb-4 text-gray-800">جدة → بورتسودان</h3>
                    <p class="text-gray-600 text-sm">من 800 ريال</p>
                    <p class="text-cyan-600 text-xs mt-1">48 ساعة</p>
                </div>
            </div>

            <!-- رحلة جوية أخرى -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-300" data-aos="fade-up" data-aos-delay="400">
                <div class="h-48 bg-gradient-to-br from-purple-400 to-purple-600 flex items-center justify-center relative">
                    <i class="fas fa-plane text-white text-4xl"></i>
                    <div class="absolute top-3 right-3 bg-purple-500 text-white px-2 py-1 rounded-full text-2xs font-bold">
                        رحلة جوية
                    </div>
                </div>
                <div class="p-4">
                    <h3 class="font-semibold text-2xl mb-4 text-gray-800">جدة → الخرطوم</h3>
                    <p class="text-gray-600 text-sm">من 750 ريال</p>
                    <p class="text-purple-600 text-xs mt-1">2.5 ساعة</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- State Section -->
<section class="bg-gradient-to-br from-primary-800 via-primary-700
to-primary-600 py-18">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <x-home.stat stat="25+" :title="t('Years of Experience')"/>
            <x-home.stat stat="20+" :title="t('Branches')"/>
            <x-home.stat stat="95%" :title="t('Customer Satisfaction')"/>
            <x-home.stat stat="50,000+" :title="t('Shipments & Trips')"/>
        </div>
    </div>
</section>