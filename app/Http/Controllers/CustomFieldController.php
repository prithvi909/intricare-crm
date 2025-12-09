<?php

namespace App\Http\Controllers;

use App\Models\CustomField;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomFieldController extends Controller
{
    public function index()
    {
        $fields = CustomField::orderBy('created_at', 'desc')->get();
        return view('custom-fields.index', compact('fields'));
    }

    public function getAll()
    {
        $fields = CustomField::orderBy('created_at', 'desc')->get();
        return response()->json([
            'success' => true,
            'fields' => $fields
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'field_name' => 'required|string|max:255',
            'field_key' => 'required|string|max:255|unique:custom_fields,field_key',
            'field_type' => 'required|in:text,number,date,textarea,select',
            'options' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $field = CustomField::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Custom field created successfully!',
            'field' => $field
        ]);
    }

    public function update(Request $request, CustomField $customField)
    {
        $validator = Validator::make($request->all(), [
            'field_name' => 'required|string|max:255',
            'field_type' => 'required|in:text,number,date,textarea,select',
            'options' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $customField->update($request->except('field_key'));

        return response()->json([
            'success' => true,
            'message' => 'Custom field updated successfully!',
            'field' => $customField->fresh()
        ]);
    }

    public function destroy(CustomField $customField)
    {
        $customField->delete();

        return response()->json([
            'success' => true,
            'message' => 'Custom field deleted successfully!'
        ]);
    }

    public function toggleActive(CustomField $customField)
    {
        $customField->update(['is_active' => !$customField->is_active]);

        return response()->json([
            'success' => true,
            'message' => $customField->is_active ? 'Field activated!' : 'Field deactivated!',
            'field' => $customField
        ]);
    }
}



