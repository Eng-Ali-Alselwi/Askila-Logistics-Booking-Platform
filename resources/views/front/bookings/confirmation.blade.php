@extends('layouts.app')

@section('head')
    <x-front.seo-head
        title="{{ __('messages.booking_confirmed_successfully') }} {{ $booking->booking_reference }} - {{ __('messages.company_name') }}"
        description="{{ __('messages.booking_confirmed_successfully') }} {{ __('messages.and') }} {{ __('messages.company_name') }}."
    />
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/aos.css') }}">
@endpush

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Success Header -->
    <div class="bg-gradient-to-r from-green-600 to-green-800 text-white py-16">
        <div class="container mx-auto px-4 text-center">
            <div class="bg-white bg-opacity-20 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6" data-aos="zoom-in">
                <i class="fas fa-check text-4xl"></i>
            </div>
            <h1 class="text-4xl font-bold mb-4" data-aos="fade-up">{{ __('messages.booking_confirmed_successfully') }}</h1>
            <p class="text-xl" data-aos="fade-up" data-aos-delay="200">
                {{ __('messages.thank_you_choosing') }}
            </p>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Booking Details -->
            <div class="lg:col-span-2">
                <!-- Flight Information -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6" data-aos="fade-up">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6">{{ __('messages.flight_details') }}</h2>
                    
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center space-x-4">
                            <div class="bg-blue-100 p-3 rounded-full">
                                <i class="fas fa-plane text-blue-600 text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800">{{ $booking->flight->airline }}</h3>
                                <p class="text-gray-600">{{ __('messages.flight_number') }}: {{ $booking->flight->flight_number }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-600">{{ __('messages.booking_reference') }}</p>
                            <p class="text-xl font-bold text-blue-600">{{ $booking->booking_reference }}</p>
                        </div>
                    </div>

                    <!-- Flight Route -->
                    <div class="flex items-center justify-between mb-6">
                        <div class="text-center">
                            <p class="text-sm text-gray-600">{{ __('messages.from') }}</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $booking->flight->departure_city }}</p>
                            <p class="text-lg text-gray-600">{{ $booking->flight->departure_time->format('H:i') }}</p>
                            <p class="text-sm text-gray-500">{{ $booking->flight->departure_time->format('Y-m-d') }}</p>
                        </div>
                        
                        <div class="flex-1 mx-8">
                            <div class="flex items-center justify-center">
                                <div class="w-full h-px bg-gray-300"></div>
                                <div class="bg-white px-4">
                                    <i class="fas fa-plane text-blue-600 text-2xl"></i>
                                </div>
                                <div class="w-full h-px bg-gray-300"></div>
                            </div>
                            <p class="text-center text-sm text-gray-600 mt-2">{{ $booking->flight->duration_formatted }}</p>
                        </div>
                        
                        <div class="text-center">
                            <p class="text-sm text-gray-600">{{ __('messages.to') }}</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $booking->flight->arrival_city }}</p>
                            <p class="text-lg text-gray-600">{{ $booking->flight->arrival_time->format('H:i') }}</p>
                            <p class="text-sm text-gray-500">{{ $booking->flight->arrival_time->format('Y-m-d') }}</p>
                        </div>
                    </div>

                    <!-- Flight Details -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-semibold text-gray-800 mb-3">{{ __('messages.flight_info') }}</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">{{ __('messages.aircraft_type') }}:</span>
                                    <span class="font-medium">{{ $booking->flight->aircraft_type ?? __('messages.not_specified') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">{{ __('messages.departure_airport') }}:</span>
                                    <span class="font-medium">{{ $booking->flight->departure_airport }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">{{ __('messages.arrival_airport') }}:</span>
                                    <span class="font-medium">{{ $booking->flight->arrival_airport }}</span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h4 class="font-semibold text-gray-800 mb-3">{{ __('messages.booking_details') }}</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">{{ __('messages.seat_class') }}:</span>
                                    <span class="font-medium">{{ ucfirst($booking->seat_class) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">{{ __('messages.number_of_passengers') }}:</span>
                                    <span class="font-medium">{{ $booking->number_of_passengers }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">{{ __('messages.booking_status') }}:</span>
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                                        {{ $booking->status === 'confirmed' ? __('messages.confirmed') : __('messages.pending') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Passenger Information -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6" data-aos="fade-up" data-aos-delay="200">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6">{{ __('messages.passenger_info') }}</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-semibold text-gray-800 mb-3">{{ __('messages.personal_data') }}</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">{{ __('messages.full_name') }}:</span>
                                    <span class="font-medium">{{ $booking->passenger_name }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">{{ __('messages.email') }}:</span>
                                    <span class="font-medium">{{ $booking->passenger_email }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">{{ __('messages.phone') }}:</span>
                                    <span class="font-medium">{{ $booking->passenger_phone }}</span>
                                </div>
                                @if($booking->passenger_id_number)
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">{{ __('messages.id_number') }}:</span>
                                        <span class="font-medium">{{ $booking->passenger_id_number }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div>
                            <h4 class="font-semibold text-gray-800 mb-3">{{ __('messages.additional_details') }}</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">{{ __('messages.booking_date') }}:</span>
                                    <span class="font-medium">{{ $booking->created_at->format('Y-m-d H:i') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">{{ __('messages.payment_method') }}:</span>
                                    <span class="font-medium">{{ ucfirst($booking->payment_method ?? __('messages.not_specified_short')) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">{{ __('messages.payment_status') }}:</span>
                                    <span class="px-2 py-1 {{ $booking->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }} rounded-full text-sm font-medium">
                                        {{ $booking->payment_status === 'paid' ? __('messages.paid') : __('messages.pending') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($booking->special_requests)
                        <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                            <h4 class="font-semibold text-blue-800 mb-2">{{ __('messages.special_requests') }}</h4>
                            <p class="text-blue-700">{{ $booking->special_requests }}</p>
                        </div>
                    @endif
                </div>

                <!-- Important Notes -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6" data-aos="fade-up" data-aos-delay="300">
                    <h3 class="text-lg font-semibold text-yellow-800 mb-4">{{ __('messages.important_notes') }}</h3>
                    <ul class="space-y-2 text-yellow-700">
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-yellow-600 ml-2 mt-1"></i>
                            <span>{{ __('messages.ticket_sent_email') }}</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-clock text-yellow-600 ml-2 mt-1"></i>
                            <span>{{ __('messages.arrive_2_hours_before') }}</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-id-card text-yellow-600 ml-2 mt-1"></i>
                            <span>{{ __('messages.bring_id_passport') }}</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-phone text-yellow-600 ml-2 mt-1"></i>
                            <span>{{ __('messages.contact_for_inquiries') }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Price Summary -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6 sticky top-4" data-aos="fade-up" data-aos-delay="400">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('messages.price_summary') }}</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ __('messages.base_price') }}:</span>
                            <span class="font-medium">{{ number_format($booking->total_amount) }} {{ __('messages.currency') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ __('messages.tax_15_percent') }}:</span>
                            <span class="font-medium">{{ number_format($booking->tax_amount) }} {{ __('messages.currency') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ __('messages.service_fee') }}:</span>
                            <span class="font-medium">{{ number_format($booking->service_fee) }} {{ __('messages.currency') }}</span>
                        </div>
                        <hr class="my-3">
                        <div class="flex justify-between text-lg font-bold">
                            <span>{{ __('messages.total') }}:</span>
                            <span class="text-green-600">{{ number_format($booking->total_amount + $booking->tax_amount + $booking->service_fee) }} {{ __('messages.currency') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="space-y-4" data-aos="fade-up" data-aos-delay="500">
                    <a href="{{ route('booking.track') }}" 
                       class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300 text-center block">
                        <i class="fas fa-search ml-2"></i>
                        {{ __('messages.track_another_booking') }}
                    </a>

                    <button onclick="window.print()" 
                            class="w-full bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300">
                        <i class="fas fa-print ml-2"></i>
                        {{ __('messages.print_ticket') }}
                    </button>

                    <a href="{{ route('home') }}" 
                       class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300 text-center block">
                        <i class="fas fa-home ml-2"></i>
                        {{ __('messages.back_to_home') }}
                    </a>
                </div>

                <!-- Contact Support -->
                <div class="bg-blue-50 rounded-lg p-6 mt-6" data-aos="fade-up" data-aos-delay="600">
                    <h3 class="text-lg font-semibold text-blue-800 mb-4">{{ __('messages.contact_support') }}</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex items-center">
                            <i class="fas fa-phone text-blue-600 ml-2"></i>
                            <span>+966 11 123 4567</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-envelope text-blue-600 ml-2"></i>
                            <span>support@askila.com</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-clock text-blue-600 ml-2"></i>
                            <span>{{ __('messages.available_24_7') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('assets/js/aos.js') }}"></script>
<script>
    AOS.init({
        duration: 1000,
        once: true
    });

    // طباعة التذكرة
    function printTicket() {
        window.print();
    }

    // إرسال إشعار نجاح الحجز (اختياري)
    const bookingConfirmedMsg = '{{ __('messages.booking_confirmed_successfully') }}';
    const ticketEmailMsg = '{{ __('messages.ticket_sent_email') }}';
    if ('Notification' in window && Notification.permission === 'granted') {
        new Notification(bookingConfirmedMsg, {
            body: ticketEmailMsg,
            icon: '/favicon.ico'
        });
    }
</script>
@endpush
@endsection
