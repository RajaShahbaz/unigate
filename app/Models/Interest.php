<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Interest extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($interest) {
            if (empty($interest->slug)) {
                $interest->slug = Str::slug($interest->name);
            }
        });
    }

    /**
     * Get the users interested in this field.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_interests');
    }

    /**
     * Get the universities offering this program.
     */
    public function universities()
    {
        return $this->belongsToMany(University::class, 'university_interests')
            ->withPivot('fee', 'fee_min', 'fee_max', 'duration_years', 'program_details')
            ->withTimestamps();
    }
}

