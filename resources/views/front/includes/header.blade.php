@php
    $currentRoute = Route::currentRouteName();
@endphp
<nav class=" container mx-auto h-[55px] lg:h-[73px] relative  px-3 py-3 flex justify-between items-center  ">

    <img src="{{ asset('assets/images/new/logo/01.png') }}" alt="{{ t('Khutut Massahiya') }}"
    class="w-20">
  {{-- <a class="text-2xl font-bold text-primary" href="#">
    {{ t('Khutut Massahiya') }}
  </a> --}}

  <div class="lg:hidden">
    <button class="navbar-burger flex items-center text-primary dark:text-gray-100 p-1" id="navbar_burger">
      <svg class="block h-6 w-6 fill-current" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
        <title>Hamberger menu</title>
        <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"></path>
      </svg>
    </button>
  </div>

  <ul
    class="hidden daisy-menu daisy-menu-horizontal absolute top-1/2 left-1/2
    transform -translate-y-1/2 -translate-x-1/2 lg:mx-auto lg:flex lg:items-center lg:w-auto lg:gap-3">
    <li>
        <x-home.nav-item href="{{ route('home') }}" :active="$currentRoute === 'home'" label="{{ t('Home') }}" />
    </li>
    <li>
        {{-- <x-home.nav-item href="{{ route('services') }}" :active="$currentRoute === 'services'"  label="{{ t('Our Services') }}"  /> --}}
        <x-home.nav-item href="#" :active="$currentRoute === 'services'"  label="{{ t('Our Services') }}"  />
    </li>
    <li>
        <x-home.nav-item href="#" :active="$currentRoute === 'contact'" label="{{ t('Contact US') }}"  />
    </li>

    <li>
        <x-home.nav-item href="#" :active="$currentRoute === 'about'" label="{{ t('About') }}"  />
    </li>
  </ul>


  <div class="hidden lg:flex justify-center gap-4">
    <a href="#">
      <button class=" py-1.5 ltr:px-4 rtl:px-8  text-center bg-gradient-to-br
       from-primary-500/90  to-primary-500/40   rounded-md
        text-white  shadow-primary-500/50 transition-transform duration-300 ease-in-out hover:scale-[1.03]
          hidden lg:block">
          {{t('Contact US')}}
      </button>
    </a>
  </div>
</nav>

<!-- mobile navbar -->
<div class="navbar-menu relative z-50  hidden" >
  <div class="navbar-backdrop fixed inset-0 bg-gray-800 opacity-50"></div>
  <nav id="navbar-drop" class="fixed ltr:animate-slide-right rtl:animate-slide-left-nav
    bg-white dark:bg-gray-600 top-0 ltr:left-0 rtl:right-0 bottom-0 flex flex-col w-4/6
    max-w-sm py-6 px-6 overflow-y-auto">

    <div class="flex items-center mb-8">
      <a class="me-auto text-xl font-bold  text-primary dark:text-gray-100" href="{{ route('home') }}">
        <img src="{{ asset('assets/images/new/logo/09.png') }}" alt="{{ t('Khutut Massahiya') }}"
        class="w-10">
      </a>

      <button class="navbar-close">
        <svg class="h-6 w-6 text-gray-400 cursor-pointer hover:text-gray-500"
        xmlns="http://www.w3.org/2000/svg"
          fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
          </path>
        </svg>
      </button>
    </div>

    <ul class=" menu menu-vertical rounded-box gap-5">
        <x-home.phone-nav-item
        href="{{ route('home') }}"
        label="{{ t('Home') }}"
        :active="$currentRoute === 'home'" />

        <x-home.phone-nav-item
        href="#"
        label="{{ t('Our Services') }}"
        :active="$currentRoute === 'services'" />

        <x-home.phone-nav-item
        href="#"
        label="{{ t('Contact US') }}"
        :active="$currentRoute === 'contact'" />

        <x-home.phone-nav-item
        href="#"
        label="{{ t('About') }}"
        :active="$currentRoute === 'about'" />
      {{-- <li class="text-center border border-primary rounded-box mb-2">
        <a href="{{ route('home') }}" class="p-2 text-lg text-primary">Home</a>
      </li>
      <li class="text-center border hover:border-primary group rounded-box">
        <a href="{{ route('services') }}" class="p-2 text-lg group-hover:text-primary">Our Services</a>
      </li> --}}
    </ul>

    <div class="mt-auto">
      <a class="block py-1.5 px-3  text-center bg-gradient-to-br
       from-primary-500/90  to-primary-500/40   rounded-md
      text-white shadow-primary-500/50 transition-transform
      duration-300 ease-in-out hover:scale-[1.03]"
        href="#">
        {{t('Contact US')}}
      </a>
      <p class="my-4 text-xs text-center text-gray-400">
        {{ t('All Rights Reserved © 2025') }}
        <a href="{{ route('home') }}" class="text-primary">{{ t('Khutut Massahiya') }}</a>
        {{ t('for Land Surveying') }}
      </p>
    </div>
  </nav>
</div>

{{-- <span class="rtl:animate-slide-left-hide-nav ltr:animate-slide-left hidden"></span> --}}