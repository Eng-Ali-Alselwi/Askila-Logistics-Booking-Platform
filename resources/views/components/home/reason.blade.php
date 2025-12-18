@props([
    'title' => '',
])

<div class="p-4 md:w-1/4 sm:w-1/2 border border-gray-200 dark:border-gray-700 hover:border-gray-400 dark:hover:border-gray-500
    transition-colors duration-500">
    <div class="px-4 py-6 transform transition duration-500 hover:scale-110">
        <div class="flex justify-center">
            {{ $slot }}
        </div>
        <h4 class="font-regular text-gray-600 dark:text-gray-300 text-xl font-semibold">{{ $title }}</h4>
    </div>
</div>
