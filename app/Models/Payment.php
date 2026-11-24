<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'payer_id',
        'school_id',
        'package_id',
        'application_package_id',
        'bill_id',
        'trx_id',
        'date',
        'paid_amount',
        'tax_paid',
        'payment_method',
        'transaction_date',
        'transaction_expiry_date',
        'transaction_status',
        'currency',
        'bill_reference',
        'retreival_reference_no',
        'mobile_no',
        'CNIC',
        'message',
        'discounted_amount',
        'promo_code',
        'discount_percentage',
        'discount_amount',
        'profit_percentage',
        'profit_amount',
    ];

    protected $casts = [
        'date' => 'date',
        'paid_amount' => 'decimal:2',
        'tax_paid' => 'decimal:2',
        'discounted_amount' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'profit_percentage' => 'decimal:2',
        'profit_amount' => 'decimal:2',
        'transaction_date' => 'string',
        'transaction_expiry_date' => 'string',
    ];

    /**
     * Get the user who made the payment
     */
    public function payer()
    {
        return $this->belongsTo(User::class, 'payer_id');
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get the package associated with the payment
     */
    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }

    /**
     * Get the application package associated with the payment
     */
    public function applicationPackage()
    {
        return $this->belongsTo(ApplicationPackage::class, 'application_package_id');
    }

    /**
     * Check if payment is successful
     */
    public function isSuccessful()
    {
        return $this->transaction_status == '999' || $this->transaction_status == '000';
    }

    /**
     * Check if payment is pending
     */
    public function isPending()
    {
        return !$this->isSuccessful() && !$this->isFailed();
    }

    /**
     * Check if payment failed
     */
    public function isFailed()
    {
        return !in_array($this->transaction_status, ['999', '000']);
    }
}
