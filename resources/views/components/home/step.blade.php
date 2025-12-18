@props([
    'number'=>'',
    'title'=>'',
    'description'=>'',
])
<div class="text-center">
    <div class="bg-primary-400/10  bg-opacity-10 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
        <div class="bg-primary-400/20  bg-opacity-10 w-16 h-16 rounded-full flex items-center justify-center mx-auto ">
            <div class="bg-primary-400  text-white  w-12 h-12 rounded-full flex items-center justify-center text-xl font-bold">{{ $number }}</div>
        </div>
    </div>
    <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-300 mb-2">{{ $title }}</h3>
    <p class="text-gray-600 dark:text-gray-400">{{ $description }}</p>
</div>
