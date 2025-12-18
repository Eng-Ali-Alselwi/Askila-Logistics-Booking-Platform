@props([
    'title' => '',
    'description' => '',
])

<div {{ $attributes->merge([
    'class' => 'flex items-center gap-4 p-4 border border-gray-200 dark:border-gray-700 hover:border-gray-400 dark:hover:border-gray-500 rounded-lg  hover:shadow-lg transition-all duration-300 group'
]) }}>
  <div class="p-3 bg-gray-300 dark:bg-gray-600 rounded-full shadow-lg
  group-hover:scale-110 border border-transparent  group-hover:border-gray-400 group-hover:dark:border-gray-300  bo transform transition duration-300">
    {{-- Icon Slot --}}
    {{ $slot }}
  </div>

  <div>
    <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200">{{ $title }}</h3>
    <p class="text-gray-600 dark:text-gray-400 text-sm">
      {{ $description }}
    </p>
  </div>
</div>
