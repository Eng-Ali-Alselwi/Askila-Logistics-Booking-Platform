@props(['title' => null])

<div class="bg-white dark:bg-gray-800 shadow rounded-xl p-4 border border-gray-200 dark:border-gray-700">
{{ $title }} 
  

    <div class="p-6">
        {{ $slot }}
    </div>
  
</div>
