@extends('dashboard.layout.guest', ['title' => 'Reset Password'])

@section('content')
    <x-auth.auth-card>
        <h3 class="text-lg xl:text-xl text-center font-medium text-gray-900 dark:text-white">
            Reset your password
        </h3>
        <p class="text-sm text-gray-600 dark:text-gray-300">
            Enter your email and we'll send you a link to reset your password.
        </p>

        @if ($errors->any())
            <div class="mt-4 text-sm text-red-600 dark:text-red-400">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}" class="mt-6 space-y-5">
            @csrf

            <!-- Hidden Token -->
            <input type="hidden" name="token" value="{{ $token }}">

            <!-- Email -->
            <x-inputs.form-input
                type="email"
                id="email"
                name="email"
                label="Email"
                :value="old('email', request()->email)"
                placeholder="Enter your email"
                required
                readonly
                class="bg-gray-100 cursor-not-allowed"
            />

            <!-- Password -->
            <x-inputs.form-input
                type="password"
                id="password"
                name="password"
                label="New Password"
                placeholder="Enter new password"
                autofocus
                required
            />

            <!-- Confirm Password -->
            <x-inputs.form-input
                type="password"
                id="password_confirmation"
                name="password_confirmation"
                label="Confirm Password"
                placeholder="Confirm your password"
                required
            />

            <x-inputs.button-primary class="w-full">
                <x-heroicon-s-lock-closed class="w-5 h-5 inline mr-2" />
                Reset Password
            </x-inputs.button-primary>
        </form>

        <div class="mt-4 flex justify-center">
            <a href="{{ route('login') }}" class="underline text-sm text-gray-600 hover:text-gray-800">
                Back to login
            </a>
        </div>
    </x-auth.auth-card>
@endsection
