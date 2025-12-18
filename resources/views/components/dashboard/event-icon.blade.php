@props([
    'route',
])

{{-- Trigger Button --}}
<a href="{{ $route }}"
    class="inline-flex items-center p-1 text-sm font-medium text-center text-gray-500 rounded-lg hover:text-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 focus:outline-none dark:text-gray-400 dark:hover:text-gray-100">
    {{ $slot }}
</a>
