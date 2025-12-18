<div>
    <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100">
        {{ t('Change Your Passsword') }}
    </h4>

    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
        {{ t('Ensure your account is using a long, random password to stay secure.') }}
    </p>

    <form class="mt-6 col-span-4 w-full xl:w-3/4" wire:submit.prevent="updatePassword">
        <x-inputs.form-input
            type="password" id="current_password" label="{{ t('Current Password') }}"
            placeholder="{{ t('Enter current password') }}" required
            wire:model.defer="current_password" />

        <x-inputs.form-input
            type="password" id="password" label="{{ t('New Password') }}"
            placeholder="{{ t('Enter new password') }}" required
            wire:model.defer="password" />

        <x-inputs.form-input
            type="password" id="password_confirmation" label="{{ t('Confirm Password') }}"
            placeholder="{{ t('Confirm new password') }}" required
            wire:model.defer="password_confirmation" />

        <x-inputs.button-primary class="w-auto mt-4 disabled:bg-primary-500/50" wire:loading.attr="disabled">
            {{ t('Change') }}
        </x-inputs.button-primary>


    </form>
</div>
