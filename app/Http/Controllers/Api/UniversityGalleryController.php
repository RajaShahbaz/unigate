<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\UniversityGallery\UniversityGalleryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Throwable;

class UniversityGalleryController extends Controller
{
    private UniversityGalleryInterface $galleryRepository;

    public function __construct(UniversityGalleryInterface $galleryRepository)
    {
        $this->galleryRepository = $galleryRepository;
    }

    /**
     * Get all galleries for a specific university
     */
    public function index(Request $request)
    {
        try {
            $query = $this->galleryRepository->builder()->with('university');

            // Filter by university
            if ($request->has('university_id')) {
                $query->where('university_id', $request->university_id);
            }

            // Filter by category
            if ($request->has('category')) {
                $query->where('category', $request->category);
            }

            // Filter by image type
            if ($request->has('image_type')) {
                $query->where('image_type', $request->image_type);
            }

            // Filter featured only
            if ($request->has('featured') && $request->featured) {
                $query->featured();
            }

            // Only active galleries
            if (!$request->has('include_inactive')) {
                $query->active();
            }

            // Order by display order
            $query->ordered();

            // Pagination
            $perPage = $request->get('per_page', 15);
            $galleries = $query->paginate($perPage);

            return response()->json([
                'error' => false,
                'message' => 'Gallery retrieved successfully',
                'data' => $galleries
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to retrieve gallery',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a single gallery item
     */
    public function show($id)
    {
        try {
            $gallery = $this->galleryRepository->findById($id, ['*'], ['university']);

            return response()->json([
                'error' => false,
                'message' => 'Gallery item retrieved successfully',
                'data' => $gallery
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'error' => true,
                'message' => 'Gallery item not found',
                'data' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Create a new gallery item
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'university_id' => 'required|exists:universities,id',
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'image_path' => 'required|file|mimes:jpeg,png,jpg,gif,mp4,webp|max:10240',
                'image_type' => 'nullable|in:image,video,virtual_tour',
                'category' => 'nullable|string|max:100',
                'display_order' => 'nullable|integer',
                'is_featured' => 'nullable|boolean',
                'status' => 'nullable|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'message' => 'Validation failed',
                    'data' => $validator->errors()
                ], 422);
            }

            $gallery = $this->galleryRepository->create($request->all());

            return response()->json([
                'error' => false,
                'message' => 'Gallery item created successfully',
                'data' => $gallery
            ], 201);
        } catch (Throwable $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to create gallery item',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a gallery item
     */
    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'university_id' => 'sometimes|required|exists:universities,id',
                'title' => 'sometimes|required|string|max:255',
                'description' => 'nullable|string',
                'image_path' => 'nullable|file|mimes:jpeg,png,jpg,gif,mp4,webp|max:10240',
                'image_type' => 'nullable|in:image,video,virtual_tour',
                'category' => 'nullable|string|max:100',
                'display_order' => 'nullable|integer',
                'is_featured' => 'nullable|boolean',
                'status' => 'nullable|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'message' => 'Validation failed',
                    'data' => $validator->errors()
                ], 422);
            }

            $gallery = $this->galleryRepository->update($id, $request->all());

            return response()->json([
                'error' => false,
                'message' => 'Gallery item updated successfully',
                'data' => $gallery
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to update gallery item',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a gallery item
     */
    public function destroy($id)
    {
        try {
            $this->galleryRepository->deleteById($id);

            return response()->json([
                'error' => false,
                'message' => 'Gallery item deleted successfully',
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to delete gallery item',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get gallery by university ID (convenience method)
     */
    public function getByUniversity($universityId, Request $request)
    {
        try {
            $query = $this->galleryRepository->builder()
                ->where('university_id', $universityId)
                ->active()
                ->ordered();

            // Filter by category
            if ($request->has('category')) {
                $query->where('category', $request->category);
            }

            // Filter featured only
            if ($request->has('featured') && $request->featured) {
                $query->featured();
            }

            $perPage = $request->get('per_page', 20);
            $galleries = $query->paginate($perPage);

            return response()->json([
                'error' => false,
                'message' => 'University gallery retrieved successfully',
                'data' => $galleries
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to retrieve university gallery',
                'data' => $e->getMessage()
            ], 500);
        }
    }
}

