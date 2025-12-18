@props([
    'href' => '#',
    'icon' => null,
    'title' => '',
    'badge' => null,
])

<li>
    <a {{ $attributes->merge(['href' => $href, 'class' => 'flex items-center p-2 text-base font-normal text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group']) }}>
        @if ($icon)
            <x-icons :icon="$icon" class="flex-shrink-0"/>
        @endif
        <span class="flex-1 ms-3 whitespace-nowrap">{{ $title }}</span>
        @if ($badge)
            <span class="inline-flex justify-center items-center w-5 h-5 text-xs font-semibold rounded-full text-primary-800 bg-primary-100 dark:bg-primary-200 dark:text-primary-800">
                {{ $badge }}
            </span>
        @endif
    </a>
</li>
