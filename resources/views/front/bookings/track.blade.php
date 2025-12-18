@extends('layouts.app')

@section('head')
    <x-front.seo-head
        title="{{ isset($booking) ? __('messages.track_booking') . ' ' . $booking->booking_reference . ' - ' . __('messages.company_name') : __('messages.track_booking') . ' - ' . __('messages.company_name') }}"
        description="{{ isset($booking) ? __('messages.track_booking') . ' ' . __('messages.and') . ' ' . __('messages.company_name') . '.' : __('messages.track_booking') . ' ' . __('messages.and') . ' ' . __('messages.company_name') . '.' }}"
    />
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/aos.css') }}">
@endpush

@section('content')
<div class="min-h-screen bg-gray-50 mt-40">
    @if(isset($booking) && $booking)
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">{{ __('messages.track_booking') }}</h1>
                    <p class="text-gray-600">{{ __('messages.booking_reference') }}: {{ $booking->booking_reference }}</p>
                </div>
                <a href="{{ route('booking.track') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition duration-300">
                    <i class="fas fa-search ml-2"></i>
                    {{ __('messages.track_another_booking') }}
                </a>
            </div>
        </div>
    </div>

    <div class="container py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Booking Status -->
            <div class="lg:col-span-2">
                <!-- Status Overview -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6" data-aos="fade-up">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6">{{ __('messages.booking_status_overview') }}</h2>
                    
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center space-x-4">
                            <div class="bg-blue-100 p-3 rounded-full">
                                <i class="fas fa-ticket-alt text-blue-600 text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800">{{ $booking->flight->airline }}</h3>
                                <p class="text-gray-600">{{ __('messages.flight_number') }}: {{ $booking->flight->flight_number }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-600">{{ __('messages.booking_status') }}</p>
                            <span class="px-3 py-1 {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-800' : ($booking->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }} rounded-full text-sm font-medium">
                                @switch($booking->status)
                                    @case('confirmed')
                                        {{ __('messages.confirmed') }}
                                        @break
                                    @case('cancelled')
                                        {{ __('messages.cancelled') }}
                                        @break
                                    @case('completed')
                                        {{ __('messages.completed') }}
                                        @break
                                    @default
                                        {{ __('messages.pending') }}
                                @endswitch
                            </span>
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
                        
                        <!-- <div class="flex-1 mx-8">
                            <div class="flex items-center justify-center">
                                <div class="w-full h-px bg-gray-300"></div>
                                <div class="bg-white px-4">
                                    <i class="fas fa-plane text-blue-600 text-2xl"></i>
                                </div>
                                <div class="w-full h-px bg-gray-300"></div>
                            </div>
                            <p class="text-center text-sm text-gray-600 mt-2">{{ $booking->flight->duration_formatted }}</p>
                        </div> -->
                        
                        <div class="text-center">
                            <p class="text-sm text-gray-600">{{ __('messages.to') }}</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $booking->flight->arrival_city }}</p>
                            <p class="text-lg text-gray-600">{{ $booking->flight->arrival_time->format('H:i') }}</p>
                            <p class="text-sm text-gray-500">{{ $booking->flight->arrival_time->format('Y-m-d') }}</p>
                        </div>
                    </div>

                    <!-- Payment Status -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas fa-credit-card text-gray-600 ml-2"></i>
                                <span class="text-gray-700">{{ __('messages.payment_status_label') }}</span>
                            </div>
                            <span class="px-3 py-1 {{ $booking->payment_status === 'paid' ? 'bg-green-100 text-green-800' : ($booking->payment_status === 'failed' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }} rounded-full text-sm font-medium">
                                @switch($booking->payment_status)
                                    @case('paid')
                                        {{ __('messages.paid_status') }}
                                        @break
                                    @case('failed')
                                        {{ __('messages.payment_failed_status') }}
                                        @break
                                    @case('refunded')
                                        {{ __('messages.refunded_status') }}
                                        @break
                                    @default
                                        {{ __('messages.pending_status') }}
                                @endswitch
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Booking Timeline -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6" data-aos="fade-up" data-aos-delay="200">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6">{{ __('messages.booking_timeline') }}</h2>
                    
                    <div class="space-y-4">
                        <!-- Booking Created -->
                        <div class="flex items-start space-x-4">
                            <div class="bg-green-100 p-2 rounded-full">
                                <i class="fas fa-plus text-green-600"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-800">{{ __('messages.booking_created_title') }}</h4>
                                <p class="text-gray-600 text-sm">{{ $booking->created_at->format('Y-m-d H:i') }}</p>
                                <p class="text-gray-500 text-sm">{{ __('messages.booking_created_description') }}</p>
                            </div>
                        </div>

                        @if($booking->payment_status === 'paid')
                            <!-- Payment Completed -->
                            <div class="flex items-start space-x-4">
                                <div class="bg-green-100 p-2 rounded-full">
                                    <i class="fas fa-check text-green-600"></i>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-800">{{ __('messages.payment_completed_title') }}</h4>
                                    <p class="text-gray-600 text-sm">{{ $booking->payment_date ? $booking->payment_date->format('Y-m-d H:i') : $booking->updated_at->format('Y-m-d H:i') }}</p>
                                    <p class="text-gray-500 text-sm">{{ __('messages.payment_completed_description') }}</p>
                                </div>
                            </div>
                        @endif

                        @if($booking->status === 'confirmed')
                            <!-- Booking Confirmed -->
                            <div class="flex items-start space-x-4">
                                <div class="bg-blue-100 p-2 rounded-full">
                                    <i class="fas fa-check-circle text-blue-600"></i>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-800">{{ __('messages.booking_confirmed_title') }}</h4>
                                    <p class="text-gray-600 text-sm">{{ $booking->updated_at->format('Y-m-d H:i') }}</p>
                                    <p class="text-gray-500 text-sm">{{ __('messages.booking_confirmed_description') }}</p>
                                </div>
                            </div>
                        @endif

                        @if($booking->status === 'cancelled')
                            <!-- Booking Cancelled -->
                            <div class="flex items-start space-x-4">
                                <div class="bg-red-100 p-2 rounded-full">
                                    <i class="fas fa-times text-red-600"></i>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-800">{{ __('messages.booking_cancelled_title') }}</h4>
                                    <p class="text-gray-600 text-sm">{{ $booking->cancelled_at ? $booking->cancelled_at->format('Y-m-d H:i') : $booking->updated_at->format('Y-m-d H:i') }}</p>
                                    @if($booking->cancellation_reason)
                                        <p class="text-gray-500 text-sm">{{ __('messages.reason') }}: {{ $booking->cancellation_reason }}</p>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Flight Departure (if upcoming) -->
                        @if($booking->flight->departure_time > now() && $booking->status !== 'cancelled')
                            <div class="flex items-start space-x-4">
                                <div class="bg-yellow-100 p-2 rounded-full">
                                    <i class="fas fa-clock text-yellow-600"></i>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-800">{{ __('messages.departure_time_title') }}</h4>
                                    <p class="text-gray-600 text-sm">{{ $booking->flight->departure_time->format('Y-m-d H:i') }}</p>
                                    <p class="text-gray-500 text-sm">{{ __('messages.departure_time_description') }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                @if($booking->status !== 'cancelled')
                    <div class="bg-white rounded-lg shadow-md p-6" data-aos="fade-up" data-aos-delay="300">
                        <h2 class="text-xl font-semibold text-gray-800 mb-6">{{ __('messages.available_actions_title') }}</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @if($booking->canBeCancelled())
                                <button onclick="showCancelModal()" 
                                        class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300">
                                    <i class="fas fa-times ml-2"></i>
                                    {{ __('messages.cancel_booking_button') }}
                                </button>
                            @endif

                            <button onclick="window.print()" 
                                    class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300">
                                <i class="fas fa-print ml-2"></i>
                                {{ __('messages.print_ticket_button') }}
                            </button>

                            <a href="{{ route('booking.payment', $booking) }}" 
                               class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300 text-center block">
                                <i class="fas fa-credit-card ml-2"></i>
                                {{ __('messages.complete_payment_button') }}
                            </a>

                            <a href="{{ route('home') }}" 
                               class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300 text-center block">
                                <i class="fas fa-home ml-2"></i>
                                {{ __('messages.back_to_home_button') }}
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Booking Summary -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6" data-aos="fade-up" data-aos-delay="400">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('messages.booking_summary_title') }}</h3>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ __('messages.booking_reference_label_short') }}:</span>
                            <span class="font-medium font-mono">{{ $booking->booking_reference }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ __('messages.passenger_label') }}:</span>
                            <span class="font-medium">{{ $booking->passenger_name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ __('messages.seat_class_label') }}:</span>
                            <span class="font-medium">{{ ucfirst($booking->seat_class) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ __('messages.number_of_passengers_label') }}:</span>
                            <span class="font-medium">{{ $booking->number_of_passengers }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ __('messages.total_amount_label') }}:</span>
                            <span class="font-bold text-blue-600">{{ number_format($booking->total_amount + $booking->tax_amount + $booking->service_fee) }} {{ __('messages.currency') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Contact Support -->
                <div class="bg-blue-50 rounded-lg p-6" data-aos="fade-up" data-aos-delay="500">
                    <h3 class="text-lg font-semibold text-blue-800 mb-4">{{ __('messages.need_help_title') }}</h3>
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
                            <span>{{ __('messages.available_24_7_short') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cancel Modal -->
<div id="cancelModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg max-w-md w-full p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('messages.cancel_booking_modal_title') }}</h3>
            <p class="text-gray-600 mb-4">{{ __('messages.sure_cancel_booking_question') }}</p>
            
            <form action="{{ route('booking.cancel', $booking) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.cancellation_reason_label') }}</label>
                    <textarea name="cancellation_reason" required rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                              placeholder="{{ __('messages.cancellation_reason_placeholder') }}"></textarea>
                </div>
                
                <div class="flex space-x-4">
                    <button type="button" onclick="hideCancelModal()" 
                            class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg transition duration-300">
                        {{ __('messages.cancel_button') }}
                    </button>
                    <button type="submit" 
                            class="flex-1 bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300">
                        {{ __('messages.confirm_cancellation_button') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@else
    <!-- رسالة عدم وجود حجز -->
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto text-center">
            <div class="bg-white rounded-lg shadow-md p-8">
                <div class="text-6xl text-gray-400 mb-4">
                    <i class="fas fa-search"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-4">{{ __('messages.no_booking_found') }}</h2>
                <p class="text-gray-600 mb-6">{{ __('messages.please_check_booking_reference') }}</p>
                <a href="{{ route('booking.track') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300">
                    <i class="fas fa-search ml-2"></i>
                    {{ __('messages.track_another_booking') }}
                </a>
            </div>
        </div>
    </div>
@endif

@push('scripts')
<script src="{{ asset('assets/js/aos.js') }}"></script>
<script>
    AOS.init({
        duration: 1000,
        once: true
    });

    function showCancelModal() {
        document.getElementById('cancelModal').classList.remove('hidden');
    }

    function hideCancelModal() {
        document.getElementById('cancelModal').classList.add('hidden');
    }

    // إغلاق النافذة عند النقر خارجها
    document.getElementById('cancelModal').addEventListener('click', function(e) {
        if (e.target === this) {
            hideCancelModal();
        }
    });
</script>
@endpush
@endsection
