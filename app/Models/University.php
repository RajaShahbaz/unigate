<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class University extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'city_id',
        'address',
        'phone',
        'email',
        'website',
        'logo',
        'cover_image',
        'type',
        'established_year',
        'facilities',
        'ranking',
        'tuition_fee_range_min',
        'tuition_fee_range_max',
        'hostel_available',
        'scholarship_available',
        'admission_criteria',
        'accreditation',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
        'hostel_available' => 'boolean',
        'scholarship_available' => 'boolean',
        'ranking' => 'decimal:2',
        'tuition_fee_range_min' => 'decimal:2',
        'tuition_fee_range_max' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($university) {
            if (empty($university->slug)) {
                $university->slug = Str::slug($university->name);
            }
        });
    }

    /**
     * Get the city where the university is located.
     */
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Get the programs/interests offered by the university.
     */
    public function interests()
    {
        return $this->belongsToMany(Interest::class, 'university_interests')
            ->withPivot('fee', 'fee_min', 'fee_max', 'duration_years', 'program_details')
            ->withTimestamps();
    }

    /**
     * Get users who favorited this university.
     */
    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'user_favorites');
    }

    /**
     * Get the gallery items for this university.
     */
    public function galleries()
    {
        return $this->hasMany(UniversityGallery::class);
    }

    /**
     * Get active gallery items for this university.
     */
    public function activeGalleries()
    {
        return $this->hasMany(UniversityGallery::class)->active()->ordered();
    }

    /**
     * Get the scholarships offered by this university.
     */
    public function scholarships()
    {
        return $this->hasMany(Scholarship::class);
    }

    /**
     * Get active scholarships for this university.
     */
    public function activeScholarships()
    {
        return $this->hasMany(Scholarship::class)->active();
    }

    /**
     * Get admission requirements for this university.
     */
    public function admissionRequirements()
    {
        return $this->hasMany(AdmissionRequirement::class);
    }

    /**
     * Get active admission requirements for this university.
     */
    public function activeAdmissionRequirements()
    {
        return $this->hasMany(AdmissionRequirement::class)->active();
    }

    /**
     * Get custom application steps for this university.
     */
    public function customApplicationSteps()
    {
        return $this->hasMany(CustomApplicationStep::class);
    }

    /**
     * Get active custom application steps for this university.
     */
    public function activeCustomApplicationSteps()
    {
        return $this->hasMany(CustomApplicationStep::class)->active()->ordered();
    }

    /**
     * Get applications submitted to the university.
     */
    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    /**
     * Get university admins for this university.
     */
    public function universityAdmins()
    {
        return $this->hasMany(UniversityAdmin::class);
    }
}

