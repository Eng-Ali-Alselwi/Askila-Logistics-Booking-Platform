<?php

namespace App\Notifications;

use App\Models\Shipment;
use App\Enums\ShipmentStatus;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ShipmentStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Shipment $shipment,
        public ShipmentStatus $status
    ) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('تحديث حالة الشحنة - ' . $this->shipment->tracking_number)
            ->greeting('مرحباً ' . $this->shipment->sender_name)
            ->line('تم تحديث حالة شحنتك رقم: ' . $this->shipment->tracking_number)
            ->line('الحالة الجديدة: ' . $this->status->label())
            ->action('تتبع الشحنة', route('shipment.track2', $this->shipment->tracking_number))
            ->line('شكراً لاختيارك الأسكلة');
    }

    public function toDatabase($notifiable): array
    {
        return [
            'shipment_id' => $this->shipment->id,
            'tracking_number' => $this->shipment->tracking_number,
            'status' => $this->status->value,
            'status_label' => $this->status->label(),
            'message' => 'تم تحديث حالة شحنتك رقم ' . $this->shipment->tracking_number . ' إلى: ' . $this->status->label(),
        ];
    }
}
