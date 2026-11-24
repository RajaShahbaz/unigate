<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\University;
use App\Models\City;
use App\Models\Interest;
use App\Models\UniversityAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UniversityManagementController extends Controller
{
    public function index(Request $request)
    {
        $statusFilter = $request->get('status', 'all');
        $typeFilter = $request->get('type', 'all');
        $search = $request->get('search');

        $query = University::with(['city'])->withCount('applications')->latest();

        if ($statusFilter !== 'all') {
            $query->where('status', $statusFilter === 'active');
        }

        if ($typeFilter !== 'all') {
            $query->where('type', $typeFilter);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Get all results for DataTables (client-side pagination)
        $universities = $query->get();

        $stats = [
            'total' => University::count(),
            'active' => University::where('status', 1)->count(),
            'inactive' => University::where('status', 0)->count(),
        ];

        $types = [
            'public' => __('Public'),
            'private' => __('Private'),
            'semi-government' => __('Semi Government'),
        ];

        return view('admin.universities.index', [
            'universities' => $universities,
            'stats' => $stats,
            'statusFilter' => $statusFilter,
            'typeFilter' => $typeFilter,
            'search' => $search,
            'types' => $types,
        ]);
    }

    public function create()
    {
        $cities = City::where('status', 1)->orderBy('name')->get();
        $interests = Interest::where('status', 1)->orderBy('name')->get();
        $types = [
            'public' => __('Public'),
            'private' => __('Private'),
            'semi-government' => __('Semi Government'),
        ];

        return view('admin.universities.create', [
            'cities' => $cities,
            'interests' => $interests,
            'types' => $types,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'city_id' => 'required|exists:cities,id',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'type' => 'required|in:public,private,semi-government',
            'established_year' => 'nullable|digits:4|integer|min:1800|max:' . date('Y'),
            'facilities' => 'nullable|string',
            'ranking' => 'nullable|numeric|min:0|max:100',
            'tuition_fee_range_min' => 'nullable|numeric|min:0',
            'tuition_fee_range_max' => 'nullable|numeric|min:0',
            'hostel_available' => 'nullable|boolean',
            'scholarship_available' => 'nullable|boolean',
            'admission_criteria' => 'nullable|string',
            'accreditation' => 'nullable|string',
            'status' => 'nullable|boolean',
            'interest_ids' => 'nullable|array',
            'interest_ids.*' => 'exists:interests,id',
            // University Admin fields (required for each university)
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|max:255|unique:university_admins,email',
            'admin_password' => 'required|string|min:8',
            'admin_phone' => 'nullable|string|max:20',
            'admin_position' => 'nullable|string|max:255',
            'admin_status' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $validator->validated();

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('universities/logos', 'public');
        }

        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('universities/covers', 'public');
        }

        // Extract interest_ids and admin data before creating university
        $interestIds = $data['interest_ids'] ?? [];
        $adminData = [
            'name' => $data['admin_name'] ?? null,
            'email' => $data['admin_email'] ?? null,
            'password' => $data['admin_password'] ?? null,
            'phone' => $data['admin_phone'] ?? null,
            'position' => $data['admin_position'] ?? null,
            'status' => $data['admin_status'] ?? true,
        ];
        
        unset($data['interest_ids'], $data['admin_name'], $data['admin_email'], $data['admin_password'], 
              $data['admin_phone'], $data['admin_position'], $data['admin_status']);

        $university = University::create($data);

        // Attach interests if provided
        if (!empty($interestIds)) {
            $university->interests()->attach($interestIds);
        }

        // Create university admin (required for each university)
        $adminData['university_id'] = $university->id;
        $adminData['password'] = Hash::make($adminData['password']);
        UniversityAdmin::create($adminData);

        return redirect()->route('admin.universities.index')
            ->with('success', __('University created successfully'));
    }

    public function show($id)
    {
        $university = University::with(['city', 'interests', 'applications', 'universityAdmins'])->findOrFail($id);
        
        return view('admin.universities.show', [
            'university' => $university,
        ]);
    }

    public function edit($id)
    {
        $university = University::with(['interests', 'universityAdmins'])->findOrFail($id);
        $cities = City::where('status', 1)->orderBy('name')->get();
        $interests = Interest::where('status', 1)->orderBy('name')->get();
        $types = [
            'public' => __('Public'),
            'private' => __('Private'),
            'semi-government' => __('Semi Government'),
        ];

        return view('admin.universities.edit', [
            'university' => $university,
            'cities' => $cities,
            'interests' => $interests,
            'types' => $types,
        ]);
    }

    public function update(Request $request, $id)
    {
        $university = University::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'city_id' => 'sometimes|exists:cities,id',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'type' => 'sometimes|in:public,private,semi-government',
            'established_year' => 'nullable|digits:4|integer|min:1800|max:' . date('Y'),
            'facilities' => 'nullable|string',
            'ranking' => 'nullable|numeric|min:0|max:100',
            'tuition_fee_range_min' => 'nullable|numeric|min:0',
            'tuition_fee_range_max' => 'nullable|numeric|min:0',
            'hostel_available' => 'nullable|boolean',
            'scholarship_available' => 'nullable|boolean',
            'admission_criteria' => 'nullable|string',
            'accreditation' => 'nullable|string',
            'status' => 'nullable|boolean',
            'interest_ids' => 'nullable|array',
            'interest_ids.*' => 'exists:interests,id',
            // University Admin fields (required if no existing admin, or when creating new)
            'admin_id' => 'nullable|exists:university_admins,id',
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|max:255|unique:university_admins,email,' . ($request->admin_id ?? 'NULL') . ',id',
            'admin_password' => 'nullable|string|min:8',
            'admin_phone' => 'nullable|string|max:20',
            'admin_position' => 'nullable|string|max:255',
            'admin_status' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $validator->validated();

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo
            if ($university->logo && Storage::disk('public')->exists($university->logo)) {
                Storage::disk('public')->delete($university->logo);
            }
            $data['logo'] = $request->file('logo')->store('universities/logos', 'public');
        }

        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            // Delete old cover
            if ($university->cover_image && Storage::disk('public')->exists($university->cover_image)) {
                Storage::disk('public')->delete($university->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')->store('universities/covers', 'public');
        }

        // Extract interest_ids and admin data before updating
        $interestIds = $data['interest_ids'] ?? null;
        $adminData = [
            'name' => $data['admin_name'] ?? null,
            'email' => $data['admin_email'] ?? null,
            'password' => $data['admin_password'] ?? null,
            'phone' => $data['admin_phone'] ?? null,
            'position' => $data['admin_position'] ?? null,
            'status' => $data['admin_status'] ?? true,
        ];
        $adminId = $data['admin_id'] ?? null;
        
        unset($data['interest_ids'], $data['admin_id'], $data['admin_name'], $data['admin_email'], 
              $data['admin_password'], $data['admin_phone'], $data['admin_position'], $data['admin_status']);

        $university->update($data);

        // Sync interests if provided
        if ($interestIds !== null) {
            $university->interests()->sync($interestIds);
        }

        // Update or create university admin (each university must have at least one admin)
        if ($adminId) {
            // Update existing admin
            $admin = UniversityAdmin::findOrFail($adminId);
            if (!empty($adminData['password'])) {
                $adminData['password'] = Hash::make($adminData['password']);
            } else {
                unset($adminData['password']);
            }
            $admin->update($adminData);
        } else {
            // Create new admin if doesn't exist
            if (empty($adminData['password'])) {
                return redirect()->back()
                    ->withErrors(['admin_password' => __('Password is required when creating a new admin.')])
                    ->withInput();
            }
            $adminData['university_id'] = $university->id;
            $adminData['password'] = Hash::make($adminData['password']);
            UniversityAdmin::create($adminData);
        }

        return redirect()->route('admin.universities.index')
            ->with('success', __('University updated successfully'));
    }

    public function destroy($id)
    {
        $university = University::findOrFail($id);
        $university->delete();

        return redirect()->route('admin.universities.index')
            ->with('success', __('University deleted successfully'));
    }
}


