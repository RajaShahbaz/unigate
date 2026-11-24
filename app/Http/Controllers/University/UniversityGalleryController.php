<?php

namespace App\Http\Controllers\University;

use App\Http\Controllers\Controller;
use App\Repositories\UniversityGallery\UniversityGalleryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UniversityGalleryController extends Controller
{
    private UniversityGalleryInterface $galleryRepository;

    public function __construct(UniversityGalleryInterface $galleryRepository)
    {
        $this->galleryRepository = $galleryRepository;
    }

    /**
     * Display gallery management page
     */
    public function index(Request $request)
    {
        $admin = Auth::guard('university')->user();
        $universityId = $admin->university_id;

        $galleries = $this->galleryRepository->builder()
            ->where('university_id', $universityId)
            ->orderBy('display_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('university.gallery.index', compact('galleries', 'admin'));
    }

    /**
     * Show form to create new gallery item
     */
    public function create()
    {
        $admin = Auth::guard('university')->user();
        return view('university.gallery.create', compact('admin'));
    }

    /**
     * Store new gallery item
     */
    public function store(Request $request)
    {
        $admin = Auth::guard('university')->user();

        $validator = Validator::make($request->all(), [
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
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = $request->all();
            $data['university_id'] = $admin->university_id;

            $this->galleryRepository->create($data);

            return redirect()->route('university.gallery.index')
                ->with('success', 'Gallery item created successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to create gallery item: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show form to edit gallery item
     */
    public function edit($id)
    {
        $admin = Auth::guard('university')->user();

        $gallery = $this->galleryRepository->builder()
            ->where('id', $id)
            ->where('university_id', $admin->university_id)
            ->firstOrFail();

        return view('university.gallery.edit', compact('gallery', 'admin'));
    }

    /**
     * Update gallery item
     */
    public function update(Request $request, $id)
    {
        $admin = Auth::guard('university')->user();

        // Verify ownership
        $gallery = $this->galleryRepository->builder()
            ->where('id', $id)
            ->where('university_id', $admin->university_id)
            ->firstOrFail();

        $validator = Validator::make($request->all(), [
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
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $this->galleryRepository->update($id, $request->all());

            return redirect()->route('university.gallery.index')
                ->with('success', 'Gallery item updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update gallery item: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Delete gallery item
     */
    public function destroy($id)
    {
        $admin = Auth::guard('university')->user();

        // Verify ownership
        $gallery = $this->galleryRepository->builder()
            ->where('id', $id)
            ->where('university_id', $admin->university_id)
            ->firstOrFail();

        try {
            $this->galleryRepository->deleteById($id);

            return redirect()->route('university.gallery.index')
                ->with('success', 'Gallery item deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete gallery item: ' . $e->getMessage());
        }
    }
}

