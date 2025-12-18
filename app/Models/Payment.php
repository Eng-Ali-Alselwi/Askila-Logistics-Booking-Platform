<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_reference',
        'payable_type',
        'payable_id',
        'amount',
        'currency',
        'payment_method',
        'status',
        'gateway_transaction_id',
        'gateway_response',
        'failure_reason',
        'processed_at',
        'processed_by'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'gateway_response' => 'array',
        'processed_at' => 'datetime',
    ];

    // العلاقات
    public function payable()
    {
        return $this->morphTo();
    }

    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    // سكوبات مفيدة
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeByMethod($query, $method)
    {
        return $query->where('payment_method', $method);
    }

    public function scopeByDateRange($query, $start, $end)
    {
        return $query->whereBetween('created_at', [$start, $end]);
    }

    // مساعدات
    public function getAmountFormattedAttribute()
    {
        return number_format($this->amount, 2) . ' ' . $this->currency;
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isFailed()
    {
        return $this->status === 'failed';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function markAsCompleted($gatewayTransactionId = null, $gatewayResponse = null)
    {
        $this->update([
            'status' => 'completed',
            'gateway_transaction_id' => $gatewayTransactionId,
            'gateway_response' => $gatewayResponse,
            'processed_at' => now()
        ]);
    }

    public function markAsFailed($reason = null, $gatewayResponse = null)
    {
        $this->update([
            'status' => 'failed',
            'failure_reason' => $reason,
            'gateway_response' => $gatewayResponse,
            'processed_at' => now()
        ]);
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            if (is_null($model->payment_reference)) {
                $model->payment_reference = 'PAY' . strtoupper(uniqid());
            }
        });
    }
}
