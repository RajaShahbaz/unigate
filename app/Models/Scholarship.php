<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Scholarship extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'university_id',
        'name',
        'scholarship_type',
        'description',
        'eligibility_criteria',
        'amount',
        'percentage',
        'amount_type',
        'total_slots',
        'available_slots',
        'application_start_date',
        'application_end_date',
        'required_documents',
        'application_process',
        'contact_email',
        'contact_phone',
        'renewable',
        'renewable_years',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
        'renewable' => 'boolean',
        'amount' => 'decimal:2',
        'percentage' => 'decimal:2',
        'total_slots' => 'integer',
        'available_slots' => 'integer',
        'renewable_years' => 'integer',
        'application_start_date' => 'date',
        'application_end_date' => 'date',
    ];

    protected $appends = ['is_active', 'is_accepting_applications'];

    /**
     * Get the university that offers the scholarship.
     */
    public function university()
    {
        return $this->belongsTo(University::class);
    }

    /**
     * Check if scholarship is currently active
     */
    public function getIsActiveAttribute()
    {
        return $this->status && 
               (!$this->application_end_date || $this->application_end_date >= Carbon::now());
    }

    /**
     * Check if scholarship is currently accepting applications
     */
    public function getIsAcceptingApplicationsAttribute()
    {
        $now = Carbon::now();
        return $this->status &&
               (!$this->application_start_date || $this->application_start_date <= $now) &&
               (!$this->application_end_date || $this->application_end_date >= $now) &&
               (!$this->available_slots || $this->available_slots > 0);
    }

    /**
     * Scope to get only active scholarships
     */
    public function scopeActive($query)
    {
        return $query->where('status', true)
                     ->where(function($q) {
                         $q->whereNull('application_end_date')
                           ->orWhere('application_end_date', '>=', Carbon::now());
                     });
    }

    /**
     * Scope to get scholarships accepting applications
     */
    public function scopeAcceptingApplications($query)
    {
        $now = Carbon::now();
        return $query->where('status', true)
                     ->where(function($q) use ($now) {
                         $q->whereNull('application_start_date')
                           ->orWhere('application_start_date', '<=', $now);
                     })
                     ->where(function($q) use ($now) {
                         $q->whereNull('application_end_date')
                           ->orWhere('application_end_date', '>=', $now);
                     })
                     ->where(function($q) {
                         $q->whereNull('available_slots')
                           ->orWhere('available_slots', '>', 0);
                     });
    }

    /**
     * Scope to filter by scholarship type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('scholarship_type', $type);
    }
}

