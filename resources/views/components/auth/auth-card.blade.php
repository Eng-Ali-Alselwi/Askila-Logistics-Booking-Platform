<div class="flex flex-col items-center justify-center px-6 pt-8 mx-auto md:h-screen pt:mt-0 dark:bg-gray-900">
    <!-- Card -->
    <div class="w-full border-t-4 border-t-primary-500 max-w-xl bg-white rounded-lg shadow-md dark:bg-gray-800">

        <div class="border-b px-4 py-6">
            <a href="/" class="flex items-center justify-center md:text-xl xl:text-2xl font-semibold dark:text-white">
                <img src="{{ asset('assets/images/logo/dark.png') }}" class="mr-3 h-9 xl:h-11" alt="Logo">
            </a>
        </div>

        <div class="px-6 pt-6 xl:pb-3 space-y-6 sm:px-8">
            {{ $slot }}
        </div>

    </div>
</div>
