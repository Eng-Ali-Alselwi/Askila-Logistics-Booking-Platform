<div class="space-y-6 relative">
    <div wire:loading.flex
            wire:target="updateStatus,quickSet,"
            class="absolute inset-0 bg-white/60 dark:bg-gray-900/60 backdrop-blur-[1px] z-10 items-center justify-center">
        <svg class="h-6 w-6 animate-spin text-gray-600 dark:text-gray-300" viewBox="0 0 24 24" fill="none">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4A4 4 0 004 12z"/>
        </svg>
    </div>
    {{-- بطاقة تفاصيل أساسية --}}
    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800">
        <div class="p-5 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <div class="text-sm text-gray-500">{{ t('Tracking Number') }}</div>
                <div class="font-semibold">{{ $shipment->tracking_number }}</div>
            </div>
            <div>
                <div class="text-sm text-gray-500">{{ t('Current Status') }}</div>
                @php
                    $status = $shipment->current_status;
                    $label  = $shipment->current_status_label;
                    $chip = match($status) {
                        'delivered'                    => 'bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-200',
                        'shipped_jed_port'             => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/40 dark:text-indigo-200',
                        'arrived_sudan_port'           => 'bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-200',
                        'arrived_destination_branch'   => 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-200',
                        'arrived_jed_warehouse'        => 'bg-sky-100 text-sky-800 dark:bg-sky-900/40 dark:text-sky-200',
                        'received_at_branch'           => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200',
                        default                        => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200',
                    };
                @endphp
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs {{ $chip }}">
                    {{ $label ? (app()->getLocale()=='en'? t($label):$label) :'—' }}
                </span>
            </div>
            <div>
                <div class="text-sm text-gray-500">{{ t('Created at') }}</div>
                <div>{{ optional($shipment->created_at)->format('Y-m-d H:i') }}</div>
            </div>
            <div>
                <div class="text-sm text-gray-500">{{ t('Sender') }}</div>
                <div class="font-medium">{{ $shipment->sender_name ?? '—' }}</div>
                <div class="text-xs text-gray-500">{{ $shipment->sender_phone ?? '' }}</div>
            </div>
            <div>
                <div class="text-sm text-gray-500">{{ t('Receiver') }}</div>
                <div class="font-medium">{{ $shipment->receiver_name ?? '—' }}</div>
                <div class="text-xs text-gray-500">{{ $shipment->receiver_phone ?? '' }}</div>
            </div>
            <div>
                <div class="text-sm text-gray-500">{{ t('Notes') }}</div>
                <div class="text-sm">{{ $shipment->notes ?? '—' }}</div>
            </div>
        </div>
    </div>

    {{-- نموذج تحديث الحالة --}}
    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800">
        <div class="p-5 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
            <h3 class="font-semibold">{{ t('Update Shipment Status') }}</h3>
            <div class="flex flex-wrap gap-2">
                {{-- أزرار اختصار للحالات الشائعة (اختياري) --}}
                @php $cases = \App\Enums\ShipmentStatus::timeline(); @endphp
                @foreach ($cases as $st)
                    <button type="button"
                        wire:click="quickSet('{{ $st->value }}')"
                        class="text-xs px-3 py-1 rounded-lg border border-gray-300 dark:border-gray-700 {{ $shipment->current_status === $st->value ? 'border border-primary-500 text-primary-500' : '' }}">
                        {{app()->getLocale()=='en'? t($st->label()): $st->label() }}
                    </button>
                @endforeach
            </div>
        </div>

        <form wire:submit.prevent="updateStatus" class="p-5 grid grid-cols-1 md:grid-cols-4 gap-4">

            <div>
                <label class="block text-sm mb-1">{{ t('Status') }}</label>
                <select wire:model.defer="status"
                        class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800">
                    @foreach ($statuses as $st)
                        <option value="{{ $st->value }}">{{ app()->getLocale()=='en'? t( $st->label()):$st->label() }}</option>
                    @endforeach
                </select>
                @error('status') <div class="text-xs text-rose-500 mt-1">{{ $message }}</div> @enderror
            </div>

            <x-dashboard.form.input-field
                name="location_text"
                label="{{ t('Location/Description') }}"
                type="text"
                wire:model.defer="location_text"
            />

            <x-dashboard.form.input-field
                name="notes"
                label="{{ t('Notes') }}"
                type="text"
                wire:model.defer="notes"
            />

            <x-dashboard.form.input-field
                name="happened_at"
                label="{{ t('Event Time') }}"
                type="datetime-local"
                wire:model.defer="happened_at"
            />

            <div class="md:col-span-4">
                <x-inputs.button-primary type="submit">
                    {{ t('Update Status and Log Event') }}
                </x-inputs.button-primary>
            </div>
        </form>

        @if (session('success') || session('info'))
            <div class="px-5 pb-5">
                @if(session('success'))
                    <div class="text-sm text-emerald-600">{{ session('success') }}</div>
                @endif
                @if(session('info'))
                    <div class="text-sm text-amber-600">{{ session('info') }}</div>
                @endif
            </div>
        @endif
    </div>

    {{-- التايملاين --}}
    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800">
        <div class="p-5">
            <h3 class="font-semibold mb-4">{{ t('Tracking Log (Timeline)') }}</h3>

            <ol class="relative border-s border-gray-200 dark:border-gray-700">
                @forelse($shipment->events as $event)
                    <li class="mb-8 ms-6">
                        <span class="absolute -start-3.5 flex h-7 w-7 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800 border border-gray-300 dark:border-gray-700">
                            <span  @class([
                                'h-2.5 w-2.5 rounded-full',
                                    'bg-green-500'   => $event->status === 'delivered',
                                    'bg-indigo-500'  => $event->status === 'shipped_jed_port',
                                    'bg-blue-500'    => $event->status === 'arrived_sudan_port',
                                    'bg-amber-500'   => $event->status === 'arrived_destination_branch',
                                    'bg-sky-500'     => $event->status === 'arrived_jed_warehouse',
                                    'bg-gray-400'    => $event->status === 'received_at_branch',
                                    'bg-gray-400'    => !in_array($event->status, [
                                        'delivered','shipped_jed_port','arrived_sudan_port',
                                        'arrived_destination_branch','arrived_jed_warehouse','received_at_branch'
                                    ])
                                ])>
                            </span>
                        </span>
                        <div class="flex flex-col gap-1">
                            <h4 class="text-sm font-semibold">
                                @php
                                    $sts=\App\Enums\ShipmentStatus::tryFrom($event->status)?->label()?? $event->status;
                                @endphp
                                {{ app()->getLocale()=='en'?t($sts):$sts }}
                            </h4>
                            <div class="text-xs text-gray-500">
                                {{ optional($event->happened_at)->format('Y-m-d H:i') }}
                                @if($event->location_text)
                                    • {{ $event->location_text }}
                                @endif
                                @if($event->creator)
                                    • {{ t('By') }}: {{ $event->creator->name ?? $event->creator->email }}
                                @endif
                            </div>
                            @if($event->notes)
                                <div class="text-sm text-gray-700 dark:text-gray-300">{{ $event->notes }}</div>
                            @endif
                        </div>
                    </li>
                @empty
                    <li class="ms-6">
                            <div class="text-gray-500">{{ t('No events found for this shipment.') }}</div>
                    </li>
                @endforelse
            </ol>
        </div>
    </div>

</div>
