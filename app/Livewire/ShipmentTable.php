<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Shipment;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use App\Enums\ShipmentStatus;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Branch;


class ShipmentTable extends Component
{
    use WithPagination;

    #[Url(as: 'q')] public ?string $q = null;
    #[Url(as: 'per')] public int $perPage = 15;
    #[Url(as: 'status')] public ?string $status = null;
    #[Url(as:'date')] public string $datePreset = 'all';   // all|today|yesterday|last7|this_month|last_month|this_year|custom
    #[Url(as:'from')] public ?string $from = null; // YYYY-MM-DD (لنطاق مخصّص)
    #[Url(as:'to')] public ?string $to = null; // YYYY-MM-DD
    #[Url(as:'branch')] public $branch_id = null; // int|string|null

    // protected $queryString = ['q', 'perPage', 'status','date','from','to'];
    protected $queryString = ['q', 'perPage', 'status'];
    public function setStatus(?string $status = null) // ← جديد
    {
        $this->status = $status ?: null;
        $this->resetPage();
    }

    public function setDatePreset(string $preset): void
    {
        $this->datePreset = $preset;
        if ($preset !== 'custom') {
            $this->from = $this->to = null;
        }
        $this->resetPage();
    }

    public function updatingQ() { $this->resetPage(); }
    public function updatedPerPage() { $this->resetPage(); }
    public function updatedFrom() { $this->datePreset = 'custom'; $this->resetPage(); }
    public function updatedTo()   { $this->datePreset = 'custom'; $this->resetPage(); }
    public function updatedBranchId() { 
        Session::put('shipments.branch_id', $this->branch_id);
        $this->resetPage(); 
    }

    public function mount(): void
    {
        $user = Auth::user();
        $saved = Session::get('shipments.branch_id');
        if ($saved !== null) {
            $this->branch_id = $saved;
        } else if ($user && $user->isBranchManager()) {
            $this->branch_id = $user->branch_id;
        }
    }

    protected function dateRange(): ?array
    {
        // يرجع [Carbon $start, Carbon $endInclusive] أو null عند "all"
        $now = now(); // يتبع timezone التطبيق (اضبطه Asia/Riyadh)
        return match ($this->datePreset) {
            'today' => [ $now->copy()->startOfDay(), $now->copy()->endOfDay() ],
            'yesterday' => [
                $now->copy()->subDay()->startOfDay(),
                $now->copy()->subDay()->endOfDay(),
            ],
            'last7' => [ $now->copy()->subDays(6)->startOfDay(), $now->copy()->endOfDay() ], // اليوم ضمن الـ7
            'this_month' => [ $now->copy()->startOfMonth(), $now->copy()->endOfMonth() ],
            'last_month' => [
                $now->copy()->subMonthNoOverflow()->startOfMonth(),
                $now->copy()->subMonthNoOverflow()->endOfMonth(),
            ],
            'this_year' => [ $now->copy()->startOfYear(), $now->copy()->endOfYear() ],
            'custom' => $this->from && $this->to
                ? [ Carbon::parse($this->from)->startOfDay(), Carbon::parse($this->to)->endOfDay() ]
                : null,
            default => null, // all
        };
    }

    public function rows()
    {
        $range = $this->dateRange();

        $query = Shipment::query()
            ->with(['latestEvent','creator:id,name'])
            ->search($this->q)
            ->when($this->status, fn ($q) => $q->status($this->status))
            ->when($range, function ($q) use ($range) {
                [$start, $end] = $range;
                $q->whereBetween('created_at', [$start, $end]);
            });

        $user = Auth::user();
        $canViewAll = $user && ($user->hasRole('super_admin') || $user->hasRole('manager') || $user->can('manage branches'));

        if ($this->branch_id) {
            $query->where('branch_id', $this->branch_id);
        } elseif ($user && $user->isBranchManager() && !$canViewAll) {
            $query->where('branch_id', $user->branch_id);
        }

        return $query->latest()->paginate($this->perPage);
    }


    protected function statusMeta(): array // ← جديد (الألوان والعناوين)
    {
        return [
            ShipmentStatus::RECEIVED_FROM_CUSTOMER->value => ['label' => ShipmentStatus::RECEIVED_FROM_CUSTOMER->label(), 'dot' => 'bg-gray-400','txt-color'=>'text-gray-400'],
            ShipmentStatus::IN_TRANSIT->value => ['label' => ShipmentStatus::IN_TRANSIT->label(), 'dot' => 'bg-yellow-500','txt-color'=>'text-yellow-500'],
            ShipmentStatus::ARRIVED_AT_BRANCH->value => ['label' => ShipmentStatus::ARRIVED_AT_BRANCH->label(), 'dot' => 'bg-orange-500','txt-color'=>'text-orange-500'],
            ShipmentStatus::DELIVERED->value => ['label' => ShipmentStatus::DELIVERED->label(), 'dot' => 'bg-emerald-500','txt-color'=>'text-emerald-500'],
        ];
    }

    protected function counts(): array // ← جديد (إحصائيات الفلاتر)
    {
        $meta   = $this->statusMeta();
        $totals = Shipment::selectRaw('COALESCE(current_status, "null") as s, COUNT(*) c')
            ->groupBy('s')
            ->pluck('c', 's')
            ->toArray();

        $result = ['all' => array_sum($totals)]; // الكل
        foreach (array_keys($meta) as $key) {
            $result[$key] = $totals[$key] ?? 0;
        }

        // شحنات بدون حالة (لو فيه)
        if (($totals['null'] ?? 0) > 0) {
            $result['null'] = $totals['null'];
        }

        return $result;
    }

    public function destroy(string $id): void
    {
        Shipment::findOrFail($id)->delete(); // SoftDeletes
        session()->flash('success', 'تم حذف الشحنة بنجاح.');
        $this->resetPage(); // لو كنت بآخر صفحة بعد الحذف يرجعك للي بعدها
    }

    public function clearStatus(): void
    {
        $this->setStatus(null);
    }

    public function clearDate(): void
    {
        $this->datePreset = 'all';
        $this->from = $this->to = null;
        $this->resetPage();
    }

    public function clearSearch(): void
    {
        $this->q = null;
        $this->resetPage();
    }

    public function clearAll(): void
    {
        $this->q = null;
        $this->clearStatus();
        $this->clearDate();
        // احتفظ بقيمة perPage كما هي أو أعدها لقيمة افتراضية
        // $this->perPage = 15;
    }

    public function render()
    {
        return view('livewire.shipment-table', [
            'rows' => $this->rows(),
            'meta'   => $this->statusMeta(), // ← جديد
            'counts' => $this->counts(),     // ← جديد

        ]);
    }
}
