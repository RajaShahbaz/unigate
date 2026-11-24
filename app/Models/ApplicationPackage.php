<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApplicationPackage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'price',
        'currency',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'status' => 'boolean',
    ];

    /**
     * Get user purchases for this package
     */
    public function userPurchases()
    {
        return $this->hasMany(UserApplicationPackage::class, 'package_id');
    }

    /**
     * Check if a user has purchased this package
     */
    public function isPurchasedByUser($userId)
    {
        return $this->userPurchases()
            ->where('user_id', $userId)
            ->where('payment_status', 'completed')
            ->exists();
    }
}





