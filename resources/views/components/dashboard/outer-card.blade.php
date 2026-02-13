@props(['title' => null])

<div class="bg-white dark:bg-gray-800 shadow rounded-xl border border-gray-200 dark:border-gray-700">
    @if(isset($header))
        {{ $header }}
    @elseif($title)
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white">{{ $title }}</h2>
        </div>
    @endif
    
    <div class="p-6">
        {{ $slot }}
    </div>
</div>
