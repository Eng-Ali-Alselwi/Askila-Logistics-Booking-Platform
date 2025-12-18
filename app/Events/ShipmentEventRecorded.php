<?php

namespace App\Events;

use App\Models\Shipment;
use App\Models\ShipmentEvent;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ShipmentEventRecorded
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Shipment $shipment,
        public ShipmentEvent $event,
    ) {}
}
