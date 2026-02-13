@extends('dashboard.layout.admin', ['title' => t('Edit Booking')])

@section('content')

    <x-dashboard.outer-card :title="t('Edit Booking')">
        <x-slot:header>
            <div class="flex px-4 border-b-1 border-b-gray-500 flex-col items-stretch justify-between py-4 space-y-3 md:flex-row md:items-center md:space-y-0">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">
                    {{ t('Edit Booking') }} - {{ $booking->booking_reference }}
                </h2>
                <div class="flex items-center gap-2">
                    <x-inputs.button-secondary as="a" href="{{ route('dashboard.bookings.show', $booking) }}">
                        <x-heroicon-s-arrow-left class="h-4 w-4 me-2 inline" />
                        {{ t('Back to Booking') }}
                    </x-inputs.button-secondary>
                </div>
            </div>
        </x-slot:header>

        <div class="p-6">
            <form action="{{ route('dashboard.bookings.update', $booking) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- Passenger Information --}}
                <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4 flex items-center">
                        <x-heroicon-o-user class="w-5 h-5 mr-2 text-indigo-600" />
                        {{ t('Passenger Information') }}
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="passenger_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ t('Passenger Name') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="passenger_name" name="passenger_name" value="{{ old('passenger_name', $booking->passenger_name) }}" required
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('passenger_name') border-red-500 @enderror"
                                   placeholder="{{ t('Enter passenger name') }}">
                            @error('passenger_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="passenger_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ t('Email') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="email" id="passenger_email" name="passenger_email" value="{{ old('passenger_email', $booking->passenger_email) }}" required
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('passenger_email') border-red-500 @enderror"
                                   placeholder="{{ t('Enter email address') }}">
                            @error('passenger_email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="passenger_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ t('Phone') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="passenger_phone" name="passenger_phone" value="{{ old('passenger_phone', $booking->passenger_phone) }}" required
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('passenger_phone') border-red-500 @enderror"
                                   placeholder="{{ t('Enter phone number') }}">
                            @error('passenger_phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="passenger_id_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ t('ID Number') }}
                            </label>
                            <input type="text" id="passenger_id_number" name="passenger_id_number" value="{{ old('passenger_id_number', $booking->passenger_id_number) }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('passenger_id_number') border-red-500 @enderror"
                                   placeholder="{{ t('Enter ID number') }}">
                            @error('passenger_id_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone_sudan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ t('Phone (Sudan)') }}
                            </label>
                            <input type="text" id="phone_sudan" name="phone_sudan" value="{{ old('phone_sudan', $booking->phone_sudan) }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('phone_sudan') border-red-500 @enderror"
                                   placeholder="{{ t('Enter phone number in Sudan') }}">
                            @error('phone_sudan')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Passport Information --}}
                <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4 flex items-center">
                        <x-heroicon-o-identification class="w-5 h-5 mr-2 text-indigo-600" />
                        {{ t('Passport Information') }}
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="passport_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ t('Passport Number') }}
                            </label>
                            <input type="text" id="passport_number" name="passport_number" value="{{ old('passport_number', $booking->passport_number) }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('passport_number') border-red-500 @enderror"
                                   placeholder="{{ t('Enter passport number') }}">
                            @error('passport_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="nationality" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ t('Nationality') }}
                            </label>
                            <input type="text" id="nationality" name="nationality" value="{{ old('nationality', $booking->nationality) }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('nationality') border-red-500 @enderror"
                                   placeholder="{{ t('Enter nationality') }}">
                            @error('nationality')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="passport_issue_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ t('Passport Issue Date') }}
                            </label>
                            <input type="date" id="passport_issue_date" name="passport_issue_date" value="{{ old('passport_issue_date', $booking->passport_issue_date?->format('Y-m-d')) }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('passport_issue_date') border-red-500 @enderror">
                            @error('passport_issue_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="passport_expiry_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ t('Passport Expiry Date') }}
                            </label>
                            <input type="date" id="passport_expiry_date" name="passport_expiry_date" value="{{ old('passport_expiry_date', $booking->passport_expiry_date?->format('Y-m-d')) }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('passport_expiry_date') border-red-500 @enderror">
                            @error('passport_expiry_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="date_of_birth" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ t('Date of Birth') }}
                            </label>
                            <input type="date" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth', $booking->date_of_birth?->format('Y-m-d')) }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('date_of_birth') border-red-500 @enderror">
                            @error('date_of_birth')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="current_residence_country" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ t('Current Residence Country') }}
                            </label>
                            <input type="text" id="current_residence_country" name="current_residence_country" value="{{ old('current_residence_country', $booking->current_residence_country) }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('current_residence_country') border-red-500 @enderror"
                                   placeholder="{{ t('Enter current residence country') }}">
                            @error('current_residence_country')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="destination_country" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ t('Destination Country') }}
                            </label>
                            <input type="text" id="destination_country" name="destination_country" value="{{ old('destination_country', $booking->destination_country) }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('destination_country') border-red-500 @enderror"
                                   placeholder="{{ t('Enter destination country') }}">
                            @error('destination_country')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="travel_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ t('Travel Date') }}
                            </label>
                            <input type="date" id="travel_date" name="travel_date" value="{{ old('travel_date', $booking->travel_date?->format('Y-m-d')) }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('travel_date') border-red-500 @enderror">
                            @error('travel_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div x-data="{ photoName: null, photoPreview: null }">
                            <label for="image" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ t('Passport Image / Attachment') }} <span class="text-xs text-gray-400">({{ t('Max 4MB: JPG, PNG, PDF') }})</span>
                            </label>
                            <input type="file" id="image" name="image" accept=".jpg,.jpeg,.png,.webp,.pdf"
                                   @change="
                                        const file = $event.target.files[0];
                                        if (file) {
                                            const reader = new FileReader();
                                            reader.onload = (e) => {
                                                photoPreview = e.target.result;
                                            };
                                            reader.readAsDataURL(file);
                                        }
                                   "
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('image') border-red-500 @enderror">
                            
                            {{-- Preview Image --}}
                            <div class="mt-3" x-show="photoPreview" style="display: none;">
                                <span class="block text-xs font-medium text-gray-500 mb-2">{{ t('New Image Preview') }}</span>
                                <img :src="photoPreview" class="rounded-lg h-32 w-auto object-cover border border-gray-200 dark:border-gray-700 shadow-sm">
                            </div>

                            @if($booking->image)
                                <div class="mt-3" x-show="!photoPreview">
                                    <span class="block text-xs font-medium text-gray-500 mb-2">{{ t('Current Passport') }}</span>
                                    <a href="{{ asset('storage/' . $booking->image) }}" target="_blank" class="block w-fit">
                                        @php $extension = pathinfo($booking->image, PATHINFO_EXTENSION); @endphp
                                        @if(in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'webp']))
                                            <img src="{{ asset('storage/' . $booking->image) }}" class="rounded-lg h-32 w-auto object-cover border border-gray-200 dark:border-gray-700 shadow-sm opacity-70 hover:opacity-100 transition-opacity">
                                        @else
                                            <div class="flex items-center text-primary-600 hover:text-primary-700">
                                                <x-heroicon-o-document-arrow-down class="w-5 h-5 mr-1" />
                                                <span class="text-sm">{{ t('View Current File') }}</span>
                                            </div>
                                        @endif
                                    </a>
                                </div>
                            @endif

                            @error('image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Booking Details --}}
                <div class="border-b border-gray-200 dark:border-gray-700 pb-6" x-data="{ numPassengers: {{ old('number_of_passengers', $booking->number_of_passengers) }}, extraDetails: {{ json_encode($booking->passenger_details ?? []) }} }">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4 flex items-center">
                        <x-heroicon-o-ticket class="w-5 h-5 mr-2 text-indigo-600" />
                        {{ t('Booking Details') }}
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="seat_class" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ t('Seat Class') }} <span class="text-red-500">*</span>
                            </label>
                            <select id="seat_class" name="seat_class" required
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('seat_class') border-red-500 @enderror">
                                <option value="economy" {{ old('seat_class', $booking->seat_class) === 'economy' ? 'selected' : '' }}>{{ t('Economy') }}</option>
                                <option value="business" {{ old('seat_class', $booking->seat_class) === 'business' ? 'selected' : '' }}>{{ t('Business') }}</option>
                                <option value="first" {{ old('seat_class', $booking->seat_class) === 'first' ? 'selected' : '' }}>{{ t('First') }}</option>
                            </select>
                            @error('seat_class')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="number_of_passengers" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ t('Number of Passengers') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="number_of_passengers" name="number_of_passengers" 
                                   x-model="numPassengers"
                                   value="{{ old('number_of_passengers', $booking->number_of_passengers) }}" min="1" max="9" required
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('number_of_passengers') border-red-500 @enderror"
                                   placeholder="{{ t('Enter number of passengers') }}">
                            @error('number_of_passengers')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Additional Passengers Section --}}
                    <div class="mt-6 bg-gray-50 dark:bg-gray-900/50 p-4 rounded-xl border border-gray-100 dark:border-gray-700" x-show="numPassengers > 1" style="display: none;">
                        <h4 class="text-sm font-bold text-gray-900 dark:text-white mb-4">{{ t('Details for Additional Passengers') }}</h4>
                        <template x-for="i in parseInt(numPassengers - 1)" :key="i">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4 pb-4 border-b border-gray-200 dark:border-gray-700 last:border-0 last:pb-0">
                                <div>
                                    <label :for="'extra_name_' + i" class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1" x-text="'{{ t('Passenger Name') }} ' + (parseInt(i) + 1)"></label>
                                    <input type="text" :id="'extra_name_' + i" :name="'passenger_details[' + i + '][name]'" 
                                           :value="extraDetails && extraDetails[i] ? extraDetails[i].name : ''"
                                           class="w-full px-3 py-1.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-900 shadow-sm"
                                           :placeholder="'{{ t('Enter passenger name') }}'">
                                </div>
                                <div>
                                    <label :for="'extra_passport_' + i" class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">{{ t('Passport Number') }}</label>
                                    <input type="text" :id="'extra_passport_' + i" :name="'passenger_details[' + i + '][passport]'" 
                                           :value="extraDetails && extraDetails[i] ? extraDetails[i].passport : ''"
                                           class="w-full px-3 py-1.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-900 shadow-sm"
                                           placeholder="{{ t('Enter passport number') }}">
                                </div>
                            </div>
                        </template>
                    </div>

                    <div class="mt-6">
                        <label for="special_requests" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ t('Special Requests') }}
                        </label>
                        <textarea id="special_requests" name="special_requests" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('special_requests') border-red-500 @enderror"
                                  placeholder="{{ t('Enter any special requests') }}">{{ old('special_requests', $booking->special_requests) }}</textarea>
                        @error('special_requests')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Status Information --}}
                <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4 flex items-center">
                        <x-heroicon-o-cog-6-tooth class="w-5 h-5 mr-2 text-indigo-600" />
                        {{ t('Status Information') }}
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ t('Booking Status') }} <span class="text-red-500">*</span>
                            </label>
                            <select id="status" name="status" required
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('status') border-red-500 @enderror">
                                <option value="pending" {{ old('status', $booking->status) === 'pending' ? 'selected' : '' }}>{{ t('Pending') }}</option>
                                <option value="confirmed" {{ old('status', $booking->status) === 'confirmed' ? 'selected' : '' }}>{{ t('Confirmed') }}</option>
                                <option value="cancelled" {{ old('status', $booking->status) === 'cancelled' ? 'selected' : '' }}>{{ t('Cancelled') }}</option>
                                <option value="completed" {{ old('status', $booking->status) === 'completed' ? 'selected' : '' }}>{{ t('Completed') }}</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="payment_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ t('Payment Status') }} <span class="text-red-500">*</span>
                            </label>
                            <select id="payment_status" name="payment_status" required
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('payment_status') border-red-500 @enderror">
                                <option value="pending" {{ old('payment_status', $booking->payment_status) === 'pending' ? 'selected' : '' }}>{{ t('Pending') }}</option>
                                <option value="paid" {{ old('payment_status', $booking->payment_status) === 'paid' ? 'selected' : '' }}>{{ t('Paid') }}</option>
                                <option value="failed" {{ old('payment_status', $booking->payment_status) === 'failed' ? 'selected' : '' }}>{{ t('Failed') }}</option>
                                <option value="refunded" {{ old('payment_status', $booking->payment_status) === 'refunded' ? 'selected' : '' }}>{{ t('Refunded') }}</option>
                            </select>
                            @error('payment_status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Flight Information (Read Only) --}}
                <div class="pb-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4 flex items-center">
                      {{-- <x-heroicon-o-plane class="w-5 h-5 mr-2 text-indigo-600" /> --}}
                      
                      <x-icons icon="plane" class="w-5 h-5 mr-2 text-indigo-600" />
                        {{ t('Flight Information') }}
                    </h3>
                    
                    <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('Flight Number') }}:</span>
                                <span class="text-sm text-gray-900 dark:text-white ml-2">{{ $booking->flight->flight_number }}</span>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('Route') }}:</span>
                                <span class="text-sm text-gray-900 dark:text-white ml-2">{{ $booking->flight->departure_city }} → {{ $booking->flight->arrival_city }}</span>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('Departure Time') }}:</span>
                                <span class="text-sm text-gray-900 dark:text-white ml-2">{{ $booking->flight->departure_time->format('Y-m-d H:i') }}</span>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('Total Amount') }}:</span>
                                <span class="text-sm text-gray-900 dark:text-white ml-2 font-semibold">{{ number_format($booking->total_amount + $booking->tax_amount + $booking->service_fee, 2) }} {{ $booking->currency }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('dashboard.bookings.show', $booking) }}" 
                       class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                        {{ t('Cancel') }}
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                        {{ t('Update Booking') }}
                    </button>
                </div>
            </form>
        </div>
    </x-dashboard.outer-card>
@endsection
