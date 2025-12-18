@props([
    'status' => null,
    'class' => 'w-6 h-6 text-gray-400', // الافتراضي (يتغير من الخارج)
])

@if ($status === 'all')
    <x-icons icon="plane" class="{{ $class }}" />
@elseif ($status === 'active')
    <x-heroicon-o-check-circle class="{{ $class }}" />
@elseif ($status === 'inactive')
    <x-heroicon-o-x-circle class="{{ $class }}" />
@elseif ($status === 'available')
    <x-heroicon-o-ticket class="{{ $class }}" />
@elseif ($status === 'full')
    <x-heroicon-o-no-symbol class="{{ $class }}" />
@elseif ($status === 'upcoming')
    <x-heroicon-o-clock class="{{ $class }}" />
@elseif ($status === 'past')
    <x-heroicon-o-calendar-days class="{{ $class }}" />
@else
    <x-heroicon-o-question-mark-circle class="{{ $class }}" />
@endif
