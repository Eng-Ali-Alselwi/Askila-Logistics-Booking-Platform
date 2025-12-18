@props(['href', 'active' => false, 'label'])

<a href="{{ $href }}"
   class="text-base relative group
          {{ $active ? 'bg-slate-100 dark:bg-slate-700 hover:bg-slate-100 dark:hover:bg-slate-700 text-primary' : 'hover:bg-transparent dark:hover:bg-transparent active:bg-transparent' }}">
    <span class="absolute  h-1 bg-primary bottom-0 {{ $active ? 'w-full' : 'w-0 group-hover:w-full transition-width duration-300' }}"></span>
    {{ $label }}
</a>
