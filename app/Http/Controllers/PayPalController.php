<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use App\Models\Booking;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingCodeMail;

class PayPalController extends Controller
{
    public function createPayment(Request $request)
    {
        // الحصول على بيانات الحجز
        $bookingId = $request->get('booking_id');
        $amount = $request->get('amount');
        $currency = $request->get('currency', 'SAR');

        if (!$bookingId || !$amount) {
            return redirect()->route('home')->withErrors(['error' => 'بيانات الدفع غير صحيحة.']);
        }

        $booking = Booking::findOrFail($bookingId);
        
        // التحقق من حالة الحجز
        if ($booking->isPaid()) {
            return redirect()->route('booking.confirmation', $booking)
                ->with('info', 'تم دفع هذا الحجز مسبقاً.');
        }

        $paypal = new PayPalClient;
        $paypal->setApiCredentials(config('paypal'));
        $token = $paypal->getAccessToken();
        $paypal->setAccessToken($token);

        // تحويل المبلغ من الريال السعودي إلى الدولار الأمريكي (معدل تقريبي)
        $usdAmount = $currency === 'SAR' ? round($amount / 3.8, 2) : $amount;

        $response = $paypal->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => route('paypal.success', ['booking_id' => $bookingId]),
                "cancel_url" => route('paypal.cancel', ['booking_id' => $bookingId]),
            ],
            "purchase_units" => [
                [
                    "amount" => [
                        "currency_code" => "USD",
                        "value" => $usdAmount
                    ],
                    "description" => "حجز رحلة - " . $booking->flight->flight_number,
                    "custom_id" => $bookingId
                ]
            ]
        ]);

        if (isset($response['id']) && $response['id'] != null) {
            // حفظ معرف PayPal في الحجز
            $booking->update([
                'payment_reference' => $response['id']
            ]);

            foreach ($response['links'] as $link) {
                if ($link['rel'] === 'approve') {
                    return redirect()->away($link['href']);
                }
            }
        }

        return redirect()->route('paypal.cancel', ['booking_id' => $bookingId])
            ->withErrors(['error' => 'فشل في إنشاء طلب الدفع.']);
    }

    public function success(Request $request)
    {
        $bookingId = $request->get('booking_id');
        
        if (!$bookingId) {
            return redirect()->route('home')->withErrors(['error' => 'بيانات الدفع غير صحيحة.']);
        }

        $booking = Booking::findOrFail($bookingId);

        try {
            DB::beginTransaction();

            $paypal = new PayPalClient;
            $paypal->setApiCredentials(config('paypal'));
            $token = $paypal->getAccessToken();
            $paypal->setAccessToken($token);

            $response = $paypal->capturePaymentOrder($request->token);

            // فحص حالات مختلفة للدفع
            if (isset($response['status'])) {
                switch ($response['status']) {
                    case 'COMPLETED':
                        // الدفع نجح
                        $this->handleSuccessfulPayment($booking, $response);
                        DB::commit();

                        // إرسال بريد برقم الحجز بعد نجاح الدفع الإلكتروني (بدون تأكيد)
                        try {
                            if (!empty($booking->passenger_email)) {
                                Mail::to($booking->passenger_email)->send(new BookingCodeMail($booking));
                            }
                        } catch (\Throwable $e) {
                            \Log::warning('Failed to send booking code email (PayPal)', [
                                'booking_id' => $booking->id,
                                'error' => $e->getMessage(),
                            ]);
                        }

                        return redirect()->route('booking.track.success')
                            ->with('success', 'تم الدفع بنجاح! سيتم تأكيد حجزك قريباً، رقم الحجز الخاص بك هو ' . $booking->booking_reference)
                            ->with('booking_reference', $booking->booking_reference);
                        
                    case 'DECLINED':
                        // الدفع رُفض (عدم كفاية رصيد، بطاقة منتهية، إلخ)
                        $this->handleDeclinedPayment($booking, $response);
                        DB::commit();
                        return redirect()->route('flights.show', $booking->flight)
                            ->withErrors(['error' => 'عملية الدفع لم تتم بالشكل الصحيح يجب عليك المحاولة مرة أخرى']);
                        
                    case 'FAILED':
                        // الدفع فشل لأسباب تقنية
                        $this->handleFailedPayment($booking, $response);
                        DB::commit();
                        return redirect()->route('flights.show', $booking->flight)
                            ->withErrors(['error' => 'عملية الدفع لم تتم بالشكل الصحيح يجب عليك المحاولة مرة أخرى']);
                        
                    default:
                        // حالة غير معروفة
                        $this->handleUnknownPaymentStatus($booking, $response);
                        DB::commit();
                        return redirect()->route('flights.show', $booking->flight)
                            ->withErrors(['error' => 'عملية الدفع لم تتم بالشكل الصحيح يجب عليك المحاولة مرة أخرى']);
                }
            } else {
                DB::rollBack();
                return redirect()->route('flights.show', $booking->flight)
                    ->withErrors(['error' => 'عملية الدفع لم تتم بالشكل الصحيح يجب عليك المحاولة مرة أخرى']);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->route('flights.show', $booking->flight)
                ->withErrors(['error' => 'عملية الدفع لم تتم بالشكل الصحيح يجب عليك المحاولة مرة أخرى']);
        }
    }

    public function cancel(Request $request)
    {
        $bookingId = $request->get('booking_id');
        
        if ($bookingId) {
            $booking = Booking::find($bookingId);
            if ($booking) {
                // إعادة المقاعد المتاحة
                $booking->flight->updateAvailableSeats($booking->number_of_passengers);
                
                return redirect()->route('flights.show', $booking->flight)
                    ->withErrors(['error' => 'تم إلغاء عملية الدفع. يمكنك المحاولة مرة أخرى.']);
            }
        }
        
        return redirect()->route('home')
            ->withErrors(['error' => 'تم إلغاء عملية الدفع.']);
    }

    /**
     * معالجة الدفع الناجح
     */
    private function handleSuccessfulPayment($booking, $response)
    {
        // التحقق من حالة الحجز المؤقتة
        if ($booking->status !== 'temporary' || $booking->payment_status !== 'awaiting_payment') {
            throw new \Exception('حالة الحجز غير صحيحة');
        }

        // تحديد مصدر الدفع من استجابة PayPal
        $paymentSource = $this->determinePaymentSource($response);
        
        // إنشاء سجل الدفع
        $payment = \App\Models\Payment::create([
            'payable_type' => Booking::class,
            'payable_id' => $booking->id,
            'amount' => $booking->total_amount + $booking->tax_amount + $booking->service_fee,
            'currency' => $booking->currency,
            'payment_method' => 'paypal',
            'status' => 'completed',
            'gateway_transaction_id' => $response['id'],
            'gateway_response' => $response,
            'processed_at' => now(),
            'processed_by' => null
        ]);

        // تحديث حالة الحجز - تأكيد الدفع
        $booking->update([
            'payment_status' => 'confirmed',
            'payment_reference' => $payment->payment_reference,
            'payment_date' => now(),
            'status' => 'pending' // الحجز يبقى معلقاً حتى موافقة الأدمن
        ]);

        // تحديث المقاعد المتاحة
        $booking->flight->decrementAvailableSeats($booking->number_of_passengers);
        
        // تسجيل مصدر الدفع في logs
        \Log::info('PayPal Payment Successful', [
            'booking_id' => $booking->id,
            'payment_source' => $paymentSource,
            'paypal_balance_used' => $response['purchase_units'][0]['payments']['captures'][0]['amount']['value'] ?? 'unknown'
        ]);
    }

    private function determinePaymentSource($response)
    {
        // محاولة تحديد مصدر الدفع من استجابة PayPal
        if (isset($response['purchase_units'][0]['payments']['captures'][0])) {
            $capture = $response['purchase_units'][0]['payments']['captures'][0];
            
            // إذا كان المبلغ من رصيد PayPal مباشر
            if (isset($capture['amount']['value']) && $capture['amount']['value'] > 0) {
                return 'paypal_balance_or_linked_account';
            }
        }
        
        return 'linked_account_or_card';
    }

    /**
     * معالجة الدفع المرفوض (عدم كفاية رصيد)
     */
    private function handleDeclinedPayment($booking, $response)
    {
        // إنشاء سجل دفع فاشل
        \App\Models\Payment::create([
            'payable_type' => Booking::class,
            'payable_id' => $booking->id,
            'amount' => $booking->total_amount + $booking->tax_amount + $booking->service_fee,
            'currency' => $booking->currency,
            'payment_method' => 'paypal',
            'status' => 'failed',
            'gateway_transaction_id' => $response['id'] ?? null,
            'gateway_response' => $response,
            'failure_reason' => 'Payment declined by PayPal - Possible insufficient funds or card issues',
            'processed_at' => now()
        ]);

        // حذف الحجز المؤقت
        if ($booking->status === 'temporary') {
            $flight = $booking->flight;
            $booking->delete();
            
            // إعادة المقاعد المتاحة
            if ($flight) {
                $flight->increment('available_seats', $booking->number_of_passengers);
            }
        } else {
            // إعادة المقاعد المتاحة
            $booking->flight->updateAvailableSeats($booking->number_of_passengers);
            
            // تحديث حالة الحجز
            $booking->update([
                'payment_status' => 'failed',
                'status' => 'cancelled'
            ]);
        }
    }

    /**
     * معالجة الدفع الفاشل لأسباب تقنية
     */
    private function handleFailedPayment($booking, $response)
    {
        // إنشاء سجل دفع فاشل
        \App\Models\Payment::create([
            'payable_type' => Booking::class,
            'payable_id' => $booking->id,
            'amount' => $booking->total_amount + $booking->tax_amount + $booking->service_fee,
            'currency' => $booking->currency,
            'payment_method' => 'paypal',
            'status' => 'failed',
            'gateway_transaction_id' => $response['id'] ?? null,
            'gateway_response' => $response,
            'failure_reason' => 'Payment failed due to technical issues',
            'processed_at' => now()
        ]);

        // حذف الحجز المؤقت
        if ($booking->status === 'temporary') {
            $flight = $booking->flight;
            $booking->delete();
            
            // إعادة المقاعد المتاحة
            if ($flight) {
                $flight->increment('available_seats', $booking->number_of_passengers);
            }
        } else {
            // إعادة المقاعد المتاحة
            $booking->flight->updateAvailableSeats($booking->number_of_passengers);
            
            // تحديث حالة الحجز
            $booking->update([
                'payment_status' => 'failed',
                'status' => 'cancelled'
            ]);
        }
    }

    /**
     * معالجة حالة دفع غير معروفة
     */
    private function handleUnknownPaymentStatus($booking, $response)
    {
        // إنشاء سجل دفع معلق
        \App\Models\Payment::create([
            'payable_type' => Booking::class,
            'payable_id' => $booking->id,
            'amount' => $booking->total_amount + $booking->tax_amount + $booking->service_fee,
            'currency' => $booking->currency,
            'payment_method' => 'paypal',
            'status' => 'pending',
            'gateway_transaction_id' => $response['id'] ?? null,
            'gateway_response' => $response,
            'failure_reason' => 'Unknown payment status received from PayPal',
            'processed_at' => now()
        ]);

        // حذف الحجز المؤقت
        if ($booking->status === 'temporary') {
            $flight = $booking->flight;
            $booking->delete();
            
            // إعادة المقاعد المتاحة
            if ($flight) {
                $flight->increment('available_seats', $booking->number_of_passengers);
            }
        } else {
            // إعادة المقاعد المتاحة
            $booking->flight->updateAvailableSeats($booking->number_of_passengers);
            
            // تحديث حالة الحجز
            $booking->update([
                'payment_status' => 'pending',
                'status' => 'pending'
            ]);
        }
    }
}
