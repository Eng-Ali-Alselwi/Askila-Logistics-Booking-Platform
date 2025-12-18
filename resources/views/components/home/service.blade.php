@props([
    'title' => '',
    'description' => '',
    'features' => [],
    'src' => null,
    'href' => null
])

<div class="card h-100" data-aos="fade-up">
    @if($src)
        <img src="{{ $src }}" class="card-img-top" alt="{{ $title }}" style="height: 200px; object-fit: cover;">
    @endif
    
    <div class="card-body d-flex flex-column">
        <div class="d-flex align-items-center mb-3">
            {{ $icon ?? '' }}
            <h5 class="card-title mb-0 ms-3">{{ $title }}</h5>
        </div>
        
        <p class="card-text text-muted mb-4">{{ $description }}</p>
        
        @if(count($features) > 0)
            <ul class="list-unstyled mb-4">
                @foreach($features as $feature)
                    <li class="mb-2">
                        <i class="fas fa-check text-success ml-2"></i>
                        {{ $feature }}
                    </li>
                @endforeach
            </ul>
        @endif
        
        @if($href)
            <div class="mt-auto">
                <a href="{{ $href }}" class="btn btn-primary w-100">
                    <i class="fas fa-arrow-left ml-2"></i>
                    تعرف على المزيد
                </a>
            </div>
        @endif
    </div>
</div>