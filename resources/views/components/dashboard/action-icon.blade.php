@props([
    'icon',
    'href' => '#',
    'tooltip' => null,
    'id' => 'tooltip-' . \Illuminate\Support\Str::uuid(),
])

<a href="{{ $href }}"
    @if($tooltip)
        data-tooltip-target="{{ $id }}"
    @endif
    {{ $attributes->merge(['class' => 'inline-flex justify-center p-2 text-gray-500 rounded cursor-pointer dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-600']) }}>
    <x-icons :icon="$icon" />
</a>

@if($tooltip)
    <div
        id="{{ $id }}"
        role="tooltip"
        class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip"
    >
        {{ $tooltip }}
        <div class="tooltip-arrow" data-popper-arrow></div>
    </div>
@endif
