<?php

namespace App\Actions\Shipments;

use App\Enums\ShipmentStatus;
use App\Events\ShipmentEventRecorded;
use App\Models\Shipment;
use App\Models\ShipmentEvent;
use App\Services\NotificationService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class RecordShipmentEvent
{
    /**
     * يسجّل حدثًا جديدًا لشحنة ويحدّث حالتها الحالية.
     *
     * @param Shipment $shipment
     * @param ShipmentStatus|string $status
     * @param array{
     *   happened_at?: \DateTimeInterface|string|null,
     *   location_text?: string|null,
     *   notes?: string|null,
     * } $data
     */
    public function handle(Shipment $shipment, ShipmentStatus|string $status, array $data = []): ShipmentEvent
    {
        $statusValue = $status instanceof ShipmentStatus ? $status->value : $status;
        $statusEnum = $status instanceof ShipmentStatus ? $status : ShipmentStatus::from($statusValue);

        $event = $shipment->events()->create([
            'status'       => $statusValue,
            'happened_at'  => Arr::get($data, 'happened_at', now()),
            'location_text'=> Arr::get($data, 'location_text'),
            'notes'        => Arr::get($data, 'notes'),
            'created_by'    => Arr::get($data, 'created_by', Auth::id()),
        ]);

        // تحديث كاش الحالة الحالية + تواريخ مفيدة
        $shipment->setCurrentStatus($statusValue);
        
        // إرسال الإشعارات
        $notificationService = app(NotificationService::class);
        $notificationService->sendShipmentStatusUpdate($shipment->fresh(), $statusEnum);
        
        event(new ShipmentEventRecorded($shipment->fresh(), $event));
        return $event;
    }
}
