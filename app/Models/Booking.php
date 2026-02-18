<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\BelongsToBranch;

class Booking extends Model
{
    use HasFactory, SoftDeletes, BelongsToBranch;

    protected $fillable = [
        'booking_reference',
        'flight_id',
        'customer_id',
        'passenger_name',
        'passenger_email',
        'passenger_phone',
        'image',
        'passenger_id_number',
        'passport_number',
        'passport_issue_date',
        'passport_expiry_date',
        'nationality',
        'date_of_birth',
        'current_residence_country',
        'destination_country',
        'phone_sudan',
        'travel_date',
        'ticket_type',
        'seat_class',
        'cabin_type',
        'number_of_passengers',
        'passenger_details',
        'total_amount',
        'tax_amount',
        'service_fee',
        'currency',
        'status',
        'payment_status',
        'payment_method',
        'payment_reference',
        'payment_date',
        'special_requests',
        'cancellation_reason',
        'cancelled_at',
        'created_by',
        'branch_id'
    ];

    protected $casts = [
        'passenger_details' => 'array',
        'total_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'service_fee' => 'decimal:2',
        'payment_date' => 'datetime',
        'cancelled_at' => 'datetime',
        'passport_issue_date' => 'date',
        'passport_expiry_date' => 'date',
        'date_of_birth' => 'date',
        'travel_date' => 'date',
    ];

    // العلاقات
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
    public function flight()
    {
        return $this->belongsTo(Flight::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function payments()
    {
        return $this->morphMany(Payment::class, 'payable');
    }

    // سكوبات مفيدة
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByPaymentStatus($query, $status)
    {
        return $query->where('payment_status', $status);
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    public function scopeByDateRange($query, $start, $end)
    {
        return $query->whereBetween('created_at', [$start, $end]);
    }

    // سكوب للبحث
    public function scopeSearch($query, $term)
    {
        if (!$term) return $query;
        return $query->where(function($q) use ($term) {
            $q->where('booking_reference', 'like', '%' . $term . '%')
              ->orWhere('passenger_name', 'like', '%' . $term . '%')
              ->orWhere('passenger_email', 'like', '%' . $term . '%')
              ->orWhere('passenger_phone', 'like', '%' . $term . '%')
              ->orWhere('passport_number', 'like', '%' . $term . '%')
              ->orWhere('nationality', 'like', '%' . $term . '%')
              ->orWhereHas('flight', function($flightQuery) use ($term) {
                  $flightQuery->where('flight_number', 'like', '%' . $term . '%')
                             ->orWhere('airline', 'like', '%' . $term . '%')
                             ->orWhere('operator_name', 'like', '%' . $term . '%')
                             ->orWhere('trip_type', 'like', '%' . $term . '%');
              });
        });
    }

    // مساعدات
    public function getTotalAmountFormattedAttribute()
    {
        return number_format($this->total_amount, 2) . ' ' . $this->currency;
    }

    public function isConfirmed()
    {
        return $this->status === 'confirmed';
    }

    public function isPaid()
    {
        return in_array($this->payment_status, ['paid', 'confirmed']);
    }

    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }

    public function canBeCancelled()
    {
        return in_array($this->status, ['pending', 'confirmed']) && 
               $this->flight->departure_time > now()->addHours(24);
    }

    public function cancel($reason = null)
    {
        if (!$this->canBeCancelled()) {
            return false;
        }

        $this->update([
            'status' => 'cancelled',
            'cancellation_reason' => $reason,
            'cancelled_at' => now()
        ]);

        // إعادة المقاعد المتاحة
        $this->flight->updateAvailableSeats($this->number_of_passengers);

        return true;
    }

    public function confirm()
    {
        $this->update(['status' => 'confirmed']);
    }

    public function markAsPaid($paymentMethod, $paymentReference = null)
    {
        $this->update([
            'payment_status' => 'paid',
            'payment_method' => $paymentMethod,
            'payment_reference' => $paymentReference,
            'payment_date' => now()
        ]);
    }

    public function markAsPendingManual()
    {
        $this->update([
            'payment_status' => 'pending_manual',
            'status' => 'pending'
        ]);
    }

    public function isPendingManual()
    {
        return $this->payment_status === 'pending_manual';
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            if (is_null($model->booking_reference)) {
                $model->booking_reference = 'ASK' . strtoupper(uniqid());
            }
        });
    }
}