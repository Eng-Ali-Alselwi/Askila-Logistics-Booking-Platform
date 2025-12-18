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


class User extends Authenticatable 
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;
    use Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'image',
        'is_active',
        'branch_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active'=> 'boolean',
        ];
    }

    public function scopeActive($q)   { return $q->where('is_active', true); }
    public function scopeInactive($q) { return $q->where('is_active', false); }
    public function getImageUrlAttribute(): string
    {
        if ($this->image && Storage::disk('public')->exists($this->image)) {
            return asset('storage/' . $this->image);
        }
        // صورة بديلة ثابتة من الأصول عندك
        return asset('assets/images/avatar.jpg');
    }
    // public function getAvatarUrlAttribute(): string
    // {
    //     if ($this->image) {
    //         return asset('storage/avatars/'.$this->image);
    //     }
    //     return asset('assets/images/avatar.jpg'); // ضع صورة افتراضية عندك
    // }
    // public function role(): BelongsTo
    // {
    //     return $this->belongsTo(Role::class);
    // }

    // تحقق من التفعيل
    // public function isActivated(): bool
    // {
    //     return $this->is_active;
    // }

    // public function isSuperAdmin(): bool
    // {
    //     return $this->hasRole('super_admin');
    // }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function isBranchManager(): bool
    {
        return $this->hasRole('branch_manager');
    }
}
