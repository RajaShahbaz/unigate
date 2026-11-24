<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdmissionRequirement extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'university_id',
        'interest_id',
        'program_level',
        'general_requirements',
        'academic_requirements',
        'minimum_percentage',
        'minimum_gpa',
        'entrance_tests',
        'minimum_test_score',
        'required_documents',
        'language_requirements',
        'language_test_score',
        'specific_requirements',
        'application_deadline',
        'application_fee',
        'admission_process',
        'estimated_processing_days',
        'interview_required',
        'portfolio_required',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
        'minimum_percentage' => 'decimal:2',
        'minimum_gpa' => 'decimal:2',
        'language_test_score' => 'decimal:2',
        'application_fee' => 'decimal:2',
        'minimum_test_score' => 'integer',
        'estimated_processing_days' => 'integer',
        'application_deadline' => 'date',
    ];

    /**
     * Get the university that owns the admission requirement.
     */
    public function university()
    {
        return $this->belongsTo(University::class);
    }

    /**
     * Get the program/interest this requirement is for.
     */
    public function interest()
    {
        return $this->belongsTo(Interest::class);
    }

    /**
     * Scope to get only active requirements
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope to filter by program level
     */
    public function scopeForLevel($query, $level)
    {
        return $query->where('program_level', $level);
    }

    /**
     * Scope to filter by program/interest
     */
    public function scopeForProgram($query, $interestId)
    {
        return $query->where('interest_id', $interestId);
    }

    /**
     * Scope to get general requirements (not program-specific)
     */
    public function scopeGeneral($query)
    {
        return $query->whereNull('interest_id');
    }
}

