<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\Storage as FacadesStorage;
use Storage;

class Notification extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'message',
        'image',
        'send_to', // 'all', 'specific', 'role_based'
        'session_year_id',
        'school_id',
        'data', // Adding this for FCM data payload
        'type',
        'is_read',
        'sent_at',
        'notifiable_id',
        'notifiable_type',
        'user_id',
        'redirect_url',
    ];

     protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'sent_at' => 'datetime'
    ];

    public function notifiable()
    {
        return $this->morphTo();
    }

    // Relationship with session year
    public function sessionYear()
    {
        return $this->belongsTo(SessionYear::class);
    }

    // Relationship with user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeOwner()
    {
        if (FacadesAuth::user()) {
            if (FacadesAuth::user()->school_id) {
                return $this->where('school_id',FacadesAuth::user()->school_id);
            }
            return $this;
        }
        return $this;
    }

    public function getImageAttribute($value)
    {
        if ($value) {
            return url(FacadesStorage::url($value));
        }
        return null;
    }
}
