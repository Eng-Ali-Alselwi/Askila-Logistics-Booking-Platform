@extends('layouts.app')

@section('head')
    <x-front.seo-head
        title="{{ __('messages.track_booking_title') }} - {{ __('messages.company_name') }}"
        description="{{ __('messages.track_booking_description') }}"
    />
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/aos.css') }}">
    <style>
        /* Payment Modal Styles */
        .payment-method-card {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        /* Glassmorphism Effect for Modal Background */
        #paymentModal {
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            background: rgba(0, 0, 0, 0.3);
        }
        
        /* Glassmorphism Effect for Page Background */
        .page-blur {
            filter: blur(5px);
            -webkit-filter: blur(5px);
            transition: filter 0.3s ease;
        }
        
        .payment-method-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
            transition: left 0.5s;
        }
        
        .payment-method-card:hover::before {
            left: 100%;
        }
        
        .payment-method-card:hover {
            transform: translateY(-5px);
        }
        
        .payment-method-card.selected {
            border-color: #3b82f6;
            background: linear-gradient(135deg, #dbeafe, #f0f9ff);
        }
        
        /* Modal Animation */
        #paymentModal {
            transition: all 0.3s ease;
        }
        
        #paymentModal:not(.hidden) {
            animation: fadeIn 0.3s ease;
        }
        
        #paymentModal .bg-white {
            animation: slideIn 0.3s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideIn {
            from { 
                opacity: 0;
                transform: translateY(-50px) scale(0.9);
            }
            to { 
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .payment-method-card {
                margin-bottom: 1rem;
            }
        }
    </style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50 mt-30">
    
    {{-- رسالة النجاح من الدفع --}}
    @if(request('success') == 'true')
        <div class="container mx-auto px-4 pt-6">
            <div class="max-w-2xl mx-auto mb-6">
                <div class="bg-green-50 border border-green-200 rounded-lg p-6 shadow-lg" data-aos="fade-up">
                    <div class="flex items-center">
                        <div class="bg-green-500 p-3 rounded-full ml-4">
                            <i class="fas fa-check-circle text-white text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-green-900 font-bold text-xl mb-1">تم الدفع بنجاح!</h3>
                            <p class="text-green-800">تم إتمام عملية الدفع الخاصة بحجزك بنجاح. سيتم تأكيد الحجز قريباً.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    
    <div class="mb-4">
        <div class="container mx-auto p-4">
            <form action="{{ route('booking.track.search') }}" method="POST" class="max-w-2xl mx-auto">
                @csrf
                <div class="flex flex-row gap-4">
                    <div class="flex-1">
                        <input type="text" name="booking_reference"  
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                                placeholder="{{ __('messages.enter_booking_reference') }}"
                                value="{{ old('booking_reference', request('booking_reference')) }}">
                        @error('booking_reference')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex items-end">
                        <button type="submit" 
                                class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition duration-300 transform hover:scale-105">
                            <!-- <i class="fas fa-search ml-2"></i> -->
                            {{ __('messages.Search') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="px-10">
        @if($errors->any())
            <!-- Error Message -->
            <div class="max-w-2xl mx-auto mb-8">
                <div class="bg-red-50 border border-red-200 rounded-lg p-4" data-aos="fade-up">
                    <div class="flex items-center justify-center">
                        <div class="p-2 ml-3">
                            <i class="fas fa-exclamation-triangle text-red-600"></i>
                        </div>
                        <div class="mx-3">
                            @foreach($errors->all() as $error)
                                <p class="text-red-600 font-semibold">{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if(isset($booking) && !$errors->any())
            <!-- Booking Details -->
            <div class="container">
                <!-- بطاقة معلومات عن الحجز -->
                <div class="mb-4" >
                    <!-- Header -->
                    <div class=" text-white p-3 rounded-t-lg">
                        <h2 class="text-xl font-bold text-center">{{ __('messages.booking_information') }}</h2>
                    </div>
                    <!-- Body -->
                    <div>
                        <div class="hidden md:block">
                            <div class="overflow-hidden">
                                <table class="w-full">
                                    <thead class="bg-white divide-y divide-gray-200">
                                        <tr>
                                            <th class="py-3 text-center text-sm font-bold text-gray-600 uppercase tracking-wider border border-gray-600">{{ __('messages.passenger_name') }}</th>
                                            <th class="py-3 text-center text-sm font-bold text-gray-600 uppercase tracking-wider border border-gray-600">{{ __('messages.flight_number') }}</th>
                                            <th class="py-3 text-center text-sm font-bold text-gray-600 uppercase tracking-wider border border-gray-600">{{ __('messages.booking_status') }}</th>
                                            <th class="py-3 text-center text-sm font-bold text-gray-600 uppercase tracking-wider border border-gray-600">{{ __('messages.payment_status_label') }}</th>
                                            <th class="py-3 text-center text-sm font-bold text-gray-600 uppercase tracking-wider border border-gray-600">{{ __('messages.number_of_passengers_label') }}</th>
                                            <th class="py-3 text-center text-sm font-bold text-gray-600 uppercase tracking-wider border border-gray-600">{{ __('messages.total_amount_label') }}</th>
                                            @if($booking->payment_status !== 'paid')
                                                <th class="py-3 text-center text-sm font-bold text-gray-600 uppercase tracking-wider border border-gray-600">{{ __('messages.Operations') }}</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <tr class="">
                                            <td class="py-4 text-sm text-center text-gray-900 border border-gray-600">{{ $booking->passenger_name }}</td>
                                            <td class="py-4 text-sm text-center text-gray-900 border border-gray-600">{{ $booking->flight->flight_number }}</td>
                                            <td class="py-4 text-sm text-center border border-gray-600">
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-xs text-sm {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-800' : ($booking->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
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
                                            </td>
                                            <td class="py-4 text-sm text-center border border-gray-600">
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-xs text-sm {{ $booking->payment_status === 'paid' ? 'bg-green-100 text-green-800' : ($booking->payment_status === 'failed' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
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
                                            </td>
                                            <td class="py-4 text-sm text-center text-gray-900 border border-gray-600">{{ $booking->number_of_passengers }}</td>
                                            <td class="py-4 text-sm text-center text-gray-900 border border-gray-600">{{ number_format($booking->total_amount + $booking->tax_amount + $booking->service_fee) }} {{ __('messages.currency') }}</td>
                                            @if($booking->payment_status !== 'paid')
                                                <td class="py-4 text-sm text-center text-gray-900 border border-gray-600">
                                                    <div class="text-center">
                                                        <button onclick="showPaymentModal()" 
                                                                class="inline-flex items-center cursor-pointer bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg transition duration-300 transform hover:scale-101">
                                                            <i class="fas fa-credit-card mx-2"></i>
                                                            {{ __('messages.complete_payment_button') }}
                                                        </button>
                                                    </div>
                                                </td>
                                            @endif
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- Mobile Cards (below 768px) -->
                        <div class="md:hidden space-y-3">
                            <div class="bg-white border border-gray-600">
                                <div class="grid grid-cols-2">
                                    <div class="py-2 text-sm text-center text-gray-500 font-bold border border-gray-600 tracking-wider">{{ __('messages.passenger_name') }}</div>
                                    <div class="py-2 text-sm text-center text-gray-500 border border-gray-600 tracking-wider">{{ $booking->passenger_name }}</div>
                                    
                                    <div class="py-2 text-sm text-center text-gray-500 font-bold border border-gray-600 tracking-wider">{{ __('messages.flight_number') }}</div>
                                    <div class="py-2 text-sm text-center text-gray-500 border border-gray-600 tracking-wider">{{ $booking->flight->flight_number }}</div>
                                    
                                    <div class="py-2 text-sm text-center text-gray-500 font-bold border border-gray-600 tracking-wider">{{ __('messages.booking_status') }}</div>
                                    <div class="text-sm text-center py-2 border border-gray-600">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-sm text-sm {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-800' : ($booking->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
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
                                    
                                    <div class="py-2 text-sm text-center text-gray-500 font-bold border border-gray-600 tracking-wider">{{ __('messages.payment_status_label') }}</div>
                                    <div class="text-sm text-center py-2 border border-gray-600">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-sm text-sm {{ $booking->payment_status === 'paid' ? 'bg-green-100 text-green-800' : ($booking->payment_status === 'failed' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
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
                                    
                                    <div class="py-2 text-sm text-center text-gray-500 font-bold border border-gray-600 tracking-wider">{{ __('messages.number_of_passengers_label') }}</div>
                                    <div class="py-2 text-sm text-center text-gray-500 border border-gray-600 tracking-wider">{{ $booking->number_of_passengers }}</div>
                                    
                                    <div class="py-2 text-sm text-center text-gray-500 font-bold border border-gray-600 tracking-wider">{{ __('messages.total_amount_label') }}</div>
                                    <div class="py-2 text-sm text-center text-gray-500 border border-gray-600 tracking-wider">{{ number_format($booking->total_amount + $booking->tax_amount + $booking->service_fee) }} {{ __('messages.currency') }}</div>

                                    @if($booking->payment_status !== 'paid')
                                        <span class="py-2 text-sm text-center text-gray-500 font-bold border border-gray-600 tracking-wider">{{ __('messages.Operations') }}</span>
                                    @endif

                                    @if($booking->payment_status !== 'paid')
                                        <div class="py-2 border border-gray-600">
                                            <div class="text-center">
                                                <button onclick="showPaymentModal()" 
                                                        class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm py-1 px-2 rounded-sm transition duration-300 transform hover:scale-105">
                                                    <i class="fas fa-credit-card ml-1"></i>
                                                    {{ __('messages.complete_payment_button') }}
                                                </button>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- بطاقة تفاصيل أكثر عن الحجز -->
                <div class="mb-6 py-6">
                    <!-- Header -->
                    <div>
                        <h2 class="text-xl font-bold text-center">{{ __('messages.more_booking_details') }}</h2>
                    </div>
                    
                    <!-- Body -->
                    <div class="py-4">
                        <!-- Desktop Table (768px and above) -->
                        <div class="hidden md:block">
                            <div class="overflow-hidden">
                                <table class="w-full">
                                    <thead class="bg-white divide-y divide-gray-200">
                                        <tr>
                                            <th class="py-3 text-center text-sm font-bold text-gray-600 uppercase tracking-wider border border-gray-600">{{ __('messages.from_city') }}</th>
                                            <th class="py-3 text-center text-sm font-bold text-gray-600 uppercase tracking-wider border border-gray-600">{{ __('messages.to_city') }}</th>
                                            <th class="py-3 text-center text-sm font-bold text-gray-600 uppercase tracking-wider border border-gray-600">{{ __('messages.departure_time') }}</th>
                                            <th class="py-3 text-center text-sm font-bold text-gray-600 uppercase tracking-wider border border-gray-600">{{ __('messages.arrival_time') }}</th>
                                            <th class="py-3 text-center text-sm font-bold text-gray-600 uppercase tracking-wider border border-gray-600">{{ __('messages.seat_class_label') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <tr>
                                            <td class="py-4 text-sm text-center text-gray-900 border border-gray-600">{{ $booking->flight->departure_city }}</td>
                                            <td class="py-4 text-sm text-center text-gray-900 border border-gray-600">{{ $booking->flight->arrival_city }}</td>
                                            <td class="py-4 border border-gray-600">
                                                <div class="text-center">
                                                    <div class="font-bold text-lg">{{ $booking->flight->departure_time->format('H:i') }}</div>
                                                    <div class="text-xs text-gray-500">{{ $booking->flight->departure_time->format('Y-m-d') }}</div>
                                                </div>
                                            </td>
                                            <td class="py-4 border border-gray-600">
                                                <div class="text-center">
                                                    <div class="font-bold text-lg">{{ $booking->flight->arrival_time->format('H:i') }}</div>
                                                    <div class="text-xs text-gray-500">{{ $booking->flight->arrival_time->format('Y-m-d') }}</div>
                                                </div>
                                            </td>
                                            <td class="py-4 border border-gray-600 text-center">
                                                <span class="inline-flex items-center text-sm text-blue-900">
                                                    {{ t(ucfirst($booking->seat_class)) }}
                                                </span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- Mobile Cards (below 768px) -->
                        <div class="md:hidden space-y-3">
                            <div class="bg-white border border-gray-600">
                                <div class="grid grid-cols-2">
                                    <div class="py-2 text-sm text-center text-gray-500 font-bold border border-gray-600 tracking-wider">{{ __('messages.from_city') }}</div>
                                    <div class="py-2 text-sm text-center text-gray-500 border border-gray-600 tracking-wider">{{ $booking->flight->departure_city }}</div>
                                    
                                    <div class="py-2 text-sm text-center text-gray-500 font-bold border border-gray-600 tracking-wider">{{ __('messages.to_city') }}</div>
                                    <div class="py-2 text-sm text-center text-gray-500 border border-gray-600 tracking-wider">{{ $booking->flight->arrival_city }}</div>
                                    
                                    <div class="py-2 text-sm text-center text-gray-500 font-bold border border-gray-600 tracking-wider flex items-center justify-center">{{ __('messages.departure_time') }}</div>
                                    <div class="text-sm border border-gray-600">
                                        <div class="p-2 text-center">
                                            <div class="font-semibold text-lg">{{ $booking->flight->departure_time->format('H:i') }}</div>
                                            <div class="text-xs text-gray-500">{{ $booking->flight->departure_time->format('Y-m-d') }}</div>
                                        </div>
                                    </div>
                                    
                                    <div class="py-2 text-sm text-center text-gray-500 font-bold border border-gray-600 tracking-wider flex items-center justify-center">{{ __('messages.arrival_time') }}</div>
                                    <div class="text-sm border border-gray-600">
                                        <div class="p-2 text-center">
                                            <div class="font-semibold text-lg">{{ $booking->flight->arrival_time->format('H:i') }}</div>
                                            <div class="text-xs text-gray-500">{{ $booking->flight->arrival_time->format('Y-m-d') }}</div>
                                        </div>
                                    </div>

                                    <div class="py-2 text-sm text-center text-gray-500 font-bold border border-gray-600 tracking-wider">{{ __('messages.seat_class_label') }}</div>
                                    <div class="py-2 text-sm text-center text-gray-500 border border-gray-600 tracking-wider">{{ t(ucfirst($booking->seat_class)) }}</div>

                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Footer -->
                    <div class="bg-gray-50 px-6 py-4 rounded-b-lg">
                        <div class="text-center">
                            <button onclick="printTicket()" 
                                    class="inline-flex items-center bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded-lg transition duration-300 transform hover:scale-101">
                                <i class="fas fa-print mx-2"></i>
                                {{ __('messages.print_ticket_button') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Help Section -->
            <div class="max-w-2xl mx-auto">
                <div class="bg-blue-50 rounded-lg p-6" data-aos="fade-up" data-aos-delay="200">
                    <h3 class="text-lg font-semibold text-blue-800 mb-4">{{ __('messages.where_find_booking_reference') }}</h3>
                    <div class="space-y-3 text-blue-700">
                        <div class="flex items-start">
                            <i class="fas fa-envelope text-blue-600 mx-3 mt-2"></i>
                            <span>{{ __('messages.in_confirmation_email') }}</span>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-sms text-blue-600 mx-3 mt-2"></i>
                            <span>{{ __('messages.in_sms_message') }}</span>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-receipt text-blue-600 mx-3 mt-2"></i>
                            <span>{{ __('messages.in_confirmation_page') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Cancel Modal -->
@if(isset($booking) && $booking->status !== 'cancelled')
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
@endif

<!-- Payment Method Modal -->
@if(isset($booking) && $booking->payment_status !== 'paid')
<div id="paymentModal" class="fixed inset-0 bg-opacity-50 hidden z-500000 mt-12">
    <div class="flex items-center justify-center min-h-screen p-3">
        <div class="relative bg-white rounded-2xl max-w-2xl w-full p-4 shadow-xl transform transition-all duration-300">
            <!-- Header -->
            <div class="text-center mb-4">
                <div class="bg-gradient-to-r from-blue-500 to-purple-600 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-credit-card text-white text-xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">{{ __('messages.choose_payment_method') }}</h3>
            </div>
            
            <!-- Payment Amount -->
            <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl py-2 px-4 mb-4">
                <div class="flex justify-between items-center">
                    <span class="text-lg font-semibold text-gray-700">{{ __('messages.total_amount') }}:</span>
                    <span class="text-2xl font-bold text-blue-600">{{ number_format($booking->total_amount + $booking->tax_amount + $booking->service_fee) }} {{ __('messages.currency') }}</span>
                </div>
            </div>
            
            <!-- Payment Methods -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                <!-- WhatsApp Method -->
                <div class="payment-method-card bg-white border-2 border-gray-600 rounded-xl p-4 cursor-pointer hover:border-green-500 hover:shadow-lg transition-all duration-300" 
                     onclick="selectPaymentMethod('whatsapp')">
                    <div class="text-center">
                        <div class="bg-green-100 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fab fa-whatsapp text-green-600 text-xl"></i>
                        </div>
                        <h4 class="text-lg font-bold text-gray-800 mb-2">واتساب</h4>
                        <p class="text-sm text-gray-600 mb-4">التواصل عبر الواتساب</p>
                    </div>
                </div>
                
                <!-- Tap Payment Method (Disabled) -->
                <div class="payment-method-card bg-gray-50 border-2 border-gray-300 rounded-xl p-4 cursor-not-allowed opacity-60 transition-all duration-300">
                    <div class="text-center">
                        <div class="bg-gray-200 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-credit-card text-gray-500 text-xl"></i>
                        </div>
                        <h4 class="text-lg font-bold text-gray-700 mb-2">Tap Payment</h4>
                        <p class="text-sm text-gray-500 mb-2">الدفع الإلكتروني</p>
                        <span class="inline-block bg-yellow-100 text-yellow-800 text-xs font-semibold px-2 py-1 rounded-full">قريباً</span>
                    </div>
                </div>
            </div>
            
            <!-- Close Button -->
            <button onclick="hidePaymentModal()" 
                    class="absolute top-4 right-4 w-8 h-8 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center transition duration-300 transform hover:scale-110">
                <i class="fas fa-times text-sm"></i>
            </button>
        </div>
    </div>
</div>
@endif

@push('scripts')
<script src="{{ asset('assets/js/aos.js') }}"></script>
<script>
    // تهيئة AOS إذا كان موجوداً
    if (typeof AOS !== 'undefined') {
        AOS.init({
            duration: 1000,
            once: true
        });
    }

    // إضافة تأثير التركيز
    document.querySelector('input[name="booking_reference"]').addEventListener('focus', function() {
        this.classList.add('ring-4', 'ring-blue-200');
    });

    document.querySelector('input[name="booking_reference"]').addEventListener('blur', function() {
        this.classList.remove('ring-4', 'ring-blue-200');
    });

    // إخفاء رسالة النجاح بعد 10 ثوانٍ
    const successMessage = document.querySelector('.bg-green-50');
    if (successMessage) {
        setTimeout(function() {
            successMessage.style.transition = 'opacity 0.5s ease-out';
            successMessage.style.opacity = '0';
            setTimeout(function() {
                successMessage.style.display = 'none';
            }, 500);
        }, 10000); // 10 ثوانٍ
    }

    // دوال نافذة الإلغاء
    function showCancelModal() {
        document.getElementById('cancelModal').classList.remove('hidden');
    }

    function hideCancelModal() {
        document.getElementById('cancelModal').classList.add('hidden');
    }

    // إغلاق النافذة عند النقر خارجها
    const cancelModal = document.getElementById('cancelModal');
    if (cancelModal) {
        cancelModal.addEventListener('click', function(e) {
            if (e.target === this) {
                hideCancelModal();
            }
        });
    }

    // دوال Payment Modal
    function showPaymentModal() {
        document.getElementById('paymentModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        // إضافة تأثير التمويه على الصفحة
        const mainContent = document.querySelector('.min-h-screen');
        if (mainContent) {
            mainContent.classList.add('page-blur');
        }
    }

    function hidePaymentModal() {
        document.getElementById('paymentModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
        
        // إزالة تأثير التمويه من الصفحة
        const mainContent = document.querySelector('.min-h-screen');
        if (mainContent) {
            mainContent.classList.remove('page-blur');
        }
    }

    function selectPaymentMethod(method) {
        hidePaymentModal();
        
        // التحقق من وجود بيانات الحجز
        @if(isset($booking) && $booking)
            if (method === 'whatsapp') {
                // توجيه إلى WhatsApp
                const bookingRef = '{{ $booking->booking_reference }}';
                const amount = {{ $booking->total_amount + $booking->tax_amount + $booking->service_fee }};
                const passengerName = '{{ $booking->passenger_name }}';
                const flightNumber = '{{ $booking->flight->flight_number }}';
                
                const message = `مرحباً، أريد إتمام الدفع للحجز التالي:
                
                رقم الحجز: ${bookingRef}
                اسم المسافر: ${passengerName}
                رقم الرحلة: ${flightNumber}
                المبلغ المطلوب: ${amount.toLocaleString()} ريال سعودي

                يرجى تأكيد استلام الدفع وتحديث حالة الحجز.

                شكراً لكم`;

                const whatsappUrl = `https://wa.me/967772734012?text=${encodeURIComponent(message)}`;
                window.open(whatsappUrl, '_blank');
            }
        @else
        // إظهار رسالة خطأ إذا لم تكن بيانات الحجز متاحة
            alert('لا يمكن إتمام الدفع: بيانات الحجز غير متاحة');
        @endif
    }

    // إغلاق Modal عند النقر خارجها
    const paymentModal = document.getElementById('paymentModal');
    if (paymentModal) {
        paymentModal.addEventListener('click', function(e) {
            if (e.target === this) {
                hidePaymentModal();
            }
        });
    }

    // دالة طباعة التذكرة
    function printTicket() {
        // التحقق من وجود بيانات الحجز
        @if(isset($booking) && $booking)
        // إنشاء نافذة طباعة جديدة
        const printWindow = window.open('', '_blank', 'width=800,height=600');
        
        // محتوى التذكرة
        const ticketContent = `
            <!DOCTYPE html>
            <html dir="rtl" lang="ar">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>تذكرة السفر - {{ $booking->booking_reference }}</title>
                <style>
                    * {
                        margin: 0;
                        padding: 0;
                        box-sizing: border-box;
                    }
                    
                    body {
                        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                        background: #f8f9fa;
                        padding: 20px;
                        direction: rtl;
                    }
                    
                    .ticket-container {
                        max-width: 800px;
                        margin: 0 auto;
                        background: white;
                        border-radius: 12px;
                        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                        overflow: hidden;
                    }
                    
                    .ticket-header {
                        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
                        color: white;
                        padding: 30px;
                        text-align: center;
                    }
                    
                    .ticket-header h1 {
                        font-size: 28px;
                        font-weight: bold;
                        margin-bottom: 10px;
                    }
                    
                    .ticket-header p {
                        font-size: 16px;
                        opacity: 0.9;
                    }
                    
                    .ticket-body {
                        padding: 30px;
                    }
                    
                    .info-table {
                        width: 100%;
                        border-collapse: collapse;
                        margin-bottom: 30px;
                    }
                    
                    .info-table th {
                        background: #f8f9fa;
                        padding: 15px;
                        text-align: right;
                        font-weight: 600;
                        color: #374151;
                        border: 1px solid #e5e7eb;
                    }
                    
                    .info-table td {
                        padding: 15px;
                        text-align: right;
                        border: 1px solid #e5e7eb;
                        color: #111827;
                    }
                    
                    .status-badge {
                        display: inline-block;
                        padding: 6px 12px;
                        border-radius: 20px;
                        font-size: 12px;
                        font-weight: 600;
                        text-transform: uppercase;
                    }
                    
                    .status-confirmed {
                        background: #dcfce7;
                        color: #166534;
                    }
                    
                    .status-pending {
                        background: #fef3c7;
                        color: #92400e;
                    }
                    
                    .status-cancelled {
                        background: #fee2e2;
                        color: #991b1b;
                    }
                    
                    .payment-paid {
                        background: #dcfce7;
                        color: #166534;
                    }
                    
                    .payment-pending {
                        background: #fef3c7;
                        color: #92400e;
                    }
                    
                    .payment-failed {
                        background: #fee2e2;
                        color: #991b1b;
                    }
                    
                    .flight-details {
                        background: #f8f9fa;
                        border-radius: 8px;
                        padding: 20px;
                        margin: 20px 0;
                    }
                    
                    .flight-route {
                        display: flex;
                        justify-content: space-between;
                        align-items: center;
                        margin: 20px 0;
                    }
                    
                    .city-info {
                        text-align: center;
                        flex: 1;
                    }
                    
                    .city-name {
                        font-size: 24px;
                        font-weight: bold;
                        color: #111827;
                        margin-bottom: 5px;
                    }
                    
                    .city-time {
                        font-size: 18px;
                        color: #6b7280;
                        margin-bottom: 3px;
                    }
                    
                    .city-date {
                        font-size: 14px;
                        color: #9ca3af;
                    }
                    
                    .flight-arrow {
                        font-size: 24px;
                        color: #3b82f6;
                        margin: 0 20px;
                    }
                    
                    .ticket-footer {
                        background: #f8f9fa;
                        padding: 20px;
                        text-align: center;
                        border-top: 1px solid #e5e7eb;
                    }
                    
                    .print-button {
                        background: #10b981;
                        color: white;
                        padding: 12px 24px;
                        border: none;
                        border-radius: 6px;
                        font-weight: 600;
                        cursor: pointer;
                        margin: 10px;
                    }
                    
                    .print-button:hover {
                        background: #059669;
                    }
                    
                    @media print {
                        body {
                            background: white;
                            padding: 0;
                        }
                        
                        .ticket-container {
                            box-shadow: none;
                            border: 1px solid #e5e7eb;
                        }
                        
                        .print-button {
                            display: none;
                        }
                    }
                </style>
            </head>
            <body>
                <div class="ticket-container">
                    <!-- Header -->
                    <div class="ticket-header">
                        <h1>معلومات عن الحجز</h1>
                        <p>رقم الحجز: {{ $booking->booking_reference }}</p>
                    </div>
                    
                    <!-- Body -->
                    <div class="ticket-body">
                        <table class="info-table">
                            <thead>
                                <tr>
                                    <th>الاسم</th>
                                    <th>رقم الرحلة</th>
                                    <th>حالة الحجز</th>
                                    <th>حالة الدفع</th>
                                    <th>عدد الركاب</th>
                                    <th>الإجمالي</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $booking->passenger_name }}</td>
                                    <td style="font-family: monospace;">{{ $booking->flight->flight_number }}</td>
                                    <td>
                                        <span class="status-badge status-{{ $booking->status }}">
                                            @switch($booking->status)
                                                @case('confirmed')
                                                    مؤكد
                                                    @break
                                                @case('cancelled')
                                                    ملغي
                                                    @break
                                                @case('completed')
                                                    مكتمل
                                                    @break
                                                @default
                                                    معلق
                                            @endswitch
                                        </span>
                                    </td>
                                    <td>
                                        <span class="status-badge payment-{{ $booking->payment_status }}">
                                            @switch($booking->payment_status)
                                                @case('paid')
                                                    مدفوع
                                                    @break
                                                @case('failed')
                                                    فشل الدفع
                                                    @break
                                                @case('refunded')
                                                    مسترد
                                                    @break
                                                @default
                                                    معلق
                                            @endswitch
                                        </span>
                                    </td>
                                    <td>{{ $booking->number_of_passengers }}</td>
                                    <td style="font-weight: bold; color: #3b82f6;">{{ number_format($booking->total_amount + $booking->tax_amount + $booking->service_fee) }} ريال</td>
                                </tr>
                            </tbody>
                        </table>
                        
                        <div class="flight-details">
                            <h2 style="text-align: center; margin-bottom: 20px; color: #374151;">تفاصيل أكثر عن الحجز</h2>
                            <table class="info-table">
                                <thead>
                                    <tr>
                                        <th>من مدينة</th>
                                        <th>إلى مدينة</th>
                                        <th>تبدأ في</th>
                                        <th>تنتهي في</th>
                                        <th>فئة المقعد</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="font-weight: bold;">{{ $booking->flight->departure_city }}</td>
                                        <td style="font-weight: bold;">{{ $booking->flight->arrival_city }}</td>
                                        <td>
                                            <div style="text-align: center;">
                                                <div style="font-weight: bold;">{{ $booking->flight->departure_time->format('H:i') }}</div>
                                                <div style="font-size: 12px; color: #6b7280;">{{ $booking->flight->departure_time->format('Y-m-d') }}</div>
                                            </div>
                                        </td>
                                        <td>
                                            <div style="text-align: center;">
                                                <div style="font-weight: bold;">{{ $booking->flight->arrival_time->format('H:i') }}</div>
                                                <div style="font-size: 12px; color: #6b7280;">{{ $booking->flight->arrival_time->format('Y-m-d') }}</div>
                                            </div>
                                        </td>
                                        <td>
                                            <span style="background: #dbeafe; color: #1e40af; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                                                {{ t(ucfirst($booking->seat_class)) }}
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Footer -->
                    <div class="ticket-footer">
                        <p style="color: #6b7280; font-size: 14px;">شكراً لاختياركم أسكلة للطيران</p>
                        <button class="print-button" onclick="window.print()">طباعة التذكرة</button>
                    </div>
                </div>
            </body>
            </html>
        `;
        
        // كتابة المحتوى في النافذة الجديدة
        printWindow.document.write(ticketContent);
        printWindow.document.close();
        
        // انتظار تحميل المحتوى ثم فتح نافذة الطباعة
        printWindow.onload = function() {
            printWindow.focus();
            printWindow.print();
        };
        @else
        // إظهار رسالة خطأ إذا لم تكن بيانات الحجز متاحة
        alert('لا يمكن طباعة التذكرة: بيانات الحجز غير متاحة');
        @endif
    }
</script>
@endpush
@endsection
