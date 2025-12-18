<div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800 relative">

    {{-- Overlay تحميل شامل للفلاتر والإجراءات --}}
    <div
        wire:loading.flex
        wire:target="search,role,status,perPage,resetFilters,clearRole,clearStatus,clearSearch,changeRole,toggle,delete"
        class="absolute inset-0 bg-white/60 dark:bg-gray-900/60 backdrop-blur-[1px] z-10 items-center justify-center">
        <svg class="h-6 w-6 animate-spin text-gray-600 dark:text-gray-300" viewBox="0 0 24 24" fill="none">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4A4 4 0 004 12z"/>
        </svg>
    </div>


    {{-- ================== Filters Toolbar ================== --}}
    <div class="p-4 sm:p-5 border-b border-gray-200 dark:border-gray-800">
        <div class="flex flex-col gap-4">

            {{-- صف: البحث + عدد العناصر --}}
            <div class="flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
                <div class="relative w-full sm:w-96">
                    <input
                        type="text"
                        placeholder="{{ t('Search by name/email/mobile') }}"
                        class="w-full ps-4 px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-primary-500"
                        wire:model.live.debounce.500ms="search"
                    />
                    @if($search)
                        <button type="button" wire:click="clearSearch"
                                class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-800 dark:hover:text-gray-200">&times;</button>
                    @endif
                </div>

                <div class="flex items-center gap-2">
                    <label class="text-xs text-gray-500">{{ t('Per page') }}</label>
                    <select
                        class="px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 focus:outline-none
                        text-gray-600 dark:text-gray-300 text-base"
                        wire:model.change="perPage"
                        title="{{ t('Number of items per page') }}">
                        <option value="10">10</option>
                        <option value="12">12</option>
                        <option value="15">15</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                </div>
            </div>

            {{-- صف: فلاتر الدور والحالة + زر مسح الكل --}}
            <div class="flex flex-col md:flex-row gap-3 md:items-center md:justify-between">
                <div class="flex flex-wrap items-center gap-2">
                    <select wire:model.live="role"
                            class="px-3 py-2 rounded-lg border text-base border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 focus:outline-none
                            text-gray-600 dark:text-gray-300 min-w-40">
                        <option value="">{{ t('All roles') }}</option>
                        @foreach($roles as $r)
                            <option value="{{ $r }}">{{ $r }}</option>
                        @endforeach
                    </select>

                    <select wire:model.live="status"
                            class="px-3 py-2 rounded-lg border text-base min-w-40
                             border-gray-300 dark:border-gray-700 bg-white
                             dark:bg-gray-800 focus:outline-none text-gray-600 dark:text-gray-300">
                        <option value="">{{ t('All statuses') }}</option>
                        <option value="active">{{ t('Active') }}</option>
                        <option value="inactive">{{ t('Inactive') }}</option>
                    </select>

                    <button wire:click="resetFilters"
                            class="px-2.5 py-1.5 rounded-md text-xs border border-gray-300 dark:border-gray-700
                            hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-600 dark:text-gray-300">
                        {{ t('Reset filters') }}
                    </button>
                </div>

                {{-- شِبس للفلاتر النشطة --}}
                @php
                    $chips = [];
                    if ($role)   { $chips[] = ['label' => t('Role').': '.$role, 'action' => 'clearRole']; }
                    if ($status) { $chips[] = ['label' => t('Status').': '.($status==='active'?t('Active'):t('Inactive')), 'action' => 'clearStatus']; }
                    if ($search) { $chips[] = ['label' => t('Search').': '.$search, 'action' => 'clearSearch']; }
                @endphp
                @if(count($chips))
                    <div class="flex flex-wrap items-center gap-2">
                        @foreach($chips as $chip)
                            <button type="button"
                                    class="inline-flex items-center gap-2 px-2.5 py-1.5 rounded-full text-xs border
                                     border-gray-300 dark:border-gray-700 ">
                                <span class="text-gray-600 dark:text-gray-400">{{ $chip['label'] }}</span>
                                <span wire:click="{{ $chip['action'] }}" class="text-gray-500 hover:text-gray-800 dark:hover:text-gray-200">&times;</span>
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>
    {{-- ================== /Filters Toolbar ================== --}}

    {{-- جدول المستخدمين --}}
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
            <thead class="bg-gray-50 dark:bg-gray-800/50">
                <tr>
                    <th class="px-4 py-3 text-start text-xs font-medium text-gray-500">{{ t('Name') }}</th>
                    <th class="px-4 py-3 text-start text-xs font-medium text-gray-500">{{ t('Email') }}</th>
                    <th class="px-4 py-3 text-start text-xs font-medium text-gray-500">{{ t('Phone') }}</th>
                    <th class="px-4 py-3 text-start text-xs font-medium text-gray-500">{{ t('Roles & Permissions') }}</th>
                    <th class="px-4 py-3 text-start text-xs font-medium text-gray-500">{{ t('Status') }}</th>
                    <th class="px-4 py-3 text-start text-xs font-medium text-gray-500">{{ t('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                @forelse ($users as $u)
                    @php
                        // نجمع صلاحيات الدور + صلاحيات المستخدم المباشرة بدون استدعاءات إضافية
                        $rolePerms  = $u->roles->flatMap->permissions->pluck('name');
                        $userPerms  = $u->permissions->pluck('name');
                        $perms      = $rolePerms->merge($userPerms)->unique()->values();

                        $permColors = [
                            'manage users'           => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-200',
                            'add shipment'           => 'bg-sky-100 text-sky-800 dark:bg-sky-900/40 dark:text-sky-200',
                            'update shipment status' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-200',
                            'delete shipment'        => 'bg-rose-100 text-rose-800 dark:bg-rose-900/40 dark:text-rose-200',
                        ];
                        $currentRole = $u->roles->first()->name ?? null;
                    @endphp

                    <tr class="hover:bg-gray-50/60 dark:hover:bg-gray-800/50">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                {{-- أفاتار بسيط (حرف أول) --}}
                                <div class="w-8 h-8 rounded-full bg-gray-200 overflow-hidden dark:bg-gray-700 grid place-items-center text-gray-700 dark:text-gray-200 text-xs font-semibold">
                                   <img src="{{ $u->image_url }}" alt="">
                                </div>

                                <div class="text-sm">
                                    <div class="text-gray-900 dark:text-gray-100 font-medium">{{ $u->name }}</div>
                                    @if($u->image)
                                        <div class="text-[11px] text-gray-500">صورة مرفوعة</div>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                            {{ $u->email }}
                        </td>

                        <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                            {{ $u->phone ?? '—' }}
                        </td>

                        <td class="px-4 py-3">
                            <div class="flex flex-col gap-2">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <select class="select select-sm select-bordered border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 rounded-lg min-w-40
                                    text-gray-600 dark:text-gray-300 text-sm"
                                            @disabled($u->hasRole('super_admin'))
                                            title="تغيير الدور"
                                            wire:change="changeRole({{ $u->id }}, $event.target.value)"
                                            wire:loading.attr="disabled"
                                            wire:target="changeRole">
                                        @foreach($roles as $r)
                                            <option value="{{ $r }}" @selected($currentRole === $r)>{{ t($r) }}</option>
                                        @endforeach
                                    </select>
                                    {{-- سبينر خفيف عند تغيير الدور --}}
                                    {{-- <span wire:loading.inline wire:target="changeRole" class="text-xs text-gray-500">جارٍ التحديث…</span> --}}
                                </div>

                                {{-- صلاحيات كبادجز --}}
                                <div class="flex flex-wrap gap-1.5">
                                    @forelse($perms as $p)
                                        @php $cls = $permColors[$p] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200'; @endphp
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] {{ $cls }}">
                                            {{ t($p) }}
                                        </span>
                                    @empty
                                        <span class="text-xs text-gray-500">—</span>
                                    @endforelse
                                </div>
                            </div>
                        </td>

                        <td class="px-4 py-3">
                            @if($u->is_active)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-200">{{ t('Active') }}</span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200 text-nowrap">{{ t('Inactive') }}</span>
                            @endif
                        </td>

                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <button class="px-2 py-1.5 rounded-md border text-xs
                                    border-gray-300 text-gray-700 hover:bg-gray-100 hover:dark:bg-gray-600 dark:border-gray-800 dark:text-gray-300"
                                        wire:click="toggle({{ $u->id }})"
                                        wire:loading.attr="disabled"
                                        wire:target="toggle"
                                        @disabled($u->hasRole('super_admin'))>
                                    {{ $u->is_active ? t('Deactivate') : t('Activate') }}
                                </button>

                                {{-- <button class="px-2 py-1.5 rounded-md border border-rose-300 text-rose-700 hover:bg-rose-50 hover:dark:bg-rose-800 dark:border-rose-800 dark:text-rose-300 text-xs"
                                        onclick="if(!confirm('تأكيد حذف المستخدم؟')) return false;"
                                        wire:click="delete({{ $u->id }})"
                                        wire:loading.attr="disabled"
                                        wire:target="delete"
                                        @disabled($u->hasRole('super_admin'))>
                                    {{ t('Delete') }}
                                </button> --}}
                                <button
                                    x-data
                                    @click="$store.confirm.ask(
                                        () => $wire.delete('{{ $u->id }}'),
                                        '{{ t('Confirm user deletion') }}',
                                        '{{ t('The user will be deleted. Are you sure?') }}'
                                    )"
                                    class="px-2 py-1.5 inline-flex gap-1 rounded-md border border-rose-300 text-rose-700 hover:bg-rose-50 hover:dark:bg-rose-800 dark:border-rose-800 dark:text-rose-300 text-xs"
                                    title="{{ t('Delete User') }}">
                                    <x-heroicon-s-trash class="w4 h-4"/>

                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-10 text-center text-gray-500">
                            {{ t('No results found for your search') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-4 border-t border-gray-200 dark:border-gray-800">
        {{ $users->onEachSide(1)->links() }}
    </div>

</div>
