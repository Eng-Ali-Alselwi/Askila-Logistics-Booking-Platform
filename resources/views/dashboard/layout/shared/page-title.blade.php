<div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">{{ t($subtitle ?? $title) }}</h1>
        <nav class="flex mt-2" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3 rtl:space-x-reverse">
                <li class="inline-flex items-center">
                    <a href="{{ route('dashboard.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-primary-600 dark:text-gray-400 dark:hover:text-white">
                        <x-heroicon-m-home class="w-4 h-4 me-2" />
                        {{ t('Dashboard') }}
                    </a>
                </li>
                @if(isset($subtitle))
                <li>
                    <div class="flex items-center">
                        <x-heroicon-m-chevron-right class="w-4 h-4 text-gray-400 rtl:rotate-180" />
                        <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">{{ t($subtitle ?? '') }}</span>
                    </div>
                </li>
                @endif
            </ol>
        </nav>
    </div>
</div>
