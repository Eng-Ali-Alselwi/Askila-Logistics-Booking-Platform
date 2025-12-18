<?php

namespace App\Livewire;

use App\Actions\Shipments\RecordShipmentEvent;
use App\Enums\ShipmentStatus;
use App\Models\Shipment;
use App\Helpers\PermissionHelper;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class UpsertShipment extends Component
{
    public ?string $shipmentId = null;

    // الحقول الأساسية
    public string $tracking_number = '';
    public ?string $sender_name = null;
    public ?string $sender_phone = null;
    public ?string $receiver_name = null;
    public ?string $receiver_phone = null;
    public ?float $weight_kg = null;
    public ?float $volume_cbm = null;
    public ?float $declared_value = null;
    public ?string $notes = null;

    // اختياري: إنشاء حدث أولي مباشرة بعد الحفظ
    public ?string $initial_status = null;
    public ?string $initial_location = null;
    public ?string $initial_notes = null;
    public ?int $branch_id = null; // selection for admins/managers


    public function mount(?string $shipmentId = null): void
    {
        // Check permissions
        if ($shipmentId) {
            // For editing, check edit permission
            if (!PermissionHelper::canEdit('shipments')) {
                abort(403, 'Unauthorized action.');
            }
        } else {
            // For creating, check create permission
            if (!PermissionHelper::canCreate('shipments')) {
                abort(403, 'Unauthorized action.');
            }
        }
        
        $this->shipmentId = $shipmentId;

        if ($shipmentId) {
            $s = Shipment::query()->findOrFail($shipmentId);
            $this->fill([
                'tracking_number' => $s->tracking_number,
                'sender_name'     => $s->sender_name,
                'sender_phone'    => $s->sender_phone,
                'receiver_name'   => $s->receiver_name,
                'receiver_phone'  => $s->receiver_phone,
                'weight_kg'       => $s->weight_kg,
                'volume_cbm'      => $s->volume_cbm,
                'declared_value'  => $s->declared_value,
                'notes'           => $s->notes,
            ]);
            $this->branch_id = $s->branch_id;
            $this->initial_status = $s->current_status;
            $latest = $s->latestEvent()->first();
            $this->initial_location = $latest?->location_text;
            $this->initial_notes    = $latest?->notes;
        }
        // default branch
        $user = Auth::user();
        if ($user && $user->isBranchManager()) {
            $this->branch_id = $user->branch_id;
        }
    }

    protected function rules(): array
    {
        $id = $this->shipmentId;

        return [
            'tracking_number' => [
                'required', 'string', 'max:40',
                Rule::unique('shipments', 'tracking_number')
                    ->ignore($id)               // تجاهل الحالية عند التعديل
                    ->whereNull('deleted_at'),  // تجاهل المحذوفة سوفت
            ],
            'sender_name'    => ['nullable', 'string', 'max:120'],
            'sender_phone'   => ['nullable', 'string', 'max:20'],
            'receiver_name'  => ['nullable', 'string', 'max:120'],
            'receiver_phone' => ['nullable', 'string', 'max:20'],
            'weight_kg'      => ['nullable', 'numeric', 'min:0'],
            'volume_cbm'     => ['nullable', 'numeric', 'min:0'],
            'declared_value' => ['nullable', 'numeric', 'min:0'],
            'notes'          => ['nullable', 'string', 'max:2000'],

            'initial_status'   => ['nullable', Rule::in(array_map(fn($c)=>$c->value, ShipmentStatus::cases()))],
            'initial_location' => ['nullable', 'string', 'max:255'],
            'initial_notes'    => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function save(RecordShipmentEvent $record)
    {
        // Check permissions again before saving
        if ($this->shipmentId) {
            // For editing, check edit permission
            if (!PermissionHelper::canEdit('shipments')) {
                abort(403, 'Unauthorized action.');
            }
        } else {
            // For creating, check create permission
            if (!PermissionHelper::canCreate('shipments')) {
                abort(403, 'Unauthorized action.');
            }
        }
        
        $data = $this->validate();

        $payload = collect($data)->only([
            'tracking_number','sender_name','sender_phone','receiver_name','receiver_phone',
            'weight_kg','volume_cbm','declared_value','notes',
        ])->toArray();

        // assign branch per role
        $user = Auth::user();
        if ($user && $user->isBranchManager()) {
            $payload['branch_id'] = $user->branch_id;
        } else if ($this->branch_id) {
            $payload['branch_id'] = $this->branch_id;
        }

        $shipment = $this->shipmentId
            ? tap(Shipment::findOrFail($this->shipmentId))->update($payload)
            : Shipment::create($payload);

        // سجّل حدث إذا:
        // - إنشاء جديد & فيه حالة ابتدائية
        // - أو تعديل & (تغيّرت الحالة أو تغيّر الموقع أو توجد ملاحظة)
        $latest = $shipment->latestEvent()->first();
        $locationChanged = $this->initial_location !== ($latest?->location_text);
        $hasNotes = filled($this->initial_notes);

        $shouldRecord =
            (!$this->shipmentId && $this->initial_status)
            || (
                $this->initial_status && (
                    ($this->shipmentId && $this->initial_status !== $shipment->current_status)
                    || $locationChanged
                    || $hasNotes
                )
            );

        if ($shouldRecord) {
            $record->handle($shipment, $this->initial_status, [
                'location_text' => $this->initial_location,
                'notes'         => $this->initial_notes,
            ]);
        }

        session()->flash('success', $this->shipmentId ? t('Shipment updated successfully') : t('Shipment created successfully'));

        // انتقال نظيف بعد الحفض
        return redirect()->route('dashboard.shipments.index');

    }

    public function render()
    {
        // Check permissions for viewing the form
        if ($this->shipmentId) {
            if (!PermissionHelper::canEdit('shipments')) {
                abort(403, 'Unauthorized action.');
            }
        } else {
            if (!PermissionHelper::canCreate('shipments')) {
                abort(403, 'Unauthorized action.');
            }
        }
        
        return view('livewire.upsert-shipment',[
            'statuses' => ShipmentStatus::cases(),
        ]);
    }
}