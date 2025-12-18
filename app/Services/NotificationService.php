<?php

namespace App\Services;

use App\Models\Shipment;
use App\Enums\ShipmentStatus;
use App\Notifications\ShipmentStatusNotification;
use App\Services\Sms\MoraSmsClient;
use App\Support\Sms\ShipmentStatusMessageBuilder;
use App\Models\SmsLog;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Cache;
use Throwable;

class NotificationService
{
    public function __construct(
        private readonly MoraSmsClient $smsClient,
        private readonly ShipmentStatusMessageBuilder $messageBuilder,
    ) {}

    public function sendShipmentStatusUpdate(Shipment $shipment, ShipmentStatus $status): void
    {
        // Send Email Notification
        if ($shipment->sender_email) {
            try {
                $shipment->notify(new ShipmentStatusNotification($shipment, $status));
            } catch (Throwable $e) {
                report($e);
            }
        }

        // Send SMS Notification
        if ($shipment->sender_phone) {
            $this->sendSmsNotification($shipment, $status);
        }
    }

    private function sendSmsNotification(Shipment $shipment, ShipmentStatus $status): void
    {
        $phone = $shipment->sender_phone;
        if (!$phone) return;

        // Prevent duplicate SMS for same status within 15 minutes
        $key = "sms:shipment:{$shipment->id}:{$status->value}";
        if (Cache::has($key)) return;
        Cache::put($key, 1, now()->addMinutes(15));

        $message = $this->messageBuilder->build($shipment, $status);

        try {
            $result = $this->smsClient->send($phone, $message);

            SmsLog::create([
                'to' => $phone,
                'message' => $message,
                'provider' => 'mora',
                'status' => $result['ok'] ? 'sent' : 'failed',
                'code' => $result['code'],
                'response' => $result['raw'],
                'error' => $result['error'],
                'sent_at' => now(),
            ]);
        } catch (Throwable $e) {
            SmsLog::create([
                'to' => $phone,
                'message' => $message,
                'provider' => 'mora',
                'status' => 'exception',
                'code' => null,
                'response' => null,
                'error' => $e->getMessage(),
                'sent_at' => now(),
            ]);
            report($e);
        }
    }
}
