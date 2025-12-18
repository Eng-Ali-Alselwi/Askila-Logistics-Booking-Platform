<div class=" mx-auto">
    {{-- رأس الصفحة --}}


    {{-- فلاش نجاح (يظهر عند الرجوع بالجلسة، احتياطيًا هنا أيضاً) --}}
    @if (session('success'))
        <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-emerald-800
                    dark:border-emerald-900/60 dark:bg-emerald-900/30 dark:text-emerald-200">
            {{ session('success') }}
        </div>
    @endif

    {{-- البطاقة --}}
    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800 relative">

        {{-- Overlay تحميل عند الحفظ --}}
        <div wire:loading.flex wire:target="save"
             class="absolute inset-0 bg-white/60 dark:bg-gray-900/60 backdrop-blur-[1px] z-10 items-center justify-center">
            <svg class="h-6 w-6 animate-spin text-gray-600 dark:text-gray-300" viewBox="0 0 24 24" fill="none">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4A4 4 0 004 12z"/>
            </svg>
        </div>

        <form wire:submit.prevent="save" class="p-5 space-y-4">
            <div class="grid md:grid-cols-2 gap-4">
                <x-dashboard.form.input-field
                    name="form.name"
                    label="{{ t('Name') }}"
                    type="text"
                    wire:model.defer="form.name"
                />
                {{-- <div>
                    <label class="block mb-1 text-sm font-medium">الاسم</label>
                    <input type="text"
                           class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700
                                  bg-white dark:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                           wire:model.defer="form.name">
                    @error('form.name') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div> --}}
                <x-dashboard.form.input-field
                    name="form.email"
                    label="{{ t('Email') }}"
                    type="email"
                    wire:model.defer="form.email"
                />

                {{-- <div>
                    <label class="block mb-1 text-sm font-medium">الإيميل</label>
                    <input type="email"
                           class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700
                                  bg-white dark:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                           wire:model.defer="form.email">
                    @error('form.email') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div> --}}

                <x-dashboard.form.input-field
                    name="form.phone"
                    label="{{ t('Phone').' ('.t('Optional').')' }}"
                    type="text"
                    wire:model.defer="form.phone"
                />

                <div>
                    <label class="block mb-2 text-sm font-medium">{{ t('Role') }}</label>
                    <select
                        class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700
                               bg-white dark:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-primary-500"
                        wire:model.defer="form.role">
                        @foreach($roles as $r)
                            <option value="{{ $r }}">{{ t($r) }}</option>
                        @endforeach
                    </select>
                    @error('form.role') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2 flex items-center gap-2">
                    <input id="is_active" type="checkbox"
                           class="w-4 h-4 rounded-sm border-gray-300 focus:ring-2 focus:ring-emerald-300"
                           wire:model.defer="form.is_active">
                    <label for="is_active" class="text-sm">{{ t('Activate the account directly') }}</label>
                </div>
            </div>

            <div class="pt-2 flex items-center justify-end gap-2">
                <x-inputs.button-secondary as="a" :href="route('dashboard.users.index')">
                    {{ t('Cancel') }}
                </x-inputs.button-secondary>
                <x-inputs.button-primary type="submit"  wire:loading.attr="disabled">
                    {{ t('Save') }}
                    <span wire:loading wire:target="save" class="ms-2 inline-block animate-spin">⏳</span>
                </x-inputs.button-primary>

            </div>
        </form>
    </div>
</div>
