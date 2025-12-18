@extends('dashboard.layout.guest', ['title' => 'Forgot Password'])

@section('content')
    <x-auth.auth-card>
        <h3 class="text-lg xl:text-xl text-center font-medium text-gray-900 dark:text-white">
            Forgot your password?
        </h3>

        <p class="text-sm text-gray-600 dark:text-gray-300">
            Enter your email and we'll send you a link to reset your password.
        </p>

        @if (session('status'))
            <div class="mt-4 font-medium text-sm text-green-600 dark:text-green-400">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <x-inputs.form-input type="email" id="email" label="Email" :value="old('email')"
                placeholder="Enter your email" required autofocus />

            <x-inputs.button-primary class="w-full mt-6 ">
               <x-heroicon-o-envelope class="w-6 h-6 mr-2 inline" /> </i> Send Reset Link
            </x-inputs.button-primary>
        </form>

        <div class="mt-4 flex justify-center">
            <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                Back to login
            </a>
        </div>
    </x-auth.auth-card>
@endsection
