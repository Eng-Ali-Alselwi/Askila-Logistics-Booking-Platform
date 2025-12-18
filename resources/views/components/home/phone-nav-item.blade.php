@props(['href','active'=>false ,'label'])
<li class="text-center border rounded-box mb-2
    {{ $active?'border-primary ':'hover:border-primary  group ' }}  ">
<a href="{{ $href }}" class="p-2 text-lg  {{ $active?'text-primary':'group-hover:text-primary' }}">{{ $label }}</a>
</li>
