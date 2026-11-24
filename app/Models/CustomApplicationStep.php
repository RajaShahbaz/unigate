<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomApplicationStep extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'university_id',
        'step_number',
        'step_title',
        'step_description',
        'step_icon',
        'display_order',
        'is_required',
        'status',
    ];

    protected $casts = [
        'step_number' => 'integer',
        'display_order' => 'integer',
        'is_required' => 'boolean',
        'status' => 'boolean',
    ];

    /**
     * Get the university that owns this step
     */
    public function university()
    {
        return $this->belongsTo(University::class);
    }

    /**
     * Get all fields for this step
     */
    public function fields()
    {
        return $this->hasMany(CustomApplicationStepField::class)->orderBy('display_order', 'asc');
    }

    /**
     * Get active fields only
     */
    public function activeFields()
    {
        return $this->hasMany(CustomApplicationStepField::class)
            ->where('status', true)
            ->orderBy('display_order', 'asc');
    }

    /**
     * Scope to get active steps
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope to order by display order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order', 'asc')->orderBy('step_number', 'asc');
    }
}

