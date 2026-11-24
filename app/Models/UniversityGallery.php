<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class UniversityGallery extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'university_id',
        'title',
        'description',
        'image_path',
        'image_type',
        'category',
        'display_order',
        'is_featured',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
        'is_featured' => 'boolean',
        'display_order' => 'integer',
    ];

    protected $appends = ['image_url'];

    protected static function boot()
    {
        parent::boot();
        
        static::deleting(function ($gallery) {
            // Delete the image file when gallery item is deleted
            if ($gallery->isForceDeleting() && $gallery->getRawOriginal('image_path') && Storage::disk('public')->exists($gallery->getRawOriginal('image_path'))) {
                Storage::disk('public')->delete($gallery->getRawOriginal('image_path'));
            }
        });
    }

    /**
     * Get the university that owns the gallery.
     */
    public function university()
    {
        return $this->belongsTo(University::class);
    }

    /**
     * Get the full image URL
     */
    public function getImageUrlAttribute()
    {
        if ($this->image_path) {
            return url(Storage::url($this->image_path));
        }
        return null;
    }

    /**
     * Scope to get only active galleries
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope to get featured galleries
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope to order by display order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order', 'asc')->orderBy('created_at', 'desc');
    }
}

