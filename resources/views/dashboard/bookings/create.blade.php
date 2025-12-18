@extends('dashboard.layout.admin', ['title' => t('Add New Booking')])

@section('content')
    @include('dashboard.layout.shared/page-title', ['subtitle' => t('Add New Booking'), 'title' => 'Dashboard'])

    <x-dashboard.outer-card :title="t('Add New Booking')">
        <x-slot:header>
            <div class="flex px-4 border-b-1 border-b-gray-500 flex-col items-stretch justify-between py-4 space-y-3 md:flex-row md:items-center md:space-y-0">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">
                    {{ t('Add New Booking') }}
                </h2>
                <div class="flex items-center gap-2">
                    <x-inputs.button-secondary as="a" href="{{ route('dashboard.bookings.index') }}">
                        <x-heroicon-s-arrow-left class="h-4 w-4 me-2 inline" />
                        {{ t('Back to Bookings') }}
                    </x-inputs.button-secondary>
                </div>
            </div>
        </x-slot:header>

        <div class="p-6">
            <form action="{{ route('dashboard.bookings.store') }}" method="POST" class="space-y-6" enctype="multipart/form-data">
                @csrf

                {{-- Flight Selection --}}
                <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4 flex items-center">
                        {{-- <x-heroicon-o-plane class="w-5 h-5 mr-2 text-indigo-600" /> --}}
                        <x-icons icon="plane" class="w-5 h-5 mr-2 text-indigo-600" />
                        {{ t('Flight Selection') }}
                    </h3>
                    
                    <div>
                        <label for="flight_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ t('Select Flight') }} <span class="text-red-500">*</span>
                        </label>
                        <select id="flight_id" name="flight_id" required
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('flight_id') border-red-500 @enderror">
                            <option value="">{{ t('Select a flight') }}</option>
                            @foreach(\App\Models\Flight::active()->upcoming()->with('bookings')->get() as $flight)
                                <option value="{{ $flight->id }}" {{ old('flight_id') == $flight->id ? 'selected' : '' }}>
                                    [{{ $flight->trip_type_label }}] {{ $flight->flight_number }} - {{ $flight->departure_city }} → {{ $flight->arrival_city }} 
                                    ({{ $flight->departure_time->format('Y-m-d H:i') }}) - 
                                    {{ $flight->available_seats }} {{ t('seats available') }}
                                </option>
                            @endforeach
                        </select>
                        @error('flight_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

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
                            <input type="text" id="passenger_name" name="passenger_name" value="{{ old('passenger_name') }}" required
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
                            <input type="email" id="passenger_email" name="passenger_email" value="{{ old('passenger_email') }}" required
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
                            <input type="text" id="passenger_phone" name="passenger_phone" value="{{ old('passenger_phone') }}" required
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
                            <input type="text" id="passenger_id_number" name="passenger_id_number" value="{{ old('passenger_id_number') }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('passenger_id_number') border-red-500 @enderror"
                                   placeholder="{{ t('Enter ID number') }}">
                            @error('passenger_id_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone_sudan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ t('Phone in Sudan') }}
                            </label>
                            <input type="text" id="phone_sudan" name="phone_sudan" value="{{ old('phone_sudan') }}"
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
                            <input type="text" id="passport_number" name="passport_number" value="{{ old('passport_number') }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('passport_number') border-red-500 @enderror"
                                   placeholder="{{ t('Enter passport number') }}">
                            @error('passport_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="image" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ t('Passport Image / Attachment') }}
                            </label>
                            <input type="file" id="image" name="image" accept=".jpg,.jpeg,.png,.webp,.pdf"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('image') border-red-500 @enderror">
                            @error('image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="nationality" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ t('Nationality') }}
                            </label>
                            <input type="text" id="nationality" name="nationality" value="{{ old('nationality') }}"
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
                            <input type="date" id="passport_issue_date" name="passport_issue_date" value="{{ old('passport_issue_date') }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('passport_issue_date') border-red-500 @enderror">
                            @error('passport_issue_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="passport_expiry_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ t('Passport Expiry Date') }}
                            </label>
                            <input type="date" id="passport_expiry_date" name="passport_expiry_date" value="{{ old('passport_expiry_date') }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('passport_expiry_date') border-red-500 @enderror">
                            @error('passport_expiry_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="date_of_birth" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ t('Date of Birth') }}
                            </label>
                            <input type="date" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('date_of_birth') border-red-500 @enderror">
                            @error('date_of_birth')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="current_residence_country" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ t('Current Residence Country') }}
                            </label>
                            <input type="text" id="current_residence_country" name="current_residence_country" value="{{ old('current_residence_country') }}"
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
                            <input type="text" id="destination_country" name="destination_country" value="{{ old('destination_country') }}"
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
                            <input type="date" id="travel_date" name="travel_date" value="{{ old('travel_date') }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('travel_date') border-red-500 @enderror">
                            @error('travel_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Booking Details --}}
                <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
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
                                <option value="">{{ t('Select seat class') }}</option>
                                <option value="economy" {{ old('seat_class') === 'economy' ? 'selected' : '' }}>{{ t('Economy') }}</option>
                                <option value="business" {{ old('seat_class') === 'business' ? 'selected' : '' }}>{{ t('Business') }}</option>
                                <option value="first" {{ old('seat_class') === 'first' ? 'selected' : '' }}>{{ t('First') }}</option>
                            </select>
                            @error('seat_class')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="cabin_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ t('Cabin Type') }}
                            </label>
                            <input type="text" id="cabin_type" name="cabin_type" value="{{ old('cabin_type') }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('cabin_type') border-red-500 @enderror"
                                   placeholder="{{ t('Enter cabin type (for sea trips)') }}">
                            @error('cabin_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="ticket_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ t('Ticket Type') }} <span class="text-red-500">*</span>
                            </label>
                            <select id="ticket_type" name="ticket_type" required
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('ticket_type') border-red-500 @enderror">
                                <option value="one_way" {{ old('ticket_type', 'one_way') === 'one_way' ? 'selected' : '' }}>{{ t('One Way') }}</option>
                                <option value="round_trip" {{ old('ticket_type') === 'round_trip' ? 'selected' : '' }}>{{ t('Round Trip') }}</option>
                            </select>
                            @error('ticket_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="number_of_passengers" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ t('Number of Passengers') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="number_of_passengers" name="number_of_passengers" value="{{ old('number_of_passengers', 1) }}" min="1" max="9" required
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('number_of_passengers') border-red-500 @enderror"
                                   placeholder="{{ t('Enter number of passengers') }}">
                            @error('number_of_passengers')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6">
                        <label for="special_requests" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ t('Special Requests') }}
                        </label>
                        <textarea id="special_requests" name="special_requests" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('special_requests') border-red-500 @enderror"
                                  placeholder="{{ t('Enter any special requests') }}">{{ old('special_requests') }}</textarea>
                        @error('special_requests')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Payment Information --}}
                <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4 flex items-center">
                        <x-heroicon-o-currency-dollar class="w-5 h-5 mr-2 text-indigo-600" />
                        {{ t('Payment Information') }}
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="payment_method" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ t('Payment Method') }}
                            </label>
                            <select id="payment_method" name="payment_method"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('payment_method') border-red-500 @enderror">
                                <option value="">{{ t('Select payment method') }}</option>
                                <option value="cash" {{ old('payment_method') === 'cash' ? 'selected' : '' }}>{{ t('Cash') }}</option>
                                <option value="paypal" {{ old('payment_method') === 'paypal' ? 'selected' : '' }}>{{ t('PayPal') }}</option>
                                <option value="credit_card" {{ old('payment_method') === 'credit_card' ? 'selected' : '' }}>{{ t('Credit Card') }}</option>
                            </select>
                            @error('payment_method')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="payment_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ t('Payment Status') }} <span class="text-red-500">*</span>
                            </label>
                            <select id="payment_status" name="payment_status" required
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('payment_status') border-red-500 @enderror">
                                <option value="pending" {{ old('payment_status', 'pending') === 'pending' ? 'selected' : '' }}>{{ t('Pending') }}</option>
                                <option value="paid" {{ old('payment_status') === 'paid' ? 'selected' : '' }}>{{ t('Paid') }}</option>
                                <option value="failed" {{ old('payment_status') === 'failed' ? 'selected' : '' }}>{{ t('Failed') }}</option>
                            </select>
                            @error('payment_status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('dashboard.bookings.index') }}" 
                       class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                        {{ t('Cancel') }}
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                        {{ t('Create Booking') }}
                    </button>
                </div>
            </form>
        </div>
    </x-dashboard.outer-card>
@endsection
