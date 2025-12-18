<?php

namespace App\Livewire;

use App\Actions\Shipments\RecordShipmentEvent;
use App\Enums\ShipmentStatus;
use App\Models\Shipment;
use App\Utils\Toast;
use Livewire\Component;
use Livewire\Attributes\On;
use SweetAlert2\Laravel\Swal;

class ShowShipment extends Component
{
    public string $shipmentId;

    // نموذج تحديث الحالة
    public ?string $status = null;
    public ?string $location_text = null;
    public ?string $notes = null;
    public ?string $happened_at = null; // datetime-local

    protected function rules(): array
    {
        return [
            'status'        => ['required', 'string', 'in:' . collect(ShipmentStatus::cases())->map->value->implode(',')],
            'location_text' => ['nullable', 'string', 'max:255'],
            'notes'         => ['nullable', 'string', 'max:1000'],
            'happened_at'   => ['nullable', 'date'],
        ];
    }

    public function mount(string $shipmentId)
    {
        // Swal::toastSuccess([
        //     'title' => 'Popup with an info icon',
        //     "position" => app()->getLocale() == 'ar' ? "top-right" : "top-end",
        //     "showConfirmButton" => false,
        //     "timer" => 3000,
        //     "timerProgressBar" =>true,
        // ]);
        $this->shipmentId = $shipmentId;

        // تعبئة افتراضية: الحالة الحالية + تفاصيل آخر حدث
        $s = $this->shipment();
        $this->status = $s->current_status ?? ShipmentStatus::RECEIVED_AT_BRANCH->value;
        $latest = $s->latestEvent()->first();
        $this->location_text = $latest?->location_text;
        $this->notes         = null; // نخلي الملاحظات فارغة لتجنّب تكرار نفس الملاحظة
        $this->happened_at   = now()->format('Y-m-d\TH:i');
    }

    public function shipment(): Shipment
    {
        // return Shipment::query()
        // ->with(['events' => fn($q) => $q->orderBy('happened_at')])
        // ->findOrFail($this->shipmentId);

        return Shipment::query()
            ->with(['events' => fn($q) => $q->orderBy('happened_at'), 'events.creator'])
            ->findOrFail($this->shipmentId);
    }

    public function updateStatus(RecordShipmentEvent $record)
    {
        $this->validate();

        $s = Shipment::findOrFail($this->shipmentId);

        // لا تسجّل حدثاً إذا لم تتغير الحالة ولم يُعدل شيء ذي معنى
        $latest = $s->latestEvent()->first();
        $noChange =
            ($this->status === $s->current_status) &&
            ($this->location_text === ($latest?->location_text)) &&
            (blank($this->notes));

            //Toast::success(t('There are no changes to update'));
        if ($noChange) {
            session()->flash('info', t('There are no changes to update'));
            return;
        }

        $record->handle($s, $this->status, [
            'location_text' => $this->location_text,
            'notes'         => $this->notes,
            'happened_at'   => $this->happened_at ?: now(),
        ]);

        // إعادة ضبط ملاحظة فقط
        $this->notes = null;
        // Toast::success(t('Shipment status updated and event logged'));
        // session()->flash('success', t('Shipment status updated and event logged'));
    }

    public function quickSet(string $status, RecordShipmentEvent $record): void
    {
        // زر اختصار يعيّن الحالة التالية بسرعة
        $this->status = $status;
        $this->updateStatus($record);
    }



    public function render()
    {
        $shipment = $this->shipment();

        return view('livewire.show-shipment', [
            'shipment' => $shipment,
            'statuses' => ShipmentStatus::cases(),
        ]);
        // return view('livewire.show-shipment');
    }
}
