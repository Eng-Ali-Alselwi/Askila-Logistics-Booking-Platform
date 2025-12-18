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
            ShipmentStatus::CREATED                     => $base.'تم إدخال الشحنة في النظام. تتبع: '.$link,
            ShipmentStatus::RECEIVED_AT_BRANCH          => $base.'تم استلام الشحنة في أحد فروع الشركة. تتبع: '.$link,
            ShipmentStatus::IN_TRANSIT                  => $base.'الشحنة في الطريق. تتبع: '.$link,
            ShipmentStatus::ARRIVED_JED_WAREHOUSE       => $base.'تم وصول الشحنة لمستودع جدة. تتبع: '.$link,
            ShipmentStatus::SHIPPED_JED_PORT            => $base.'تم شحن الشحنة من ميناء جدة الإسلامي. تتبع: '.$link,
            ShipmentStatus::ARRIVED_SUDAN_PORT          => $base.'تم وصول الشحنة إلى ميناء عثمان دقنة. تتبع: '.$link,
            ShipmentStatus::ARRIVED_DESTINATION_BRANCH  => $base.'تم وصول شحنتك لفرع الاستلام. تتبع: '.$link,
            ShipmentStatus::READY_FOR_DELIVERY          => $base.'الشحنة وصلت إلى وجهتها وجاهزة للتوصيل. تتبع: '.$link,
            ShipmentStatus::DELIVERED                   => $base.'تم تسليم شحنتك بنجاح. شكراً لاختيارك الأسكلة.',
        };
    }
}
