<?php

namespace App\Support\Sms;

use App\Enums\ShipmentStatus;
use App\Models\Shipment;

class ShipmentStatusMessageBuilder
{
    public function build(Shipment $shipment, ShipmentStatus $status): string
    {
        $code = $shipment->tracking_number ;
        $name = $shipment->sender_name ?? 'عميلنا الكريم';
        $link = route('shipment.track2', ['tracking_number' => $code]); // عدّل حسب روت التتبع لديك

        $base = "مرحباً {$name}، رقم شحنتك {$code}: ";

        return match ($status) {
            ShipmentStatus::RECEIVED_FROM_CUSTOMER => $base.'تم استلام الشحنة من العميل. تتبع: '.$link,
            ShipmentStatus::IN_TRANSIT => $base.'الشحنة في الطريق. تتبع: '.$link,
            ShipmentStatus::ARRIVED_AT_BRANCH => $base.'تم وصول شحنتك للفرع الخاص بالاستلام. تتبع: '.$link,
            ShipmentStatus::DELIVERED => $base.'تم تسليم شحنتك بنجاح. شكراً لاختيارك شركتنا.',
        };
    }
}
