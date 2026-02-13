<div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800 relative">

    <div wire:loading.flex
         wire:target="setStatus,setTripType,setDatePreset,from,to,q,perPage,clearStatus,clearTripType,clearDate,clearSearch,clearAll"
         class="absolute inset-0 bg-white/60 dark:bg-gray-900/60 backdrop-blur-[1px] z-10 items-center justify-center">
        <svg class="h-6 w-6 animate-spin text-gray-600 dark:text-gray-300" viewBox="0 0 24 24" fill="none">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4A4 4 0 004 12z"/>
        </svg>
    </div>

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
                    <button wire:click="clearTripType"
                            class="px-2.5 py-1.5 rounded-md text-xs border border-gray-300 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-800">
                        {{ t('Clear trip type') }}
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
                        placeholder="{{ t('Search by trip number, company, vehicle type, departure/arrival city...') }}"
                        class="w-full placeholder:text-xs pr-3 px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-primary-500"
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
                    <label class="text-gray-500">{{ t('Per page') }}</label>
                    <select
                        class="text-xs px-8 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 focus:outline-none"
                        wire:model.change="perPage"
                        title="{{ t('Number of items per page') }}"
                    >
                        <option value="10">10</option>
                        <option value="15">15</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <label class="text-gray-500">{{ t('Trip Type') }}</label>
                    <select wire:model.change="trip_type"
                            class="text-xs px-6 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 focus:outline-none">
                        <option value="">{{ t('All Trip Types') }}</option>
                        <option value="air">{{ t('Air') }}</option>
                        <option value="land">{{ t('Land') }}</option>
                        <option value="sea">{{ t('Sea') }}</option>
                    </select>
                </div>

                @can('manage branches')
                <div class="flex items-center gap-2">
                    <label class="text-gray-500">{{ t('Branch') }}</label>
                    <select wire:model.change="branch_id"
                            class="text-xs px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 focus:outline-none">
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
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-7 gap-3 min-w-[640px] sm:min-w-0">

                    {{-- الكل --}}
                    @php $active = is_null($status); @endphp
                    <button
                        wire:click="setStatus"
                        class="text-right rounded-xl border transition shadow-sm px-4 py-3
                            {{ $active ? 'border-emerald-500/50 ring-1 ring-emerald-500/30 bg-emerald-50 dark:bg-emerald-950/20' : 'border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800' }}">
                        <div class="flex items-center justify-between">
                            <div class="text-lg font-semibold text-gray-600">{{ $counts['all'] ?? 0 }}</div>
                            <x-dashboard.flight-status-icon class="w-7 text-slate-500" status='all'/>
                        </div>
                        <div class="mt-1 flex items-center gap-2 text-xs text-gray-500">
                            <span class="w-2 h-2 rounded-full bg-slate-400"></span> {{ t("All Flights") }}
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
                                <x-dashboard.flight-status-icon class="w-5 {{ $m['txt-color'] }}" status='{{  $key  }}'/>
                            </div>
                            <div class="mt-1 flex items-center gap-2 text-xs text-gray-500">
                                <span class="w-2 h-2 rounded-full {{ $m['dot'] }}"></span>
                                {{ t($m['label']) }}
                            </div>
                        </button>
                    @endforeach

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
                if($trip_type){ $chips[] = ['label' => t('Trip Type') . ': ' . ($trip_type == 'air' ? t('Air') : ($trip_type == 'land' ? t('Land') : t('Sea'))), 'action' => 'clearTripType']; }
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
                    <th class="px-1 py-3 text-xs text-center border-r border-gray-200 dark:border-gray-700 font-medium text-gray-500">{{ t('Trip Number') }}</th>
                    <th class="px-1 py-3 text-xs text-center border-r border-gray-200 dark:border-gray-700 font-medium text-gray-500">{{ t('Trip Type') }}</th>
                    <th class="px-1 py-3 text-xs text-center border-r border-gray-200 dark:border-gray-700 font-medium text-gray-500">{{ t('Route') }}</th>
                    <th class="px-1 py-3 text-xs text-center border-r border-gray-200 dark:border-gray-700 font-medium text-gray-500">{{ t('Departure Time') }}</th>
                    <th class="px-1 py-3 text-xs text-center border-r border-gray-200 dark:border-gray-700 font-medium text-gray-500">{{ t('Seats') }}</th>
                    <th class="px-1 py-3 text-xs text-center border-r border-gray-200 dark:border-gray-700 font-medium text-gray-500">{{ t('Price') }}</th>
                    <th class="px-1 py-3 text-xs text-center border-r border-gray-200 dark:border-gray-700 font-medium text-gray-500">{{ t('Status') }}</th>
                    <th class="px-1 py-3 text-xs text-center border-r border-gray-200 dark:border-gray-700 font-medium text-gray-500">{{ t('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                @forelse ($rows as $row)
                    <tr class="hover:bg-gray-50/60 dark:hover:bg-gray-800/50">
                        <td class="px-1 py-3 text-xs text-center border-r border-gray-200 dark:border-gray-700">
                            {{ $row->flight_number }}
                        </td>
                        <td class="px-1 py-3 text-xs text-center border-r border-gray-200 dark:border-gray-700">
                            @php
                                $tripTypeColor = match($row->trip_type) {
                                    'air' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-200',
                                    'land' => 'bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-200',
                                    'sea' => 'bg-cyan-100 text-cyan-800 dark:bg-cyan-900/40 dark:text-cyan-200',
                                    default => 'bg-gray-100 text-gray-800 dark:bg-gray-900/40 dark:text-gray-200',
                                };
                            @endphp
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs {{ $tripTypeColor }}">
                                {{ $row->trip_type_label }}
                            </span>
                        </td>
                        <td class="px-1 py-3 text-xs text-center border-r border-gray-200 dark:border-gray-700">
                            <div class="text-gray-900 dark:text-gray-100">
                                {{ $row->departure_city }} → {{ $row->arrival_city }}
                            </div>
                            <div class="text-gray-500 text-xs">
                                {{ $row->departure_airport }} → {{ $row->arrival_airport }}
                            </div>
                        </td>
                        <td class="px-1 py-3 text-xs text-center border-r border-gray-200 dark:border-gray-700">
                            <div class="text-gray-900 dark:text-gray-100">
                                {{ $row->departure_time->format('Y-m-d') }}
                            </div>
                            <div class="text-gray-500 text-xs">
                                {{ $row->departure_time->format('H:i') }} - {{ $row->arrival_time->format('H:i') }}
                            </div>
                        </td>
                         <td class="px-1 py-3 text-xs text-center border-r border-gray-200 dark:border-gray-700">
                            <div class="text-gray-900 dark:text-gray-100">
                                {{ $row->available_seats }}/{{ $row->total_seats }}
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: <?php echo ($row->available_seats / $row->total_seats) * 100; ?>%"></div>
                            </div>
                        </td>
                        <td class="px-1 py-3 text-xs text-center border-r border-gray-200 dark:border-gray-700">
                            {{ number_format($row->base_price, 2) }} {{ $row->currency }}
                        </td>
                        <td class="px-1 py-3 text-xs text-center border-r border-gray-200 dark:border-gray-700">
                            @php
                                $statusColor = match(true) {
                                    !$row->is_active => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200',
                                    $row->available_seats == 0 => 'bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-200',
                                    $row->available_seats <= $row->total_seats * 0.2 => 'bg-orange-100 text-orange-800 dark:bg-orange-900/40 dark:text-orange-200',
                                    $row->departure_time < now() => 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-200',
                                    default => 'bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-200',
                                };
                                $statusText = match(true) {
                                    !$row->is_active => t('Inactive'),
                                    $row->available_seats == 0 => t('Full'),
                                    $row->available_seats <= $row->total_seats * 0.2 => t('Almost Full'),
                                    $row->departure_time < now() => t('Departed'),
                                    default => t('Available'),
                                };
                            @endphp
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs {{ $statusColor }}">
                                {{ $statusText }}
                            </span>
                        </td>
                        <td class="px-1 py-3 text-xs text-center border-r border-gray-200 dark:border-gray-700">
                            <div class="flex justify-evenly items-center">
                                <x-dashboard.event-icon route="{{ route('dashboard.flights.show', $row) }}">
                                    <x-heroicon-s-eye class="w-4 h-4"/>
                                </x-dashboard.event-icon>

                                <x-dashboard.event-icon route="{{ route('dashboard.flights.edit', $row) }}">
                                    <x-heroicon-s-pencil-square class="w-4 h-4"/>
                                </x-dashboard.event-icon>

                                <button
                                    wire:click="toggleStatus('{{ $row->id }}')"
                                    class="inline-flex items-center p-1 text-sm font-medium text-center text-gray-500 rounded-lg hover:text-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 focus:outline-none dark:text-gray-400 dark:hover:text-gray-100"
                                    title="{{ $row->is_active ? t('Deactivate') : t('Activate') }}">
                                    @if($row->is_active)
                                        <x-heroicon-s-pause-circle class="w-4 h-4"/>
                                    @else
                                        <x-heroicon-s-play-circle class="w-4 h-4"/>
                                    @endif
                                </button>

                                <button
                                    x-data
                                    @click="$store.confirm.ask(
                                        () => $wire.destroy('{{ $row->id }}'),
                                        '{{ t('Confirm flight deletion') }}',
                                        '{{ t('The flight and all associated bookings will be deleted. Are you sure?') }}'
                                    )"
                                    class="inline-flex items-center p-1 text-xs font-medium text-center text-gray-500 rounded-lg hover:text-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 focus:outline-none dark:text-gray-400 dark:hover:text-gray-100"
                                    title="{{ t('Delete flight') }}">
                                    <x-heroicon-s-trash class="w-4 h-4"/>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="px-4 py-10 text-center text-gray-500">
                            {{ t('There are no flights matching your search.') }}
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