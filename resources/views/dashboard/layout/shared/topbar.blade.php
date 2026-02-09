<header class="antialiased shadow-lg">
    <nav class="bg-white border-gray-200 px-4 lg:px-6 py-2.5 dark:bg-gray-800">
        <div class="flex flex-wrap justify-between items-center">
            <div class="flex justify-start items-center">

                <button data-drawer-target="default-sidebar" data-drawer-toggle="default-sidebar"
                    aria-controls="default-sidebar" type="button"
                    class="inline-flex items-center p-2  text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
                    <span class="sr-only">Open Sidebar</span>
                    <x-icons icon="hamburger" />
                </button>

                <a href="#" class="flex mr-4">
                    <img src="{{ asset('assets/images/logo/dark.png') }}" class="me-3 h-8 dark:hidden" alt="Askila Logo" />
                    <img src="{{ asset('assets/images/logo/light.png') }}" class="me-3 h-8 hidden dark:block" alt="Askila Logo" />
                    <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white hidden md:block">{{ t('Askila') }}</span>
                </a>
            </div>

            <div class="flex items-center lg:order-2 space-x-1.5">
                <x-theme-switcher/>
                @include('dashboard.layout.shared.user-avatar')
            </div>
        </div>
    </nav>
</header>
