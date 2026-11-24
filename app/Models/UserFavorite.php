<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserFavorite extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'university_id',
    ];

    /**
     * Get the user who favorited.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the favorited university.
     */
    public function university()
    {
        return $this->belongsTo(University::class);
    }
}

