<button type="button" data-dropdown-toggle="language-dropdown"
    class="inline-flex justify-center p-2 text-gray-500 rounded cursor-pointer dark:hover:text-white dark:text-gray-400 hover:text-gray-900 hover:bg-gray-100 dark:hover:bg-gray-600">
    <x-icons icon="{{ app()->getLocale() }}" class="h-5 w-5 mt-0.5" />
</button>
<!-- Dropdown -->
<div class="hidden z-50 my-4 text-base list-none bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700"
    id="language-dropdown">
    <ul class="py-1" role="none">

        <li>
            <a href="{{ route('lang.switch', 'en') }}"
                class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:hover:text-white dark:text-gray-300 dark:hover:bg-gray-600"
                role="menuitem">
                <div class="inline-flex items-center">
                    <x-icons icon="en" class="h-3.5 w-3.5 me-2" />
                    English (US)
                </div>
            </a>
        </li>

        <li>
            <a href="{{ route('lang.switch', 'ar') }}"
                class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:text-white dark:hover:bg-gray-600"
                role="menuitem">
                <div class="inline-flex items-center">
                    <x-icons icon="ar" class="h-3.5 w-3.5 me-2" />
                    Arabic (SD)
                </div>
            </a>
        </li>
    </ul>

</div>
