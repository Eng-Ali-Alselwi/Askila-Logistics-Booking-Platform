@extends('dashboard.layout.guest', ['title' => 'Account Inactive'])

@section('content')
    <div class="flex flex-col items-center justify-center px-6 pt-8 mx-auto md:h-screen dark:bg-gray-900">
        <!-- Card -->
        <div class="w-full border-t-4 border-t-red-500 max-w-xl bg-white rounded-lg shadow-md dark:bg-gray-800">

            <div class="border-b px-4 py-6">
                <a href="/"
                   class="flex items-center justify-center md:text-xl xl:text-2xl font-semibold dark:text-white">
                    <img src="{{ asset('assets/images/logo/dark.png') }}" class="mr-3 h-9 xl:h-11" alt="Logo">
                </a>
            </div>

            <div class="px-6 pt-6 xl:pb-3 space-y-6 sm:px-8 text-center">
                <h3 class="text-lg xl:text-xl font-medium text-red-600 dark:text-red-400">
                    Your Account is Inactive
                </h3>

                <p class="text-sm text-gray-600 dark:text-gray-300">
                    Your account has been deactivated or is pending approval.<br>
                    Please contact support if you believe this is a mistake.
                </p>

                @if (session('message'))
                    <div class="text-sm text-green-600 dark:text-green-400 font-medium">
                        {{ session('message') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-inputs.button-secondary class="w-full mt-4">
                        <x-heroicon-s-arrow-left-start-on-rectangle class="w-5 h-5 inline mr-2" />
                        Log Out
                    </x-inputs.button-secondary>
                </form>

                <div class="flex justify-center text-sm text-gray-500 mt-6">
                    &copy; {{ now()->year }} <a class="ml-2 text-primary-500" href="#">AB Company plc</a>.
                </div>

            </div>

        </div>

    </div>
@endsection
