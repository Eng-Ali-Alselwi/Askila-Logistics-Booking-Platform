@props([
    'id' => null,
    'label' => 'Textarea',
    'placeholder' => null,
    'required' => false,
    'disabled' => false,
    'value' => '',
    'rows' => 4,
    'autofocus' => false,
    'readonly' => false,
    'extra' => '',
])

{{-- A complete form textarea --}}

<div class="mb-4">
    <label for="{{ $id }}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
        {{ $label }}
    </label>

    <textarea
        id="{{ $id }}"
        name="{{ $id }}"
        rows="{{ $rows }}"
        placeholder="{{ $placeholder }}"
        {{ $required ? 'required' : '' }}
        {{ $autofocus ? 'autofocus' : '' }}
        {{ $readonly ? 'readonly' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        {!! $extra !!}
        class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
    >{{ $value }}</textarea>

    @error($id)
        <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
    @enderror
</div>
