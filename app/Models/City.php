<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'state_province',
        'country',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    /**
     * Get the users who want to study in this city.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_cities');
    }

    /**
     * Get the universities in this city.
     */
    public function universities()
    {
        return $this->hasMany(University::class);
    }
}

