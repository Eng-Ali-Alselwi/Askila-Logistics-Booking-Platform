@extends('dashboard.layout.admin', ['title' => t('Booking Details')])

@section('content')
    <x-dashboard.outer-card :title="t('Booking Details')">
        <x-slot:header>
            <div class="flex px-4 border-b-1 border-b-gray-500 flex-col items-stretch justify-between py-4 space-y-3 md:flex-row md:items-center md:space-y-0">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">
                    {{ t('Booking Details') }} - {{ $booking->booking_reference }}
                </h2>
                <div class="flex items-center">
                    <x-inputs.button-primary as="a" href="{{ route('dashboard.bookings.index') }}">
                        {{ t('Back to Bookings') }}
                        <x-heroicon-s-arrow-left class="h-4 w-4 {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }} inline" />
                    </x-inputs.button-primary>
                </div>
            </div>
        </x-slot:header>

        <div class="p-3">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Booking Information --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ t('Booking Information') }}</h3>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('Booking Reference') }}</span>
                            <span class="text-sm text-gray-900 dark:text-white font-semibold">{{ $booking->booking_reference }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('Status') }}</span>
                            @php
                                $statusColor = match($booking->status) {
                                    'confirmed' => 'bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-200',
                                    'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/40 dark:text-yellow-200',
                                    'cancelled' => 'bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-200',
                                    'completed' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-200',
                                    default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200',
                                };
                            @endphp
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs {{ $statusColor }}">
                                {{ t(ucfirst($booking->status)) }}
                            </span>
                        </div>
                        
                        <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('Payment Status') }}</span>
                            @php
                                $paymentStatusColor = match($booking->payment_status) {
                                    'paid' => 'bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-200',
                                    'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/40 dark:text-yellow-200',
                                    'pending_manual' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-200',
                                    'failed' => 'bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-200',
                                    'refunded' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200',
                                    default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200',
                                };
                                $paymentStatusText = match($booking->payment_status) {
                                    'pending_manual' => t('Manual Confirmation Pending'),
                                    default => t(ucfirst($booking->payment_status)),
                                };
                            @endphp
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs {{ $paymentStatusColor }}">
                                {{ $paymentStatusText }}
                            </span>
                        </div>
                        
                        <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('Seat Class') }}</span>
                            <span class="text-sm text-gray-900 dark:text-white">{{ t(ucfirst($booking->seat_class)) }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('Number of Passengers') }}</span>
                            <span class="text-sm text-gray-900 dark:text-white font-semibold">{{ $booking->number_of_passengers }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center py-2">
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('Booking Date') }}</span>
                            <span class="text-sm text-gray-900 dark:text-white">{{ $booking->created_at->format('Y-m-d H:i') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Passenger Information --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ t('Passenger Information') }}</h3>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('Passenger Name') }}</span>
                            <span class="text-sm text-gray-900 dark:text-white font-semibold">{{ $booking->passenger_name }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('Email') }}</span>
                            <span class="text-sm text-gray-900 dark:text-white">{{ $booking->passenger_email }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('ID Number') }}</span>
                            <span class="text-sm text-gray-900 dark:text-white">{{ $booking->passenger_id_number ?? t('Not provided') }}</span>
                        </div>
                  
                        <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('Phone Sudia') }}</span>
                            <span class="text-sm text-gray-900 dark:text-white">{{ $booking->passenger_phone }}</span>
                        </div>

                        @if($booking->phone_sudan)
                            <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('Phone Sudan') }}</span>
                                <span class="text-sm text-gray-900 dark:text-white">{{ $booking->phone_sudan }}</span>
                            </div>
                        @endif
                        
                        @if($booking->passenger_details && count($booking->passenger_details) > 0)
                            <div class="mt-6 pt-4 border-t border-gray-100 dark:border-gray-700">
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400 block mb-3">{{ t('Additional Passengers') }}</span>
                                <div class="grid grid-cols-1 gap-3">
                                    @foreach($booking->passenger_details as $index => $extra)
                                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-900 rounded-lg">
                                            <div>
                                                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $extra['name'] ?? t('Unnamed') }}</p>
                                                <p class="text-xs text-gray-500">{{ t('Passport') }}: {{ $extra['passport'] ?? t('N/A') }}</p>
                                            </div>
                                            <span class="text-[10px] font-bold bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded-full">#{{ $index + 2 }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if($booking->special_requests)
                            <div class="mt-6 py-2 border-t border-gray-100 dark:border-gray-700">
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('Special Requests') }}</span>
                                <p class="text-sm text-gray-700 dark:text-gray-300 mt-1">{{ $booking->special_requests }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Passport & Travel Information --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ t('Passport & Travel Information') }}</h3>
                    
                    <div class="space-y-3">
                        @if($booking->passport_number)
                            <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('Passport Number') }}</span>
                                <span class="text-sm text-gray-900 dark:text-white">{{ $booking->passport_number }}</span>
                            </div>
                        @endif

                        @if($booking->nationality)
                            <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('Nationality') }}</span>
                                <span class="text-sm text-gray-900 dark:text-white">{{ $booking->nationality }}</span>
                            </div>
                        @endif

                        @if($booking->date_of_birth)
                            <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('Date of Birth') }}</span>
                                <span class="text-sm text-gray-900 dark:text-white">{{ $booking->date_of_birth->format('Y-m-d') }}</span>
                            </div>
                        @endif

                        @if($booking->passport_issue_date)
                            <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('Passport Issue Date') }}</span>
                                <span class="text-sm text-gray-900 dark:text-white">{{ $booking->passport_issue_date->format('Y-m-d') }}</span>
                            </div>
                        @endif

                        @if($booking->passport_expiry_date)
                            <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('Passport Expiry Date') }}</span>
                                <span class="text-sm text-gray-900 dark:text-white @if($booking->passport_expiry_date->isPast()) text-red-600 font-bold @endif">
                                    {{ $booking->passport_expiry_date->format('Y-m-d') }}
                                    @if($booking->passport_expiry_date->isPast()) ({{ t('Expired') }}) @endif
                                </span>
                            </div>
                        @endif

                        @if($booking->current_residence_country)
                            <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('Current Residence') }}</span>
                                <span class="text-sm text-gray-900 dark:text-white">{{ $booking->current_residence_country }}</span>
                            </div>
                        @endif

                        @if($booking->destination_country)
                            <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('Destination Country') }}</span>
                                <span class="text-sm text-gray-900 dark:text-white">{{ $booking->destination_country }}</span>
                            </div>
                        @endif

                        @if($booking->travel_date)
                            <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('Travel Date') }}</span>
                                <span class="text-sm text-gray-900 dark:text-white font-semibold">{{ $booking->travel_date->format('Y-m-d') }}</span>
                            </div>
                        @endif

                        {{-- Passport Image Display --}}
                        <div class="mt-4">
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400 block mb-3">{{ t('Passport Image / Attachment') }}</span>
                            @if($booking->image)
                                <div class="relative group">
                                    <a href="{{ asset('storage/' . $booking->image) }}" target="_blank" class="block border dark:border-gray-600 rounded-lg overflow-hidden hover:shadow-lg transition-shadow bg-gray-50 dark:bg-gray-900">
                                        @php $extension = pathinfo($booking->image, PATHINFO_EXTENSION); @endphp
                                        @if(in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'webp']))
                                            <img src="{{ asset('storage/' . $booking->image) }}" alt="{{ t('Passport Image') }}" class="w-full h-auto max-h-80 object-contain">
                                        @else
                                            <div class="p-8 flex flex-col items-center justify-center text-indigo-600 dark:text-indigo-400">
                                                <x-heroicon-o-document-arrow-down class="w-16 h-16 mb-2" />
                                                <span class="text-sm font-medium">{{ t('Download File') }} ({{ strtoupper($extension) }})</span>
                                                <span class="text-xs text-gray-500 mt-1">{{ basename($booking->image) }}</span>
                                            </div>
                                        @endif
                                    </a>
                                </div>
                            @else
                                <p class="text-sm text-gray-400 italic">{{ t('No image uploaded') }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Flight Information --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ t('Flight Information') }}</h3>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('Flight Number') }}</span>
                            <span class="text-sm text-gray-900 dark:text-white font-semibold">{{ $booking->flight->flight_number }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('Airline') }}</span>
                            <span class="text-sm text-gray-900 dark:text-white">{{ $booking->flight->airline }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('Route') }}</span>
                            <span class="text-sm text-gray-900 dark:text-white">{{ $booking->flight->departure_city }} → {{ $booking->flight->arrival_city }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('Departure Time') }}</span>
                            <span class="text-sm text-gray-900 dark:text-white">{{ $booking->flight->departure_time->format('Y-m-d H:i') }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('Arrival Time') }}</span>
                            <span class="text-sm text-gray-900 dark:text-white">{{ $booking->flight->arrival_time->format('Y-m-d H:i') }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center py-2">
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('Duration') }}</span>
                            <span class="text-sm text-gray-900 dark:text-white">{{ $booking->flight->duration_formatted }}</span>
                        </div>
                    </div>
                </div>

                {{-- Payment Information --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ t('Payment Information') }}</h3>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('Base Amount') }}</span>
                            <span class="text-sm text-gray-900 dark:text-white">{{ number_format($booking->total_amount, 2) }} {{ $booking->currency }}</span>
                        </div>
                                              
                        @if($booking->payment_method)
                            <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('Payment Method') }}</span>
                                <span class="text-sm text-gray-900 dark:text-white">
                                    {{ t($booking->payment_method) }}
                                </span>
                            </div>
                        @endif
                        
                        @if($booking->payment_reference)
                            <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('Payment Reference') }}</span>
                                <span class="text-sm text-gray-900 dark:text-white">{{ $booking->payment_reference }}</span>
                            </div>
                        @endif
                        
                        @if($booking->payment_date)
                            <div class="flex justify-between items-center py-2">
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('Payment Date') }}</span>
                                <span class="text-sm text-gray-900 dark:text-white">{{ $booking->payment_date->format('Y-m-d H:i') }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Cancellation Information --}}
            @if($booking->status === 'cancelled')
                <div class="mt-6 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ t('Cancellation Information') }}</h3>
                    
                    <div class="space-y-4">
                        @if($booking->cancelled_at)
                            <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('Cancelled At') }}</span>
                                <span class="text-sm text-gray-900 dark:text-white">{{ $booking->cancelled_at->format('Y-m-d H:i') }}</span>
                            </div>
                        @endif
                        
                        @if($booking->cancellation_reason)
                            <div class="py-2">
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('Cancellation Reason') }}</span>
                                <p class="text-sm text-gray-700 dark:text-gray-300 mt-1">{{ $booking->cancellation_reason }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Payment History --}}
            @if($booking->payments->count() > 0)
                <div class="mt-6 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ t('Payment History') }}</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-4 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-300">{{ t('Date') }}</th>
                                    <th class="px-4 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-300">{{ t('Amount') }}</th>
                                    <th class="px-4 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-300">{{ t('Method') }}</th>
                                    <th class="px-4 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-300">{{ t('Status') }}</th>
                                    <th class="px-4 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-300">{{ t('Reference') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($booking->payments as $payment)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ $payment->created_at->format('Y-m-d H:i') }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ number_format($payment->amount, 2) }} {{ $payment->currency }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ t(ucfirst($payment->payment_method)) }}</td>
                                        <td class="px-4 py-3 text-sm">
                                            @php
                                                $paymentStatusColor = match($payment->status) {
                                                    'completed' => 'bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-200',
                                                    'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/40 dark:text-yellow-200',
                                                    'failed' => 'bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-200',
                                                    default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200',
                                                };
                                            @endphp
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs {{ $paymentStatusColor }}">
                                                {{ t(ucfirst($payment->status)) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ $payment->payment_reference }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </x-dashboard.outer-card>
@endsection
