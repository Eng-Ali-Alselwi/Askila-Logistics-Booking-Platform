<div>
    <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100">
        {{ t('Manage Your Sessions') }}
    </h4>

    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
        {{ t('If necessary, you may log out of all of your other browser sessions across all of your devices. Some of your recent sessions are listed below; however, this list may not be exhaustive. If you feel your account has been compromised, you should also update your password.') }}
    </p>

    <div class=" align-items-center mt-4">
        <x-inputs.button-danger data-modal-target="authentication-modal" data-modal-toggle="authentication-modal"
        class="flex gap-3 space-x-2 align-items-center justify-center">

            {{ t('Log Out Other Browser Sessions') }}
            <x-heroicon-o-arrow-up-right class="h-4 w-4 mt-1"/>
        </x-inputs.button-danger>
    </div>


    <!-- Main modal -->
    <div id="authentication-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        {{ t('Sign in to our platform') }}
                    </h3>
                    <button type="button" class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="authentication-modal">
                        <x-heroicon-o-x-mark class="w-6 h-6" aria-hidden="true" />
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-4 md:p-5">
                    <form class="space-y-6" action="#">
                        <x-inputs.form-input type="password"  id="password" label="Enter your password"
                            placeholder="••••••••" required />

                        {{-- <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Login to your account</button> --}}
                        <div class="flex items-center  gap-2">
                            <x-inputs.button-outlined data-modal-hide="authentication-modal" class="text-sm !py-1.5">
                                {{ t('Cancel') }}
                            </x-inputs.button-outlined>

                            <x-inputs.button-danger class="ml-3 " >
                                {{ t('Log Out Other Browser Sessions') }}
                            </x-inputs.button-danger>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
