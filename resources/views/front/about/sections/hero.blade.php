<div class="relative w-full h-[500px] md:h-[400px] bg-black/80 overflow-hidden" id="home">
    <div class="absolute inset-0 opacity-70">
      <img src="{{ asset('assets/images/contact/contact-banner.jpg') }}"
           alt="Contact Us About Hero Image ltr"
           class="rtl:hidden block object-cover object-center w-full h-full" />
      <img src="{{ asset('assets/images/contact/contact-banner_rtl.jpeg') }}"
           alt="Contact Us About Hero Image rtl"
           class="ltr:hidden block object-cover object-center w-full h-full" />
    </div>

    <!-- بدّل inset-9 بـ inset-0 + padding داخلي -->
    <div class="absolute inset-0 flex flex-col md:flex-row items-center justify-center md:justify-between px-4 sm:px-6 lg:px-8">
      <!-- ألغينا container لتفادي صدامه مع absolute -->
      <div class="md:w-1/2 mb-4 md:mb-0">
        <h1 data-aos="fade-up"
            class="text-gray-100 font-medium text-4xl md:text-5xl leading-tight mb-2">
          {{ t('About Askila Group') }}
        </h1>
        <p data-aos="fade-up" data-aos-duration="2000"
           class="font-regular text-gray-200 text-xl mb-8 mt-4">
          {{ t('Over two decades of building trust with comprehensive shipping and travel solutions') }}
        </p>
      </div>
    </div>
  </div>
