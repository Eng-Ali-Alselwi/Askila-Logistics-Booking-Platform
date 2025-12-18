<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Flight;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class FlightTable extends Component
{
    use WithPagination;

    #[Url(as: 'q')] public ?string $q = null;
    #[Url(as: 'per')] public int $perPage = 15;
    #[Url(as: 'status')] public ?string $status = null;
    #[Url(as: 'trip_type')] public ?string $trip_type = null; // Add trip type filter
    #[Url(as:'date')] public string $datePreset = 'all';   // all|today|yesterday|last7|this_month|last_month|this_year|custom
    #[Url(as:'from')] public ?string $from = null; // YYYY-MM-DD (لنطاق مخصّص)
    #[Url(as:'to')] public ?string $to = null; // YYYY-MM-DD
    #[Url(as:'branch')] public $branch_id = null;

    protected $queryString = ['q', 'perPage', 'status', 'trip_type']; // Add trip_type to query string

    public function setStatus(?string $status = null)
    {
        $this->status = $status ?: null;
        $this->resetPage();
    }

    // Add trip type filter method
    public function setTripType(?string $trip_type = null)
    {
        $this->trip_type = $trip_type ?: null;
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
    public function updatedBranchId() { Session::put('flights.branch_id', $this->branch_id); $this->resetPage(); }

    // Add trip type update method
    public function updatedTripType() { $this->resetPage(); }

    public function mount(): void
    {
        $user = Auth::user();
        $saved = Session::get('flights.branch_id');
        if ($saved !== null) {
            $this->branch_id = $saved;
        } else if ($user && $user->isBranchManager()) {
            $this->branch_id = $user->branch_id;
        }
    }

    protected function dateRange(): ?array
    {
        $now = now();
        return match ($this->datePreset) {
            'today' => [ $now->copy()->startOfDay(), $now->copy()->endOfDay() ],
            'yesterday' => [
                $now->copy()->subDay()->startOfDay(),
                $now->copy()->subDay()->endOfDay(),
            ],
            'last7' => [ $now->copy()->subDays(6)->startOfDay(), $now->copy()->endOfDay() ],
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

        $query = Flight::query()
            ->search($this->q)
            ->when($this->status, function ($query) {
                if ($this->status === 'active') {
                    $query->where('is_active', true);
                } elseif ($this->status === 'inactive') {
                    $query->where('is_active', false);
                } elseif ($this->status === 'available') {
                    $query->where('available_seats', '>', 0);
                } elseif ($this->status === 'full') {
                    $query->where('available_seats', 0);
                } elseif ($this->status === 'upcoming') {
                    $query->where('departure_time', '>', now());
                } elseif ($this->status === 'past') {
                    $query->where('departure_time', '<', now());
                }
            })
            // Add trip type filter
            ->when($this->trip_type, function ($query) {
                $query->where('trip_type', $this->trip_type);
            })
            ->when($range, function ($q) use ($range) {
                [$start, $end] = $range;
                $q->whereBetween('departure_time', [$start, $end]);
            });

        $user = Auth::user();
        $canViewAll = $user && ($user->hasRole('super_admin') || $user->hasRole('manager') || $user->can('manage branches'));
        if ($this->branch_id) {
            $query->where('branch_id', $this->branch_id);
        } elseif ($user && $user->isBranchManager() && !$canViewAll) {
            $query->where('branch_id', $user->branch_id);
        }

        return $query->orderBy('departure_time', 'desc')->paginate($this->perPage);
    }

    protected function statusMeta(): array
    {
        return [
            'active' => ['label' => 'Active Flights', 'dot' => 'bg-green-500', 'txt-color' => 'text-green-500'],
            'inactive' => ['label' => 'Inactive Flights', 'dot' => 'bg-gray-500', 'txt-color' => 'text-gray-500'],
            'available' => ['label' => 'Available Seats', 'dot' => 'bg-blue-500', 'txt-color' => 'text-blue-500'],
            'full' => ['label' => 'Fully Booked', 'dot' => 'bg-red-500', 'txt-color' => 'text-red-500'],
            'upcoming' => ['label' => 'Upcoming Flights', 'dot' => 'bg-indigo-500', 'txt-color' => 'text-indigo-500'],
            'past' => ['label' => 'Past Flights', 'dot' => 'bg-amber-500', 'txt-color' => 'text-amber-500'],
        ];
    }

    protected function counts(): array
    {
        $meta = $this->statusMeta();
        $totals = [];

        // إحصائيات الحالات
        $totals['active'] = Flight::where('is_active', true)->count();
        $totals['inactive'] = Flight::where('is_active', false)->count();
        $totals['available'] = Flight::where('available_seats', '>', 0)->count();
        $totals['full'] = Flight::where('available_seats', 0)->count();
        $totals['upcoming'] = Flight::where('departure_time', '>', now())->count();
        $totals['past'] = Flight::where('departure_time', '<', now())->count();

        $result = ['all' => Flight::count()]; // الكل
        foreach (array_keys($meta) as $key) {
            $result[$key] = $totals[$key] ?? 0;
        }

        return $result;
    }

    public function destroy(string $id): void
    {
        $flight = Flight::findOrFail($id);
        
        // التحقق من وجود حجوزات
        if ($flight->bookings()->exists()) {
            session()->flash('error', 'لا يمكن حذف رحلة تحتوي على حجوزات.');
            return;
        }

        $flight->delete();
        session()->flash('success', 'تم حذف الرحلة بنجاح.');
        $this->resetPage();
    }

    public function toggleStatus(string $id): void
    {
        $flight = Flight::findOrFail($id);
        $flight->update(['is_active' => !$flight->is_active]);
        
        $status = $flight->is_active ? 'تفعيل' : 'إلغاء تفعيل';
        session()->flash('success', "تم {$status} الرحلة بنجاح.");
    }

    public function clearStatus(): void
    {
        $this->setStatus(null);
    }

    // Add clear trip type method
    public function clearTripType(): void
    {
        $this->setTripType(null);
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
        $this->clearTripType(); // Clear trip type
        $this->clearDate();
    }

    public function render()
    {
        return view('livewire.flight-table', [
            'rows' => $this->rows(),
            'meta' => $this->statusMeta(),
            'counts' => $this->counts(),
        ]);
    }
}