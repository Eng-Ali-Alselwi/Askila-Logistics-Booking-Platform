<div>
    <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100">
        {{ t('Your profile Information') }}
    </h4>

    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
        {{ t('Update your account\'s profile information and email address.') }}
    </p>

    <form class="mt-6 col-span-4 w-full xl:w-3/4" >

        <x-inputs.form-input
        type="text" id="name" label="{{ t('Name') }}"
        value="{{ Auth::user()->name }}"
        placeholder="Enter your name"
        required
        />

        <x-inputs.form-input type="email" id="phone" label="{{ t('Phone') }}"
        value="{{ Auth::user()->phone }}"
        placeholder="{{ t('Enter your phone number') }}"
        />

        <x-inputs.form-input type="email" id="email" label="Email"
        placeholder="{{ t('Enter your email') }}"
        value="{{ Auth::user()->email }}"
        required
        />

        @if (Auth::user()->email_verified_at === null)
            <p class="text-sm mt-2">
                {{ t('Your email address is unverified.') }}

                <button type="button"
                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    wire:click.prevent="sendEmailVerification">
                    {{ t('Click here to re-send the verification email.') }}
                </button>
            </p>

            {{-- @if ($this->verificationLinkSent) --}}
                <p class="mt-2 font-medium text-sm text-green-600">
                    {{ t('A new verification link has been sent to your email address.') }}
                </p>
            {{-- @endif --}}
        @endif

        <x-inputs.button-primary class="mt-4">
            {{ t('Change') }}
        </x-inputs.button-primary>
    </form>
</div>
