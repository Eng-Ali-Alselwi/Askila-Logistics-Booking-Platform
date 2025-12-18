@props([
    'label' => '',
    'route' => '#',
    'active' => false
])

<li class="nav-item">
    <a class="nav-link {{ request()->routeIs($route) ? 'active' : '' }}" 
       href="{{ $route }}">
        {{ $label }}
    </a>
</li>