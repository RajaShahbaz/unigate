<?php

namespace App\Http\Controllers\University;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Interest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UniversityProfileController extends Controller
{
    /**
     * Show university profile
     */
    public function show()
    {
        $admin = Auth::guard('university')->user();
        $university = $admin->university()->with(['city', 'interests'])->first();
        $cities = City::orderBy('name')->get();
        $allInterests = Interest::orderBy('name')->get();

        return view('university.profile.show', compact('university', 'cities', 'allInterests'));
    }

    /**
     * Update university profile
     */
    public function update(Request $request)
    {
        $admin = Auth::guard('university')->user();
        $university = $admin->university;

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
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->only([
            'name',
            'description',
            'city_id',
            'address',
            'phone',
            'email',
            'website',
            'type',
            'established_year',
            'facilities',
            'ranking',
            'tuition_fee_range_min',
            'tuition_fee_range_max',
            'admission_criteria',
            'accreditation',
        ]);

        $data['hostel_available'] = $request->has('hostel_available');
        $data['scholarship_available'] = $request->has('scholarship_available');

        // Handle logo upload
        if ($request->hasFile('logo')) {
            if ($university->getRawOriginal('logo') && Storage::disk('public')->exists($university->getRawOriginal('logo'))) {
                Storage::disk('public')->delete($university->getRawOriginal('logo'));
            }
            $data['logo'] = $request->file('logo')->store('universities/logos', 'public');
        }

        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            if ($university->getRawOriginal('cover_image') && Storage::disk('public')->exists($university->getRawOriginal('cover_image'))) {
                Storage::disk('public')->delete($university->getRawOriginal('cover_image'));
            }
            $data['cover_image'] = $request->file('cover_image')->store('universities/covers', 'public');
        }

        $university->update($data);

        return redirect()->back()
            ->with('success', 'University profile updated successfully');
    }

    /**
     * Update university programs/interests
     */
    public function updatePrograms(Request $request)
    {
        $admin = Auth::guard('university')->user();
        $university = $admin->university;

        $validator = Validator::make($request->all(), [
            'interests' => 'required|array',
            'interests.*.interest_id' => 'required|exists:interests,id',
            'interests.*.fee' => 'nullable|numeric|min:0',
            'interests.*.fee_min' => 'nullable|numeric|min:0',
            'interests.*.fee_max' => 'nullable|numeric|min:0',
            'interests.*.duration_years' => 'nullable|integer|min:1|max:10',
            'interests.*.program_details' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $syncData = [];
        foreach ($request->interests as $interest) {
            $syncData[$interest['interest_id']] = [
                'fee' => $interest['fee'] ?? null,
                'fee_min' => $interest['fee_min'] ?? null,
                'fee_max' => $interest['fee_max'] ?? null,
                'duration_years' => $interest['duration_years'] ?? null,
                'program_details' => $interest['program_details'] ?? null,
            ];
        }

        $university->interests()->sync($syncData);

        return redirect()->back()
            ->with('success', 'Programs updated successfully');
    }
}

