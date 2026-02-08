<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    <!-- Preferred theme color -->
    <meta name="theme-color" content="#0a3e91">
    @yield('head')

    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/images/logo/dark.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css','resources/js/app.js'])
    @stack('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- RTL Support CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/rtl-support.css') }}">
    
    <style>
        /* Toast Notification Styles */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 999999;
            max-width: 400px;
        }
        
        .toast {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            margin-bottom: 10px;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            transform: translateX(100%);
            transition: transform 0.3s ease-in-out;
            border-left: 4px solid #10b981;
        }
        
        .toast.show {
            transform: translateX(0);
        }
        
        .toast.error {
            border-left-color: #ef4444;
        }
        
        .toast.success {
            border-left-color: #10b981;
        }
        
        .toast-icon {
            margin-left: 12px;
            font-size: 20px;
        }
        
        .toast.success .toast-icon {
            color: #10b981;
        }
        
        .toast.error .toast-icon {
            color: #ef4444;
        }
        
        .toast-content {
            flex: 1;
        }
        
        .toast-title {
            font-weight: 600;
            margin-bottom: 4px;
            color: #1f2937;
        }
        
        .toast-message {
            color: #6b7280;
            font-size: 14px;
        }
        
        .toast-close {
            background: none;
            border: none;
            color: #9ca3af;
            cursor: pointer;
            font-size: 18px;
            margin-right: 8px;
            padding: 4px;
        }
        
        .toast-close:hover {
            color: #6b7280;
        }
        
        /* Additional RTL Support */
        [dir="rtl"] {
            font-family: 'IBM Plex Sans Arabic', 'Arial', sans-serif;
        }
        
        [dir="rtl"] .rtl\:text-right {
            text-align: right !important;
        }
        
        [dir="rtl"] .rtl\:text-left {
            text-align: left !important;
        }
        
        [dir="rtl"] input, [dir="rtl"] textarea, [dir="rtl"] select {
            text-align: right;
            direction: rtl;
        }
        
        [dir="rtl"] .rtl\:mr-2 {
            margin-right: 0.5rem;
            margin-left: 0;
        }
        
        [dir="rtl"] .rtl\:ml-2 {
            margin-left: 0.5rem;
            margin-right: 0;
        }
    </style>
</head>

<body class="relative font-inter font-normal text-base leading-[1.8] bg-bodyBg dark:bg-bodyBg-dark">

  <!-- theme controller -->
  @include('front.includes.theme_controller')

  <!-- Toast Container -->
  <div id="toast-container" class="toast-container"></div>

  <!-- scroll up button -->
  <div>
    <button
      class="scroll-up w-[50px] h-[50px] leading-[50px] text-center text-primary-500 bg-white hover:text-white
      hover:bg-primary-500 rounded-full fixed end-5 bottom-[60px] shadow shadow-primary-400 z-[9999] text-xl
      dark:text-white dark:bg-gray-950 hidden transition-all duration-300"
    >
    <span class="flex justify-center items-center">
        <x-heroicon-o-chevron-up class="w-8 font-bold"/>
    </span>

    </button>
  </div>

    @include('front.includes.header2')
    @include('front.includes.marketing-strip')
  {{-- <header class=" fixed dark:bg-lightGrey10-dark top-0 inset-x-0 z-[999999999] shadow-header h-[55px] lg:h-[73px]">
    @include('front.includes.header')
  </header> --}}
  <!-- main body -->
  {{-- mt-[55px] lg:mt-[73px] --}}
  <main class="bg-transparent ">


    @yield('content')
    @include('front.includes.footer')
    @include('front.includes.footer-scripts')

  </main>

  {{-- @vite(entrypoints: 'resources/js/app.js') --}}

  @stack('scripts')
  {{-- <script src="{{asset('assets/js/swiper-bundle.min.js')}}"></script> --}}
  {{-- <script src="{{asset('assets/js/theme.js')}}"></script> --}}
  {{-- <script src="{{asset('assets/js/navbar.js')}}"></script> --}}
  <script src="{{asset('assets/js/aos.js')}}"></script>
  {{-- <script src="{{asset('assets/js/slider.js')}}"></script> --}}
  <script  src="{{ asset('assets/js/vanilla-tilt.min.js') }}"></script>

  <script src="{{ asset('assets/js/scrollUp.js') }}"></script>
  {{-- <script src="{{ asset('assets/js/tabs.js') }}"></script>
  <script src="{{ asset('assets/js/select.js') }}"></script>
  <script  src="{{ asset('assets/js/ajax-form.js') }}"></script> --}}

  <script src="{{asset('assets/js/main.js')}}"></script>
  <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
  <script src="{{ asset('assets/js/custom.js') }}"></script>
  
  <!-- Toast Notification Script -->
  <script>
    class ToastNotification {
        constructor() {
            this.container = document.getElementById('toast-container');
        }

        show(message, type = 'success', duration = 8000) {
            const toast = this.createToast(message, type);
            this.container.appendChild(toast);

            // إظهار الرسالة
            setTimeout(() => {
                toast.classList.add('show');
            }, 100);

            // إخفاء الرسالة تلقائياً
            setTimeout(() => {
                this.hide(toast);
            }, duration);
        }

        createToast(message, type) {
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            
            const icon = type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle';
            
            toast.innerHTML = `
                <i class="toast-icon ${icon}"></i>
                <div class="toast-content">
                    <div class="toast-message">${message}</div>
                </div>
                <button class="toast-close" onclick="toastNotification.hide(this.parentElement)">
                    <i class="fas fa-times"></i>
                </button>
            `;

            return toast;
        }

        hide(toast) {
            toast.classList.remove('show');
            setTimeout(() => {
                if (toast.parentElement) {
                    toast.parentElement.removeChild(toast);
                }
            }, 300);
        }
    }

    // إنشاء instance عام للـ toast
    const toastNotification = new ToastNotification();

    // عرض الرسائل من session إذا كانت موجودة
    @if(session('success'))
        toastNotification.show('{{ session('success') }}', 'success');
    @endif

    @if(session('error'))
        toastNotification.show('{{ session('error') }}', 'error');
    @endif

    @if(session('warning'))
        toastNotification.show('{{ session('warning') }}', 'warning');
    @endif

    @if(session('info'))
        toastNotification.show('{{ session('info') }}', 'info');
    @endif
  </script>
</body>

</html>
