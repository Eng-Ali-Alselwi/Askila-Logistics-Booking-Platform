<div>
    <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100">
        {{ t('Your profile Information') }}
    </h4>
    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
        {{ t('Update your account\'s profile information and email address.') }}
    </p>

    {{-- صورة البروفايل + رفع --}}
    <div class="mt-6 flex items-center gap-4">
        <img src="{{ auth()->user()->image_url }}"
             class="w-16 h-16 rounded-full ring-2 ring-secondary-300 dark:ring-gray-300 object-cover"
             alt="Avatar">

        {{-- زر اختيار صورة + حفظ --}}
        <div class="flex items-center gap-2">
            <input type="file" class="hidden" x-ref="photo"
                   accept="image/png,image/jpeg,image/webp"
                   wire:model="photo">

            <x-inputs.button-secondary type="button" x-on:click="$refs.photo.click()" wire:loading.attr="disabled"
                class="disabled:opacity-50 text-sm font-normal">
                {{ t('Choose Photo') }}
            </x-inputs.button-secondary>

            <x-inputs.button-primary type="button" wire:click="savePhoto" wire:loading.attr="disabled"
                class="disabled:opacity-50 text-sm py-1 font-normal" >
                {{ t('Save Photo') }}
            </x-inputs.button-primary>

            <x-inputs.button-danger type="button" wire:click="removePhoto" wire:loading.attr="disabled"
            class="disabled:opacity-50 text-sm" >
                {{ t('Remove') }}
            </x-inputs.button-danger>
        </div>
    </div>

    {{-- معاينة مؤقتة --}}
    @if ($photo)
        <div class="mt-3">
            <p class="text-xs text-gray-500">{{ t('Preview') }}</p>
            <img src="{{ $photo->temporaryUrl() }}"
             class="w-16 h-16 rounded-full ring-2 ring-primary-300 object-cover"
             alt="Preview">
        </div>
    @endif

    {{-- أخطاء رفع الصورة --}}
    @error('photo')
        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
    @enderror

    {{-- نموذج البيانات الأساسية --}}
    <form class="mt-6 col-span-4 w-full xl:w-3/4" wire:submit.prevent="saveProfile">
        <x-inputs.form-input
            type="text" id="name" label="{{ t('Name') }}"
            placeholder="Enter your name" required
            wire:model.defer="name"  />

        <x-inputs.form-input
            type="text" id="phone" label="{{ t('Phone') }}"
            placeholder="{{ t('Enter your phone number') }}"
            wire:model.defer="phone" />

        <x-inputs.form-input
            type="email" id="email" label="Email"
            placeholder="{{ t('Enter your email') }}" required
            wire:model.defer="email" />

        @if (Auth::user()->email_verified_at === null)
            <p class="text-sm mt-2">
                {{ t('Your email address is unverified.') }}
                <button type="button"
                        class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        wire:click="sendEmailVerification">
                    {{ t('Click here to re-send the verification email.') }}
                </button>
            </p>
        @endif

        <x-inputs.button-primary type="submit" class="mt-4 disabled:bg-primary-500/50" wire:loading.attr="disabled">
            {{ t('Change') }}
        </x-inputs.button-primary>
    </form>
</div>
