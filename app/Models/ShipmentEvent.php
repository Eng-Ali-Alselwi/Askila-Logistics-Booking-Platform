<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Casts\Attribute;


class ShipmentEvent extends Model
{
    use  HasFactory, HasUlids;

    protected $guarded = [];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'happened_at' => 'datetime',
        ];
    }

    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }

    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function scopeRecent($q)
    {
        return $q->orderByDesc('happened_at');
    }

    /**
     * Get the status label for this event
     */
    protected function statusLabel(): Attribute
    {
        return Attribute::get(function(){
            if (!$this->status) return null;
            $case = \App\Enums\ShipmentStatus::tryFrom($this->status);
            return $case?->label() ?? $this->status;
        });
    }

}
