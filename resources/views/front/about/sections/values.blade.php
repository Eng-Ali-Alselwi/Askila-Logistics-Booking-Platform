<section class="mb-16 py-12">
    <div class="text-center">
        <h2 class="mb-12 text-3xl font-bold text-gray-800 md:text-4xl dark:text-gray-200">
            {{ t('Our Core Principles') }}
            <span class="text-primary-600 dark:text-primary-300">{{ t('Askila Values') }}</span>
        </h2>
    </div>
    <div class="grid gap-8 md:grid-cols-3">
        <!-- Value 1 -->
        <div class="group relative overflow-hidden rounded-2xl bg-white p-8 shadow-xl transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl dark:bg-gray-800">
            <div class="absolute -top-10 -right-10 h-20 w-20 rounded-full bg-primary-100/30 transition-all duration-500 group-hover:scale-150 dark:bg-primary-900/30"></div>
            <div class="relative mb-6 flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-primary-100 to-primary-100 shadow-sm dark:from-primary-900 dark:to-primary-900">
                <x-heroicon-o-light-bulb class="h-8 w-8 text-primary-600 transition-all duration-300 group-hover:scale-110"/>
            </div>
            <h3 class="mb-3 text-xl font-semibold text-gray-800 dark:text-gray-200">{{ t('Reliability') }}</h3>
            <p class="text-gray-600 dark:text-gray-400">
                {{ t('Delivering shipments and tickets on time, every time—because trust is built on consistency.') }}
            </p>
            <div class="absolute bottom-0 left-0 h-1 w-full origin-left scale-x-0 bg-gradient-to-r from-primary-500 to-primary-600 transition-transform duration-500 group-hover:scale-x-100"></div>
        </div>

        <!-- Value 2 -->
        <div class="group relative overflow-hidden rounded-2xl bg-white p-8 shadow-xl transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl dark:bg-gray-800">
            <div class="absolute -top-10 -right-10 h-20 w-20 rounded-full bg-primary-100/30 transition-all duration-500 group-hover:scale-150 dark:bg-primary-900/30"></div>
            <div class="relative mb-6 flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-primary-100 to-primary-100 shadow-sm dark:from-primary-900 dark:to-primary-900">
                <x-heroicon-o-shield-check class="h-8 w-8 text-primary-600 transition-all duration-300 group-hover:scale-110"/>
            </div>
            <h3 class="mb-3 text-xl font-semibold text-gray-800 dark:text-gray-200">{{ t('Transparency') }}</h3>
            <p class="text-gray-600 dark:text-gray-400">
                {{ t('Clear pricing, honest communication, and no hidden surprises in every step of your journey.') }}
            </p>
            <div class="absolute bottom-0 left-0 h-1 w-full origin-left scale-x-0 bg-gradient-to-r from-primary-500 to-primary-600 transition-transform duration-500 group-hover:scale-x-100"></div>
        </div>

        <!-- Value 3 -->
        <div class="group relative overflow-hidden rounded-2xl bg-white p-8 shadow-xl transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl dark:bg-gray-800">
            <div class="absolute -top-10 -right-10 h-20 w-20 rounded-full bg-primary-100/30 transition-all duration-500 group-hover:scale-150 dark:bg-primary-900/30"></div>
            <div class="relative mb-6 flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-primary-100 to-primary-100 shadow-sm dark:from-primary-900 dark:to-primary-900">
                <x-heroicon-o-users class="h-8 w-8 text-primary-600 transition-all duration-300 group-hover:scale-110"/>
            </div>
            <h3 class="mb-3 text-xl font-semibold text-gray-800 dark:text-gray-200">{{ t('Care for People') }}</h3>
            <p class="text-gray-600 dark:text-gray-400">
                {{ t('Behind every shipment is a family and a story—we treat each one with respect and responsibility.') }}
            </p>
            <div class="absolute bottom-0 left-0 h-1 w-full origin-left scale-x-0 bg-gradient-to-r from-primary-500 to-primary-600 transition-transform duration-500 group-hover:scale-x-100"></div>
        </div>
    </div>
</section>
