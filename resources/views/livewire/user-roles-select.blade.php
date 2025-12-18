<div class="relative" x-data>
    {{-- @if(!$isMainSuperAdmin) --}}
        <select
            multiple
            class="w-56 min-h-10 rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
            wire:model.defer="selected"
            title="اختر الأدوار"
            wire:change.debounce.250ms="save"
            @if($isMainSuperAdmin) disabled @endif
        >
            @foreach($allRoles as $roleName)
                <option value="{{ $roleName }}">{{ $roleName }}</option>
            @endforeach
        </select>

        {{-- حالة تحميل مصغرة --}}
        <div class="absolute -top-2 -left-2" wire:loading>
            <span class="animate-pulse text-xs text-gray-500 dark:text-gray-400">حفظ…</span>
        </div>
    {{-- @endif --}}

    {{-- عرض مختصر للأدوار الحالية كبادجات (اختياري) --}}
    <div class="mt-1 flex flex-wrap gap-1">
        @foreach($selected as $role)
            {{-- @php
                $roleClasses = [
                    'super_admin' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                    'manager'     => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
                    'updater'     => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-300',
                    'sender'      => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300',
                ];
                $badge = $roleClasses[$role] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300';
            @endphp --}}
            @php
                $roleClasses = [
                    'super_admin'=>'green',
                    'manager'=>'default',
                    'updater'=>'yellow',
                    'sender'=>'dark',
                ];
            @endphp
            <x-badge label="{{ $role }}" type="{{  $roleClasses[$role]??'dark'}}"/>
            {{-- <span class="inline-flex items-center text-[11px] font-medium px-2 py-0.5 rounded {{ $badge }}">
                {{ $role }}
            </span> --}}

        @endforeach
    </div>
</div>
