<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserApplicationPackage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'package_id',
        'application_id',
        'amount_paid',
        'currency',
        'payment_status',
        'payment_method',
        'transaction_id',
        'purchased_at',
    ];

    protected $casts = [
        'amount_paid' => 'decimal:2',
        'purchased_at' => 'datetime',
    ];

    /**
     * Get the user who purchased this package
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the package
     */
    public function package()
    {
        return $this->belongsTo(ApplicationPackage::class, 'package_id');
    }

    /**
     * Get the application this purchase is for
     */
    public function application()
    {
        return $this->belongsTo(Application::class);
    }
}





