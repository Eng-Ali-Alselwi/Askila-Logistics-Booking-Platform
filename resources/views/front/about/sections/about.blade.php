<section class="mb-16 py-12 overflow-x-hidden">
    <div class="max-w-screen-xl mx-auto px-4">
      <div class="grid items-center gap-10 md:gap-16 md:grid-cols-2">
        <div class="relative">
          <!-- الزخرفة الخلفية: اخفِها على الموبايل لتجنب التمدد -->
          <div class="hidden md:block absolute -top-4 -start-4 -z-10 h-full w-full rounded-2xl bg-primary-100/50 dark:bg-primary-900/50"></div>

          <div class="overflow-hidden rounded-xl shadow-2xl transition-all duration-500 hover:scale-[1.01] hover:shadow-primary-200/50">
            <img
              src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80"
              alt="{{ t('Askila team coordinating logistics and travel') }}"
              class="block h-auto w-full object-cover transition-transform duration-700 hover:scale-105" />
          </div>

          <!-- بطاقة tilt: relative في الموبايل، absolute من md وفوق -->
          <div
            class="tilt relative mt-4 w-full rounded-xl border border-primary-200 bg-white p-6 shadow-2xl transition-all duration-500 hover:shadow-primary-200/50 dark:border-primary-700 dark:bg-gray-800
                   md:absolute md:-end-6 md:-bottom-6 md:w-2/3">
            <svg class="absolute -top-3 start-6 h-6 w-6 text-primary-500" fill="currentColor" viewBox="0 0 24 24">
              <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h2v6h-2zm0 8h2v2h-2z" />
            </svg>
            <h3 class="mb-2 text-lg font-bold text-gray-800 dark:text-gray-200">
              {{ t('From a single route to a trusted bridge') }}
            </h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">
              {{ t('We started with one route bringing families together, and grew into a door-to-door bridge between Saudi Arabia and Sudan.') }}
            </p>
          </div>
        </div>

        <div>
          <h2 class="mb-6 text-3xl font-bold text-gray-800 md:text-4xl dark:text-gray-200">
            {{ t('Who We Are') }}
            <span class="text-primary-600 dark:text-primary-300">{{ t('Askila Story') }}</span>
          </h2>

          <p class="mb-4 text-lg leading-relaxed text-gray-600 dark:text-gray-400">
            {{ t('Askila began with a simple promise: make travel and shipping feel personal and safe. Step by step, we built a community-first service that guides you from pickup to final delivery—without hassle or worry.') }}
          </p>

          <p class="mb-6 text-lg leading-relaxed text-gray-600 dark:text-gray-400">
            {{ t('Today, our team connects cities and families across KSA and Sudan with land, sea, and air shipping, plus flight and ferry tickets—coordinated end to end, with timelines you can trust.') }}
          </p>

          <div class="flex flex-wrap gap-3">
            <div class="flex items-center rounded-full bg-white px-4 py-2 shadow-sm dark:bg-gray-800">
              <x-icons icon="check" class="me-2 text-primary-500"/>
              <span class="text-sm font-medium text-gray-800 dark:text-gray-200">{{ t('50,000+ journeys & shipments delivered') }}</span>
            </div>
            <div class="flex items-center rounded-full bg-white px-4 py-2 shadow-sm dark:bg-gray-800">
              <x-icons icon="check" class="me-2 text-primary-500"/>
              <span class="text-sm font-medium text-gray-800 dark:text-gray-200">{{ t('20+ branches across KSA & Sudan') }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
