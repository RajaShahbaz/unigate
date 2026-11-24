<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Application extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'university_id',
        'interest_id',
        'application_number',
        'first_name',
        'last_name',
        'email',
        'phone',
        'date_of_birth',
        'gender',
        'current_address',
        'permanent_address',
        'nationality',
        'previous_school',
        'previous_degree',
        'previous_gpa',
        'graduation_year',
        'transcript_file',
        'id_document_file',
        'photo_file',
        'recommendation_letter_file',
        'status',
        'status_before_documents_pending',
        'application_message',
        'program_preference_reason',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relation',
        'admin_notes',
        'rejection_reason',
        'submitted_at',
        'reviewed_at',
        'reviewed_by',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'previous_gpa' => 'decimal:2',
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($application) {
            if (empty($application->application_number)) {
                $application->application_number = self::generateApplicationNumber();
            }
        });

        static::deleting(function ($application) {
            if ($application->isForceDeleting()) {
                // Delete all uploaded files
                $fileFields = [
                    'transcript_file',
                    'id_document_file',
                    'photo_file',
                    'recommendation_letter_file'
                ];

                foreach ($fileFields as $field) {
                    if ($application->$field && Storage::disk('public')->exists($application->$field)) {
                        Storage::disk('public')->delete($application->$field);
                    }
                }

                // Delete custom field response files
                foreach ($application->fieldResponses as $response) {
                    if ($response->file_path && Storage::disk('public')->exists($response->file_path)) {
                        Storage::disk('public')->delete($response->file_path);
                    }
                }
            }
        });
    }

    /**
     * Generate unique application number
     */
    public static function generateApplicationNumber()
    {
        $year = date('Y');
        $lastApplication = self::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();

        if ($lastApplication) {
            $lastNumber = (int) substr($lastApplication->application_number, -6);
            $newNumber = str_pad($lastNumber + 1, 6, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '000001';
        }

        return 'APP' . $year . $newNumber;
    }

    /**
     * Get the user who submitted the application
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the university for this application
     */
    public function university()
    {
        return $this->belongsTo(University::class);
    }

    /**
     * Get the interest/program for this application
     */
    public function interest()
    {
        return $this->belongsTo(Interest::class);
    }

    /**
     * Get the user who reviewed this application
     */
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Get all custom field responses for this application
     */
    public function fieldResponses()
    {
        return $this->hasMany(ApplicationFieldResponse::class);
    }

    /**
     * Get document re-upload requests.
     */
    public function documentReuploadRequests()
    {
        return $this->hasMany(DocumentReuploadRequest::class);
    }

    /**
     * Scope to filter by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to filter by university
     */
    public function scopeByUniversity($query, $universityId)
    {
        return $query->where('university_id', $universityId);
    }

    /**
     * Scope to filter submitted applications
     */
    public function scopeSubmitted($query)
    {
        return $query->whereNotNull('submitted_at');
    }

    /**
     * Check if application is editable
     */
    public function isEditable()
    {
        return in_array($this->status, ['draft']);
    }

    /**
     * Check if application can be withdrawn
     */
    public function canBeWithdrawn()
    {
        return in_array($this->status, ['submitted', 'under_review', 'shortlisted']);
    }

    /**
     * Get full name
     */
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
