@props([
    'icon',
    'label',
])

<a {{ $attributes->merge(['class' => 'block p-4 text-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 group']) }}>
    <x-icons :icon="$icon" class="mx-auto mb-2 w-5 h-5 text-gray-400 group-hover:text-gray-500 dark:text-gray-400 dark:group-hover:text-gray-400"/>
    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $label }}</div>
</a>
