@props([
    'name',                 // اسم الحقل: ex. tracking_number
    'label' => null,
    'type' => 'text',
    'required' => false,
])

<div>
    @if($label)
        <label for="{{ $name }}" class="block text-sm mb-2">
            {{ $label }} @if($required)<span class="text-rose-500">*</span>@endif
        </label>
    @endif

    <input
        id="{{ $name }}"
        name="{{ $name }}"
        type="{{ $type }}"
        {{ $attributes->merge([
            'class' => 'w-full px-3 py-2 bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500'
        ]) }}
    />

    @error($name)
        <div class="text-xs text-rose-500 mt-1">{{ $message }}</div>
    @enderror
</div>
