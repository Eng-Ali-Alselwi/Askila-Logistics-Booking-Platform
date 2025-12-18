<section class="contact-section py-4 mt-34 mb-10 relative overflow-hidden">
    <!-- Background decorative elements -->
    <div class="absolute inset-0 bg-gradient-to-br from-primary-50/20 via-transparent to-secondary-50/20 dark:from-primary-900/10 dark:via-transparent dark:to-secondary-900/10"></div>
    <div class="absolute top-10 right-5 w-20 h-20 bg-primary-200/20 dark:bg-primary-800/20 rounded-full blur-2xl"></div>
    <div class="absolute bottom-10 left-5 w-24 h-24 bg-secondary-200/20 dark:bg-secondary-800/20 rounded-full blur-2xl"></div>
    
    <div class="container mx-auto px-4 py-2 max-w-5xl relative z-10">
        <div data-aos="fade-up" data-aos-duration="600"
            class="grid sm:grid-cols-2 gap-4 bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm rounded-xl
            shadow-xl border border-white/30 dark:border-gray-700/30 overflow-hidden
            hover:shadow-2xl transition-all duration-300">

            <!-- Contact Info -->
            <div
                class="order-2 sm:order-1 p-4 lg:p-6 bg-gradient-to-br from-primary-600 via-primary-700
                to-primary-800
                dark:from-gray-900 dark:via-gray-800
                dark:to-gray-700
                text-white relative overflow-hidden">

                <div class="relative z-10">
                    <h2 class="text-xl lg:text-2xl font-bold text-white mb-4 relative pb-4">
                    {{ __('messages.contact_information') }}
                        <span class="absolute bottom-0 start-0 w-8 h-0.5 bg-gradient-to-r from-white to-primary-300 rounded-full"></span>
                    </h2>

                <!-- Location -->
                <div class="flex items-center gap-3 mb-8 group">
                    <div
                        class="w-10 h-10 bg-white/20 backdrop-blur-sm border border-white/30 text-white flex items-center justify-center rounded-lg shadow-md group-hover:scale-105 transition-all duration-300">
                        <x-heroicon-o-map-pin class="h-5 w-5" />
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-white">{{ __('messages.our_location') }}</h3>
                        <p class="text-white/90 text-sm">
                            Lucknow
                        </p>
                    </div>
                </div>

                <!-- Phone Numbers -->
                <div class="flex items-center gap-3 mb-8 group">
                    <div class="w-10 h-10 bg-white/20 backdrop-blur-sm border border-white/30 text-white flex items-center justify-center rounded-lg shadow-md group-hover:scale-105 transition-all duration-300">
                        <x-heroicon-s-phone class="h-4 w-4" />
                    </div>
                    <div>
                            <h3 class="text-sm font-semibold text-white">{{ __('messages.phone_numbers') }}</h3>
                            <p class="text-white/90 text-sm">
                                <a href="tel:+978589658" class="hover:text-primary-300 transition-colors duration-300 block">+91 748598658</a>
                                <a href="tel:+748596255" class="hover:text-primary-300 transition-colors duration-300 block">+91 789456123</a>
                        </p>
                    </div>
                </div>

                <!-- Email -->
                <div class="flex items-center gap-3 mb-8 group">
                    <div class="w-10 h-10 bg-white/20 backdrop-blur-sm border border-white/30 text-white flex items-center justify-center rounded-lg shadow-md group-hover:scale-105 transition-all duration-300">
                        <x-heroicon-s-envelope class="h-4 w-4" />
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-white">{{ __('messages.email') }}</h3>
                        <p class="text-white/90 text-sm">
                            <a href="mailto:askilahgroup@gmail.com"
                                class="hover:text-primary-300 transition-colors duration-300">askilahgroup@gmail.com</a>
                        </p>
                    </div>
                </div>

                <!-- Social Links -->
                <div class="flex gap-2 mt-16 justify-center">
                    <a href="#"
                            class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/20 backdrop-blur-sm border border-white/30 text-white transition-all duration-300 hover:bg-blue-500 hover:scale-105 shadow-md">
                        <x-icons icon="facebook" />
                    </a>
                    <a href="#"
                            class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/20 backdrop-blur-sm border border-white/30 text-white transition-all duration-300 hover:bg-[#E4405F] hover:scale-105 shadow-md">
                        <x-icons icon="instagram" />
                    </a>
                    <a href="#"
                            class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/20 backdrop-blur-sm border border-white/30 text-white transition-all duration-300 hover:bg-[#010101] hover:scale-105 shadow-md">
                        <x-icons icon="tiktok" />
                    </a>
                </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="p-4 lg:p-6 order-1 sm:order-2">
                <h2 class="text-xl pb-4 lg:text-2xl font-bold text-primary-600 dark:text-primary-400 mb-4 relative pb-2">
                    {{ __('messages.send_us_message') }}
                    <span class="absolute bottom-0 start-0 w-8 h-0.5 bg-gradient-to-r from-primary-500 to-primary-300 rounded-full"></span>
                </h2>
                
                <form class="space-y-3" method="POST" action="{{ route('contact.send') }}">
                    @csrf
                    <!-- Name -->
                    <div class="group">
                        <label for="name" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('messages.your_name') }}
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}"
                            placeholder="{{ __('messages.enter_your_name') }}" required
                            class="w-full px-3 py-2 bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm border border-gray-300 dark:border-gray-600 
                            rounded-lg text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400
                            focus:border-primary-500 focus:ring-1 focus:ring-primary-500/20 focus:outline-none
                            transition-all duration-300 group-hover:border-primary-300 dark:group-hover:border-primary-500
                            shadow-sm hover:shadow-md text-sm" />
                    </div>

                    <!-- Email -->
                    <div class="group">
                        <label for="email" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('messages.your_email') }}
                        </label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                            placeholder="{{ __('messages.enter_your_email') }}" required
                            class="w-full px-3 py-2 bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm border border-gray-300 dark:border-gray-600 
                            rounded-lg text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400
                            focus:border-primary-500 focus:ring-1 focus:ring-primary-500/20 focus:outline-none
                            transition-all duration-300 group-hover:border-primary-300 dark:group-hover:border-primary-500
                            shadow-sm hover:shadow-md text-sm" />
                    </div>

                    <!-- Message -->
                    <div class="group">
                        <label for="message" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('messages.your_message') }}
                        </label>
                        <textarea id="message" name="message" rows="3"
                            placeholder="{{ __('messages.write_message_here') }}" required
                            class="w-full px-3 py-2 bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm border border-gray-300 dark:border-gray-600 
                            rounded-lg text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400
                            focus:border-primary-500 focus:ring-1 focus:ring-primary-500/20 focus:outline-none
                            transition-all duration-300 group-hover:border-primary-300 dark:group-hover:border-primary-500
                            shadow-sm hover:shadow-md resize-none text-sm"></textarea>
                    </div>

                    <div class="flex flex-col md:flex-row justify-start gap-2 pt-1">
                        @if ($errors->any())
                            <div class="w-full text-red-600 text-xs">
                                {{ $errors->first() }}
                            </div>
                        @endif
                        @if (session('success'))
                            <div class="w-full text-green-600 text-xs">
                                {{ session('success') }}
                            </div>
                        @endif
                        <!-- Submit Button -->
                        <button type="submit"
                            class="flex w-full sm:w-auto items-center justify-center gap-2 bg-gradient-to-r from-primary-600 to-primary-700 
                            text-white font-semibold py-2 px-4 rounded-lg hover:from-primary-700 hover:to-primary-800 
                            hover:scale-105 hover:shadow-lg transition-all duration-300 shadow-md text-sm">
                            <x-icons icon="paper-plane" class="w-3 h-3" />
                            {{ __('messages.send_message') }}
                        </button>

                        <a href="#"
                            class="flex w-full sm:w-auto items-center justify-center gap-2 border border-primary-500 text-primary-600
                            dark:border-primary-400 dark:text-primary-400 font-semibold py-2 px-4 rounded-lg 
                            hover:bg-primary-50 dark:hover:bg-primary-900/20 hover:scale-105 hover:shadow-md 
                            transition-all duration-300 shadow-sm text-sm">
                            <x-icons icon="whatsapp" class="w-3 h-3" />
                            {{ __('messages.contact_on_whatsapp') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
