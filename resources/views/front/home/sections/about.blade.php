<section class="relative py-12 md:py-16 bg-white dark:bg-gray-900 overflow-hidden">

  <!-- Modern Background Elements -->
  <div class="absolute inset-0 opacity-5">
    <div class="absolute top-1/4 right-10 w-96 h-96 bg-primary-400 rounded-full mix-blend-multiply filter blur-xl animate-float"></div>
    <div class="absolute bottom-1/4 left-10 w-80 h-80 bg-secondary-400 rounded-full mix-blend-multiply filter blur-xl animate-float animate-delay-200"></div>
  </div>
  
  <!-- SVG Pattern Background -->
  <x-home.svg-pattern/>

  <!-- Content Container -->
  <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid lg:grid-cols-2 gap-8 lg:gap-12 items-center">
      
      <!-- Text Content -->
      <div class="animate-fade-in-up">
        <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 dark:text-white mb-4">
          <span class="block">{{ __("messages.about") }}</span>
          <span class="text-gradient-primary bg-gradient-to-r from-primary-600 to-primary-800 bg-clip-text text-transparent">
            {{ __("messages.askilah") }}
          </span>
        </h2>
        
        <p class="text-lg text-gray-600 dark:text-gray-300 mb-6 leading-relaxed">
          {{ __("messages.about_content") }}
        </p>
        
        <div class="space-y-5">
          <!-- Enhanced Feature 1 -->
          <div class="flex items-start  rtl:space-x-reverse group pt-2">
            <div class="flex-shrink-0 w-10 h-10 mr-6 rtl:ml-6 bg-primary-100 dark:bg-primary-900/30 rounded-xl flex items-center justify-center transition-transform duration-300">
              <x-heroicon-o-truck class="w-5 h-5 text-primary-600 dark:text-primary-400" />
            </div>
            <div>
              <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">
                {{ __("messages.door_to_door_a2z") }}
              </h3>
              <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
                {{ __("messages.door_to_door_description") }}
              </p>
            </div>
          </div>

          <!-- Enhanced Feature 2 -->
          <div class="flex items-start space-x-2 rtl:space-x-reverse group pt-2">
            <div class="flex-shrink-0 w-10 h-10 mr-6 rtl:ml-6 bg-primary-100 dark:bg-primary-900/30 rounded-xl flex items-center justify-center transition-transform duration-300">
              <x-heroicon-o-shield-check class="w-5 h-5 text-primary-600 dark:text-primary-400" />
            </div>
            <div>
              <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">
                {{ __("messages.twenty_five_years_experience") }}
              </h3>
              <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
                {{ __("messages.experience_description") }}
              </p>
            </div>
          </div>

          <!-- Enhanced Feature 3 -->
          <div class="flex items-start space-x-2 rtl:space-x-reverse group pt-2">
            <div class="flex-shrink-0 w-10 h-10 mr-6 rtl:ml-6 bg-primary-100 dark:bg-primary-900/30 rounded-xl flex items-center justify-center transition-transform duration-300">
              <x-heroicon-o-map-pin class="w-5 h-5 text-primary-600 dark:text-primary-400" />
            </div>
            <div>
              <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">
                {{ __("messages.care_tracking_delivery") }}
              </h3>
              <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
                {{ __("messages.care_description") }}
              </p>
            </div>
          </div>
        </div>
        
        <!-- Call to Action -->
        <div class="mt-8">
          <a href="{{ route('contact.index') }}" class="btn btn-primary text-xl p-4">
            {{ __("messages.contact_us") }}
            <!-- <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg> -->
          </a>
        </div>
      </div>

      <!-- Enhanced Image Section -->
      <div class="relative animate-fade-in-up animate-delay-200">
          <div class="relative group image-container">
              <!-- خلفية متدرجة زخرفية محسنة -->
              <div class="absolute inset-0 bg-gradient-to-br from-primary-400/25 via-primary-600/25 to-secondary-600/25 rounded-3xl transform rotate-2 smooth-transform background-gradient shadow-lg">
              </div>
              
              <!-- حلقة ضوئية خارجية -->
              <div class="absolute inset-0 bg-gradient-to-br from-primary-500/10 via-transparent to-purple-500/10 rounded-3xl transform rotate-1 smooth-transform scale-110 opacity-0 group-hover:opacity-100 group-hover:scale-105">
              </div>
              
              <!-- الحاوية الرئيسية للصورة -->
              <div class="relative bg-white dark:bg-gray-800 p-3 rounded-3xl shadow-2xl transform scale-[1.02] ultra-smooth main-image-wrapper backdrop-blur-sm border border-white/20">
                  <!-- تأثير الانعكاس -->
                  <div class="absolute inset-0 bg-gradient-to-br from-white/10 via-transparent to-transparent rounded-3xl pointer-events-none">
                  </div>
                  
                  <img src="{{ asset('assets/images/about.png') }}" alt="Askila logistics & travel" class="relative rounded-2xl shadow-xl object-cover w-full h-full ultra-smooth main-image gpu-accelerated" />
                  
                  <!-- البطاقات العائمة المحسنة -->
                  <div class="absolute -top-6 -left-6 bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-4 floating-card floating-card-left pulse-glow border border-primary-200 dark:border-primary-800 ripple-effect backdrop-blur-sm bg-white/90 dark:bg-gray-800/90">
                      <div class="text-center">
                          <div class="text-2xl font-bold text-primary-600 dark:text-primary-400 
                                    bg-gradient-to-r from-primary-600 to-purple-600 bg-clip-text text-transparent">
                              25+
                          </div>
                          <div class="text-xs text-gray-600 dark:text-gray-400 font-medium">
                              {{ __("messages.years") }}
                          </div>
                      </div>
                  </div>
                  
                  <div class="absolute -bottom-6 -right-6 bg-white dark:bg-gray-800 rounded-2xl 
                            shadow-2xl p-4 floating-card floating-card-right pulse-glow
                            border border-secondary-200 dark:border-secondary-800 ripple-effect
                            backdrop-blur-sm bg-white/90 dark:bg-gray-800/90">
                      <div class="text-center">
                          <div class="text-2xl font-bold text-secondary-600 dark:text-secondary-400
                                    bg-gradient-to-r from-secondary-600 to-orange-600 bg-clip-text text-transparent">
                              20+
                          </div>
                          <div class="text-xs text-gray-600 dark:text-gray-400 font-medium">
                              {{ t("Branches") }}
                          </div>
                      </div>
                  </div>

                  <!-- مؤشرات إضافية -->
                  <div class="absolute top-3 right-3 w-2 h-2 bg-green-500 rounded-full 
                            animate-pulse shadow-lg"></div>
                  <div class="absolute bottom-3 left-3 w-1.5 h-1.5 bg-primary-500 rounded-full 
                            animate-ping"></div>
              </div>

              <!-- تأثير الهالة -->
              <div class="absolute inset-0 rounded-3xl opacity-0 group-hover:opacity-100 
                          transition-opacity duration-1000 ease-out pointer-events-none
                          bg-gradient-to-r from-primary-500/5 via-purple-500/5 to-secondary-500/5 
                          blur-xl scale-110"></div>
          </div>
      </div>

    </div>
  </div>
</section>