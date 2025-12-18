<section class="relative overflow-hidden rounded-3xl bg-white py-5 shadow-xl dark:bg-gray-800">
    <!-- Decorative elements -->
    <div class="absolute -top-20 -right-20 h-64 w-64 rounded-full bg-primary-100/30 dark:bg-primary-900/30"></div>
    <div class="absolute -bottom-10 -left-10 h-32 w-32 rounded-full bg-primary-100/30 dark:bg-primary-900/30"></div>

    <div class="relative z-10 mx-auto max-w-4xl px-4 text-center sm:px-6 lg:px-8">
        <h2 class="mb-6 text-3xl font-bold text-gray-800 md:text-4xl dark:text-gray-200">
            {{ t('Ready to ship or travel') }}
            <span class="text-primary-600 dark:text-primary-300">{{ t('with Askila?') }}</span>
        </h2>

        <p class="mx-auto mb-8 max-w-2xl text-xl text-gray-600 dark:text-gray-400">
            {{ t('Contact us now for tickets and door-to-door shipping between Saudi Arabia and Sudan.') }}
        </p>

        <div class="flex flex-col space-y-4 sm:flex-row sm:justify-center sm:space-y-0 sm:space-x-4">

            <a href="{{ route('contact.index') }}"
            class="flex w-full md:w-fit items-center justify-center gap-2 bg-gradient-to-br from-primary-500 to-primary-400 text-white font-semibold py-2 px-6 rounded-full hover:scale-105 hover:shadow-lg transition duration-300">
                <x-icons icon="paper-plane" />
                {{ t('Send Message') }}
            </a>

            <a href="https://wa.me/9665XXXXXXXX"
            class="flex w-full md:w-fit items-center justify-center gap-2 border border-gray-500 text-gray-600 dark:border-gray-300 dark:text-gray-400 font-semibold py-2 px-6 rounded-full hover:scale-105 hover:shadow-lg transition duration-300">
                <x-icons icon="whatsapp" />
                {{ t('Contact On Whatsapp') }}
            </a>

        </div>
    </div>
</section>
