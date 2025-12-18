@props([
    'title' => '',
    'icon' => null,
    'id' => 'dropdown-' . Str::slug($title),
])

<li>
    <button type="button"
        class="flex items-center p-2 w-full text-base font-normal text-gray-900 rounded-lg transition
        duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
        aria-controls="{{ $id }}" data-collapse-toggle="{{ $id }}">
        @if ($icon)
            <x-icons :icon="$icon" class="flex-shrink-0 w-5 h-5 text-gray-400 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"/>
        @endif
        <span class="flex-1 ms-3 text-start whitespace-nowrap">{{ $title }}</span>
        <x-icons icon="arrow-down" class="rtl:rotate-180"/>
    </button>
    <ul id="{{ $id }}" class="hidden py-2 space-y-2">
        {{ $slot }}
    </ul>
</li>
