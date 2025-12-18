<?php

namespace App\Http\Controllers;

use App\Services\StripeService;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingCodeMail;

class StripeController extends Controller
{
    protected $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    /**
     * إنشاء Payment Intent للدفع
     */
    public function createPaymentIntent(Request $request, Booking $booking)
    {
        try {
            // التحقق من حالة الحجز
            if ($booking->isPaid()) {
                return response()->json([
                    'success' => false,
                    'error' => 'تم دفع هذا الحجز مسبقاً'
                ], 400);
            }

            $totalAmount = $booking->total_amount + $booking->tax_amount + $booking->service_fee;

            // إنشاء Payment Intent
            $result = $this->stripeService->createPaymentIntent(
                $totalAmount,
                $booking->currency,
                [
                    'booking_id' => $booking->id,
                    'booking_reference' => $booking->booking_reference,
                    'passenger_name' => $booking->passenger_name,
                    'flight_number' => $booking->flight->flight_number
                ]
            );

            if (!$result['success']) {
                return response()->json([
                    'success' => false,
                    'error' => 'فشل في إنشاء طلب الدفع: ' . $result['error']
                ], 500);
            }

            // حفظ Payment Intent ID في قاعدة البيانات
            $payment = Payment::create([
                'payable_type' => Booking::class,
                'payable_id' => $booking->id,
                'amount' => $totalAmount,
                'currency' => $booking->currency,
                'payment_method' => 'stripe',
                'status' => 'processing',
                'gateway_transaction_id' => $result['payment_intent']->id,
                'gateway_response' => $result['payment_intent']->toArray(),
                'processed_by' => null
            ]);

            return response()->json([
                'success' => true,
                'client_secret' => $result['client_secret'],
                'payment_intent_id' => $result['payment_intent']->id,
                'amount' => $totalAmount,
                'currency' => $booking->currency
            ]);

        } catch (\Exception $e) {
            Log::error('Stripe Payment Intent Creation Error', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'حدث خطأ أثناء إنشاء طلب الدفع: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * تأكيد الدفع
     */
    public function confirmPayment(Request $request, Booking $booking)
    {
        try {
            $request->validate([
                'payment_intent_id' => 'required|string'
            ]);

            $paymentIntentId = $request->payment_intent_id;

            // البحث عن سجل الدفع
            $payment = Payment::where('gateway_transaction_id', $paymentIntentId)
                ->where('payable_id', $booking->id)
                ->first();

            if (!$payment) {
                return response()->json([
                    'success' => false,
                    'error' => 'لم يتم العثور على سجل الدفع'
                ], 404);
            }

            // تأكيد الدفع مع Stripe
            $result = $this->stripeService->confirmPayment($paymentIntentId);

            if (!$result['success']) {
                return response()->json([
                    'success' => false,
                    'error' => 'فشل في تأكيد الدفع: ' . $result['error']
                ], 500);
            }

            $paymentIntent = $result['payment_intent'];

            DB::beginTransaction();

            if ($paymentIntent->status === 'succeeded') {
                // الدفع نجح - تأكيد الحجز
                Log::info('Payment succeeded on Stripe - confirming on server', [
                    'booking_id' => $booking->id,
                    'payment_intent_id' => $paymentIntentId,
                    'current_booking_status' => $booking->status,
                    'current_payment_status' => $booking->payment_status,
                    'payment_intent_status' => $paymentIntent->status
                ]);

                $payment->markAsCompleted($paymentIntentId, [
                    'status' => 'succeeded',
                    'payment_intent' => $paymentIntent->toArray(),
                    'processed_at' => now()->toISOString()
                ]);

                // التحقق من حالة الحجز المؤقتة أو أي حالة تسمح بالتأكيد
                $canConfirm = ($booking->status === 'temporary' && $booking->payment_status === 'awaiting_payment') 
                           || ($booking->payment_status === 'awaiting_payment') 
                           || ($booking->payment_status === 'processing')
                           || in_array($booking->payment_status, ['pending', 'awaiting_payment', 'processing']);

                if ($canConfirm) {
                    // تحديث حالة الحجز بعد نجاح الدفع
                    $booking->update([
                        'payment_status' => 'confirmed',
                        'payment_method' => 'stripe',
                        'payment_reference' => $payment->payment_reference,
                        'payment_date' => now(),
                        'status' => 'pending' // بعد الدفع بنجاح، الحجز منتظر موافقة الأدمن
                    ]);

                    DB::commit();

                    // إرسال بريد برقم الحجز بعد نجاح الدفع (بدون تأكيد)
                    try {
                        if (!empty($booking->passenger_email)) {
                            Mail::to($booking->passenger_email)->send(new BookingCodeMail($booking));
                        }
                    } catch (\Throwable $e) {
                        Log::warning('Failed to send booking code email (Stripe)', [
                            'booking_id' => $booking->id,
                            'error' => $e->getMessage(),
                        ]);
                    }

                    Log::info('Stripe payment confirmed successfully', [
                        'booking_id' => $booking->id,
                        'payment_intent_id' => $paymentIntentId,
                        'status' => $paymentIntent->status,
                        'booking_status' => 'pending',
                        'payment_status' => 'confirmed'
                    ]);

                    // إنشاء URL للتوجيه
                    $redirectUrl = route('booking.track.success', [
                        'booking_reference' => $booking->booking_reference
                    ]) . '?success=true';

                    return response()->json([
                        'success' => true,
                        'message' => 'تم الدفع بنجاح! سيتم تأكيد حجزك قريباً',
                        'booking_reference' => $booking->booking_reference,
                        'payment_status' => 'confirmed',
                        'booking_status' => 'pending',
                        'redirect_url' => $redirectUrl
                    ]);
                } else {
                    // الحجز في حالة غير متوقعة - لكن الدفع نجح في Stripe
                    Log::warning('Booking state does not allow confirmation, but payment succeeded', [
                        'booking_id' => $booking->id,
                        'booking_status' => $booking->status,
                        'payment_status' => $booking->payment_status,
                        'payment_intent_id' => $paymentIntentId
                    ]);

                    // على أي حال، الدفع نجح في Stripe، دعنا نؤكد الحجز
                    $booking->update([
                        'payment_status' => 'confirmed',
                        'payment_method' => 'stripe',
                        'payment_reference' => $payment->payment_reference,
                        'payment_date' => now(),
                        'status' => 'pending'
                    ]);

                    DB::commit();

                    // إرسال بريد برقم الحجز بعد نجاح الدفع (بدون تأكيد)
                    try {
                        if (!empty($booking->passenger_email)) {
                            Mail::to($booking->passenger_email)->send(new BookingCodeMail($booking));
                        }
                    } catch (\Throwable $e) {
                        Log::warning('Failed to send booking code email (Stripe-nonstandard)', [
                            'booking_id' => $booking->id,
                            'error' => $e->getMessage(),
                        ]);
                    }

                    Log::info('Booking confirmed despite non-standard state - payment succeeded in Stripe', [
                        'booking_id' => $booking->id,
                        'payment_intent_id' => $paymentIntentId
                    ]);

                    $redirectUrl = route('booking.track.success', [
                        'booking_reference' => $booking->booking_reference
                    ]) . '?success=true';

                    return response()->json([
                        'success' => true,
                        'message' => 'تم الدفع بنجاح! سيتم تأكيد حجزك قريباً',
                        'booking_reference' => $booking->booking_reference,
                        'payment_status' => 'confirmed',
                        'booking_status' => 'pending',
                        'redirect_url' => $redirectUrl
                    ]);
                }

            } elseif ($paymentIntent->status === 'requires_payment_method') {
                // الدفع فشل - حذف الحجز المؤقت
                $payment->markAsFailed('Payment failed - requires payment method', [
                    'status' => 'requires_payment_method',
                    'payment_intent' => $paymentIntent->toArray(),
                    'processed_at' => now()->toISOString()
                ]);

                // حذف الحجز المؤقت إذا كان في حالة temporary
                if ($booking->status === 'temporary') {
                    $flight = $booking->flight;
                    $booking->delete();
                    
                    // إعادة المقاعد المتاحة
                    if ($flight) {
                        $flight->increment('available_seats', $booking->number_of_passengers);
                    }
                }

                DB::commit();

                return response()->json([
                    'success' => false,
                    'error' => 'فشل في معالجة الدفع. يرجى المحاولة مرة أخرى'
                ], 400);

            } else {
                // حالة غير متوقعة
                $payment->update([
                    'status' => 'processing',
                    'gateway_response' => $paymentIntent->toArray(),
                    'processed_at' => now()
                ]);

                DB::commit();

                return response()->json([
                    'success' => false,
                    'error' => 'حالة الدفع غير متوقعة: ' . $paymentIntent->status
                ], 400);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Stripe Payment Confirmation Error', [
                'booking_id' => $booking->id,
                'payment_intent_id' => $request->payment_intent_id ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'حدث خطأ أثناء تأكيد الدفع'
            ], 500);
        }
    }

    /**
     * إلغاء الدفع
     */
    public function cancelPayment(Request $request, Booking $booking)
    {
        try {
            $request->validate([
                'payment_intent_id' => 'required|string'
            ]);

            $paymentIntentId = $request->payment_intent_id;

            // البحث عن سجل الدفع
            $payment = Payment::where('gateway_transaction_id', $paymentIntentId)
                ->where('payable_id', $booking->id)
                ->first();

            if (!$payment) {
                return response()->json([
                    'success' => false,
                    'error' => 'لم يتم العثور على سجل الدفع'
                ], 404);
            }

            // إلغاء الدفع مع Stripe
            $result = $this->stripeService->cancelPayment($paymentIntentId);

            if (!$result['success']) {
                return response()->json([
                    'success' => false,
                    'error' => 'فشل في إلغاء الدفع: ' . $result['error']
                ], 500);
            }

            // تحديث سجل الدفع
            $payment->update([
                'status' => 'cancelled',
                'gateway_response' => $result['payment_intent']->toArray(),
                'processed_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم إلغاء الدفع بنجاح'
            ]);

        } catch (\Exception $e) {
            Log::error('Stripe Payment Cancellation Error', [
                'booking_id' => $booking->id,
                'payment_intent_id' => $request->payment_intent_id ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'حدث خطأ أثناء إلغاء الدفع'
            ], 500);
        }
    }

    /**
     * التحقق من حالة الدفع
     */
    public function checkPaymentStatus(Request $request, Booking $booking)
    {
        try {
            $request->validate([
                'payment_intent_id' => 'required|string'
            ]);

            $paymentIntentId = $request->payment_intent_id;

            // البحث عن سجل الدفع
            $payment = Payment::where('gateway_transaction_id', $paymentIntentId)
                ->where('payable_id', $booking->id)
                ->first();

            if (!$payment) {
                return response()->json([
                    'success' => false,
                    'error' => 'لم يتم العثور على سجل الدفع'
                ], 404);
            }

            // الحصول على تفاصيل الدفع من Stripe
            $result = $this->stripeService->getPaymentDetails($paymentIntentId);

            if (!$result['success']) {
                return response()->json([
                    'success' => false,
                    'error' => 'فشل في الحصول على تفاصيل الدفع: ' . $result['error']
                ], 500);
            }

            $paymentIntent = $result['payment_intent'];

            return response()->json([
                'success' => true,
                'payment_status' => $paymentIntent->status,
                'amount' => $paymentIntent->amount,
                'currency' => $paymentIntent->currency,
                'created' => $paymentIntent->created,
                'booking_status' => $booking->status,
                'payment_method' => $booking->payment_method
            ]);

        } catch (\Exception $e) {
            Log::error('Stripe Payment Status Check Error', [
                'booking_id' => $booking->id,
                'payment_intent_id' => $request->payment_intent_id ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'حدث خطأ أثناء التحقق من حالة الدفع'
            ], 500);
        }
    }
}
