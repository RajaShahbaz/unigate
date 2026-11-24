<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StudentDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DocumentController extends Controller
{
    /**
     * Get all documents for the authenticated user
     */
    public function index(Request $request)
    {
        try {
            $user = Auth::user();
            $query = StudentDocument::byUser($user->id);

            // Search functionality
            if ($request->has('search')) {
                $query->search($request->search);
            }

            // Sort by
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            
            $allowedSortFields = ['document_name', 'created_at', 'updated_at', 'file_size'];
            if (in_array($sortBy, $allowedSortFields)) {
                $query->orderBy($sortBy, $sortOrder);
            } else {
                $query->orderBy('created_at', 'desc');
            }

            $perPage = $request->get('per_page', 15);
            $documents = $query->paginate($perPage);

            return response()->json([
                'error' => false,
                'message' => 'Documents retrieved successfully',
                'data' => $documents
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to retrieve documents',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get single document details
     */
    public function show($id)
    {
        try {
            $user = Auth::user();
            $document = StudentDocument::byUser($user->id)->findOrFail($id);

            return response()->json([
                'error' => false,
                'message' => 'Document retrieved successfully',
                'data' => $document
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to retrieve document',
                'data' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Upload a new document
     */
    public function store(Request $request)
    {
        try {
            $user = Auth::user();

            $validator = Validator::make($request->all(), [
                'document_name' => 'required|string|max:255',
                'notes' => 'nullable|string|max:5000',
                'file' => 'required|file|max:10240', // Max 10MB
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'message' => 'Validation error',
                    'data' => $validator->errors()
                ], 422);
            }

            if (!$request->hasFile('file')) {
                return response()->json([
                    'error' => true,
                    'message' => 'No file uploaded',
                    'data' => null
                ], 400);
            }

            $file = $request->file('file');

            // Store file in public disk under student_documents folder
            $filePath = $file->store('student_documents', 'public');

            $document = StudentDocument::create([
                'user_id' => $user->id,
                'document_name' => $request->document_name,
                'notes' => $request->notes,
                'file_path' => $filePath,
                'original_filename' => $file->getClientOriginalName(),
                'file_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
            ]);

            // Send notification for document upload
            if (!function_exists('send_notification')) {
                require_once app_path('Helpers/notification_helper.php');
            }

            $title = 'Document Uploaded';
            $body = 'Your document "' . $request->document_name . '" has been uploaded successfully.';
            $type = 'document_upload';
            
            $customData = [
                'type' => 'document_upload',
                'document_id' => (string) $document->id,
                'document_name' => $request->document_name,
            ];

            send_notification([$user->id], $title, $body, $type, $customData);

            return response()->json([
                'error' => false,
                'message' => 'Document uploaded successfully',
                'data' => $document
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to upload document',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update document details (name and notes)
     */
    public function update(Request $request, $id)
    {
        try {
            $user = Auth::user();
            $document = StudentDocument::byUser($user->id)->findOrFail($id);

            $validator = Validator::make($request->all(), [
                'document_name' => 'sometimes|required|string|max:255',
                'notes' => 'nullable|string|max:5000',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'message' => 'Validation error',
                    'data' => $validator->errors()
                ], 422);
            }

            $document->update($validator->validated());

            return response()->json([
                'error' => false,
                'message' => 'Document updated successfully',
                'data' => $document
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to update document',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Replace document file
     */
    public function replaceFile(Request $request, $id)
    {
        try {
            $user = Auth::user();
            $document = StudentDocument::byUser($user->id)->findOrFail($id);

            $validator = Validator::make($request->all(), [
                'file' => 'required|file|max:10240', // Max 10MB
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'message' => 'Validation error',
                    'data' => $validator->errors()
                ], 422);
            }

            if (!$request->hasFile('file')) {
                return response()->json([
                    'error' => true,
                    'message' => 'No file uploaded',
                    'data' => null
                ], 400);
            }

            $file = $request->file('file');

            // Delete old file
            if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
                Storage::disk('public')->delete($document->file_path);
            }

            // Store new file
            $filePath = $file->store('student_documents', 'public');

            $document->update([
                'file_path' => $filePath,
                'original_filename' => $file->getClientOriginalName(),
                'file_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
            ]);

            return response()->json([
                'error' => false,
                'message' => 'Document file replaced successfully',
                'data' => $document
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to replace document file',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete document
     */
    public function destroy($id)
    {
        try {
            $user = Auth::user();
            $document = StudentDocument::byUser($user->id)->findOrFail($id);

            // Delete file from storage
            if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
                Storage::disk('public')->delete($document->file_path);
            }

            $document->delete();

            return response()->json([
                'error' => false,
                'message' => 'Document deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to delete document',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download document
     */
    public function download($id)
    {
        try {
            $user = Auth::user();
            $document = StudentDocument::byUser($user->id)->findOrFail($id);

            if (!$document->file_path || !Storage::disk('public')->exists($document->file_path)) {
                return response()->json([
                    'error' => true,
                    'message' => 'File not found',
                    'data' => null
                ], 404);
            }

            return Storage::disk('public')->download($document->file_path, $document->original_filename);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to download document',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get document statistics for the authenticated user
     */
    public function statistics()
    {
        try {
            $user = Auth::user();
            
            $totalDocuments = StudentDocument::byUser($user->id)->count();
            $totalSize = StudentDocument::byUser($user->id)->sum('file_size');
            
            // Get document counts by type
            $byType = StudentDocument::byUser($user->id)
                ->selectRaw("
                    CASE 
                        WHEN file_type LIKE 'image/%' THEN 'Images'
                        WHEN file_type = 'application/pdf' THEN 'PDF Documents'
                        WHEN file_type LIKE 'application/vnd.openxmlformats-officedocument%' OR file_type LIKE 'application/msword%' THEN 'Office Documents'
                        ELSE 'Other Files'
                    END as type_group,
                    COUNT(*) as count
                ")
                ->groupBy('type_group')
                ->get();

            // Format total size
            $totalSizeFormatted = $this->formatBytes($totalSize);

            // Recent uploads (last 30 days)
            $recentUploads = StudentDocument::byUser($user->id)
                ->where('created_at', '>=', now()->subDays(30))
                ->count();

            return response()->json([
                'error' => false,
                'message' => 'Statistics retrieved successfully',
                'data' => [
                    'total_documents' => $totalDocuments,
                    'total_size' => $totalSize,
                    'total_size_formatted' => $totalSizeFormatted,
                    'recent_uploads' => $recentUploads,
                    'by_type' => $byType
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to retrieve statistics',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Format bytes to human readable format
     */
    private function formatBytes($bytes)
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }
}
