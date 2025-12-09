@extends('layouts.crm')

@section('title', 'Custom Fields')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-indigo-50/30">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 flex items-center gap-2">
                    <i data-lucide="settings" class="h-6 w-6 text-slate-500"></i>
                    Custom Fields
                </h1>
                <p class="text-slate-500 mt-1">Manage additional fields for your contacts</p>
            </div>
            <button onclick="openFieldModal()" class="flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                <i data-lucide="plus" class="h-4 w-4"></i>
                Add Field
            </button>
        </div>

        <!-- Fields List -->
        <div id="fieldsList" class="space-y-3">
            @forelse($fields as $field)
                @include('custom-fields.partials.field-card', ['field' => $field])
            @empty
                <div class="bg-white rounded-xl border border-slate-200 p-12 text-center">
                    <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="settings" class="h-6 w-6 text-slate-400"></i>
                    </div>
                    <h3 class="font-medium text-slate-900">No custom fields yet</h3>
                    <p class="text-sm text-slate-500 mt-1">Add custom fields to capture additional contact information</p>
                    <button onclick="openFieldModal()" class="mt-4 px-4 py-2 border border-slate-200 rounded-lg hover:bg-slate-50">
                        <i data-lucide="plus" class="h-4 w-4 inline mr-1"></i>
                        Create First Field
                    </button>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Create/Edit Modal -->
<div id="fieldModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/50" onclick="closeFieldModal()"></div>
    <div class="relative bg-white rounded-2xl shadow-xl max-w-md w-full">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 id="fieldModalTitle" class="text-xl font-semibold text-slate-900">Create Custom Field</h2>
                <button onclick="closeFieldModal()" class="p-2 hover:bg-slate-100 rounded-lg">
                    <i data-lucide="x" class="h-5 w-5"></i>
                </button>
            </div>
            
            <form id="fieldForm" class="space-y-4">
                <input type="hidden" id="fieldId">
                
                <div class="space-y-2">
                    <label class="text-sm font-medium text-slate-700">Field Name *</label>
                    <input type="text" id="fieldNameInput" placeholder="e.g., Company Name" 
                           class="w-full px-4 py-2.5 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                
                <div class="space-y-2">
                    <label class="text-sm font-medium text-slate-700">Field Key</label>
                    <input type="text" id="fieldKeyInput" placeholder="e.g., company_name" 
                           class="w-full px-4 py-2.5 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 disabled:bg-slate-50">
                    <p class="text-xs text-slate-500">Used internally to store the value. Cannot be changed after creation.</p>
                </div>
                
                <div class="space-y-2">
                    <label class="text-sm font-medium text-slate-700">Field Type</label>
                    <select id="fieldTypeInput" class="w-full px-4 py-2.5 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="text">Text</option>
                        <option value="number">Number</option>
                        <option value="date">Date</option>
                        <option value="textarea">Long Text</option>
                        <option value="select">Dropdown</option>
                    </select>
                </div>
                
                <div id="optionsContainer" class="hidden space-y-2">
                    <label class="text-sm font-medium text-slate-700">Dropdown Options</label>
                    <div class="flex gap-2">
                        <input type="text" id="newOptionInput" placeholder="Add option" 
                               class="flex-1 px-4 py-2.5 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <button type="button" onclick="addOption()" class="px-3 py-2.5 border border-slate-200 rounded-lg hover:bg-slate-50">
                            <i data-lucide="plus" class="h-4 w-4"></i>
                        </button>
                    </div>
                    <div id="optionsList" class="flex flex-wrap gap-2"></div>
                </div>
                
                <div class="flex justify-end gap-3 pt-4">
                    <button type="button" onclick="closeFieldModal()" class="px-4 py-2 border border-slate-200 rounded-lg text-slate-600 hover:bg-slate-50">
                        Cancel
                    </button>
                    <button type="submit" id="fieldSubmitBtn" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                        Create Field
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let currentOptions = [];

$(document).ready(function() {
    $('#fieldNameInput').on('input', function() {
        if (!$('#fieldId').val()) {
            const key = $(this).val().toLowerCase().replace(/[^a-z0-9]/g, '_').replace(/_+/g, '_');
            $('#fieldKeyInput').val(key);
        }
    });
    
    $('#fieldTypeInput').on('change', function() {
        if ($(this).val() === 'select') {
            $('#optionsContainer').removeClass('hidden');
        } else {
            $('#optionsContainer').addClass('hidden');
        }
    });
    
    $('#fieldForm').on('submit', function(e) {
        e.preventDefault();
        submitFieldForm();
    });
    
    $('#newOptionInput').on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            addOption();
        }
    });
});

function openFieldModal(field = null) {
    $('#fieldForm')[0].reset();
    $('#fieldId').val('');
    $('#fieldKeyInput').prop('disabled', false);
    currentOptions = [];
    renderOptions();
    $('#optionsContainer').addClass('hidden');
    
    if (field) {
        $('#fieldModalTitle').text('Edit Custom Field');
        $('#fieldSubmitBtn').text('Update Field');
        $('#fieldId').val(field.id);
        $('#fieldNameInput').val(field.field_name);
        $('#fieldKeyInput').val(field.field_key).prop('disabled', true);
        $('#fieldTypeInput').val(field.field_type);
        
        if (field.field_type === 'select') {
            $('#optionsContainer').removeClass('hidden');
            currentOptions = field.options || [];
            renderOptions();
        }
    } else {
        $('#fieldModalTitle').text('Create Custom Field');
        $('#fieldSubmitBtn').text('Create Field');
    }
    
    $('#fieldModal').removeClass('hidden');
    lucide.createIcons();
}

function closeFieldModal() {
    $('#fieldModal').addClass('hidden');
}

function addOption() {
    const option = $('#newOptionInput').val().trim();
    if (option && !currentOptions.includes(option)) {
        currentOptions.push(option);
        renderOptions();
        $('#newOptionInput').val('');
    }
}

function removeOption(index) {
    currentOptions.splice(index, 1);
    renderOptions();
}

function renderOptions() {
    let html = '';
    currentOptions.forEach((option, index) => {
        html += `
            <span class="inline-flex items-center gap-1 px-3 py-1 bg-slate-100 rounded-lg text-sm">
                ${option}
                <button type="button" onclick="removeOption(${index})" class="hover:text-red-500">
                    <i data-lucide="x" class="h-3 w-3"></i>
                </button>
            </span>
        `;
    });
    $('#optionsList').html(html);
    lucide.createIcons();
}

function submitFieldForm() {
    const fieldId = $('#fieldId').val();
    const data = {
        field_name: $('#fieldNameInput').val(),
        field_key: $('#fieldKeyInput').val(),
        field_type: $('#fieldTypeInput').val(),
        options: currentOptions
    };
    
    const url = fieldId ? `/custom-fields/${fieldId}` : "{{ route('custom-fields.store') }}";
    const method = fieldId ? 'PUT' : 'POST';
    
    $.ajax({
        url: url,
        method: method,
        data: data,
        success: function(response) {
            if (response.success) {
                showSuccess(fieldId ? 'Custom Field Updated! ✅' : 'Custom Field Created! 🎉',
                           fieldId ? 'Your changes have been saved.' : 'The new field is now available for contacts.');
                closeFieldModal();
                location.reload();
            }
        },
        error: function(xhr) {
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;
                const firstError = Object.values(errors)[0][0];
                showError('Validation Error', firstError);
            } else {
                showError('Something went wrong', 'Please try again later.');
            }
        }
    });
}

function toggleFieldActive(id) {
    $.ajax({
        url: `/custom-fields/${id}/toggle`,
        method: 'PATCH',
        success: function(response) {
            if (response.success) {
                showSuccess(response.message);
                location.reload();
            }
        }
    });
}

function editField(field) {
    openFieldModal(field);
}

function deleteField(id, name) {
    Swal.fire({
        title: 'Delete Custom Field',
        text: `Are you sure you want to delete "${name}"? This will remove the field from all contacts.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#DC2626',
        cancelButtonColor: '#64748B',
        confirmButtonText: 'Delete',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/custom-fields/${id}`,
                method: 'DELETE',
                success: function(response) {
                    if (response.success) {
                        showSuccess('Custom Field Deleted! 🗑️', 'The field has been removed.');
                        location.reload();
                    }
                }
            });
        }
    });
}
</script>
@endpush



