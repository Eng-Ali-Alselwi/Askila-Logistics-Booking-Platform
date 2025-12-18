<div class="relative w-full h-[500px] md:h-[400px] bg-black/80 overflow-hidden" id="home">
    <div class="absolute inset-0 opacity-70">
      <img src="{{ asset('assets/images/contact/contact-banner.jpg') }}"
           alt="Contact Us About Hero Image ltr"
           class="rtl:hidden block object-cover object-center w-full h-full" />
      <img src="{{ asset('assets/images/contact/contact-banner_rtl.jpeg') }}"
           alt="Contact Us About Hero Image rtl"
           class="ltr:hidden block object-cover object-center w-full h-full" />
    </div>

    <!-- بدل inset-9 → inset-0 + padding داخلي + حاوية بعرض أقصى -->
    <div class="absolute inset-0 flex items-center">
      <div class="w-full max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row items-center justify-center md:justify-between">
          <div class="md:w-1/2 mb-4 md:mb-0">
            <h1 data-aos="fade-up"
                class="text-gray-100 font-medium text-4xl md:text-5xl leading-tight mb-2">
              {{ t('Track Your Shipment Easily') }}
            </h1>
            <p data-aos="fade-up" data-aos-duration="2000"
               class="font-regular text-gray-200 text-xl mb-8 mt-4">
              {{ t('Enter your tracking number and follow every step of your shipment’s journey until delivery') }}
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
