<?php

namespace App\Listeners;

use App\Events\ShipmentEventRecorded;
use App\Actions\Sms\SendShipmentStatusSms;
use App\Enums\ShipmentStatus;

class SendSmsOnShipmentEventRecorded
{
    public function __construct(
        private readonly SendShipmentStatusSms $action
    ) {}

    public function handle(ShipmentEventRecorded $payload): void
    {
        $shipment = $payload->shipment;
        $status = ShipmentStatus::from($payload->event->status); // تحويل من string إلى enum
        ($this->action)($shipment, $status);
    }
}
