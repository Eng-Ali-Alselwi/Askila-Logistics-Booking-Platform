<?php

namespace App\Services;

use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Exception\ApiErrorException;
use Illuminate\Support\Facades\Log;

class StripeService
{
    protected $secretKey;
    protected $publishableKey;

    public function __construct()
    {
        $this->secretKey = config('stripe.secret');
        $this->publishableKey = config('stripe.key');
        
        if ($this->secretKey) {
            Stripe::setApiKey($this->secretKey);
        }
    }

    /**
     * إنشاء Payment Intent للدفع
     */
    public function createPaymentIntent($amount, $currency = 'SAR', $metadata = [])
    {
        try {
            // تحويل المبلغ من الريال السعودي إلى هللة (Stripe يتعامل بالهللة)
            $amountInHalala = $amount * 100;

            $paymentIntent = PaymentIntent::create([
                'amount' => $amountInHalala,
                'currency' => strtolower($currency),
                'metadata' => $metadata,
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
            ]);

            return [
                'success' => true,
                'payment_intent' => $paymentIntent,
                'client_secret' => $paymentIntent->client_secret
            ];

        } catch (ApiErrorException $e) {
            Log::error('Stripe Payment Intent Creation Failed', [
                'error' => $e->getMessage(),
                'amount' => $amount,
                'currency' => $currency
            ]);

            return [
                'success' => false,
                'error' => 'Stripe API Error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * تأكيد الدفع
     */
    public function confirmPayment($paymentIntentId)
    {
        try {
            $paymentIntent = PaymentIntent::retrieve($paymentIntentId);
            
            return [
                'success' => true,
                'payment_intent' => $paymentIntent,
                'status' => $paymentIntent->status
            ];

        } catch (ApiErrorException $e) {
            Log::error('Stripe Payment Confirmation Failed', [
                'error' => $e->getMessage(),
                'payment_intent_id' => $paymentIntentId
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * إلغاء الدفع
     */
    public function cancelPayment($paymentIntentId)
    {
        try {
            $paymentIntent = PaymentIntent::retrieve($paymentIntentId);
            $paymentIntent->cancel();

            return [
                'success' => true,
                'payment_intent' => $paymentIntent
            ];

        } catch (ApiErrorException $e) {
            Log::error('Stripe Payment Cancellation Failed', [
                'error' => $e->getMessage(),
                'payment_intent_id' => $paymentIntentId
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * الحصول على تفاصيل الدفع
     */
    public function getPaymentDetails($paymentIntentId)
    {
        try {
            $paymentIntent = PaymentIntent::retrieve($paymentIntentId);
            
            return [
                'success' => true,
                'payment_intent' => $paymentIntent
            ];

        } catch (ApiErrorException $e) {
            Log::error('Stripe Payment Details Retrieval Failed', [
                'error' => $e->getMessage(),
                'payment_intent_id' => $paymentIntentId
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * التحقق من صحة المفاتيح
     */
    public function validateKeys()
    {
        if (!$this->secretKey || !$this->publishableKey) {
            return false;
        }

        try {
            // محاولة إنشاء Payment Intent صغير للتحقق من صحة المفاتيح
            PaymentIntent::create([
                'amount' => 100, // 1 ريال
                'currency' => 'sar',
                'metadata' => ['test' => 'validation']
            ]);

            return true;
        } catch (ApiErrorException $e) {
            Log::error('Stripe Keys Validation Failed', [
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
}
