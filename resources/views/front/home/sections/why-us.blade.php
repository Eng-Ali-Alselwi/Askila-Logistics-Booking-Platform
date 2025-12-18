<section class="text-gray-700 py-12 ">
    <div class="flex justify-center mt-10 font-regular">
        <h3 class="text-4xl font-extrabold text-gray-800 dark:text-gray-200 mb-12">
            <span>{{ __('messages.why_askilah') }}</span>
        </h3>
    </div>
    <div class="container px-4 mx-auto">
        <div class="flex flex-col md:flex-row text-center justify-center gap-2 md:gap-0">
            <x-home.reason :title="__('messages.door_to_door_service')">
                <x-heroicon-o-truck class="text-primary-400 w-16 mb-3" />
            </x-home.reason>

            <x-home.reason :title="__('messages.wide_coverage')">
                <x-heroicon-o-globe-alt class="text-primary-400 w-16 mb-3" />
            </x-home.reason>

            <x-home.reason :title="__('messages.licensed_trusted')">
                <x-heroicon-o-shield-check class="text-primary-400 w-16 mb-3" />
            </x-home.reason>

            <x-home.reason :title="__('messages.support_24_7_why')">
                <x-heroicon-o-phone class="text-primary-400 w-16 mb-3" />
            </x-home.reason>

        </div>
    </div>
</section>