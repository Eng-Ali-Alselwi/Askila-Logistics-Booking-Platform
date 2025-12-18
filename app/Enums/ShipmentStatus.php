<?php

namespace App\Enums;

enum ShipmentStatus: string
{
    case CREATED                      = 'created';                       // تم الإنشاء
    case RECEIVED_AT_BRANCH           = 'received_at_branch';           // تم الاستلام
    case IN_TRANSIT                   = 'in_transit';                   // قيد الشحن
    case ARRIVED_JED_WAREHOUSE        = 'arrived_jed_warehouse';        // مستودع جدة
    case SHIPPED_JED_PORT             = 'shipped_jed_port';             // شحن من ميناء جدة الإسلامي
    case ARRIVED_SUDAN_PORT           = 'arrived_sudan_port';           // وصول ميناء عثمان دقنة
    case ARRIVED_DESTINATION_BRANCH   = 'arrived_destination_branch';   // وصول فرع الاستلام
    case READY_FOR_DELIVERY           = 'ready_for_delivery';           // جاهزة للتسليم
    case DELIVERED                    = 'delivered';                     // تم التسليم

    public function label(): string
    {
        return match ($this) {
            self::CREATED                     => 'تم إدخال الشحنة في النظام',
            self::RECEIVED_AT_BRANCH          => 'تم استلام الشحنة من العميل',
            self::IN_TRANSIT                  => 'الشحنة في الطريق',
            self::ARRIVED_JED_WAREHOUSE       => 'تم وصول الشحنة لمستودع جدة',
            self::SHIPPED_JED_PORT            => 'تم شحن الشحنة من ميناء جدة الإسلامي',
            self::ARRIVED_SUDAN_PORT          => 'تم وصول الشحنة إلى ميناء عثمان دقنة',
            self::ARRIVED_DESTINATION_BRANCH  => 'تم وصول شحنتك لفرع الاستلام',
            self::READY_FOR_DELIVERY          => 'الشحنة وصلت إلى وجهتها وجاهزة للتوصيل',
            self::DELIVERED                   => 'تم تسليم الشحنة بنجاح',
            // self::RECEIVED_AT_BRANCH          =>t('Received at Branch'),
            // self::ARRIVED_JED_WAREHOUSE       =>t('Arrived Jed Warehouse'),
            // self::SHIPPED_JED_PORT            =>t('Shipped Jed Port'),
            // self::ARRIVED_SUDAN_PORT          =>t('Arrived Sudan Port'),
            // self::ARRIVED_DESTINATION_BRANCH  =>t('Arrived Destination Branch'),
            // self::DELIVERED                   =>t('Delivered'),
        };
    }
    // "تم استلام الشحنة في أحد فروع الشركة"=>"Received at Branch",
    // "تم وصول الشحنة لمستودع جدة"=>"Arrived Jed Warehouse",
    // "تم شحن الشحنة من ميناء جدة الإسلامي"=>"Shipped Jed Port",
    // "تم وصول الشحنة إلى ميناء عثمان دقنة"=>"Arrived Sudan Port",
    // "تم وصول شحنتك لفرع الاستلام"=>"Arrived Destination Branch",
    // "تم تسليم شحنتك بنجاح" => "Delivered",
    public function isTerminal(): bool
    {
        return $this === self::DELIVERED;
    }

    /** تسلسل منطقي مقترح للأحداث */
    public static function timeline(): array
    {
        return [
            self::CREATED,
            self::RECEIVED_AT_BRANCH,
            self::IN_TRANSIT,
            self::ARRIVED_JED_WAREHOUSE,
            self::SHIPPED_JED_PORT,
            self::ARRIVED_SUDAN_PORT,
            self::ARRIVED_DESTINATION_BRANCH,
            self::READY_FOR_DELIVERY,
            self::DELIVERED,
        ];
    }
}
