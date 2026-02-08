<?php

namespace App\Models;

use App\Enums\ShipmentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Auth;

class Shipment extends Model
{
    use HasFactory, HasUlids, SoftDeletes;

    protected $fillable = [
        'tracking_number', 
        'sender_name', 
        'sender_phone', 
        'receiver_name', 
        'receiver_phone', 
        'weight_kg', 
        'volume_cbm', 
        'declared_value', 
        'notes',
        'customer_id',
        'branch_id',
        'destination_branch_id'
    ];

    protected function casts(): array
    {
        return [
            'shipped_at'   => 'datetime',
            'delivered_at' => 'datetime',
        ];
    }


    // العلاقات
    public function events()
    {
        return $this->hasMany(ShipmentEvent::class)->orderBy('happened_at');
    }

    public function latestEvent()
    {
        return $this->hasOne(ShipmentEvent::class)->latestOfMany('happened_at');
    }

    // مساعد: تحديث الحالة الحالية مع ضبط تواريخ الشحن/التسليم إن لزم
    public function setCurrentStatus(ShipmentStatus|string $status): void
    {

        $status = $status instanceof ShipmentStatus ? $status->value : $status;
        $this->current_status = $status;

        if ($status === ShipmentStatus::IN_TRANSIT->value && is_null($this->shipped_at)) {
            $this->shipped_at = now();
        }

        if ($status === ShipmentStatus::DELIVERED->value && is_null($this->delivered_at)) {
            $this->delivered_at = now();
        }

        $this->save();
    }

    // سكوبات مفيدة للوحة التحكم
    public function scopeTracking($q, string $tracking)
    {
        return $q->where('tracking_number', $tracking);
    }

    public function scopeStatus($q, ShipmentStatus|string $status)
    {
        $status = $status instanceof ShipmentStatus ? $status->value : $status;
        return $q->where('current_status', $status);
    }

    public function scopeSearch($q, ?string $term)
    {
        if (!$term) return $q;
        return $q->where(function($qq) use ($term){
            $qq->where('tracking_number', 'like', "%{$term}%")
               ->orWhere('sender_name', 'like', "%{$term}%")
               ->orWhere('receiver_name', 'like', "%{$term}%");
        });
    }

    // عرض وسم لطيف للحالة الحالية (label) بدون تخزينه
    protected function currentStatusLabel(): Attribute
    {
        return Attribute::get(function(){
            if (!$this->current_status) return null;
            $case = ShipmentStatus::tryFrom($this->current_status);
            return $case?->label() ?? $this->current_status;
        });
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function destinationBranch()
    {
        return $this->belongsTo(Branch::class, 'destination_branch_id');
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            if (is_null($model->created_by) && Auth::check()) {
                $model->created_by = Auth::id();
            }
        });
    }

    public function canonicalTimeline2(): array
    {
        $order = ShipmentStatus::timeline(); // [enum cases بالترتيب]
        $orderedValues = array_map(fn($c) => $c->value, $order);

        // جميع الأحداث مرتبَة، ونأخذ أول ظهور لكل حالة
        $firstSeen = [];
        foreach ($this->events()->orderBy('happened_at')->get() as $ev) {
            if (!isset($firstSeen[$ev->status])) {
                $firstSeen[$ev->status] = $ev->happened_at;
            }
        }

        // حسم "الحالة الحالية"
        $current = $this->current_status;
        if (!$current) {
            // لو ما في current_status ناخذ آخر حدث أو null
            $last = $this->events()->latest('happened_at')->first();
            $current = $last?->status;
        }

        // موقع الحالة الحالية في الترتيب
        $currentIdx = is_string($current) ? array_search($current, $orderedValues, true) : false;

        // إبناء الخط الستّي
        $steps = [];
        foreach ($order as $i => $case) {
            $val = $case->value;
            $reachedAt = $firstSeen[$val] ?? null;

            $steps[] = [
                'status'      => $val,
                'label'       => $case->label(),
                'reached_at'  => $reachedAt,        // Carbon|null
                'is_current'  => ($currentIdx !== false && $i === $currentIdx),
                'is_reached'  => !is_null($reachedAt) && ($currentIdx === false ? true : $i <= $currentIdx),
                'is_future'   => ($currentIdx !== false && $i > $currentIdx),
            ];
        }

        return $steps;
    }



    public function canonicalTimeline(): array
{
    $order = \App\Enums\ShipmentStatus::timeline();
    $orderedValues = array_map(fn($c) => $c->value, $order);

    // أول ظهور لكل حالة حسب الزمن (أول مرة وصلت لها الشحنة)
    // ملاحظة: التحسين الأسرع بالأسفل باستخدام GROUP BY
    $firstSeen = [];
    foreach ($this->events()->orderBy('happened_at')->get(['status','happened_at']) as $ev) {
        if (!isset($firstSeen[$ev->status])) {
            $firstSeen[$ev->status] = $ev->happened_at; // Carbon instance
        }
    }

    // الحالة الحالية من الشحنة أو آخر حدث
    $current = $this->current_status ?: $this->events()->latest('happened_at')->value('status');
    $currentIdx = is_string($current) ? array_search($current, $orderedValues, true) : false;

    // بناء الخط الستّي بدون تكرار + تمرير التاريخ عند الوصول
    $steps = [];
    foreach ($order as $i => $case) {
        $val = $case->value;
        $reachedAt = $firstSeen[$val] ?? null;

        $steps[] = [
            'status'       => $val,
            'label'        => $case->label(),
            'reached'      => array_key_exists($val, $firstSeen),
            'is_current'   => ($currentIdx !== false && $i === $currentIdx),
            'is_future'    => ($currentIdx !== false && $i > $currentIdx),

            // جديد: تاريخ الوصول الخام
            'changed_at'   => $reachedAt, // Carbon|null

            // جديد: صيغة جاهزة للعرض (اختياري)
            'changed_at_fmt' => $reachedAt?->timezone(config('app.display_tz', config('app.timezone')))
                                          ?->format('Y-m-d H:i'),
            // أو لو تحب صيغة بشرية:
            'changed_at_human' => $reachedAt?->diffForHumans(),
        ];
    }

    return $steps;
}

}
