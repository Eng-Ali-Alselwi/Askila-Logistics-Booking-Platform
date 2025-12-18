<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Booking;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class BookingTable extends Component
{
    use WithPagination;

    #[Url(as: 'q')] public ?string $q = null;
    #[Url(as: 'per')] public int $perPage = 15;
    #[Url(as: 'status')] public ?string $status = null;
    #[Url(as: 'payment_status')] public ?string $payment_status = null;
    #[Url(as: 'trip_type')] public ?string $trip_type = null; // Add trip type filter
    #[Url(as:'date')] public string $datePreset = 'all';   // all|today|yesterday|last7|this_month|last_month|this_year|custom
    #[Url(as:'from')] public ?string $from = null; // YYYY-MM-DD (لنطاق مخصّص)
    #[Url(as:'to')] public ?string $to = null; // YYYY-MM-DD
    #[Url(as:'branch')] public $branch_id = null;

    protected $queryString = ['q', 'perPage', 'status', 'payment_status', 'trip_type']; // Add trip_type to query string

    public function setStatus(?string $status = null)
    {
        $this->status = $status ?: null;
        $this->resetPage();
    }

    public function setPaymentStatus(?string $payment_status = null)
    {
        $this->payment_status = $payment_status ?: null;
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
    public function updatedBranchId() { Session::put('bookings.branch_id', $this->branch_id); $this->resetPage(); }

    // Add trip type update method
    public function updatedTripType() { $this->resetPage(); }

    public function mount(): void
    {
        $user = Auth::user();
        $saved = Session::get('bookings.branch_id');
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

        $query = Booking::with(['flight', 'customer', 'creator'])
            ->search($this->q)
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->when($this->payment_status, function ($query) {
                $query->where('payment_status', $this->payment_status);
            })
            // Add trip type filter through flight relationship
            ->when($this->trip_type, function ($query) {
                $query->whereHas('flight', function ($q) {
                    $q->where('trip_type', $this->trip_type);
                });
            })
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

        return $query->orderBy('created_at', 'desc')->paginate($this->perPage);
    }

    protected function statusMeta(): array
    {
        return [
            'pending' => ['label' => 'Pending Bookings', 'dot' => 'bg-yellow-500', 'txt-color' => 'text-yellow-500'],
            'confirmed' => ['label' => 'Confirmed Bookings', 'dot' => 'bg-green-500', 'txt-color' => 'text-green-500'],
            'cancelled' => ['label' => 'Cancelled Bookings', 'dot' => 'bg-red-500', 'txt-color' => 'text-red-500'],
            'completed' => ['label' => 'Completed Bookings', 'dot' => 'bg-blue-500', 'txt-color' => 'text-blue-500'],
        ];
    }

    protected function paymentStatusMeta(): array
    {
        return [
            'pending' => ['label' => 'Pending Payments', 'dot' => 'bg-yellow-500', 'txt-color' => 'text-yellow-500'],
            'pending_manual' => ['label' => 'Manual Confirmation Pending', 'dot' => 'bg-blue-500', 'txt-color' => 'text-blue-500'],
            'paid' => ['label' => 'Paid Bookings', 'dot' => 'bg-green-500', 'txt-color' => 'text-green-500'],
            'failed' => ['label' => 'Failed Payments', 'dot' => 'bg-red-500', 'txt-color' => 'text-red-500'],
            'refunded' => ['label' => 'Refunded Bookings', 'dot' => 'bg-gray-500', 'txt-color' => 'text-gray-500'],
        ];
    }

    protected function counts(): array
    {
        $statusMeta = $this->statusMeta();
        $paymentStatusMeta = $this->paymentStatusMeta();
        $totals = [];

        // إحصائيات الحالات
        foreach (array_keys($statusMeta) as $key) {
            $totals['status_' . $key] = Booking::where('status', $key)->count();
        }

        foreach (array_keys($paymentStatusMeta) as $key) {
            $totals['payment_' . $key] = Booking::where('payment_status', $key)->count();
        }

        $result = ['all' => Booking::count()]; // الكل
        foreach ($totals as $key => $count) {
            $result[$key] = $count;
        }

        return $result;
    }

    public function destroy(string $id): void
    {
        $booking = Booking::findOrFail($id);
        
        // التحقق من إمكانية الحذف
        if ($booking->status === 'confirmed' && $booking->flight->departure_time > now()) {
            session()->flash('error', 'لا يمكن حذف حجز مؤكد لرحلة لم تغادر بعد.');
            return;
        }

        $booking->delete();
        session()->flash('success', 'تم حذف الحجز بنجاح.');
        $this->resetPage();
    }

    public function cancel(string $id): void
    {
        $booking = Booking::findOrFail($id);
        
        if (!$booking->canBeCancelled()) {
            session()->flash('error', 'لا يمكن إلغاء هذا الحجز.');
            return;
        }

        $booking->cancel('تم الإلغاء من قبل الإدارة');
        session()->flash('success', 'تم إلغاء الحجز بنجاح.');
    }

    public function confirm(string $id): void
    {
        $booking = Booking::findOrFail($id);
        
        if ($booking->status !== 'pending') {
            session()->flash('error', 'يمكن تأكيد الحجوزات المعلقة فقط.');
            return;
        }

        $booking->confirm();
        session()->flash('success', 'تم تأكيد الحجز بنجاح.');
    }

    public function clearStatus(): void
    {
        $this->setStatus(null);
    }

    public function clearPaymentStatus(): void
    {
        $this->setPaymentStatus(null);
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
        $this->clearPaymentStatus();
        $this->clearTripType(); // Clear trip type
        $this->clearDate();
    }

    public function render()
    {
        return view('livewire.booking-table', [
            'rows' => $this->rows(),
            'statusMeta' => $this->statusMeta(),
            'paymentStatusMeta' => $this->paymentStatusMeta(),
            'counts' => $this->counts(),
        ]);
    }
}