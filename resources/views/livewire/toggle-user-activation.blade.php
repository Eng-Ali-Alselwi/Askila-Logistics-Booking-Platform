<div>

    <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
        <div class="flex items-center">
            <div class="w-3 h-3 me-2 {{ $is_active ? 'bg-green-500' : 'bg-red-500' }}  rounded-full">
            </div>
            {{ $is_active ? 'Active' : 'Inactive' }}
        </div>
    </td>

    <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
        <label class="relative inline-flex items-center cursor-pointer">
            <input type="checkbox"
            wire:model.live="is_active" {{ $is_active ? 'checked' : '' }}
                    @disabled($user->isSuperAdmin())
                 class="sr-only peer" name="promote">
            <div class="w-11 h-6 {{ $user->getRoleNames()[0] == 'super_admin' ? 'cursor-not-allowed' : '' }} bg-gray-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-primary-300 dark:peer-focus:ring-primary-800 dark:bg-gray-600 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary-600">
            </div>
        </label>
    </td>

</div>
