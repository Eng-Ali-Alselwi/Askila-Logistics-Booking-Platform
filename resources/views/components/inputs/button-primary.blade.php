{{-- @props([
    'type' => 'button',
    'href' => null,
    'as' => 'button'
])

@if($as === 'a')
    <a {{ $attributes->merge(['class' => 'btn btn-primary']) }} href="{{ $href }}">
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['class' => 'btn btn-primary', 'type' => $type]) }}>
        {{ $slot }}
    </button>
@endif --}}

@props([
    'as' => 'button',
    'href' => null,
    'type' => 'button',
    'size' => 'md', // xs, sm, md, lg, xl
    'variant' => 'primary', // primary, secondary, success, danger
    'loading' => false,
    'disabled' => false,
])

@php
    $sizeClasses = [
        'xs' => 'px-3 py-1.5 text-xs',
        'sm' => 'px-4 py-2 text-sm',
        'md' => 'px-6 py-3 text-base',
        'lg' => 'px-8 py-4 text-lg',
        'xl' => 'px-10 py-5 text-xl',
    ];
    
    $variantClasses = [
        'primary' => 'btn btn-primary',
        'secondary' => 'btn btn-secondary',
        'success' => 'btn btn-success',
        'danger' => 'btn btn-outline-danger',
        'outline' => 'btn btn-outline-primary',
    ];
    
    $classes = implode(' ', [
        $variantClasses[$variant] ?? $variantClasses['primary'],
        $sizeClasses[$size] ?? $sizeClasses['md'],
        $disabled ? 'opacity-60 cursor-not-allowed' : '',
        $loading ? 'opacity-75 cursor-wait' : '',
    ]);
@endphp

<{{ $as }}
    @if($as === 'a')
        href="{{ $href }}"
    @else
        type="{{ $type }}"
        @if($disabled || $loading) disabled @endif
    @endif
    
    {{ $attributes->merge(['class' => trim($classes)]) }}
>
    @if($loading)
        <svg class="w-5 h-5 mr-2 animate-spin" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4A4 4 0 004 12z"/>
        </svg>
    @endif
    
    {{ $slot }}
</{{ $as }}>