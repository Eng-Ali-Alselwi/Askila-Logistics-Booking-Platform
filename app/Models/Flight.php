<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

use App\Traits\BelongsToBranch;

class Flight extends Model
{
    use HasFactory, BelongsToBranch;

    protected $fillable = [
        'flight_number',
        'trip_type',
        'vehicle_type',
        'operator_name',
        'airline',
        'aircraft_type',
        'departure_airport',
        'departure_terminal',
        'arrival_airport',
        'arrival_terminal',
        'departure_city',
        'arrival_city',
        'departure_time',
        'arrival_time',
        'duration_minutes',
        'base_price',
        'total_seats',
        'available_seats',
        'seat_classes',
        'pricing_tiers',
        'is_active',
        'notes',
        'branch_id'
    ];

    protected $casts = [
        'departure_time' => 'datetime',
        'arrival_time' => 'datetime',
        'seat_classes' => 'array',
        'pricing_tiers' => 'array',
        'is_active' => 'boolean',
        'base_price' => 'decimal:2',
    ];

    // العلاقات
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // سكوبات مفيدة
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeAvailable($query)
    {
        return $query->where('available_seats', '>', 0);
    }

    public function scopeByRoute($query, $departure, $arrival)
    {
        return $query->where('departure_city', $departure)
                    ->where('arrival_city', $arrival);
    }

    public function scopeByDate($query, $date)
    {
        return $query->whereDate('departure_time', $date);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('departure_time', '>', now());
    }

    // سكوب للبحث
    public function scopeSearch($query, $term)
    {
        if (!$term) return $query;
        return $query->where(function($q) use ($term) {
            $q->where('flight_number', 'like', '%' . $term . '%')
              ->orWhere('airline', 'like', '%' . $term . '%')
              ->orWhere('operator_name', 'like', '%' . $term . '%')
              ->orWhere('vehicle_type', 'like', '%' . $term . '%')
              ->orWhere('departure_city', 'like', '%' . $term . '%')
              ->orWhere('arrival_city', 'like', '%' . $term . '%')
              ->orWhere('trip_type', 'like', '%' . $term . '%');
        });
    }

    // مساعدات
    public function getDurationFormattedAttribute()
    {
        $hours = floor($this->duration_minutes / 60);
        $minutes = $this->duration_minutes % 60;
        
        if ($hours > 0) {
            return $hours . 'س ' . $minutes . 'د';
        }
        return $minutes . 'د';
    }

    public function getPriceForClass($class)
    {
        $pricing = $this->pricing_tiers ?? [];
        return $pricing[$class] ?? $this->base_price;
    }

    public function isAvailable()
    {
        return $this->is_active && $this->available_seats > 0;
    }

    public function canBook($seats = 1)
    {
        return $this->isAvailable() && $this->available_seats >= $seats;
    }

    public function updateAvailableSeats($change)
    {
        $this->available_seats = max(0, $this->available_seats + $change);
        $this->save();
    }

    public function decrementAvailableSeats($count)
    {
        $this->available_seats = max(0, $this->available_seats - $count);
        $this->save();
    }

    // مساعدات لنوع الرحلة
    public function isAirTrip()
    {
        return $this->trip_type === 'air';
    }

    public function isLandTrip()
    {
        return $this->trip_type === 'land';
    }

    public function isSeaTrip()
    {
        return $this->trip_type === 'sea';
    }

    public function getTripTypeLabelAttribute()
    {
        return match($this->trip_type) {
            'air' => 'رحلة جوية',
            'land' => 'رحلة برية',
            'sea' => 'رحلة بحرية',
            default => 'غير محدد'
        };
    }

    public function getVehicleTypeLabelAttribute()
    {
        if ($this->isAirTrip()) {
            return $this->aircraft_type ?: $this->vehicle_type ?: 'طائرة';
        } elseif ($this->isLandTrip()) {
            return $this->vehicle_type ?: 'حافلة';
        } elseif ($this->isSeaTrip()) {
            return $this->vehicle_type ?: 'سفينة';
        }
        return $this->vehicle_type ?: 'مركبة';
    }

    public function getOperatorNameAttribute($value)
    {
        return $value ?: $this->airline;
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            if (is_null($model->flight_number)) {
                $model->flight_number = 'FL' . strtoupper(substr(uniqid(), -6));
            }
        });
    }
}