@extends('dashboard.layout.admin', ['title' => t('Flight Details')])

@section('content')
    @include('dashboard.layout.shared/page-title', ['subtitle' => t('Flight Details'), 'title' => 'Dashboard'])

    <x-dashboard.outer-card :title="t('Flight Details')">
        <x-slot:header>
            <div class="flex px-4 border-b-1 border-b-gray-500 flex-col items-stretch justify-between py-4 space-y-3 md:flex-row md:items-center md:space-y-0">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">
                    {{ t('Flight Details') }} - {{ $flight->flight_number }}
                </h2>
                <div class="flex items-center gap-2">
                    <x-inputs.button-secondary as="a" href="{{ route('dashboard.flights.index') }}">
                        <x-heroicon-s-arrow-left class="h-4 w-4 me-2 inline" />
                        {{ t('Back to Flights') }}
                    </x-inputs.button-secondary>
                    <x-inputs.button-primary as="a" href="{{ route('dashboard.flights.edit', $flight) }}">
                        <x-heroicon-s-pencil-square class="h-4 w-4 me-2 inline" />
                        {{ t('Edit Flight') }}
                    </x-inputs.button-primary>
                </div>
            </div>
        </x-slot:header>

        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Flight Information --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ t('Flight Information') }}</h3>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('Flight Number') }}</span>
                            <span class="text-sm text-gray-900 dark:text-white font-semibold">{{ $flight->flight_number }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('Airline') }}</span>
                            <span class="text-sm text-gray-900 dark:text-white">{{ $flight->airline }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('Aircraft Type') }}</span>
                            <span class="text-sm text-gray-900 dark:text-white">{{ $flight->aircraft_type ?? t('Not specified') }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('Duration') }}</span>
                            <span class="text-sm text-gray-900 dark:text-white">{{ $flight->duration_formatted }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('Base Price') }}</span>
                            <span class="text-sm text-gray-900 dark:text-white font-semibold">{{ number_format($flight->base_price, 2) }} {{ t('SAR') }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center py-2">
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('Status') }}</span>
                            @php
                                $statusColor = match(true) {
                                    !$flight->is_active => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200',
                                    $flight->available_seats == 0 => 'bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-200',
                                    $flight->available_seats <= $flight->total_seats * 0.2 => 'bg-orange-100 text-orange-800 dark:bg-orange-900/40 dark:text-orange-200',
                                    $flight->departure_time < now() => 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-200',
                                    default => 'bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-200',
                                };
                                $statusText = match(true) {
                                    !$flight->is_active => t('Inactive'),
                                    $flight->available_seats == 0 => t('Full'),
                                    $flight->available_seats <= $flight->total_seats * 0.2 => t('Almost Full'),
                                    $flight->departure_time < now() => t('Departed'),
                                    default => t('Available'),
                                };
                            @endphp
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs {{ $statusColor }}">
                                {{ $statusText }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Route Information --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ t('Route Information') }}</h3>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('Departure City') }}</span>
                            <span class="text-sm text-gray-900 dark:text-white">{{ $flight->departure_city }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('Departure Airport') }}</span>
                            <span class="text-sm text-gray-900 dark:text-white">{{ $flight->departure_airport }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('Arrival City') }}</span>
                            <span class="text-sm text-gray-900 dark:text-white">{{ $flight->arrival_city }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('Arrival Airport') }}</span>
                            <span class="text-sm text-gray-900 dark:text-white">{{ $flight->arrival_airport }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('Departure Time') }}</span>
                            <span class="text-sm text-gray-900 dark:text-white">{{ $flight->departure_time->format('Y-m-d H:i') }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center py-2">
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('Arrival Time') }}</span>
                            <span class="text-sm text-gray-900 dark:text-white">{{ $flight->arrival_time->format('Y-m-d H:i') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Seats Information --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ t('Seats Information') }}</h3>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('Total Seats') }}</span>
                            <span class="text-sm text-gray-900 dark:text-white font-semibold">{{ $flight->total_seats }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('Available Seats') }}</span>
                            <span class="text-sm text-gray-900 dark:text-white font-semibold">{{ $flight->available_seats }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('Booked Seats') }}</span>
                            <span class="text-sm text-gray-900 dark:text-white font-semibold">{{ $flight->total_seats - $flight->available_seats }}</span>
                        </div>
                        
                        <div class="py-2">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('Occupancy Rate') }}</span>
                                <span class="text-sm text-gray-900 dark:text-white">{{ round((($flight->total_seats - $flight->available_seats) / $flight->total_seats) * 100, 1) }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3">
                                <div class="bg-blue-600 h-3 rounded-full" style="width: {{ (($flight->total_seats - $flight->available_seats) / $flight->total_seats) * 100 }}%"></div>
                            </div>
                        </div>
                        
                        <div class="py-2">
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('Seat Classes') }}</span>
                            <div class="mt-2 flex flex-wrap gap-2">
                                @if($flight->seat_classes)
                                    @foreach($flight->seat_classes as $class)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-200">
                                            {{ ucfirst($class) }}
                                        </span>
                                    @endforeach
                                @else
                                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ t('Not specified') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Pricing Information --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ t('Pricing Information') }}</h3>
                    
                    <div class="space-y-4">
                        @if($flight->pricing_tiers)
                            @foreach($flight->pricing_tiers as $class => $price)
                                <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ ucfirst($class) }} {{ t('Class') }}</span>
                                    <span class="text-sm text-gray-900 dark:text-white font-semibold">{{ number_format($price, 2) }} {{ t('SAR') }}</span>
                                </div>
                            @endforeach
                        @else
                            <div class="flex justify-between items-center py-2">
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('Base Price') }}</span>
                                <span class="text-sm text-gray-900 dark:text-white font-semibold">{{ number_format($flight->base_price, 2) }} {{ t('SAR') }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Notes Section --}}
            @if($flight->notes)
                <div class="mt-6 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ t('Notes') }}</h3>
                    <p class="text-sm text-gray-700 dark:text-gray-300">{{ $flight->notes }}</p>
                </div>
            @endif

            {{-- Bookings Section --}}
            @if($flight->bookings->count() > 0)
                <div class="mt-6 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ t('Recent Bookings') }}</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-4 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-300">{{ t('Booking Reference') }}</th>
                                    <th class="px-4 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-300">{{ t('Passenger Name') }}</th>
                                    <th class="px-4 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-300">{{ t('Seat Class') }}</th>
                                    <th class="px-4 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-300">{{ t('Passengers') }}</th>
                                    <th class="px-4 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-300">{{ t('Total Amount') }}</th>
                                    <th class="px-4 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-300">{{ t('Status') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($flight->bookings->take(5) as $booking)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ $booking->booking_reference }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ $booking->passenger_name }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ ucfirst($booking->seat_class) }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ $booking->number_of_passengers }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ number_format($booking->total_amount, 2) }} {{ t('SAR') }}</td>
                                        <td class="px-4 py-3 text-sm">
                                            @php
                                                $bookingStatusColor = match($booking->status) {
                                                    'confirmed' => 'bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-200',
                                                    'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/40 dark:text-yellow-200',
                                                    'cancelled' => 'bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-200',
                                                    'completed' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-200',
                                                    default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200',
                                                };
                                            @endphp
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs {{ $bookingStatusColor }}">
                                                {{ ucfirst($booking->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    @if($flight->bookings->count() > 5)
                        <div class="mt-4 text-center">
                            <a href="{{ route('dashboard.bookings.index', ['flight_id' => $flight->id]) }}" 
                               class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                {{ t('View all bookings') }} ({{ $flight->bookings->count() }})
                            </a>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </x-dashboard.outer-card>
@endsection
