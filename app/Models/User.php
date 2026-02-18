<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Storage;
use Laravel\Cashier\Billable;
use App\Traits\BelongsToBranch;


class User extends Authenticatable 
{
    use HasFactory, Notifiable, HasRoles, BelongsToBranch;
    use Billable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'image',
        'is_active',
        'branch_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active'=> 'boolean',
        ];
    }

    public function scopeActive($q) { 
        return $q->where('is_active', true); 
    }

    public function scopeInactive($q) { 
        return $q->where('is_active', false); 
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->image && Storage::disk('public')->exists($this->image)) {
            return asset('storage/' . $this->image);
        }
        // صورة بديلة ثابتة من الأصول عندك
        return asset('assets/images/avatar.jpg');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function isBranchManager(): bool
    {
        return $this->hasRole('branch_manager');
    }
}
