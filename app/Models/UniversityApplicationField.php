<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UniversityApplicationField extends Model
{
    use HasFactory;

    protected $fillable = [
        'university_id',
        'field_name',
        'field_label',
        'field_description',
        'field_type',
        'field_options',
        'file_types',
        'max_file_size',
        'is_required',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'field_options' => 'array',
        'is_required' => 'boolean',
        'status' => 'boolean',
        'sort_order' => 'integer',
        'max_file_size' => 'integer',
    ];

    /**
     * Get the university that owns this field
     */
    public function university()
    {
        return $this->belongsTo(University::class);
    }

    /**
     * Get all responses for this field
     */
    public function responses()
    {
        return $this->hasMany(ApplicationFieldResponse::class);
    }

    /**
     * Scope to get active fields only
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope to order by sort_order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    /**
     * Check if field accepts file upload
     */
    public function isFileField()
    {
        return $this->field_type === 'file';
    }

    /**
     * Get allowed file extensions as array
     */
    public function getAllowedFileTypesArray()
    {
        if (empty($this->file_types)) {
            return [];
        }
        return explode(',', $this->file_types);
    }

    /**
     * Get validation rules for this field
     */
    public function getValidationRules()
    {
        $rules = [];

        if ($this->is_required) {
            $rules[] = 'required';
        } else {
            $rules[] = 'nullable';
        }

        switch ($this->field_type) {
            case 'text':
            case 'textarea':
                $rules[] = 'string';
                $rules[] = 'max:5000';
                break;
            case 'number':
                $rules[] = 'numeric';
                break;
            case 'date':
                $rules[] = 'date';
                break;
            case 'email':
                $rules[] = 'email';
                break;
            case 'url':
                $rules[] = 'url';
                break;
            case 'file':
                if (!$this->is_required) {
                    $rules[] = 'nullable';
                }
                $rules[] = 'file';
                if ($this->file_types) {
                    $rules[] = 'mimes:' . $this->file_types;
                }
                if ($this->max_file_size) {
                    $rules[] = 'max:' . $this->max_file_size;
                }
                break;
        }

        return implode('|', $rules);
    }
}
