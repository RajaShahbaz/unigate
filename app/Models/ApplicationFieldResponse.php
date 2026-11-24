<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ApplicationFieldResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'university_application_field_id',
        'response_value',
        'file_path',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::deleting(function ($response) {
            // Delete file if exists
            if ($response->file_path && Storage::disk('public')->exists($response->file_path)) {
                Storage::disk('public')->delete($response->file_path);
            }
        });
    }

    /**
     * Get the application this response belongs to
     */
    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    /**
     * Get the field definition for this response
     */
    public function field()
    {
        return $this->belongsTo(UniversityApplicationField::class, 'university_application_field_id');
    }

    /**
     * Get the response value or file URL
     */
    public function getDisplayValueAttribute()
    {
        if ($this->file_path) {
            return Storage::disk('public')->url($this->file_path);
        }
        return $this->response_value;
    }
}
