<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\CustomField;
use App\Models\MergeHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ContactController extends Controller
{
    public function index()
    {
        $customFields = CustomField::where('is_active', true)->get();
        return view('contacts.index', compact('customFields'));
    }

    public function datatable(Request $request)
    {
        $query = Contact::query();

        if ($request->status && $request->status !== 'all') {
            $query->where('status', $request->status);
        } else {
            $query->where('status', 'active');
        }

        if ($request->gender && $request->gender !== 'all') {
            $query->where('gender', $request->gender);
        }

        if ($request->search_term) {
            $search = $request->search_term;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $query->orderBy('created_at', 'desc');
        $selectionMode = $request->selection_mode == 1;

        if ($request->has('json') && $request->json == 1) {
            $contacts = $query->get();
            return response()->json([
                'success' => true,
                'contacts' => $contacts
            ]);
        }

        return DataTables::of($query)
            ->addColumn('avatar', function($contact) use ($selectionMode) {
                return view('contacts.partials.avatar', compact('contact', 'selectionMode'))->render();
            })
            ->addColumn('contact_info', function($contact) {
                return view('contacts.partials.contact-info', compact('contact'))->render();
            })
            ->addColumn('custom_fields_preview', function($contact) {
                $customFields = CustomField::where('is_active', true)->get();
                return view('contacts.partials.custom-fields-preview', compact('contact', 'customFields'))->render();
            })
            ->addColumn('actions', function($contact) {
                return view('contacts.partials.actions', compact('contact'))->render();
            })
            ->rawColumns(['avatar', 'contact_info', 'custom_fields_preview', 'actions'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:2|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|regex:/^[\d\s\-\+\(\)]{7,20}$/',
            'gender' => 'nullable|in:male,female,other',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'additional_file' => 'nullable|file|mimes:pdf,doc,docx,jpeg,png,jpg|max:5120',
        ], [
            'name.required' => 'Name is required',
            'name.min' => 'Name must be at least 2 characters',
            'email.required' => 'Email is required',
            'email.email' => 'Please enter a valid email address',
            'phone.regex' => 'Please enter a valid phone number',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->except(['profile_image', 'additional_file']);
        
        if ($request->hasFile('profile_image')) {
            $data['profile_image'] = $request->file('profile_image')->store('contacts/profiles', 'public');
        }
        
        if ($request->hasFile('additional_file')) {
            $data['additional_file'] = $request->file('additional_file')->store('contacts/files', 'public');
        }

        if ($request->custom_field_values) {
            $data['custom_field_values'] = json_decode($request->custom_field_values, true);
        }

        $contact = Contact::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Contact created successfully!',
            'contact' => $contact
        ]);
    }

    public function show(Contact $contact)
    {
        $customFields = CustomField::where('is_active', true)->get();
        return response()->json([
            'success' => true,
            'contact' => $contact,
            'customFields' => $customFields
        ]);
    }

    public function update(Request $request, Contact $contact)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:2|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|regex:/^[\d\s\-\+\(\)]{7,20}$/',
            'gender' => 'nullable|in:male,female,other',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'additional_file' => 'nullable|file|mimes:pdf,doc,docx,jpeg,png,jpg|max:5120',
        ], [
            'name.required' => 'Name is required',
            'name.min' => 'Name must be at least 2 characters',
            'email.required' => 'Email is required',
            'email.email' => 'Please enter a valid email address',
            'phone.regex' => 'Please enter a valid phone number',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->except(['profile_image', 'additional_file', '_method']);
        
        if ($request->hasFile('profile_image')) {
            if ($contact->profile_image) {
                Storage::disk('public')->delete($contact->profile_image);
            }
            $data['profile_image'] = $request->file('profile_image')->store('contacts/profiles', 'public');
        }
        
        if ($request->hasFile('additional_file')) {
            if ($contact->additional_file) {
                Storage::disk('public')->delete($contact->additional_file);
            }
            $data['additional_file'] = $request->file('additional_file')->store('contacts/files', 'public');
        }

        if ($request->custom_field_values) {
            $data['custom_field_values'] = json_decode($request->custom_field_values, true);
        }

        $contact->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Contact updated successfully!',
            'contact' => $contact->fresh()
        ]);
    }

    public function destroy(Contact $contact)
    {
        if ($contact->profile_image) {
            Storage::disk('public')->delete($contact->profile_image);
        }
        if ($contact->additional_file) {
            Storage::disk('public')->delete($contact->additional_file);
        }

        $contact->delete();

        return response()->json([
            'success' => true,
            'message' => 'Contact deleted successfully!'
        ]);
    }

    public function merge(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'master_id' => 'required|exists:contacts,id',
            'secondary_id' => 'required|exists:contacts,id|different:master_id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $master = Contact::findOrFail($request->master_id);
        $secondary = Contact::findOrFail($request->secondary_id);

        $additionalEmails = $master->additional_emails ?? [];
        $additionalPhones = $master->additional_phones ?? [];
        $customFieldValues = $master->custom_field_values ?? [];
        $fieldsAdded = [];

        if ($secondary->email && $secondary->email !== $master->email) {
            $additionalEmails[] = $secondary->email;
            $fieldsAdded[] = 'Email';
        }

        if ($secondary->phone && $secondary->phone !== $master->phone) {
            $additionalPhones[] = $secondary->phone;
            $fieldsAdded[] = 'Phone';
        }

        if ($secondary->custom_field_values) {
            foreach ($secondary->custom_field_values as $key => $value) {
                if (!isset($customFieldValues[$key]) && $value) {
                    $customFieldValues[$key] = $value;
                    $customFields = CustomField::where('field_key', $key)->first();
                    $fieldsAdded[] = $customFields ? $customFields->field_name : $key;
                }
            }
        }

        $master->update([
            'additional_emails' => $additionalEmails,
            'additional_phones' => $additionalPhones,
            'custom_field_values' => $customFieldValues,
        ]);

        $secondary->update([
            'status' => 'merged',
            'merged_into_id' => $master->id
        ]);

        MergeHistory::create([
            'master_contact_id' => $master->id,
            'merged_contact_id' => $secondary->id,
            'master_contact_name' => $master->name,
            'merged_contact_name' => $secondary->name,
            'merged_data' => $secondary->toArray(),
            'fields_added' => $fieldsAdded,
        ]);

        return response()->json([
            'success' => true,
            'message' => "{$secondary->name} has been merged into {$master->name}",
        ]);
    }

    public function getMergePreview(Request $request)
    {
        $master = Contact::findOrFail($request->master_id);
        $secondary = Contact::findOrFail($request->secondary_id);
        $customFields = CustomField::where('is_active', true)->get();

        $fieldsToAdd = [];
        $conflicts = [];

        if ($secondary->email && $secondary->email !== $master->email) {
            $fieldsToAdd[] = ['field' => 'Email', 'value' => $secondary->email];
        }

        if ($secondary->phone && $secondary->phone !== $master->phone) {
            $fieldsToAdd[] = ['field' => 'Phone', 'value' => $secondary->phone];
        }

        if ($secondary->custom_field_values) {
            foreach ($secondary->custom_field_values as $key => $value) {
                $masterValue = $master->custom_field_values[$key] ?? null;
                $fieldConfig = $customFields->firstWhere('field_key', $key);
                $fieldName = $fieldConfig ? $fieldConfig->field_name : $key;

                if (!$masterValue && $value) {
                    $fieldsToAdd[] = ['field' => $fieldName, 'value' => $value];
                } elseif ($masterValue && $value && $masterValue !== $value) {
                    $conflicts[] = [
                        'field' => $fieldName,
                        'masterValue' => $masterValue,
                        'secondaryValue' => $value,
                    ];
                }
            }
        }

        return response()->json([
            'success' => true,
            'master' => $master,
            'secondary' => $secondary,
            'fieldsToAdd' => $fieldsToAdd,
            'conflicts' => $conflicts,
        ]);
    }
}

