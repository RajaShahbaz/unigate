<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomApplicationStepField extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'custom_application_step_id',
        'field_name',
        'field_type',
        'field_label',
        'field_placeholder',
        'field_options',
        'is_required',
        'validation_rules',
        'field_icon',
        'helper_text',
        'min_value',
        'max_value',
        'accept_types',
        'max_file_size',
        'display_order',
        'status',
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'status' => 'boolean',
        'display_order' => 'integer',
        'min_value' => 'integer',
        'max_value' => 'integer',
        'max_file_size' => 'integer',
    ];

    protected $appends = ['options_array'];

    /**
     * Get the step that owns this field
     */
    public function step()
    {
        return $this->belongsTo(CustomApplicationStep::class, 'custom_application_step_id');
    }

    /**
     * Get field options as array
     */
    public function getOptionsArrayAttribute()
    {
        if ($this->field_options) {
            return json_decode($this->field_options, true);
        }
        return null;
    }

    /**
     * Set field options from array
     */
    public function setFieldOptionsAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['field_options'] = json_encode($value);
        } else {
            $this->attributes['field_options'] = $value;
        }
    }

    /**
     * Scope to get active fields
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
        return $query->orderBy('display_order', 'asc');
    }
}

