<?php

namespace App\Http\Controllers\University;

use App\Http\Controllers\Controller;
use App\Models\UniversityApplicationField;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UniversityFormFieldController extends Controller
{
    /**
     * Display a listing of custom fields
     */
    public function index(Request $request)
    {
        $admin = Auth::guard('university')->user();
        $fields = UniversityApplicationField::where('university_id', $admin->university_id)
            ->orderBy('sort_order')
            ->get();

        return view('university.form-fields.index', compact('fields'));
    }

    /**
     * Show the form for creating a new field
     */
    public function create()
    {
        return view('university.form-fields.create');
    }

    /**
     * Store a newly created field
     */
    public function store(Request $request)
    {
        $admin = Auth::guard('university')->user();

        $validator = Validator::make($request->all(), [
            'field_name' => 'required|string|max:255',
            'field_label' => 'required|string|max:255',
            'field_description' => 'nullable|string',
            'field_type' => 'required|in:text,textarea,number,date,file,select,checkbox,radio,email,url',
            'is_required' => 'nullable',
            'field_options' => 'nullable|array',
            'file_types' => 'nullable|string',
            'max_file_size' => 'nullable|integer|min:1|max:10240',
            'status' => 'nullable',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Get the highest sort order
        $maxOrder = UniversityApplicationField::where('university_id', $admin->university_id)->max('sort_order') ?? -1;

        $data = $request->only([
            'field_name',
            'field_label',
            'field_description',
            'field_type',
            'is_required',
            'file_types',
            'max_file_size',
            'status'
        ]);

        $data['university_id'] = $admin->university_id;
        $data['sort_order'] = $maxOrder + 1;
        $data['is_required'] = $request->has('is_required');
        $data['status'] = $request->has('status') ? true : false;

        // Handle field options for select, checkbox, radio
        if (in_array($request->field_type, ['select', 'checkbox', 'radio'])) {
            if ($request->has('field_options')) {
                $data['field_options'] = json_encode($request->field_options);
            }
        }

        UniversityApplicationField::create($data);

        return redirect()->route('university.form-fields.index')
            ->with('success', 'Custom field created successfully');
    }

    /**
     * Show the form for editing the specified field
     */
    public function edit($id)
    {
        $admin = Auth::guard('university')->user();
        $field = UniversityApplicationField::where('university_id', $admin->university_id)->findOrFail($id);

        return view('university.form-fields.edit', compact('field'));
    }

    /**
     * Update the specified field
     */
    public function update(Request $request, $id)
    {
        $admin = Auth::guard('university')->user();
        $field = UniversityApplicationField::where('university_id', $admin->university_id)->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'field_name' => 'required|string|max:255',
            'field_label' => 'required|string|max:255',
            'field_description' => 'nullable|string',
            'field_type' => 'required|in:text,textarea,number,date,file,select,checkbox,radio,email,url',
            'is_required' => 'nullable|boolean',
            'field_options' => 'nullable|array',
            'file_types' => 'nullable|string',
            'max_file_size' => 'nullable|integer|min:1|max:10240',
            'status' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->only([
            'field_name',
            'field_label',
            'field_description',
            'field_type',
            'is_required',
            'file_types',
            'max_file_size',
            'status'
        ]);

        $data['is_required'] = $request->has('is_required');
        $data['status'] = $request->has('status') ? true : false;

        // Handle field options
        if (in_array($request->field_type, ['select', 'checkbox', 'radio'])) {
            if ($request->has('field_options')) {
                $data['field_options'] = json_encode($request->field_options);
            }
        } else {
            $data['field_options'] = null;
        }

        $field->update($data);

        return redirect()->route('university.form-fields.index')
            ->with('success', 'Custom field updated successfully');
    }

    /**
     * Remove the specified field
     */
    public function destroy($id)
    {
        $admin = Auth::guard('university')->user();
        $field = UniversityApplicationField::where('university_id', $admin->university_id)->findOrFail($id);

        // Check if field has responses
        if ($field->responses()->exists()) {
            return redirect()->back()
                ->with('error', 'Cannot delete field with existing responses. Please deactivate it instead.');
        }

        $field->delete();

        return redirect()->route('university.form-fields.index')
            ->with('success', 'Custom field deleted successfully');
    }

    /**
     * Toggle field status
     */
    public function toggleStatus($id)
    {
        $admin = Auth::guard('university')->user();
        $field = UniversityApplicationField::where('university_id', $admin->university_id)->findOrFail($id);

        $field->update(['status' => !$field->status]);

        return redirect()->back()
            ->with('success', 'Field status updated successfully');
    }

    /**
     * Reorder fields
     */
    public function reorder(Request $request)
    {
        $admin = Auth::guard('university')->user();

        $validator = Validator::make($request->all(), [
            'field_orders' => 'required|array',
            'field_orders.*.id' => 'required|exists:university_application_fields,id',
            'field_orders.*.sort_order' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error'
            ], 422);
        }

        foreach ($request->field_orders as $order) {
            UniversityApplicationField::where('university_id', $admin->university_id)
                ->where('id', $order['id'])
                ->update(['sort_order' => $order['sort_order']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Fields reordered successfully'
        ]);
    }
}

