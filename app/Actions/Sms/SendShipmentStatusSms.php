<?php

namespace App\Actions\Sms;

use App\Models\Shipment;
use App\Enums\ShipmentStatus;
use App\Services\Sms\MoraSmsClient;
use App\Support\Sms\ShipmentStatusMessageBuilder;
use App\Models\SmsLog;
use Illuminate\Support\Facades\Cache;
use Throwable;

class SendShipmentStatusSms
{
    public function __construct(
        private readonly MoraSmsClient $client,
        private readonly ShipmentStatusMessageBuilder $builder,
    ) {}

    public function __invoke(Shipment $shipment, ShipmentStatus $status): void
    {
        $to = $shipment->sender_phone; // احرص على صيغة 9665XXXXXXXX
        if (! $to) return;

        // منع التكرار لنفس الشحنة والحالة لمدة 15 دقيقة (اختياري)
        $key = "sms:shipment:{$shipment->id}:{$status->value}";
        if (Cache::has($key)) return;
        Cache::put($key, 1, now()->addMinutes(15));

        $message = $this->builder->build($shipment, $status);

        try {
            $result = $this->client->send($to, $message);

            SmsLog::create([
                'to'       => $to,
                'message'  => $message,
                'provider' => 'mora',
                'status'   => $result['ok'] ? 'sent' : 'failed',
                'code'     => $result['code'],
                'response' => $result['raw'],
                'error'    => $result['error'],
                'sent_at'  => now(),
            ]);
        } catch (Throwable $e) {
            SmsLog::create([
                'to'       => $to,
                'message'  => $message,
                'provider' => 'mora',
                'status'   => 'exception',
                'code'     => null,
                'response' => null,
                'error'    => $e->getMessage(),
                'sent_at'  => now(),
            ]);
            // لا نرمي الاستثناء حتى لا نكسر تدفق تحديث الحالة
        }
    }
}
