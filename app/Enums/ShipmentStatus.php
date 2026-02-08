<?php

namespace App\Enums;

enum ShipmentStatus: string
{

    case RECEIVED_FROM_CUSTOMER = 'received_from_customer';
    case IN_TRANSIT             = 'in_transit';
    case ARRIVED_AT_BRANCH      = 'arrived_at_branch';
    case DELIVERED              = 'delivered';

    public function label(): string
    {
        return match ($this) {
            self::RECEIVED_FROM_CUSTOMER => 'تم استلام الشحنة من العميل',
            self::IN_TRANSIT             => 'الشحنة في الطريق',
            self::ARRIVED_AT_BRANCH      => 'تم وصول شحنتك للفرع الخاص بالاستلام',
            self::DELIVERED              => 'تم تسليم الشحنة للعميل بنجاح',
        };
    }

    public function isTerminal(): bool
    {
        return $this === self::DELIVERED;
    }

    public static function timeline(): array
    {
        return [
            self::RECEIVED_FROM_CUSTOMER,
            self::IN_TRANSIT,
            self::ARRIVED_AT_BRANCH,
            self::DELIVERED,
        ];
    }
}
