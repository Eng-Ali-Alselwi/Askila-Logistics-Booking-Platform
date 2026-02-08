<!-- Professional Services Section -->
<section id="services" class="relative py-20 bg-gradient-to-br from-gray-50 to-blue-50 dark:from-gray-900 dark:to-gray-800 overflow-hidden">
        
    <!-- Background Elements -->
    <div class="absolute inset-0 opacity-5">
        <div class="absolute top-1/4 left-10 w-96 h-96 bg-primary-400 rounded-full mix-blend-multiply filter blur-xl animate-float"></div>
        <div class="absolute bottom-1/4 right-10 w-80 h-80 bg-secondary-400 rounded-full mix-blend-multiply filter blur-xl animate-float animate-delay-300"></div>
    </div>
        
    <!-- Pattern Background -->
    <x-home.svg-pattern/>
        
    <div class="relative z-10 container mx-auto px-6 lg:px-8">
            
        <!-- Section Header -->
        <div class="text-center mb-12 animate-fade-in-up">
            <div class="inline-flex items-center px-4 py-2 text-3xl md:text-4xl lg:text-5xl font-bold mb-4 text-gradient-primary bg-gradient-to-r from-primary-600 to-primary-800 bg-clip-text">
                {{ __('messages.services_badge') }}
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Land Shipping -->
            <x-front.service-card
                :title="__('messages.service_land')"
                :description="__('messages.desc_land')"
                :image="asset('assets/images/services/land_shipping.jpg')"
                gradient="from-blue-500 to-blue-600"
                :link="route('contact.index')"
                :delay="0"
            />

            <!-- Sea Shipping -->
            <x-front.service-card
                :title="__('messages.service_sea_hardcoded')"
                :description="__('messages.service_sea_desc_hardcoded')"
                :image="asset('assets/images/services/sea_shipping.jpg')"
                gradient="from-cyan-500 to-cyan-600"
                :link="route('contact.index')"
                :delay="100"
            />

            <!-- Air Shipping -->
            <x-front.service-card
                :title="__('messages.service_air_hardcoded')"
                :description="__('messages.service_air_desc_hardcoded')"
                :image="asset('assets/images/services/air_shipping.jpg')"
                gradient="from-purple-500 to-purple-600"
                :link="route('contact.index')"
                :delay="200"
            />


            <!-- Ferry Tickets -->
            <x-front.service-card
                :title="__('messages.service_ferry_hardcoded')"
                :description="__('messages.service_ferry_desc_hardcoded')"
                :image="asset('assets/images/services/ferry_tickets.jpeg')"
                gradient="from-orange-500 to-orange-600"
                :link="route('contact.index')"
                :delay="400"
            />

            <!-- Flight Tickets -->
            <x-front.service-card
                :title="__('messages.service_flight_hardcoded')"
                :description="__('messages.service_flight_desc_hardcoded')"
                :image="asset('assets/images/services/flight_tickets.jpg')"
                gradient="from-green-500 to-green-600"
                :link="route('flights.index')"
                :delay="500"
            />
        </div>
    </div>
</section>