@props([
    'status' => null,
    'class' => 'w-6 h-6 text-gray-400', // الافتراضي (يتغير من الخارج)
])

@if ($status === 'all')
    <x-icons icon="shipment" class="{{ $class }}" />
@elseif ($status === 'received_at_branch')
    <x-heroicon-o-building-office class="{{ $class }}" />
@elseif ($status == 'arrived_jed_warehouse')
    <x-heroicon-o-archive-box class="{{ $class }}" />
@elseif ($status == 'shipped_jed_port')
    <x-icons icon="ship" class="{{ $class }}" />
@elseif ($status === 'arrived_sudan_port')
    <x-heroicon-o-building-office class="{{ $class }}" />
@elseif ($status === 'arrived_destination_branch')
    <x-heroicon-o-truck class="{{ $class }}" />
@elseif ($status === 'delivered')
    <x-heroicon-o-check-circle class="{{ $class }}" />
@else
    <x-heroicon-o-question-mark-circle class="{{ $class }}" />
@endif
