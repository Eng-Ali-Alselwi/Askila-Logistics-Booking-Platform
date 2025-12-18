@include('dashboard.layout.shared/main')

<head>
    @include('dashboard.layout.shared/title-meta', ['title' => $title])
    @yield('css')
    @include('dashboard.layout.shared/head-css')
    <script src="{{ asset('plugins/toast/toast.js') }}"></script>
    @livewireStyles
    {{-- Sweetalert To use it also run 'npm install sweetalert2' --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body class="anantialiased dark:bg-gray-900 dark:text-white bg-[#ECF2FE] text-gray-800">
    <!-- Loader HTML -->
    <div id="global-loader" class="hidden fixed inset-0 bg-white/80 flex justify-center items-center z-50">
        <div class="w-16 h-16 border-4 border-gray-300 border-t-blue-500 rounded-full animate-spin"></div>
    </div>

    <div class="wrapper">

        @include('dashboard.layout.shared/sidenav')

        <div class="page-content md:ms-64">

            @include('dashboard.layout.shared/topbar')

            <main class=" relative mx-auto max-w-screen-2xl p-4 md:p-10 2xl:p-12">
                <!-- Start Content-->
                @yield('content')
            </main>

            @include('dashboard.layout.shared/footer')

        </div>

    </div>

    </div>

    @include('dashboard.layout.shared/footer-scripts')

    @yield('script')
    @livewireScripts


    {{-- <script src="{{ asset('plugins/toast/toast.js') }}"></script> --}}
    @include('dashboard.layout.shared.notify')




    <script>
        // لإظهار Loader
        function showLoader() {
            document.getElementById('global-loader').classList.remove('hidden');
        }

        // لإخفاء Loader
        function hideLoader() {
            document.getElementById('global-loader').classList.add('hidden');
        }

        // يُفضل إظهار Loader عند بدء تحميل الصفحة وإخفاؤه بعد اكتمالها
        document.addEventListener("DOMContentLoaded", function() {
            showLoader();
        });

        window.addEventListener("load", function() {
            hideLoader();
        });
    </script>
    {{-- <x-dashboard.confirm /> --}}
</body>

</html>
