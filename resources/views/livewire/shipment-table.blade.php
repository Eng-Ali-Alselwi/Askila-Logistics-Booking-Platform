<div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800 relative">

    {{-- <div class="relative"> --}}
        <div wire:loading.flex
             wire:target="setStatus,setDatePreset,from,to,q,perPage,clearStatus,clearDate,clearSearch,clearAll"
             class="absolute inset-0 bg-white/60 dark:bg-gray-900/60 backdrop-blur-[1px] z-10 items-center justify-center">
            <svg class="h-6 w-6 animate-spin text-gray-600 dark:text-gray-300" viewBox="0 0 24 24" fill="none">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4A4 4 0 004 12z"/>
            </svg>
        </div>
    {{-- </div> --}}


    {{-- ================== Filters Toolbar ================== --}}
    <div class="p-4 sm:p-5 border-b border-gray-200 dark:border-gray-800">
        <div class="flex flex-col gap-4">

            {{-- صف علوي: عنوان + سبينر + أزرار إلغاء --}}
            <div class="flex items-center justify-between gap-3">
                <div class="flex items-center gap-2">
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">{{ t('Filters') }}</h3>
                </div>

                {{-- أزرار إلغاء الفلاتر --}}
                <div class="flex flex-wrap items-center gap-2">
                    <button wire:click="clearStatus"
                            class="px-2.5 py-1.5 rounded-md text-xs border border-gray-300 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-800">
                        {{ t('Clear status') }}
                    </button>
                    <button wire:click="clearDate"
                            class="px-2.5 py-1.5 rounded-md text-xs border border-gray-300 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-800">
                        {{ t('Clear date') }}
                    </button>
                    <button wire:click="clearSearch"
                            class="px-2.5 py-1.5 rounded-md text-xs border border-gray-300 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-800">
                        {{ t('Clear search') }}
                    </button>
                    <button wire:click="clearAll"
                            class="px-2.5 py-1.5 rounded-md text-xs bg-gray-900 text-white dark:bg-gray-100 dark:text-gray-900 hover:opacity-90">
                        {{ t('Clear all') }}
                    </button>
                </div>
            </div>

            {{-- صف: البحث + عدد العناصر --}}
            <div class="flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
                <div class="relative w-full sm:w-96">
                    <input
                        type="text"
                        placeholder="{{ t('Search by shipment/sender/recipient number...') }}"
                        class="w-full pr-9 px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-primary-500"
                        wire:model.live.debounce.500ms="q"
                    />
                    {{-- زر × لمسح البحث يظهر عند وجود نص --}}
                    @if($q)
                        <button type="button" wire:click="clearSearch"
                                class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-800 dark:hover:text-gray-200">
                            &times;
                        </button>
                    @endif
                </div>

                <div class="flex items-center gap-2">
                    <label class="text-xs text-gray-500">{{ t('Per page') }}</label>
                    <select
                        class="px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 focus:outline-none"
                        wire:model.change="perPage"
                        title="{{ t('Number of items per page') }}"
                    >
                        <option value="10">10</option>
                        <option value="15">15</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                </div>

                @can('manage branches')
                <div class="flex items-center gap-2">
                    <label class="text-xs text-gray-500">{{ t('Branch') }}</label>
                    <select wire:model.change="branch_id"
                            class="px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 focus:outline-none">
                        <option value="">{{ t('All Branches') }}</option>
                        @foreach(\App\Models\Branch::orderBy('name')->get(['id','name']) as $b)
                            <option value="{{ $b->id }}">{{ $b->name }}</option>
                        @endforeach
                    </select>
                </div>
                @endcan
            </div>

            {{-- صف: فلاتر الحالة (بطاقات) — سحب أفقي على الجوال --}}
            <div class="overflow-x-auto -mx-1 px-1">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-3 min-w-[640px] sm:min-w-0">

                    {{-- الكل --}}
                    @php $active = is_null($status); @endphp
                    <button
                        wire:click="setStatus"
                        class="text-right rounded-xl border transition shadow-sm px-4 py-3
                            {{ $active ? 'border-emerald-500/50 ring-1 ring-emerald-500/30 bg-emerald-50 dark:bg-emerald-950/20' : 'border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800' }}">
                        <div class="flex items-center justify-between">
                            <div class="text-lg font-semibold text-gray-600">{{ $counts['all'] ?? 0 }}</div>
                            <x-dashboard.shipment-status-icon class="w-7 text-slate-500" status='all'/>
                        </div>
                        <div class="mt-1 flex items-center gap-2 text-xs text-gray-500">
                            <span class="w-2 h-2 rounded-full bg-slate-400"></span> {{ t("All Shipments") }}
                        </div>
                    </button>

                    {{-- الحالات المعرفة --}}
                    @foreach ($meta as $key => $m)
                        @php $active = $status === $key; @endphp
                        <button
                            wire:click="setStatus('{{ $key }}')"
                            class="text-right rounded-xl border transition shadow-sm px-4 py-3
                                {{ $active ? 'border-emerald-500/50 ring-1 ring-emerald-500/30 bg-emerald-50 dark:bg-emerald-950/20' : 'border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800' }}">
                            <div class="flex items-center justify-between">
                                <div class="text-lg font-semibold text-gray-600">{{ $counts[$key] ?? 0 }}</div>
                                <x-dashboard.shipment-status-icon class="w-5 {{ $m['txt-color'] }}" status='{{  $key  }}'/>
                            </div>
                            <div class="mt-1 flex items-center gap-2 text-xs text-gray-500">
                                <span class="w-2 h-2 rounded-full {{ $m['dot'] }}"></span>
                                {{ app()->getLocale() == 'ar' ? $m['label'] : t($m['label']) }}
                            </div>
                        </button>
                    @endforeach

                    {{-- بدون حالة --}}
                    @if (isset($counts['null']) && $counts['null'] > 0)
                        @php $active = $status === 'null'; @endphp
                        <button
                            wire:click="setStatus('null')"
                            class="text-right rounded-xl border transition shadow-sm px-4 py-3
                                {{ $active ? 'border-emerald-500/50 ring-1 ring-emerald-500/30 bg-emerald-50 dark:bg-emerald-950/20' : 'border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800' }}">
                            <div class="flex items-center justify-between">
                                <x-dashboard.shipment-status-icon class="w-5 bg-gray-300" status='null'/>
                                <div class="text-lg font-semibold text-gray-600">{{ $counts['null'] }}</div>
                            </div>
                            <div class="mt-1 flex items-center gap-2 text-xs text-gray-500">
                                <span class="w-2 h-2 rounded-full bg-gray-300"></span> {{ t('Without Status') }}
                            </div>
                        </button>
                    @endif

                </div>
            </div>

            {{-- صف: فلاتر التاريخ (Segmented) + نطاق مخصص --}}
            <div class="flex flex-col md:flex-row gap-3 md:items-center md:justify-between">
                <div class="flex flex-wrap gap-2">
                    @php
                        $presets = [
                            'all'         =>'All',
                            'today'       =>'Today',
                            'yesterday'   =>'Yesterday',
                            'last7'       =>'Last 7 Days',
                            'this_month'  =>'This Month',
                            'last_month'  =>'Last Month',
                            'this_year'   =>'This Year',
                            'custom'      =>'Custom Range',
                        ];
                    @endphp

                    @foreach ($presets as $key => $label)
                        @php $active = $datePreset === $key; @endphp
                        <button type="button"
                            wire:click="setDatePreset('{{ $key }}')"
                            class="px-3 py-1.5 rounded-lg border text-sm
                                {{ $active ? 'border-emerald-500 text-emerald-700 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-950/30' : 'border-gray-300 dark:border-gray-700 text-gray-600 dark:text-gray-400' }}">
                            {{ t($label) }}
                        </button>
                    @endforeach
                </div>

                @if ($datePreset === 'custom')
                    <div class="flex items-center gap-2">
                        <input type="date" wire:model.change="from"
                            class="px-2 py-1.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800">
                        <span class="text-sm text-gray-500">{{ t('To') }}</span>
                        <input type="date" wire:model.change="to"
                            class="px-2 py-1.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800">
                    </div>
                @endif
            </div>

            {{-- صف: الشيبس للفلتر النشط --}}
            @php
                $chips = [];
                if($status){ $chips[] = ['label' => t('Status') . ': ' . ($meta[$status]['label'] ?? $status), 'action' => 'clearStatus']; }
                if($datePreset && $datePreset !== 'all'){
                    $chips[] = ['label' => t('Date') . ': ' . t(match($datePreset){
                        'today'=>'Today','yesterday'=>'Yesterday','last7'=>'Last 7 Days',
                        'this_month'=>'This Month','last_month'=>'Last Month','this_year'=>'This Year','custom'=>'Custom'
                    }), 'action' => 'clearDate'];
                }
                if($datePreset === 'custom' && $from && $to){
                    $chips[] = ['label' => ($from . ' → ' . $to), 'action' => 'clearDate'];
                }
                if($q){ $chips[] = ['label' => t('Search') . ': ' . $q, 'action' => 'clearSearch']; }
            @endphp

            @if(count($chips))
                <div class="flex flex-wrap items-center gap-2">
                    @foreach($chips as $chip)
                        <button type="button" wire:click="{{ $chip['action'] }}"
                                class="inline-flex items-center gap-2 px-2.5 py-1.5 rounded-full text-xs border border-gray-300 dark:border-gray-700">
                            <span>{{ $chip['label'] }}</span>
                            <span class="text-gray-500 hover:text-gray-800 dark:hover:text-gray-200">&times;</span>
                        </button>
                    @endforeach
                    <button type="button" wire:click="clearAll"
                            class="inline-flex items-center gap-2 px-2.5 py-1.5 rounded-full text-xs bg-gray-900 text-white dark:bg-gray-100 dark:text-gray-900 hover:opacity-90">
                        {{ t('Clear all') }}
                    </button>
                </div>
            @endif

        </div>
    </div>
    {{-- ================== /Filters Toolbar ================== --}}


    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
            <thead class="bg-gray-50 dark:bg-gray-800/50">
                <tr>
                    <th class="px-4 py-3 text-start text-xs font-medium text-gray-500">{{ t('Tracking Number') }}</th>
                    <th class="px-4 py-3 text-start text-xs font-medium text-gray-500">{{ t('Current Status') }}</th>
                    <th class="px-4 py-3 text-start text-xs font-medium text-gray-500">{{ t('Sender') }}</th>
                    <th class="px-4 py-3 text-start text-xs font-medium text-gray-500">{{ t('Receiver') }}</th>
                    <th class="px-4 py-3 text-start text-xs font-medium text-gray-500">{{ t('Created At') }}</th>
                    <th class="px-4 py-3 text-start text-xs font-medium text-gray-500">{{ t('Created By') }}</th>
                    <th class="px-4 py-3 text-start text-xs font-medium text-gray-500">{{ t('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                @forelse ($rows as $row)
                    <tr class="hover:bg-gray-50/60 dark:hover:bg-gray-800/50">
                        <td class="px-4 py-3 font-semibold text-gray-900 dark:text-gray-100">
                            {{ $row->tracking_number }}
                        </td>
                        <td class="px-4 py-3">
                            @php
                                $status = $row->current_status;
                                $label = $row->current_status_label; // من الـ Accessor في Shipment
                                $color = match($status) {
                                    'delivered' => 'bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-200',
                                    'shipped_jed_port' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/40 dark:text-indigo-200',
                                    'arrived_sudan_port' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-200',
                                    'arrived_destination_branch' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-200',
                                    'arrived_jed_warehouse' => 'bg-sky-100 text-sky-800 dark:bg-sky-900/40 dark:text-sky-200',
                                    'received_at_branch' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200',
                                    default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200',
                                };
                            @endphp
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs {{ $color }}">
                                {{  $label?(app()->getLocale()=='en'? t($label):$label)  : '—' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <div class="text-gray-900 dark:text-gray-100">{{ $row->sender_name ?? '—' }}</div>
                            <div class="text-gray-500 text-xs">{{ $row->sender_phone ?? '' }}</div>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <div class="text-gray-900 dark:text-gray-100">{{ $row->receiver_name ?? '—' }}</div>
                            <div class="text-gray-500 text-xs">{{ $row->receiver_phone ?? '' }}</div>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                            {{ optional($row->created_at)->format('Y-m-d H:i') }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                            {{ $row->creator->name ??'-'}}
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <div class="flex items-center gap-2" >
                                <x-dashboard.event-icon route="{{ route('dashboard.shipments.show', $row)  }}">
                                    <x-heroicon-s-eye class="w4 h-4"/>
                                </x-dashboard.event-icon>

                                @can('edit shipments')
                                <x-dashboard.event-icon route="{{ route('dashboard.shipments.edit', $row) }}" >
                                    <x-heroicon-s-pencil-square class="w4 h-4"/>
                                </x-dashboard.event-icon>
                                @endcan

                                @can('delete shipments')
                                <button
                                    x-data
                                    @click="$store.confirm.ask(
                                        () => $wire.destroy('{{ $row->id }}'),
                                        '{{ t('Confirm shipment deletion') }}',
                                        '{{ t('The shipment and all associated events will be deleted. Are you sure?') }}'
                                    )"
                                    class="inline-flex items-center p-1 text-sm font-medium text-center text-gray-500 rounded-lg hover:text-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 focus:outline-none dark:text-gray-400 dark:hover:text-gray-100"
                                    title="{{ t('Delete shipment') }}">
                                    <x-heroicon-s-trash class="w4 h-4"/>
                                </button>
                                @endcan

                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-10 text-center text-gray-500">
                            {{ t('There are no shipments matching your search.') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-4 border-t border-gray-200 dark:border-gray-800">
        {{ $rows->onEachSide(1)->links() }}
    </div>
</div>
