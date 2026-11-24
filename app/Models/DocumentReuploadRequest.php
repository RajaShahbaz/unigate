<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class DocumentReuploadRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'field_name',
        'field_label',
        'field_source',
        'custom_field_id',
        'step_number',
        'highlight_field',
        'previous_status',
        'reason',
        'status',
        'requested_by',
        'resolved_by',
        'resolved_at',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    /**
     * Application relationship.
     */
    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    /**
     * University admin who raised the request.
     */
    public function requester()
    {
        return $this->belongsTo(UniversityAdmin::class, 'requested_by');
    }

    /**
     * University admin who resolved the request.
     */
    public function resolver()
    {
        return $this->belongsTo(UniversityAdmin::class, 'resolved_by');
    }

    /**
     * Scope pending requests.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Resolve all pending requests for a given application field.
     */
    public static function resolveForField(Application $application, string $fieldName): void
    {
        $pendingRequests = self::where('application_id', $application->id)
            ->where('field_name', $fieldName)
            ->where('status', 'pending')
            ->get();

        if ($pendingRequests->isEmpty()) {
            return;
        }

        $now = Carbon::now();
        foreach ($pendingRequests as $request) {
            $request->update([
                'status' => 'resolved',
                'resolved_at' => $now,
                'resolved_by' => null,
            ]);
        }

        $hasOtherPending = self::where('application_id', $application->id)
            ->where('status', 'pending')
            ->exists();

        if (!$hasOtherPending) {
            $fallbackStatus = $application->status_before_documents_pending ?? 'under_review';
            $application->update([
                'status' => $fallbackStatus,
                'status_before_documents_pending' => null,
            ]);
        }
    }
}

