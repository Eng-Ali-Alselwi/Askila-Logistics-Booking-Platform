{{-- <button
    {{ $attributes->merge(['type' => 'submit', 'class' => 'px-5 py-2 text-base font-medium text-center text-white bg-secondary-700 rounded-lg hover:bg-secondary-800 focus:ring-4 focus:ring-secondary-300 sm:w--auto dark:bg-secondary-600 dark:hover:bg-secondary-700 dark:focus:ring-secondary-800 transition-colors duration-300']) }}>
    {{ $slot }}
</button> --}}
@props([
    'as' => 'button', // الافتراضي زر
    'href' => null,   // للرابط إذا اخترت a
    'type' => 'submit',
])

<{{ $as }}
    @if($as === 'a')
        href="{{ $href }}"
        role="button" {{-- مهم للوصولية --}}
    @else
        type="{{ $type }}"
    @endif

    {{ $attributes->merge([
        'class' => 'px-5 py-2 text-base font-medium text-center text-white bg-secondary-700 rounded-lg hover:bg-secondary-800 focus:ring-4 focus:ring-secondary-300 sm:w-auto dark:bg-secondary-600 dark:hover:bg-secondary-700 dark:focus:ring-secondary-800 transition-colors duration-300'
    ]) }}
>
    {{ $slot }}
</{{ $as }}>
