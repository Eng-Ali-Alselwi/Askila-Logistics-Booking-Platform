<div x-data class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800 relative">

    <div wire:loading.flex
         wire:target="setStatus,setPaymentStatus,setTripType,setDatePreset,from,to,q,perPage,clearStatus,clearPaymentStatus,clearTripType,clearDate,clearSearch,clearAll"
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
                    <button wire:click="clearPaymentStatus"
                            class="px-2.5 py-1.5 rounded-md text-xs border border-gray-300 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-800">
                        {{ t('Clear payment status') }}
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
                        placeholder="{{ t('Search by booking reference, passenger name, email, phone, passport, nationality, or trip details...') }}"
                        class="placeholder:text-xs w-full px-6 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-primary-500"
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

                @can('manage branches')
                <div class="flex items-center gap-2">
                    <label class="text-gray-500">{{ t('Branch') }}</label>
                    <select wire:model.change="branch_id"
                            class="text-xs px-8 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 focus:outline-none">
                        <option value="">{{ t('All Branches') }}</option>
                        @foreach(\App\Models\Branch::orderBy('name')->get(['id','name']) as $b)
                            <option value="{{ $b->id }}">{{ $b->name }}</option>
                        @endforeach
                    </select>
                </div>
                @endcan

                <div class="flex items-center gap-2">
                    <label class="text-gray-500">{{ t('Trip Type') }}</label>
                    <select wire:model.change="trip_type"
                            class="text-xs px-8 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 focus:outline-none">
                        <option value="">{{ t('All Trip Types') }}</option>
                        <option value="air">{{ t('Air') }}</option>
                        <option value="land">{{ t('Land') }}</option>
                        <option value="sea">{{ t('Sea') }}</option>
                    </select>
                </div>
            </div>

            {{-- صف: فلاتر الحالة (بطاقات) — سحب أفقي على الجوال --}}
            <div class="overflow-x-auto -mx-1 px-1">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-9 gap-3 min-w-[640px] sm:min-w-0">

                    {{-- الكل --}}
                    @php $active = is_null($status) && is_null($payment_status); @endphp
                    <button
                        wire:click="clearStatus(); clearPaymentStatus();"
                        class="text-right rounded-xl border transition shadow-sm px-4 py-3
                            {{ $active ? 'border-emerald-500/50 ring-1 ring-emerald-500/30 bg-emerald-50 dark:bg-emerald-950/20' : 'border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800' }}">
                        <div class="flex items-center justify-between">
                            <div class="text-lg font-semibold text-gray-600">{{ $counts['all'] ?? 0 }}</div>
                            <x-heroicon-o-ticket class="w-7 text-slate-500"/>
                        </div>
                        <div class="mt-1 flex items-center gap-2 text-xs text-gray-500">
                            <span class="w-2 h-2 rounded-full bg-slate-400"></span> {{ t("All Bookings") }}
                        </div>
                    </button>

                    {{-- حالات الحجز --}}
                    @foreach ($statusMeta as $key => $m)
                        @php $active = $status === $key; @endphp
                        <button
                            wire:click="setStatus('{{ $key }}')"
                            class="text-right rounded-xl border transition shadow-sm px-4 py-3
                                {{ $active ? 'border-emerald-500/50 ring-1 ring-emerald-500/30 bg-emerald-50 dark:bg-emerald-950/20' : 'border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800' }}">
                            <div class="flex items-center justify-between">
                                <div class="text-lg font-semibold text-gray-600">{{ $counts['status_' . $key] ?? 0 }}</div>
                                <div class="w-5 h-5 {{ $m['txt-color'] }}">
                                    @if($key === 'pending')
                                        <x-heroicon-o-clock class="w-5 h-5"/>
                                    @elseif($key === 'confirmed')
                                        <x-heroicon-o-check-circle class="w-5 h-5"/>
                                    @elseif($key === 'cancelled')
                                        <x-heroicon-o-x-circle class="w-5 h-5"/>
                                    @elseif($key === 'completed')
                                        <x-heroicon-o-check-badge class="w-5 h-5"/>
                                    @endif
                                </div>
                            </div>
                            <div class="mt-1 flex items-center gap-2 text-xs text-gray-500">
                                <span class="w-2 h-2 rounded-full {{ $m['dot'] }}"></span> {{ t($m['label']) }}
                            </div>
                        </button>
                    @endforeach

                    {{-- حالات الدفع --}}
                    @foreach ($paymentStatusMeta as $key => $m)
                        @php $active = $payment_status === $key; @endphp
                        <button
                            wire:click="setPaymentStatus('{{ $key }}')"
                            class="text-right rounded-xl border transition shadow-sm px-4 py-3
                                {{ $active ? 'border-emerald-500/50 ring-1 ring-emerald-500/30 bg-emerald-50 dark:bg-emerald-950/20' : 'border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800' }}">
                            <div class="flex items-center justify-between">
                                <div class="text-lg font-semibold text-gray-600">{{ $counts['payment_' . $key] ?? 0 }}</div>
                                <div class="w-5 h-5 {{ $m['txt-color'] }}">
                                    @if($key === 'pending')
                                        <x-heroicon-o-clock class="w-5 h-5"/>
                                    @elseif($key === 'paid')
                                        <x-heroicon-o-check-circle class="w-5 h-5"/>
                                    @elseif($key === 'failed')
                                        <x-heroicon-o-x-circle class="w-5 h-5"/>
                                    @elseif($key === 'refunded')
                                        <x-heroicon-o-arrow-uturn-left class="w-5 h-5"/>
                                    @endif
                                </div>
                            </div>
                            <div class="mt-1 flex items-center gap-2 text-xs text-gray-500">
                                <span class="w-2 h-2 rounded-full {{ $m['dot'] }}"></span> {{ t($m['label']) }}
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
                if($status){ $chips[] = ['label' => t('Status') . ': ' . ($statusMeta[$status]['label'] ?? $status), 'action' => 'clearStatus']; }
                if($payment_status){ $chips[] = ['label' => t('Payment Status') . ': ' . ($paymentStatusMeta[$payment_status]['label'] ?? $payment_status), 'action' => 'clearPaymentStatus']; }
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
                    <th class="px-2 py-3 border border-x-1 border-gray-200 dark:border-gray-600 text-center font-medium text-gray-500">{{ t('Booking Reference') }}</th>
                    <th class="px-2 py-3 border border-x-1 border-gray-200 dark:border-gray-600 text-center font-medium text-gray-500">{{ t('Passenger') }}</th>
                    <th class="px-2 py-3 border border-x-1 border-gray-200 dark:border-gray-600 text-center font-medium text-gray-500">{{ t('Seat Class') }}</th>
                    <th class="px-2 py-3 border border-x-1 border-gray-200 dark:border-gray-600 text-center font-medium text-gray-500">{{ t('Passengers') }}</th>
                    <th class="px-2 py-3 border border-x-1 border-gray-200 dark:border-gray-600 text-center font-medium text-gray-500">{{ t('Total Amount') }}</th>
                    <th class="px-2 py-3 border border-x-1 border-gray-200 dark:border-gray-600 text-center font-medium text-gray-500">{{ t('Status') }}</th>
                    <th class="px-2 py-3 border border-x-1 border-gray-200 dark:border-gray-600 text-center font-medium text-gray-500">{{ t('Payment Status') }}</th>
                    <th class="px-2 py-3 border border-x-1 border-gray-200 dark:border-gray-600 text-center font-medium text-gray-500">{{ t('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                @forelse ($rows as $row)
                    <tr class="hover:bg-gray-50/60 dark:hover:bg-gray-800/50">
                        <td class="px-2 py-3 text-sm border border-x-1 border-gray-200 dark:border-gray-600 text-center text-gray-900 dark:text-gray-100">
                            {{ $row->booking_reference }}
                        </td>
                        <td class="px-2 py-3 text-sm border border-x-1 border-gray-200 dark:border-gray-600 text-center text-gray-900 dark:text-gray-100">
                            <div class="text-gray-900 dark:text-gray-100 font-medium">
                                {{ $row->passenger_name }}
                            </div>
                            <div class="text-gray-500 text-xs">
                                {{ $row->passenger_email }}
                            </div>
                            <div class="text-gray-500 text-xs">
                                {{ $row->passenger_phone }}
                            </div>
                        </td>
                        <td class="text-sm px-2 py-3 border border-x-1 border-gray-200 dark:border-gray-600 text-center text-gray-900 dark:text-gray-100">
                            {{ t(ucfirst($row->seat_class)) }}
                        </td>
                        <td class="text-sm px-2 py-3 border border-x-1 border-gray-200 dark:border-gray-600 text-center text-gray-900 dark:text-gray-100">
                            {{ $row->number_of_passengers }}
                        </td>
                        <td class="text-sm px-2 py-3 border border-x-1 border-gray-200 dark:border-gray-600 text-center text-gray-900 dark:text-gray-100 font-semibold">
                            {{ $row->currency }} {{ number_format($row->total_amount, 2) }} 
                        </td>
                        <td class="px-2 py-3 border border-x-1 border-gray-200 dark:border-gray-600 text-center text-gray-900 dark:text-gray-100">
                            @php
                                $statusColor = match($row->status) {
                                    'confirmed' => 'bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-200',
                                    'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/40 dark:text-yellow-200',
                                    'pending_confirmation' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-200',
                                    'cancelled' => 'bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-200',
                                    'completed' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-200',
                                    default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200',
                                };
                            @endphp
                            <span class="inline-flex items-center p-2 text-xs {{ $statusColor }}">
                                {{ t(ucfirst($row->status)) }}
                            </span>
                        </td>
                        <td class="px-2 py-3 border border-x-1 border-gray-200 dark:border-gray-600 text-center text-gray-900 dark:text-gray-100">
                            @php
                                $paymentStatusColor = match($row->payment_status) {
                                    'paid' => 'bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-200',
                                    'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/40 dark:text-yellow-200',
                                    'pending_manual' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-200',
                                    'failed' => 'bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-200',
                                    'refunded' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200',
                                    default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200',
                                };
                                $paymentStatusText = match($row->payment_status) {
                                    'pending_manual' => t('Manual Confirmation Pending'),
                                    default => t(ucfirst($row->payment_status)),
                                };
                            @endphp
                            <span class="inline-flex items-center p-2 py-1 text-xs {{ $paymentStatusColor }}">
                                {{ $paymentStatusText }}
                            </span>
                        </td>
                        <td class="px-1 py-3 border border-x-1 border-gray-200 dark:border-gray-600 text-center text-gray-900 dark:text-gray-100">
                            <div class="flex items-center justify-evenly">
                                <x-dashboard.event-icon route="{{ route('dashboard.bookings.show', $row) }}">
                                    <x-heroicon-s-eye class="w-4 h-4"/>
                                </x-dashboard.event-icon>

                                <x-dashboard.event-icon route="{{ route('dashboard.bookings.edit', $row) }}">
                                    <x-heroicon-s-pencil-square class="w-4 h-4"/>
                                </x-dashboard.event-icon>

                                @if($row->status === 'pending')
                                    <button
                                        wire:click="confirm('{{ $row->id }}')"
                                        class="inline-flex items-center p-1 text-sm font-medium text-center text-gray-500 rounded-lg hover:text-green-800 hover:bg-green-200 dark:hover:bg-green-700 focus:outline-none dark:text-gray-400 dark:hover:text-green-100"
                                        title="{{ t('Confirm booking') }}">
                                        <x-heroicon-s-check-circle class="w-4 h-4"/>
                                    </button>
                                @endif

                                @if($row->canBeCancelled())
                                    <button
                                        wire:click="cancel('{{ $row->id }}')"
                                        class="inline-flex items-center p-1 text-sm font-medium text-center text-gray-500 rounded-lg hover:text-orange-800 hover:bg-orange-200 dark:hover:bg-orange-700 focus:outline-none dark:text-gray-400 dark:hover:text-orange-100"
                                        title="{{ t('Cancel booking') }}">
                                        <x-heroicon-s-x-circle class="w-4 h-4"/>
                                    </button>
                                @endif

                                <button
                                    @click="Alpine.store('confirm').ask(
                                        () => $wire.destroy('{{ $row->id }}'),
                                        @js(t('Confirm booking deletion')),
                                        @js(t('The booking will be deleted permanently. Are you sure?'))
                                    )"
                                    class="inline-flex items-center p-1 text-sm font-medium text-center text-gray-500 rounded-lg hover:text-red-800 hover:bg-red-200 dark:hover:bg-red-700 focus:outline-none dark:text-gray-400 dark:hover:text-red-100"
                                    title="{{ t('Delete booking') }}">
                                    <x-heroicon-s-trash class="w-4 h-4"/>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="px-4 py-10 text-center text-gray-500">
                            {{ t('There are no bookings matching your search.') }}
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