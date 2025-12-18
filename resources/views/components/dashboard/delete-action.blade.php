@props([
    'route',
    'message' => 'Are you sure you want to delete this item?',
    'id' => 'delete-modal-' . uniqid() // ID فريد للمودال لتجنب التكرار
])

{{-- Trigger Button --}}
<button type="button" data-modal-target="{{ $id }}" data-modal-toggle="{{ $id }}"
    class="inline-flex items-center p-1 text-sm font-medium text-center text-gray-500 rounded-lg hover:text-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 focus:outline-none dark:text-gray-400 dark:hover:text-gray-100">
    <x-heroicon-s-trash class="w-4 h-4" />
</button>

{{-- Modal --}}
<div id="{{ $id }}" tabindex="-1" class="hidden fixed z-50 inset-0 overflow-y-auto overflow-x-hidden justify-center items-center w-full h-full">
    <div class="relative p-4 w-full max-w-md">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">

            {{-- Close Button --}}
            <button type="button" data-modal-hide="{{ $id }}"
                class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                <svg class="w-3 h-3" fill="none" viewBox="0 0 14 14" xmlns="http://www.w3.org/2000/svg">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M1 1l12 12M13 1L1 13" />
                </svg>
                <span class="sr-only">Close modal</span>
            </button>

            {{-- Content --}}
            <div class="p-5 text-center">
                <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" fill="none"
                    viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 11V6m0 8h.01M19 10a9 9 0 11-18 0 9 9 0 0118 0Z" />
                </svg>
                <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">{{ $message }}</h3>

                {{-- Delete Form --}}
                <form action="{{ $route }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none
                        focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center
                        px-5 py-2.5 text-center" data-modal-hide="{{ $id }}">
                        Yes, I'm sure
                    </button>
                </form>

                <button type="button" data-modal-hide="{{ $id }}"
                    class="ml-3 py-2.5 px-5 text-sm font-medium text-gray-900 bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:outline-none focus:ring-4 focus:ring-gray-100 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700">
                    No, cancel
                </button>

            </div>

        </div>
    </div>
</div>
