<nav id="navbar" class="fixed w-full z-1000 top-[40px] mt-1 start-0 transition-all duration-500 ease-in-out
border-b border-transparent text-black bg-white">
  <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
    <a href="{{ route('home') }}" class="flex items-center">
      <img id="logo-light" src="{{ asset('assets/images/logo/light.png') }}" class="h-10 transition-opacity duration-500" alt="شعار الشركة">
      <img id="logo-dark" src="{{ asset('assets/images/logo/dark.png') }}" class="h-10 hidden transition-opacity duration-500" alt="شعار الشركة">
    </a>

    <div class="flex lg:order-2 items-center gap-3">
      @php $currentLocale = app()->getLocale(); @endphp
      <!-- Language Switcher -->
      <a href="{{ route('lang.switch', $currentLocale === 'ar' ? 'en' : 'ar') }}"
        id="language-switcher"
        class="h-10 w-10 flex items-center rounded-full font-bold bg-white dark:bg-gray-800/10 text-gray-500 dark:text-gray-300 dark:hover:text-white text-xs lg:text-sm shadow-lg hover:bg-blue-100 transition-colors duration-300 cursor-pointer"
        aria-label="{{ $currentLocale === 'ar' ? 'English' : 'العربية' }}"
        title="{{ $currentLocale === 'ar' ? 'Switch to English' : 'التبديل إلى العربية' }}">
        <!-- أيقونة الكرة الأرضية -->
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 m-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 3C7.03 3 3 7.03 3 12s4.03 9 9 9 9-4.03 9-9-4.03-9-9-9zm0 0c3 0 5 4 5 9s-2 9-5 9-5-4-5-9 2-9 5-9z" />
          <path stroke-linecap="round" stroke-linejoin="round" d="M3.6 9h16.8M3.6 15h16.8" />
        </svg>
      </a>

      <!-- Theme Toggle -->
      <button id="navbar-theme-toggle" class="group relative w-10 h-10 bg-white/10 dark:bg-gray-800/10 hover:dark:bg-gray-800/10 hover:bg-blue-100 backdrop-blur-md border border-white/20 dark:border-gray-700/20 shadow-lg hover:shadow-xl rounded-full transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500/50 cursor-pointer">
        <!-- Sun Icon (Light Mode ) -->
        <svg id="navbar-sun-icon" class="w-5 h-5 text-yellow-400 transition-all duration-300 absolute inset-0 m-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
        </svg>
        
        <!-- Moon Icon (Dark Mode) -->
        <svg id="navbar-moon-icon" class="w-5 h-5 font-bold dark:text-gray-300 dark:hover:text-white transition-all duration-300 absolute inset-0 m-auto opacity-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
        </svg>
      </button>

      @auth
        {{-- Login button is now inside the main nav items for large screens --}}
      @else
        {{-- This container is only for the large screen login button --}}
        <div class="hidden lg:flex items-center gap-3">
            <a href="{{ route('login') }}" id="login-btn-desktop" class="px-4 py-2 rounded-lg bg-blue-500 font-bold text-white dark:text-gray-100 hover:bg-blue-700 transition-all duration-300">{{ __('messages.login') }}</a>
        </div>
      @endauth

      <button data-collapse-toggle="navbar-sticky" type="button" id="mobile-menu-btn" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-black dark:text-white cursor-pointer rounded-lg lg:hidden hover:bg-white/20 focus:outline-none transition-colors duration-300" aria-controls="navbar-sticky" aria-expanded="false">
        <span class="sr-only">{{ __('messages.menu') }}</span>
        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15" /></svg>
      </button>
    </div>

    <div id="navbar-sticky" class="items-center justify-between hidden w-full lg:flex lg:w-auto lg:order-1">
        <ul class="flex flex-col lg:flex-row lg:items-center gap-4 lg:gap-8 p-4 lg:p-0 mt-4 lg:mt-0 font-medium border border-gray-100 rounded-lg !bg-white dark:!bg-gray-800 lg:!bg-transparent lg:dark:!bg-transparent lg:border-0">
          <li>
            <a href="{{ route('home' ) }}" class="nav-link block py-2 px-3 text-black dark:text-gray-300 hover:text-blue-600 dark:lg:hover:text-white underline-offset-4 transition-colors duration-300 {{ request()->routeIs('home') ? 'dark:lg:text-white lg:text-blue-500' : '' }}">{{ __('messages.home') }}</a>
          </li>
          <!-- <li>
            <a href="{{ route('services.index') }}" class="nav-link block py-2 px-3 text-black dark:text-gray-300 hover:text-blue-600 dark:lg:hover:text-white underline-offset-4 transition-colors duration-300 {{ request()->routeIs('services.index') ? 'dark:lg:text-white lg:text-blue-500' : '' }}">{{ __('messages.services') }}</a>
          </li> -->
          <li>
            <a href="{{ route('shipment.track') }}" class="nav-link block py-2 px-3 text-black dark:text-gray-300 hover:text-blue-600 dark:lg:hover:text-white underline-offset-4 transition-colors duration-300 {{ request()->routeIs('shipment.track') ? 'dark:lg:text-white lg:text-blue-500' : '' }}">{{ __('messages.track_shipment') }}</a>
          </li>
          <li>
            <a href="{{ route('booking.track') }}" class="nav-link block py-2 px-3 text-black dark:text-gray-300 hover:text-blue-600 dark:lg:hover:text-white underline-offset-4 transition-colors duration-300 {{ request()->routeIs('booking.track') ? 'dark:lg:text-white lg:text-blue-500' : '' }}">{{ __('messages.track_booking') }}</a>
          </li>
          <li>
            <a href="{{ route('flights.index') }}" class="nav-link block py-2 px-3 text-black dark:text-gray-300 hover:text-blue-600 dark:lg:hover:text-white underline-offset-4 transition-colors duration-300 {{ request()->routeIs('flights.*') ? 'dark:lg:text-white lg:text-blue-500' : '' }}">{{ __('messages.Available_trips') }}</a>
          </li>
          @auth
            {{-- Buttons for authenticated users --}}
            <li class="lg:hidden">
              <a href="{{ route('my-flights') }}" class="block py-2 px-3 text-black dark:text-gray-300 hover:text-blue-600">{{ __('messages.my_flights') }}</a>
            </li>
            <li class="lg:hidden">
              <a href="{{ route('dashboard.index') }}" class="block py-2 px-3 text-black dark:text-gray-300 hover:text-blue-600">{{ __('messages.dashboard') }}</a>
            </li>
          @else
            {{-- Login buttons for mobile menu --}}
            <li class="lg:hidden mt-4 space-y-2">
              <a href="{{ route('login') }}" id="login-btn-mobile" class="block text-center px-3 py-2 rounded-lg bg-blue-500 font-bold text-white hover:bg-blue-700 transition-all duration-300">{{ __('messages.login') }}</a>
            </li>
          @endauth
      </ul>
    </div>

  </div>
</nav>

<script>
// Enhanced Navbar with Smart Scroll Detection - Fixed Version
const navbar = document.getElementById("navbar");
const mobileMenuButton = document.getElementById("mobile-menu-btn");
const mobileMenu = document.getElementById("navbar-sticky");
const logoLight = document.getElementById("logo-light");
const logoDark = document.getElementById("logo-dark");
// We don't need a single loginBtn anymore, as they are separate for desktop and mobile
const navLinks = document.querySelectorAll(".nav-link");
const navbarThemeToggle = document.getElementById("navbar-theme-toggle");
const navbarSunIcon = document.getElementById("navbar-sun-icon");
const navbarMoonIcon = document.getElementById("navbar-moon-icon");
const languageSwitcher = document.getElementById("language-switcher");
let isMenuOpen = false;

// 🔹 دالة تحديث الشعار والأيقونات
function updateThemeUI(theme) {
  if (theme === 'dark') {
    document.documentElement.classList.add("dark");
    navbarSunIcon.classList.add("opacity-0");
    navbarMoonIcon.classList.remove("opacity-0");
    logoLight.classList.remove("hidden");
    logoDark.classList.add("hidden");
  } else {
    document.documentElement.classList.remove("dark");
    navbarSunIcon.classList.remove("opacity-0");
    navbarMoonIcon.classList.add("opacity-0");
    logoLight.classList.add("hidden");
    logoDark.classList.remove("hidden");
  }
}

// Smart scroll effect with proper color management
window.addEventListener("scroll", () => {
  const scrolled = window.scrollY > 40;

  if (scrolled) {
    navbar.classList.add("border-gray-200");
    navbar.classList.remove("border-transparent");

    navLinks.forEach(link => {
      link.classList.add("lg:text-black", "lg:hover:text-blue-600");
    });
  } else {
    navbar.classList.add("border-transparent");
    navbar.classList.remove("shadow-lg", "border-gray-200");
  }
});

// Mobile Menu Toggle with Animation
if (mobileMenuButton && mobileMenu) {
  mobileMenuButton.addEventListener("click", () => {
    isMenuOpen = !isMenuOpen;

    if (isMenuOpen) {
      mobileMenu.classList.remove("hidden");
      mobileMenu.classList.add("block");
      mobileMenuButton.setAttribute("aria-expanded", "true");
      mobileMenuButton.querySelector("svg").style.transform = "rotate(90deg)";
    } else {
      mobileMenu.classList.add("hidden");
      mobileMenu.classList.remove("block");
      mobileMenuButton.setAttribute("aria-expanded", "false");
      mobileMenuButton.querySelector("svg").style.transform = "rotate(0deg)";
    }
  });

  document.addEventListener("click", (e) => {
    if (isMenuOpen && !navbar.contains(e.target)) {
      isMenuOpen = false;
      mobileMenu.classList.add("hidden");
      mobileMenu.classList.remove("block");
      mobileMenuButton.setAttribute("aria-expanded", "false");
      mobileMenuButton.querySelector("svg").style.transform = "rotate(0deg)";
    }
  });

  const mobileLinks = mobileMenu.querySelectorAll("a");
  mobileLinks.forEach(link => {
    link.addEventListener("click", () => {
      if (window.innerWidth < 992) { // lg breakpoint in Tailwind is 1024px, 992px is a custom value
        isMenuOpen = false;
        mobileMenu.classList.add("hidden");
        mobileMenu.classList.remove("block");
        mobileMenuButton.setAttribute("aria-expanded", "false");
        mobileMenuButton.querySelector("svg").style.transform = "rotate(0deg)";
      }
    });
  });
}

// Smooth scroll for anchors
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
  anchor.addEventListener('click', function (e) {
    e.preventDefault();
    const target = document.querySelector(this.getAttribute('href'));
    if (target) {
      target.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
  });
});

// Navbar Theme Toggle
if (navbarThemeToggle) {
  navbarThemeToggle.addEventListener('click', function() {
    const currentTheme = localStorage.getItem('theme') || 'light';
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    localStorage.setItem('theme', newTheme);
    updateThemeUI(newTheme);
  });

  // Initialize based on saved theme
  document.addEventListener('DOMContentLoaded', function() {
    const savedTheme = localStorage.getItem('theme') || 'light';
    updateThemeUI(savedTheme);
    window.dispatchEvent(new Event('scroll'));
  });
}
</script>