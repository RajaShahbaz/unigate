<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserEducation extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'user_education';

    protected $fillable = [
        'user_id',
        'education_level',
        'institution_name',
        'board_university',
        'field_of_study',
        'start_year',
        'end_year',
        'percentage_cgpa',
        'grade_type',
        'certificate_document',
        'additional_info',
        'is_current',
    ];

    protected $casts = [
        'is_current' => 'boolean',
        'percentage_cgpa' => 'decimal:2',
    ];

    /**
     * Get the user that owns the education record.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

