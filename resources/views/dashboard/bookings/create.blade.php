@extends('dashboard.layout.admin', ['title' => t('Add New Booking')])

@section('content')
    <x-dashboard.outer-card :title="t('Add New Booking')">
        <x-slot:header>
            <div class="flex px-4 border-b-1 border-b-gray-500 flex-col items-stretch justify-between py-4 space-y-3 md:flex-row md:items-center md:space-y-0">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">
                    {{ t('Add New Booking') }}
                </h2>
                <div class="flex items-center gap-2">
                    <x-inputs.button-primary as="a" href="{{ route('dashboard.bookings.index') }}">
                        {{ t('Back to Bookings') }}
                        <x-heroicon-s-arrow-left class="h-4 w-4 {{app()->getLocale() == 'ar' ? 'mr-2' : 'ml-2'}} inline" />
                    </x-inputs.button-primary>
                </div>
            </div>
        </x-slot:header>

        <div class="p-6">
            <form action="{{ route('dashboard.bookings.store') }}" method="POST" class="space-y-6" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">                   
                    <div>
                        <label for="flight_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ t('Select Flight') }} <span class="text-red-500">*</span>
                        </label>
                        <select id="flight_id" name="flight_id" 
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
                            <p class="mt-1 text-sm text-red-600">{{ t($message) }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="passenger_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ t('Passenger Name') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="passenger_name" name="passenger_name" value="{{ old('passenger_name') }}" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('passenger_name') border-red-500 @enderror">
                            @error('passenger_name')
                                <p class="mt-1 text-sm text-red-600">{{ t($message) }}</p>
                            @enderror
                    </div>

                    <div>
                        <label for="passenger_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ t('Email') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="email" id="passenger_email" name="passenger_email" value="{{ old('passenger_email') }}" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('passenger_email') border-red-500 @enderror">
                            @error('passenger_email')
                                <p class="mt-1 text-sm text-red-600">{{ t($message) }}</p>
                            @enderror
                    </div>

                    <div x-data="{ photoName: null, photoPreview: null }" class="relative overflow-hidden">
                        <label for="image" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-id-card dark:text-gray-300 mx-1"></i>
                            {{ t('Passport Image / Attachment') }} <span class="text-xs text-gray-400">({{ t('Max 4MB: JPG, PNG, PDF') }})</span>
                        </label>
                        
                        <!-- الحاوية المخصصة لزر الإرفاق -->
                        <div :class="photoName ? 'border-green-400 bg-green-50' : 'border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800'" 
                             class="flex items-center justify-between p-3 border-2 border-dashed rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition duration-150 ease-in-out cursor-pointer dark:text-gray-300">
                            <span class="truncate text-sm font-medium">
                                <template x-if="photoName">
                                    <span><i class="fas fa-check-circle text-green-500 mx-2"></i> <span x-text="photoName"></span></span>
                                </template>
                                <template x-if="!photoName">
                                    <span><i class="fas fa-upload text-indigo-500 mx-2"></i> {{t('Click here to select a file')}} </span>
                                </template>
                            </span>
                            <span class="text-xs font-semibold text-indigo-600 bg-indigo-100 px-3 py-1 rounded-full shadow-sm">
                                {{ t('Choose') }}
                            </span>
                        </div>

                        <input type="file" id="image" name="image" accept=".jpg,.jpeg,.png,.webp,.pdf"
                                style="opacity: 0; position: absolute; width: 100%; height: 100%; cursor: pointer; top: 0; right: 0; z-index: 10;"
                                @change="
                                    const file = $event.target.files[0];
                                    if (file) {
                                        photoName = file.name;
                                        const reader = new FileReader();
                                        reader.onload = (e) => {
                                            photoPreview = e.target.result;
                                        };
                                        reader.readAsDataURL(file);
                                    } else {
                                        photoName = null;
                                        photoPreview = null;
                                    }
                                "
                                class="w-full @error('image') border-red-500 @enderror">
                        
                        {{-- Preview Image --}}
                        <div class="mt-3" x-show="photoPreview" style="display: none;">
                            <span class="block text-xs font-medium text-gray-500 mb-2">{{ t('Image Preview') }}</span>
                            <img :src="photoPreview" class="rounded-lg h-32 w-auto object-cover border border-gray-200 dark:border-gray-700 shadow-sm">
                        </div>

                        @error('image')
                            <p class="mt-1 text-sm text-red-600">{{ t($message) }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="passenger_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ t('Phone Sudia') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="passenger_phone" name="passenger_phone" value="{{ old('passenger_phone') }}" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('passenger_phone') border-red-500 @enderror">
                        @error('passenger_phone')
                            <p class="mt-1 text-sm text-red-600">{{ t($message) }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone_sudan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ t('Phone Sudan') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="phone_sudan" name="phone_sudan" value="{{ old('phone_sudan') }}" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('phone_sudan') border-red-500 @enderror">
                        @error('phone_sudan')
                            <p class="mt-1 text-sm text-red-600">{{ t($message) }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="passenger_id_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ t('National ID or Residence Number') }}
                        </label>
                        <input type="text" id="passenger_id_number" name="passenger_id_number" value="{{ old('passenger_id_number') }}"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('passenger_id_number') border-red-500 @enderror">
                        @error('passenger_id_number')
                            <p class="mt-1 text-sm text-red-600">{{ t($message) }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="current_residence_country" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ t('Current Country') }}
                        </label>
                        <input type="text" id="current_residence_country" name="current_residence_country" value="{{ old('current_residence_country') }}"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('current_residence_country') border-red-500 @enderror">
                        @error('current_residence_country')
                            <p class="mt-1 text-sm text-red-600">{{ t($message) }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="destination_country" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ t('Destination Country') }}
                        </label>
                        <input type="text" id="destination_country" name="destination_country" value="{{ old('destination_country') }}"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('destination_country') border-red-500 @enderror">
                        @error('destination_country')
                            <p class="mt-1 text-sm text-red-600">{{ t($message) }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="seat_class" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ t('Seat Class') }} <span class="text-red-500">*</span>
                        </label>
                        <select id="seat_class" name="seat_class" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('seat_class') border-red-500 @enderror">
                            <option value="">{{ t('Select seat class') }}</option>
                            <option value="economy" {{ old('seat_class') === 'economy' ? 'selected' : '' }}>{{ t('Economy') }}</option>
                            <option value="business" {{ old('seat_class') === 'business' ? 'selected' : '' }}>{{ t('Business') }}</option>
                            <option value="first" {{ old('seat_class') === 'first' ? 'selected' : '' }}>{{ t('First') }}</option>
                        </select>
                        @error('seat_class')
                            <p class="mt-1 text-sm text-red-600">{{ t($message) }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="ticket_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ t('Ticket Type') }} <span class="text-red-500">*</span>
                        </label>
                        <select id="ticket_type" name="ticket_type" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('ticket_type') border-red-500 @enderror">
                            <option value="one_way" {{ old('ticket_type', 'one_way') === 'one_way' ? 'selected' : '' }}>{{ t('One Way') }}</option>
                            <option value="round_trip" {{ old('ticket_type') === 'round_trip' ? 'selected' : '' }}>{{ t('Round Trip') }}</option>
                        </select>
                        @error('ticket_type')
                            <p class="mt-1 text-sm text-red-600">{{ t($message) }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="number_of_passengers" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ t('Number of Passengers') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="number_of_passengers" name="number_of_passengers" 
                                x-model="numPassengers"
                                value="{{ old('number_of_passengers', 1) }}"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('number_of_passengers') border-red-500 @enderror"
                                placeholder="{{ t('Enter number of passengers') }}">
                        @error('number_of_passengers')
                            <p class="mt-1 text-sm text-red-600">{{ t($message) }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold dark:text-gray-300 mb-2">
                            <i class="fas fa-credit-card dark:text-gray-300 ml-1"></i>
                            طريقة الدفع *
                        </label>
                        <select name="payment_method"  
                                class="w-full px-4 py-3 border-2 {{ $errors->has('payment_method') ? 'border-red-500' : 'border-gray-200' }} rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent input-focus">
                            <option value="">اختر طريقة الدفع</option>
                            <option value="on_arrival" {{ old('payment_method') == 'on_arrival' ? 'selected' : '' }}>
                                الدفع عند الحضور
                            </option>
                            <option value="whatsapp" {{ old('payment_method') == 'whatsapp' ? 'selected' : '' }}>
                                الدفع عبر الواتساب
                            </option>
                            <option value="tap_payment" disabled {{ old('payment_method') == 'tap_payment' ? 'selected' : '' }}>
                                Tap Payment - قريباً (غير متاح حالياً)
                            </option>
                        </select>
                        @error('payment_method')
                            <p class="mt-1 text-sm text-red-600">{{ t($message) }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="payment_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ t('Payment Status') }} <span class="text-red-500">*</span>
                        </label>
                        <select id="payment_status" name="payment_status" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('payment_status') border-red-500 @enderror">
                            <option value="pending" {{ old('payment_status', 'pending') === 'pending' ? 'selected' : '' }}>{{ t('Pending') }}</option>
                            <option value="paid" {{ old('payment_status') === 'paid' ? 'selected' : '' }}>{{ t('Paid') }}</option>
                            <option value="failed" {{ old('payment_status') === 'failed' ? 'selected' : '' }}>{{ t('Failed') }}</option>
                        </select>
                        @error('payment_status')
                            <p class="mt-1 text-sm text-red-600">{{ t($message) }}</p>
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
                                           class="w-full px-3 py-1.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-900 shadow-sm"
                                           :placeholder="'{{ t('Enter passenger name') }}'">
                                </div>
                                <div>
                                    <label :for="'extra_passport_' + i" class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">{{ t('Passport Number') }}</label>
                                    <input type="text" :id="'extra_passport_' + i" :name="'passenger_details[' + i + '][passport]'" 
                                           class="w-full px-3 py-1.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-900 shadow-sm"
                                           placeholder="{{ t('Enter passport number') }}">
                                </div>
                            </div>
                        </template>
                    </div>

                    <div class="mt-4">
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
