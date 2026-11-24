<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class UniversityAdmin extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $guard = 'university';

    protected $fillable = [
        'university_id',
        'name',
        'email',
        'password',
        'phone',
        'position',
        'profile_image',
        'status',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'status' => 'boolean',
        'last_login_at' => 'datetime',
        'email_verified_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::deleting(function ($admin) {
            if ($admin->isForceDeleting() && $admin->getRawOriginal('profile_image') && Storage::disk('public')->exists($admin->getRawOriginal('profile_image'))) {
                Storage::disk('public')->delete($admin->getRawOriginal('profile_image'));
            }
        });
    }

    /**
     * Get the university this admin belongs to
     */
    public function university()
    {
        return $this->belongsTo(University::class);
    }

    /**
     * Get profile image URL
     */
    public function getProfileImageAttribute($value)
    {
        if ($value && Storage::disk('public')->exists($value)) {
            return url(Storage::url($value));
        }
        return asset('assets/dummy_logo.jpg');
    }

    /**
     * Check if admin is active
     */
    public function isActive()
    {
        return $this->status === true;
    }
}

