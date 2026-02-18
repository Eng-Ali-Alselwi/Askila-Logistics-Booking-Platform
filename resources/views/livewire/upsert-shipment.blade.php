<div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800">
    @php
        use App\Helpers\PermissionHelper;
        $canCreate = PermissionHelper::canCreate('shipments');
        $canEdit = PermissionHelper::canEdit('shipments');
        $hasPermission = $shipmentId ? $canEdit : $canCreate;
    @endphp
    
    @if($hasPermission)
        <form wire:submit.prevent="save" class="p-5 space-y-6">
            {{-- Basic Information --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <x-dashboard.form.input-field
                    name="weight_kg"
                    label="{{ t('Weight (kg)') }}"
                    type="number"
                    step="0.01"
                    wire:model.defer="weight_kg"
                />

                <x-dashboard.form.input-field
                    name="volume_cbm"
                    label="{{ t('Volume (m³)') }}"
                    type="number"
                    step="0.001"
                    wire:model.defer="volume_cbm"
                />

                <x-dashboard.form.input-field
                    name="declared_value"
                    label="{{ t('Declared Value') }}"
                    type="number"
                    step="0.01"
                    wire:model.defer="declared_value"
                />
            </div>

            {{-- Sender/Receiver Information --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 pt-2">
                <x-dashboard.form.input-field
                    name="sender_name"
                    label="{{ t('Sender Name') }}"
                    type="text"
                    wire:model.defer="sender_name"
                />

                <x-dashboard.form.input-field
                    name="sender_phone"
                    label="{{ t('Sender Phone') }}"
                    type="text"
                    wire:model.defer="sender_phone"
                />

                <x-dashboard.form.input-field
                    name="receiver_name"
                    label="{{ t('Receiver Name') }}"
                    type="text"
                    wire:model.defer="receiver_name"
                />

                <x-dashboard.form.input-field
                    name="receiver_phone"
                    label="{{ t('Receiver Phone') }}"
                    type="text"
                    wire:model.defer="receiver_phone"
                />
            </div>

            <div>
                <label class="block text-sm mb-1">{{ t('Notes') }}</label>
                <textarea wire:model.defer="notes" rows="3"
                        class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800"></textarea>
            </div>

            {{-- Optional Initial Event --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-4">
                <div>
                    <label class="block text-sm mb-1">{{ t('Set initial status') }}</label>
                    <select wire:model.defer="initial_status"
                            class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800">
                        <option value="">— {{ t('No initial status') }} —</option>
                        @foreach ($statuses as $st)
                            <option value="{{ $st->value }}">{{ $st->label() }}</option>
                        @endforeach
                    </select>
                    @error('initial_status') <div class="text-xs text-rose-500 mt-1">{{ $message }}</div> @enderror
                </div>

                {{-- Event Location/Description --}}
                <x-dashboard.form.input-field
                    name="initial_location"
                    label="{{ t('Location/Description') }}"
                    type="text"
                    wire:model.defer="initial_location"
                />

                {{-- Event Notes --}}
                <x-dashboard.form.input-field
                    name="initial_notes"
                    label="{{ t('Notes') }}"
                    type="text"
                    wire:model.defer="initial_notes"
                />
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                        class="px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">
                    {{ $shipmentId ? t('Save Changes') : t('Create Shipment') }}
                </button>

                <a href="{{ route('dashboard.shipments.index') }}"
                class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700">
                    {{ t('Back to List') }}
                </a>
            </div>
        </form>
    @else
        <div class="p-5 bg-red-50 border border-red-200 rounded-lg text-red-700">
            <p>{{ t('You do not have permission to perform this action.') }}</p>
            <a href="{{ route('dashboard.shipments.index') }}" class="mt-2 inline-block text-blue-600 hover:underline">
                {{ t('Back to Shipments') }}
            </a>
        </div>
    @endif
</div>