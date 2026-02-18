<div class="space-y-6">
    <form wire:submit="save" class="space-y-6">
        {{-- Trip Information --}}
        <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div>
                    <label for="trip_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ t('Trip Type') }} <span class="text-red-500">*</span>
                    </label>
                    <select id="trip_type" wire:model.live="trip_type" 
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('trip_type') border-red-500 @enderror">
                        <option value="air">{{ t('Air Trip') }}</option>
                        <option value="land">{{ t('Land Trip') }}</option>
                        <option value="sea">{{ t('Sea Trip') }}</option>
                    </select>
                    @error('trip_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- نوع المركبة -->
                <div>
                    <label for="vehicle_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ t('Vehicle Type') }}
                    </label>
                    <input type="text" id="vehicle_type" wire:model="vehicle_type"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('vehicle_type') border-red-500 @enderror"
                           placeholder="@if($trip_type === 'air'){{ t('e.g., Boeing 737, Airbus A320') }}@elseif($trip_type === 'land'){{ t('e.g., Bus, Car') }}@else{{ t('e.g., Ship, Ferry') }}@endif">
                    @error('vehicle_type')
                        <p class="mt-1 text-sm text-red-600">{{ t($message) }}</p>
                    @enderror
                </div>

                <!-- اسم الشركة -->
                <div>
                    <label for="airline" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ t('Company Name') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="airline" wire:model="airline" 
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('airline') border-red-500 @enderror"
                           placeholder="{{ t('Enter company name') }}">
                    @error('airline')
                        <p class="mt-1 text-sm text-red-600">{{ t($message) }}</p>
                    @enderror
                </div>

                <div>
                    <label for="departure_city" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ t('Departure City') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="departure_city" wire:model="departure_city" 
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('departure_city') border-red-500 @enderror"
                           placeholder="{{ t('Enter departure city') }}">
                    @error('departure_city')
                        <p class="mt-1 text-sm text-red-600">{{ t($message) }}</p>
                    @enderror
                </div>

                <div>
                    <label for="arrival_city" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ t('Arrival City') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="arrival_city" wire:model="arrival_city" 
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('arrival_city') border-red-500 @enderror"
                           placeholder="{{ t('Enter arrival city') }}">
                    @error('arrival_city')
                        <p class="mt-1 text-sm text-red-600">{{ t($message) }}</p>
                    @enderror
                </div>

                <!-- مطار المغادرة -->
                <div>
                    <label for="departure_airport" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        @if($trip_type === 'air'){{ t('Departure Airport') }}@elseif($trip_type === 'land'){{ t('Departure Station') }}@else{{ t('Departure Port') }}@endif <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="departure_airport" wire:model="departure_airport" 
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('departure_airport') border-red-500 @enderror"
                           placeholder="@if($trip_type === 'air'){{ t('e.g., RUH, JED') }}@elseif($trip_type === 'land'){{ t('e.g., Central Station') }}@else{{ t('e.g., Jeddah Port') }}@endif">
                    @error('departure_airport')
                        <p class="mt-1 text-sm text-red-600">{{ t($message) }}</p>
                    @enderror
                </div>

                <!-- مطار الوصول -->
                <div>
                    <label for="arrival_airport" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        @if($trip_type === 'air'){{ t('Arrival Airport') }}@elseif($trip_type === 'land'){{ t('Arrival Station') }}@else{{ t('Arrival Port') }}@endif <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="arrival_airport" wire:model="arrival_airport" 
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('arrival_airport') border-red-500 @enderror"
                           placeholder="@if($trip_type === 'air'){{ t('e.g., KRT, PZU') }}@elseif($trip_type === 'land'){{ t('e.g., Khartoum Station') }}@else{{ t('e.g., Port Sudan') }}@endif">
                    @error('arrival_airport')
                        <p class="mt-1 text-sm text-red-600">{{ t($message) }}</p>
                    @enderror
                </div>

                <div>
                    <label for="departure_terminal" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        @if($trip_type === 'air'){{ t('Departure Terminal') }}@elseif($trip_type === 'land'){{ t('Departure Gate') }}@else{{ t('Departure Dock') }}@endif
                    </label>
                    <input type="text" id="departure_terminal" wire:model="departure_terminal"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('departure_terminal') border-red-500 @enderror"
                           placeholder="@if($trip_type === 'air'){{ t('e.g., Terminal 1') }}@elseif($trip_type === 'land'){{ t('e.g., Gate A') }}@else{{ t('e.g., Dock 3') }}@endif">
                    @error('departure_terminal')
                        <p class="mt-1 text-sm text-red-600">{{ t($message) }}</p>
                    @enderror
                </div>

                <div>
                    <label for="arrival_terminal" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        @if($trip_type === 'air'){{ t('Arrival Terminal') }}@elseif($trip_type === 'land'){{ t('Arrival Gate') }}@else{{ t('Arrival Dock') }}@endif
                    </label>
                    <input type="text" id="arrival_terminal" wire:model="arrival_terminal"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('arrival_terminal') border-red-500 @enderror"
                           placeholder="@if($trip_type === 'air'){{ t('e.g., Terminal 2') }}@elseif($trip_type === 'land'){{ t('e.g., Gate B') }}@else{{ t('e.g., Dock 1') }}@endif">
                    @error('arrival_terminal')
                        <p class="mt-1 text-sm text-red-600">{{ t($message) }}</p>
                    @enderror
                </div>

                <div>
                    <label for="departure_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ t('Departure Time') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="datetime-local" id="departure_time" wire:model="departure_time" 
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('departure_time') border-red-500 @enderror">
                    @error('departure_time')
                        <p class="mt-1 text-sm text-red-600">{{ t($message) }}</p>
                    @enderror
                </div>

                <div>
                    <label for="arrival_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ t('Arrival Time') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="datetime-local" id="arrival_time" wire:model="arrival_time" 
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('arrival_time') border-red-500 @enderror">
                    @error('arrival_time')
                        <p class="mt-1 text-sm text-red-600">{{ t($message) }}</p>
                    @enderror
                </div>

                <div>
                    <label for="total_seats" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ t('Total Seats') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="total_seats" wire:model="total_seats" min="1" max="1000" 
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('total_seats') border-red-500 @enderror"
                           placeholder="{{ t('Enter total seats') }}">
                    @error('total_seats')
                        <p class="mt-1 text-sm text-red-600">{{ t($message) }}</p>
                    @enderror
                </div>

                <div>
                    <label for="base_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ t('Base Price') }} ({{ t('SAR') }}) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="base_price" wire:model.live="base_price" min="0" step="0.01" 
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('base_price') border-red-500 @enderror"
                           placeholder="{{ t('Enter base price') }}">
                    @error('base_price')
                        <p class="mt-1 text-sm text-red-600">{{ t($message) }}</p>
                    @enderror
                </div>

                {{-- Combined Classes and Prices Grid --}}
                <div class="col-span-2">
                    <div class="grid grid-cols-2 gap-6">
                        {{-- Group Header --}}
                        <div class="space-y-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ t('Seat Classes') }}
                            </label>
                            <div class="space-y-5"> {{-- Space between checkbox rows --}}
                                @foreach(['economy', 'business', 'first'] as $class)
                                    <div class="h-10 flex items-center"> {{-- Fixed height to match input height --}}
                                        <label class="flex items-center cursor-pointer group">
                                            <input type="checkbox" wire:model.live="seat_classes" value="{{ $class }}"
                                                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded transition duration-150">
                                            <span class="ml-2 mr-2 text-sm text-gray-700 dark:text-gray-300 group-hover:text-indigo-600 transition duration-150">
                                                {{ t(ucfirst($class)) }}
                                            </span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @error('seat_classes')
                                <p class="mt-1 text-sm text-red-600">{{ t($message) }}</p>
                            @enderror
                        </div>

                        <div class="space-y-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ t('Seat Prices') }}
                            </label>
                            <div class="space-y-5">
                                @foreach(['economy', 'business', 'first'] as $class)
                                    <div class="h-10"> {{-- Reserved space for each class --}}
                                        @if(in_array($class, $seat_classes))
                                            <div class="relative" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95">
                                                <input type="number" id="pricing_{{ $class }}" 
                                                       wire:model="pricing_tiers.{{ $class }}" 
                                                       min="0" step="any"
                                                       class="w-full pl-3 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white dark:bg-gray-900 shadow-sm"
                                                       placeholder="{{ t('Price') }}">
                                                @error('pricing_tiers.' . $class)
                                                    <p class="absolute -bottom-5 left-0 text-[10px] text-red-600 truncate">{{   t($message) }}</p>
                                                @enderror
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Additional Information --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-start">
            <div class="md:col-span-3">
                <textarea id="notes" wire:model="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('notes') border-red-500 @enderror" 
                          placeholder="{{ t('Enter any additional notes') }}">
                </textarea>
                @error('notes')
                    <p class="mt-1 text-sm text-red-600">{{ t($message) }}</p>
                @enderror
            </div>

            <div class="md:col-span-1 flex">
                <div class="flex  ">
                    <input
                        type="checkbox"
                        id="is_active"
                        wire:model="is_active"
                        class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded"
                    >
                    <label for="is_active" class="ml-2 mr-2 text-sm text-gray-700 dark:text-gray-300">
                        {{ t('Activate Flight') }}
                    </label>
                </div>
            </div>

        </div>

        {{-- Action Buttons --}}
        <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
            <a href="{{ route('dashboard.flights.index') }}" 
               class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                {{ t('Cancel') }}
            </a>
            <button type="submit" 
                    class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                {{ $flightId ? t('Update Flight') : t('Create Flight') }}
            </button>
        </div>
    </form>
</div>
