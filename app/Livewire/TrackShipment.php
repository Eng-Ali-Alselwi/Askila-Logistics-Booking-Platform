<?php

namespace App\Livewire;

use App\Models\Shipment;
use Livewire\Attributes\Url;
use Livewire\Component;

class TrackShipment extends Component
{
    #[Url(as: 'q')]
    public ?string $query = null;
    public bool $searched = false;
    public ?array $steps = null;
    public ?array $summary = null; // رقم الشحنة + الحالة الحالية (label فقط)
    public ?string $error = null;  // 'not_found' | 'server' | null

    public function mount(?string $prefill = null): void
    {
        // if ($prefill && !$this->query) {
        //     $this->query = $prefill;
        //     $this->search();
        // }

        // أولوية للـ prefill من الباث
        if ($prefill && !$this->query) {
            $this->query = $prefill;
        }

        // إذا كان فيه قيمة جاهزة (من الباث أو الكويري)، نطلق البحث فوراً
        if ($this->query) {
            $this->search();
        }
    }

    protected function rules(): array
    {
        return ['query' => ['required','string','max:40']];
    }

    public function search(): void
    {
        $this->validate();
        $this->searched = true;
        $this->error = null;
        $this->steps = null;
        $this->summary = null;

        try {
            $s = Shipment::query()
                ->where('tracking_number', $this->query)
                ->first();

            if (!$s) {
                $this->error = 'not_found';
                return;
            }

            // عرض مختصر فقط: ترتيب الحالات وموضعنا الحالي
            $this->steps = $s->canonicalTimeline();

            $this->summary = [
                'tracking_number'       => $s->tracking_number,
                'current_status'        => $s->current_status,
                'current_status_label'  => $s->current_status_label,
            ];

        } catch (\Throwable $e) {
            report($e);
            $this->error = 'server';
        }
    }

    public function resetForm(): void
    {
        $this->query = null;
        $this->searched = false;
        $this->steps = null;
        $this->summary = null;
        $this->error = null;
    }

    public function render()
    {
        return view('livewire.track-shipment');
    }

}
