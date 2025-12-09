@extends('layouts.crm')

@section('title', 'Contacts')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-indigo-50/30">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Contacts</h1>
                    <p class="text-slate-500 mt-1">Manage and organize your contact database</p>
                </div>
                <div class="flex items-center gap-3">
                    <button id="mergeToggleBtn" class="flex items-center gap-2 px-4 py-2 border border-slate-200 rounded-lg bg-white text-slate-600 hover:bg-slate-50 transition-colors">
                        <i data-lucide="git-merge" class="h-4 w-4"></i>
                        Merge Contacts
                    </button>
                    <button id="addContactBtn" class="flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                        <i data-lucide="plus" class="h-4 w-4"></i>
                        Add Contact
                    </button>
                </div>
                <!-- Merge Actions (Hidden, will replace buttons when active) -->
                <div id="mergeActionsHeader" class="hidden flex items-center gap-3">
                    <button id="cancelMergeBtnHeader" class="flex items-center gap-2 px-4 py-2 border border-slate-200 rounded-lg bg-white text-slate-600 hover:bg-slate-50">
                        <i data-lucide="x" class="h-4 w-4"></i>
                        Cancel
                    </button>
                    <button id="executeMergeBtnHeader" disabled class="flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed">
                        <i data-lucide="git-merge" class="h-4 w-4"></i>
                        Merge (<span id="selectedCountHeader">0</span>/2)
                    </button>
                </div>
            </div>
            <!-- Stats -->
            <div class="flex items-center gap-4 mt-6">
                <div class="flex items-center gap-2 px-4 py-2 bg-white rounded-full border border-slate-200 shadow-sm">
                    <i data-lucide="users" class="h-4 w-4 text-indigo-600"></i>
                    <span id="activeCount" class="text-sm font-medium text-slate-700">0 Active</span>
                </div>
                <div class="flex items-center gap-2 px-4 py-2 bg-white rounded-full border border-slate-200 shadow-sm">
                    <i data-lucide="git-merge" class="h-4 w-4 text-amber-600"></i>
                    <span id="mergedCount" class="text-sm font-medium text-slate-700">0 Merged</span>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="flex flex-col lg:flex-row lg:items-center gap-4 mb-6">
            <div class="flex-1 flex flex-col sm:flex-row gap-3">
                <!-- Search -->
                <div class="relative flex-1">
                    <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400"></i>
                    <input type="text" id="searchInput" placeholder="Search by name or email..." 
                           class="w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                
                <!-- Gender Filter -->
                <select id="genderFilter" class="px-4 py-2.5 border border-slate-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="all">All Genders</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select>
                
                <!-- Status Filter -->
                <select id="statusFilter" class="px-4 py-2.5 border border-slate-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="active">Active</option>
                    <option value="merged">Merged</option>
                    <option value="all">All Status</option>
                </select>
            </div>
            <!-- View Toggle -->
            <div class="flex items-center gap-2">
                <button id="gridViewBtn" class="p-2 border border-slate-200 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-100 transition-colors" title="Grid View">
                    <i data-lucide="grid-3x3" class="h-5 w-5"></i>
                </button>
                <button id="listViewBtn" class="p-2 border border-slate-200 rounded-lg bg-white text-slate-600 hover:bg-slate-50 transition-colors" title="List View">
                    <i data-lucide="list" class="h-5 w-5"></i>
                </button>
            </div>
        </div>

        <!-- Selection Mode Banner -->
        <div id="selectionBanner" class="hidden mb-6 p-4 bg-indigo-50 border border-indigo-200 rounded-xl flex items-center justify-between">
            <div class="flex items-center gap-3">
                <i data-lucide="check-square" class="h-5 w-5 text-indigo-600"></i>
                <span class="text-sm font-medium text-indigo-800">Select 2 contacts to merge them together</span>
            </div>
            <div id="selectedContactsPreview" class="flex items-center gap-2"></div>
        </div>

        <!-- Selection Mode Banner -->
        <div id="selectionBanner" class="hidden mb-6 p-4 bg-indigo-50 border border-indigo-200 rounded-xl flex items-center justify-between">
            <div class="flex items-center gap-3">
                <i data-lucide="check-square" class="h-5 w-5 text-indigo-600"></i>
                <span class="text-sm font-medium text-indigo-800">Select 2 contacts to merge them together</span>
            </div>
            <div id="selectedContactsPreview" class="flex items-center gap-2"></div>
        </div>

        <!-- Card Grid View -->
        <div id="cardGridView" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Cards will be dynamically loaded here -->
        </div>

        <!-- List/Table View -->
        <div id="listTableView" class="hidden bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
            <div class="overflow-x-auto">
                <table id="contactsTable" class="w-full">
                    <thead class="bg-gradient-to-r from-slate-50 to-slate-50/50 border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">
                                <div class="flex items-center gap-2">
                                    <span>Contact</span>
                                    <i data-lucide="arrow-up-down" class="h-3 w-3 text-slate-400"></i>
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">
                                <div class="flex items-center gap-2">
                                    <span>Info</span>
                                    <i data-lucide="arrow-up-down" class="h-3 w-3 text-slate-400"></i>
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">
                                <div class="flex items-center gap-2">
                                    <span>Custom Fields</span>
                                    <i data-lucide="arrow-up-down" class="h-3 w-3 text-slate-400"></i>
                                </div>
                            </th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-slate-700 uppercase tracking-wider">
                                <div class="flex items-center justify-end gap-2">
                                    <span>Actions</span>
                                    <i data-lucide="arrow-up-down" class="h-3 w-3 text-slate-400"></i>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100/50"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Create/Edit Modal -->
<div id="contactModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/50" onclick="closeContactModal()"></div>
    <div class="absolute right-0 top-0 h-full w-full max-w-xl bg-white shadow-xl overflow-y-auto">
        <div class="sticky top-0 bg-white border-b border-slate-200 px-6 py-4 flex items-center justify-between">
            <h2 id="modalTitle" class="text-xl font-semibold text-slate-900">Add Contact</h2>
            <button onclick="closeContactModal()" class="p-2 hover:bg-slate-100 rounded-lg">
                <i data-lucide="x" class="h-5 w-5"></i>
            </button>
        </div>
        <form id="contactForm" class="p-6 space-y-6" enctype="multipart/form-data">
            <input type="hidden" id="contactId" name="contact_id">
            
            <!-- Profile Image -->
            <div class="flex flex-col items-center gap-4 pb-6 border-b border-slate-100">
                <div class="relative group">
                    <div id="avatarPreview" class="h-24 w-24 rounded-full ring-4 ring-slate-100 bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center overflow-hidden">
                        <span id="avatarInitials" class="text-white text-2xl font-semibold">CN</span>
                        <img id="avatarImage" src="" alt="" class="hidden h-full w-full object-cover">
                    </div>
                    <label class="absolute inset-0 flex items-center justify-center bg-black/50 rounded-full opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                        <i data-lucide="upload" class="h-6 w-6 text-white"></i>
                        <input type="file" name="profile_image" id="profileImageInput" accept="image/*" class="hidden">
                    </label>
                </div>
                <p class="text-sm text-slate-500">Click to upload profile image</p>
            </div>

            <!-- Basic Fields -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div class="space-y-2">
                    <label class="flex items-center gap-2 text-sm font-medium text-slate-700">
                        <i data-lucide="user" class="h-4 w-4"></i>
                        Full Name *
                    </label>
                    <input type="text" name="name" id="nameInput" placeholder="John Doe" 
                           class="w-full px-4 py-2.5 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <p id="nameError" class="hidden text-sm text-red-500 flex items-center gap-1">
                        <i data-lucide="alert-circle" class="h-3 w-3"></i>
                        <span></span>
                    </p>
                </div>

                <!-- Email -->
                <div class="space-y-2">
                    <label class="flex items-center gap-2 text-sm font-medium text-slate-700">
                        <i data-lucide="mail" class="h-4 w-4"></i>
                        Email Address *
                    </label>
                    <input type="email" name="email" id="emailInput" placeholder="john@example.com" 
                           class="w-full px-4 py-2.5 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <p id="emailError" class="hidden text-sm text-red-500 flex items-center gap-1">
                        <i data-lucide="alert-circle" class="h-3 w-3"></i>
                        <span></span>
                    </p>
                </div>

                <!-- Phone -->
                <div class="space-y-2">
                    <label class="flex items-center gap-2 text-sm font-medium text-slate-700">
                        <i data-lucide="phone" class="h-4 w-4"></i>
                        Phone Number
                    </label>
                    <input type="text" name="phone" id="phoneInput" placeholder="+1 234 567 8900" 
                           class="w-full px-4 py-2.5 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <p id="phoneError" class="hidden text-sm text-red-500 flex items-center gap-1">
                        <i data-lucide="alert-circle" class="h-3 w-3"></i>
                        <span></span>
                    </p>
                </div>

                <!-- Gender -->
                <div class="space-y-2">
                    <label class="text-sm font-medium text-slate-700">Gender</label>
                    <div class="flex gap-6 pt-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="gender" value="male" class="w-4 h-4 text-indigo-600">
                            <span class="text-sm text-slate-700">Male</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="gender" value="female" class="w-4 h-4 text-indigo-600">
                            <span class="text-sm text-slate-700">Female</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="gender" value="other" class="w-4 h-4 text-indigo-600">
                            <span class="text-sm text-slate-700">Other</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Additional File -->
            <div class="space-y-2">
                <label class="text-sm font-medium text-slate-700">Additional Document</label>
                <div id="fileUploadArea" class="border-2 border-dashed border-slate-200 rounded-xl p-6 hover:border-indigo-300 transition-colors">
                    <label class="flex flex-col items-center gap-2 cursor-pointer">
                        <i data-lucide="upload" class="h-8 w-8 text-slate-400"></i>
                        <span class="text-sm text-slate-500">Click to upload document</span>
                        <span class="text-xs text-slate-400">PDF, DOC, or images</span>
                        <input type="file" name="additional_file" id="additionalFileInput" class="hidden">
                    </label>
                </div>
                <div id="filePreview" class="hidden flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-indigo-50 rounded-lg">
                            <i data-lucide="file-text" class="h-5 w-5 text-indigo-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-700">Document uploaded</p>
                            <a id="fileLink" href="#" target="_blank" class="text-xs text-indigo-600 hover:underline">View file</a>
                        </div>
                    </div>
                    <button type="button" onclick="removeFile()" class="p-2 hover:bg-slate-200 rounded-lg">
                        <i data-lucide="x" class="h-4 w-4"></i>
                    </button>
                </div>
            </div>

            <!-- Custom Fields -->
            <div id="customFieldsContainer" class="space-y-4">
                <h3 class="font-semibold text-slate-800 flex items-center gap-2">
                    <div class="h-1 w-1 rounded-full bg-indigo-500"></div>
                    Custom Fields
                </h3>
                <div id="customFieldsGrid" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Dynamic custom fields will be inserted here -->
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                <button type="button" onclick="closeContactModal()" class="px-6 py-2.5 border border-slate-200 rounded-lg text-slate-600 hover:bg-slate-50">
                    Cancel
                </button>
                <button type="submit" id="submitBtn" class="px-6 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 flex items-center gap-2">
                    <span id="submitBtnText">Create Contact</span>
                    <i id="submitBtnLoader" data-lucide="loader-2" class="hidden h-4 w-4 animate-spin"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- View Contact Modal -->
<div id="viewModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/50" onclick="closeViewModal()"></div>
    <div class="relative bg-white rounded-2xl shadow-xl max-w-lg w-full max-h-[90vh] overflow-y-auto">
        <div id="viewModalContent">
            <!-- Content will be dynamically loaded -->
        </div>
    </div>
</div>

<!-- Merge Modal -->
<div id="mergeModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/50" onclick="closeMergeModal()"></div>
    <div class="relative bg-white rounded-2xl shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div id="mergeModalContent">
            <!-- Content will be dynamically loaded -->
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let table;
let selectionMode = false;
let selectedContacts = [];
let customFields = @json($customFields);
let currentView = 'grid';

function initializeDataTable() {
    if ($.fn.DataTable.isDataTable('#contactsTable')) {
        return;
    }
    table = $('#contactsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('contacts.datatable') }}",
            data: function(d) {
                d.search_term = $('#searchInput').val();
                d.gender = $('#genderFilter').val();
                d.status = $('#statusFilter').val();
                d.selection_mode = selectionMode ? 1 : 0;
            }
        },
        columns: [
            { data: 'avatar', name: 'name', orderable: true, className: '!py-5' },
            { data: 'contact_info', name: 'email', orderable: true, className: '!py-5' },
            { data: 'custom_fields_preview', orderable: false, className: '!py-5' },
            { data: 'actions', orderable: false, className: '!py-5' }
        ],
        order: [[0, 'asc']],
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        dom: '<"flex flex-col sm:flex-row justify-between items-center mb-4"<"mb-2 sm:mb-0"l><"mb-2 sm:mb-0"f>>rt<"flex flex-col sm:flex-row justify-between items-center mt-4"<"mb-2 sm:mb-0"i><"mb-2 sm:mb-0"p>>',
        language: {
            processing: '<div class="flex items-center justify-center py-12"><div class="flex flex-col items-center gap-4"><div class="w-12 h-12 rounded-full bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center"><i data-lucide="loader-2" class="h-6 w-6 animate-spin text-indigo-600"></i></div><p class="text-sm font-medium text-slate-600">Loading contacts...</p></div></div>',
            emptyTable: '<div class="text-center py-20"><div class="w-24 h-24 rounded-2xl bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center mx-auto mb-6 shadow-sm"><i data-lucide="users" class="h-12 w-12 text-indigo-500"></i></div><h3 class="text-xl font-bold text-slate-900 mb-2">No contacts found</h3><p class="text-slate-500 text-sm mb-6">Get started by adding your first contact</p><button onclick="$(\'#addContactBtn\').click()" class="px-6 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium">Add Your First Contact</button></div>',
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            infoEmpty: "Showing 0 to 0 of 0 entries",
            infoFiltered: "(filtered from _MAX_ total entries)",
            search: "",
            searchPlaceholder: "Search contacts...",
            zeroRecords: '<div class="text-center py-12"><p class="text-slate-500">No matching contacts found</p></div>'
        },
        rowCallback: function(row, data) {
            $(row).addClass('group');
        },
        drawCallback: function() {
            lucide.createIcons();
            updateStats();
        }
    });
}

$(document).ready(function() {

    $('#gridViewBtn').on('click', function() {
        switchToGridView();
    });

    $('#listViewBtn').on('click', function() {
        switchToListView();
    });

    let searchTimeout;
    $('#searchInput').on('keyup', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            if (currentView === 'grid') {
                loadContactsGrid();
            } else {
                table.draw();
            }
        }, 300);
    });

    $('#genderFilter').on('change', function() {
        if (currentView === 'grid') {
            loadContactsGrid();
        } else {
            if (table) table.draw();
        }
    });

    $('#statusFilter').on('change', function() {
        if (currentView === 'grid') {
            loadContactsGrid();
        } else {
            if (table) table.draw();
        }
    });

    loadContactsGrid();

    $('#addContactBtn').on('click', function() {
        openContactModal();
    });

    $('#mergeToggleBtn').on('click', function() {
        toggleSelectionMode(true);
    });

    $('#cancelMergeBtnHeader').on('click', function() {
        toggleSelectionMode(false);
    });

    $('#executeMergeBtnHeader').on('click', function() {
        if (selectedContacts.length === 2) {
            openMergeModal();
        }
    });

    $('#contactForm').on('submit', function(e) {
        e.preventDefault();
        submitContactForm();
    });

    $('#profileImageInput').on('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#avatarImage').attr('src', e.target.result).removeClass('hidden');
                $('#avatarInitials').addClass('hidden');
            };
            reader.readAsDataURL(file);
        }
    });

    $('#nameInput').on('input', function() {
        const name = $(this).val();
        const initials = name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2) || 'CN';
        $('#avatarInitials').text(initials);
    });

    $('#additionalFileInput').on('change', function() {
        if (this.files[0]) {
            $('#fileUploadArea').addClass('hidden');
            $('#filePreview').removeClass('hidden');
        }
    });

    loadCustomFields();
});

function updateStats() {
    $.get("{{ route('contacts.datatable') }}", { status: 'active', length: 1 }, function(data) {
        $('#activeCount').text(data.recordsFiltered + ' Active');
    });
    $.get("{{ route('contacts.datatable') }}", { status: 'merged', length: 1 }, function(data) {
        $('#mergedCount').text(data.recordsFiltered + ' Merged');
    });
}

function toggleSelectionMode(enable) {
    selectionMode = enable;
    selectedContacts = [];
    
    if (enable) {
        $('#mergeToggleBtn').addClass('hidden');
        $('#addContactBtn').addClass('hidden');
        $('#mergeActionsHeader').removeClass('hidden');
        $('#selectionBanner').removeClass('hidden');
    } else {
        $('#mergeToggleBtn').removeClass('hidden');
        $('#addContactBtn').removeClass('hidden');
        $('#mergeActionsHeader').addClass('hidden');
        $('#selectionBanner').addClass('hidden');
    }
    
    updateSelectionUI();
    if (currentView === 'grid') {
        loadContactsGrid();
    } else {
        table.ajax.reload();
    }
}

function toggleContactSelection(id, name) {
    const index = selectedContacts.findIndex(c => c.id == id);
    
    if (index > -1) {
        selectedContacts.splice(index, 1);
    } else {
        if (selectedContacts.length >= 2) {
            showWarning('Selection Limit Reached!', 'You can only select 2 contacts to merge at a time.');
            return;
        }
        selectedContacts.push({ id: parseInt(id), name });
    }
    
    updateSelectionUI();
}

function updateSelectionUI() {
    $('#selectedCountHeader').text(selectedContacts.length);
    $('#executeMergeBtnHeader').prop('disabled', selectedContacts.length !== 2);
    
    let preview = '';
    selectedContacts.forEach(c => {
        preview += `<span class="px-3 py-1 bg-indigo-100 text-indigo-700 rounded-full text-sm font-medium">${c.name}</span>`;
    });
    $('#selectedContactsPreview').html(preview);
    
    $('.contact-checkbox').each(function() {
        const id = parseInt($(this).data('id'));
        $(this).prop('checked', selectedContacts.some(c => c.id == id));
    });
}

function switchToGridView() {
    currentView = 'grid';
    $('#listTableView').addClass('hidden');
    $('#cardGridView').removeClass('hidden');
    $('#gridViewBtn').removeClass('bg-white text-slate-600').addClass('bg-indigo-50 text-indigo-600');
    $('#listViewBtn').removeClass('bg-indigo-50 text-indigo-600').addClass('bg-white text-slate-600');
    loadContactsGrid();
}

function switchToListView() {
    currentView = 'list';
    $('#cardGridView').addClass('hidden');
    $('#listTableView').removeClass('hidden');
    $('#listViewBtn').removeClass('bg-white text-slate-600').addClass('bg-indigo-50 text-indigo-600');
    $('#gridViewBtn').removeClass('bg-indigo-50 text-indigo-600').addClass('bg-white text-slate-600');
    if (!$.fn.DataTable.isDataTable('#contactsTable')) {
        initializeDataTable();
    } else {
        table.draw();
    }
}

function loadContactsGrid() {
    $('#cardGridView').html('<div class="col-span-full flex items-center justify-center py-12"><div class="flex flex-col items-center gap-4"><div class="w-12 h-12 rounded-full bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center"><i data-lucide="loader-2" class="h-6 w-6 animate-spin text-indigo-600"></i></div><p class="text-sm font-medium text-slate-600">Loading contacts...</p></div></div>');
    lucide.createIcons();
    
    $.ajax({
        url: "{{ route('contacts.datatable') }}",
        method: 'GET',
        data: {
            search_term: $('#searchInput').val(),
            gender: $('#genderFilter').val(),
            status: $('#statusFilter').val(),
            selection_mode: selectionMode ? 1 : 0,
            json: 1
        },
        success: function(response) {
            let html = '';
            if (response.success && response.contacts && response.contacts.length > 0) {
                response.contacts.forEach(function(contact) {
                    html += renderContactCard(contact);
                });
                $('#cardGridView').html(html);
                lucide.createIcons();
                updateStats();
            } else {
                html = '<div class="col-span-full text-center py-20"><div class="w-24 h-24 rounded-2xl bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center mx-auto mb-6 shadow-sm"><i data-lucide="users" class="h-12 w-12 text-indigo-500"></i></div><h3 class="text-xl font-bold text-slate-900 mb-2">No contacts found</h3><p class="text-slate-500 text-sm mb-6">Get started by adding your first contact</p><button onclick="$(\'#addContactBtn\').click()" class="px-6 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium">Add Your First Contact</button></div>';
                $('#cardGridView').html(html);
                lucide.createIcons();
                updateStats();
            }
        },
        error: function() {
            $('#cardGridView').html('<div class="col-span-full text-center py-12"><p class="text-slate-500">Error loading contacts</p></div>');
        }
    });
}

function renderContactCard(contact) {
    const genderColors = {
        'male': 'bg-blue-50 text-blue-700 border-blue-200',
        'female': 'bg-pink-50 text-pink-700 border-pink-200',
        'other': 'bg-purple-50 text-purple-700 border-purple-200'
    };
    
    let customFieldsHtml = '';
    if (contact.custom_field_values && Object.keys(contact.custom_field_values).length > 0) {
        const fields = Object.entries(contact.custom_field_values).slice(0, 3);
        customFieldsHtml = '<div class="pt-4 border-t border-slate-100"><div class="flex flex-wrap gap-2 mb-2">';
        fields.forEach(([key, value]) => {
            const field = customFields.find(f => f.field_key === key);
            const fieldName = field ? field.field_name : key;
            customFieldsHtml += `<span class="inline-flex items-center px-2.5 py-1 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium text-slate-700"><span class="text-slate-500 mr-1.5">${fieldName}:</span><span class="text-slate-900">${value.length > 20 ? value.substring(0, 20) + '...' : value}</span></span>`;
        });
        customFieldsHtml += '</div>';
        if (Object.keys(contact.custom_field_values).length > 3) {
            customFieldsHtml += `<button class="text-xs font-medium text-indigo-600 hover:text-indigo-700 flex items-center gap-1">+${Object.keys(contact.custom_field_values).length - 3} more<i data-lucide="chevron-down" class="h-3 w-3"></i></button>`;
        }
        customFieldsHtml += '</div>';
    }
    
    const initials = contact.name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2);
    const avatarHtml = contact.profile_image 
        ? `<img src="/storage/${contact.profile_image}" class="h-full w-full object-cover">`
        : `<span class="text-white font-bold text-xl">${initials}</span>`;
    
    return `
        <div class="bg-white rounded-xl border border-slate-200 hover:border-indigo-300 hover:shadow-lg transition-all duration-200 overflow-hidden group ${selectionMode ? 'cursor-pointer' : ''}" 
             data-contact-id="${contact.id}"
             onclick="${selectionMode ? `toggleContactSelection(${contact.id}, '${contact.name.replace(/'/g, "\\'")}')` : ''}">
            <div class="p-6">
                <div class="flex items-start gap-4 mb-4">
                    ${selectionMode ? `<input type="checkbox" class="contact-checkbox h-5 w-5 rounded-md border-2 border-slate-300 text-indigo-600 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-1 cursor-pointer transition-all mt-1" data-id="${contact.id}" onclick="event.stopPropagation(); toggleContactSelection(${contact.id}, '${contact.name.replace(/'/g, "\\'")}')">` : ''}
                    <div class="relative flex-shrink-0">
                        <div class="h-16 w-16 rounded-xl ring-2 ring-slate-100 shadow-sm bg-gradient-to-br from-indigo-500 via-indigo-600 to-purple-600 flex items-center justify-center overflow-hidden group-hover:ring-indigo-200 transition-all">
                            ${avatarHtml}
                        </div>
                        ${contact.gender ? `<div class="absolute -bottom-1 -right-1 p-1.5 rounded-lg border-2 border-white shadow-sm ${genderColors[contact.gender] || ''}"><i data-lucide="user" class="h-3 w-3"></i></div>` : ''}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-2">
                            <div class="flex-1 min-w-0">
                                <h3 class="font-semibold text-slate-900 text-lg leading-tight mb-1.5 truncate">${contact.name}</h3>
                                <div class="flex items-center gap-2 flex-wrap">
                                    ${contact.gender ? `<span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium capitalize shadow-sm ${genderColors[contact.gender] || ''}">${contact.gender}</span>` : ''}
                                    ${contact.status === 'merged' ? '<span class="inline-flex items-center px-2 py-0.5 bg-amber-50 border border-amber-200 rounded-md text-xs font-semibold text-amber-700 shadow-sm"><i data-lucide="git-merge" class="h-3 w-3 mr-1"></i>Merged</span>' : ''}
                                </div>
                            </div>
                            ${!selectionMode ? `<div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button onclick="event.stopPropagation(); viewContact(${contact.id})" class="p-2 hover:bg-indigo-50 rounded-lg transition-all duration-150" title="View"><i data-lucide="eye" class="h-4 w-4 text-slate-500 hover:text-indigo-600"></i></button>
                                <button onclick="event.stopPropagation(); editContact(${contact.id})" class="p-2 hover:bg-indigo-50 rounded-lg transition-all duration-150" title="Edit"><i data-lucide="pencil" class="h-4 w-4 text-slate-500 hover:text-indigo-600"></i></button>
                                <button onclick="event.stopPropagation(); deleteContact(${contact.id}, '${contact.name.replace(/'/g, "\\'")}')" class="p-2 hover:bg-red-50 rounded-lg transition-all duration-150" title="Delete"><i data-lucide="trash-2" class="h-4 w-4 text-slate-500 hover:text-red-600"></i></button>
                            </div>` : ''}
                        </div>
                    </div>
                </div>
                <div class="space-y-2.5 mb-4">
                    <div class="flex items-center gap-2.5">
                        <div class="p-1.5 rounded-lg bg-slate-50"><i data-lucide="mail" class="h-4 w-4 text-slate-500"></i></div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-slate-900 truncate">${contact.email}</p>
                            ${contact.additional_emails && contact.additional_emails.length > 0 ? `<span class="inline-flex items-center px-2 py-0.5 mt-1 bg-indigo-50 text-indigo-700 rounded-md text-xs font-medium">+${contact.additional_emails.length}</span>` : ''}
                        </div>
                    </div>
                    ${contact.phone ? `<div class="flex items-center gap-2.5">
                        <div class="p-1.5 rounded-lg bg-slate-50"><i data-lucide="phone" class="h-4 w-4 text-slate-500"></i></div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-slate-900">${contact.phone}</p>
                            ${contact.additional_phones && contact.additional_phones.length > 0 ? `<span class="inline-flex items-center px-2 py-0.5 mt-1 bg-indigo-50 text-indigo-700 rounded-md text-xs font-medium">+${contact.additional_phones.length}</span>` : ''}
                        </div>
                    </div>` : ''}
                </div>
                ${customFieldsHtml}
            </div>
        </div>
    `;
}

function openContactModal(contact = null) {
    $('#contactForm')[0].reset();
    $('#contactId').val('');
    $('#avatarImage').addClass('hidden');
    $('#avatarInitials').removeClass('hidden').text('CN');
    $('#fileUploadArea').removeClass('hidden');
    $('#filePreview').addClass('hidden');
    clearErrors();
    
    $('.custom-field-input').val('');
    
    if (contact) {
        $('#modalTitle').text('Edit Contact');
        $('#submitBtnText').text('Update Contact');
        $('#contactId').val(contact.id);
        $('#nameInput').val(contact.name);
        $('#emailInput').val(contact.email);
        $('#phoneInput').val(contact.phone || '');
        $(`input[name="gender"][value="${contact.gender}"]`).prop('checked', true);
        
        const initials = contact.name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2);
        $('#avatarInitials').text(initials);
        
        if (contact.profile_image) {
            $('#avatarImage').attr('src', '/storage/' + contact.profile_image).removeClass('hidden');
            $('#avatarInitials').addClass('hidden');
        }
        
        if (contact.additional_file) {
            $('#fileUploadArea').addClass('hidden');
            $('#filePreview').removeClass('hidden');
            $('#fileLink').attr('href', '/storage/' + contact.additional_file);
        }
        
        if (contact.custom_field_values) {
            Object.keys(contact.custom_field_values).forEach(key => {
                $(`#custom_${key}`).val(contact.custom_field_values[key]);
            });
        }
    } else {
        $('#modalTitle').text('Add Contact');
        $('#submitBtnText').text('Create Contact');
    }
    
    $('#contactModal').removeClass('hidden');
    lucide.createIcons();
}

function closeContactModal() {
    $('#contactModal').addClass('hidden');
}

function loadCustomFields() {
    let html = '';
    customFields.forEach(field => {
        if (!field.is_active) return;
        
        let input = '';
        switch (field.field_type) {
            case 'textarea':
                input = `<textarea id="custom_${field.field_key}" name="custom_fields[${field.field_key}]" 
                          class="custom-field-input w-full px-4 py-2.5 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-none" 
                          rows="3" placeholder="Enter ${field.field_name.toLowerCase()}"></textarea>`;
                break;
            case 'select':
                let options = field.options ? field.options.map(opt => `<option value="${opt}">${opt}</option>`).join('') : '';
                input = `<select id="custom_${field.field_key}" name="custom_fields[${field.field_key}]" 
                          class="custom-field-input w-full px-4 py-2.5 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                          <option value="">Select ${field.field_name.toLowerCase()}</option>
                          ${options}
                         </select>`;
                break;
            case 'number':
                input = `<input type="number" id="custom_${field.field_key}" name="custom_fields[${field.field_key}]" 
                          class="custom-field-input w-full px-4 py-2.5 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" 
                          placeholder="Enter ${field.field_name.toLowerCase()}">`;
                break;
            case 'date':
                input = `<input type="date" id="custom_${field.field_key}" name="custom_fields[${field.field_key}]" 
                          class="custom-field-input w-full px-4 py-2.5 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">`;
                break;
            default:
                input = `<input type="text" id="custom_${field.field_key}" name="custom_fields[${field.field_key}]" 
                          class="custom-field-input w-full px-4 py-2.5 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" 
                          placeholder="Enter ${field.field_name.toLowerCase()}">`;
        }
        
        html += `
            <div class="space-y-2">
                <label class="text-sm font-medium text-slate-700">${field.field_name}</label>
                ${input}
            </div>
        `;
    });
    
    $('#customFieldsGrid').html(html);
    
    if (customFields.filter(f => f.is_active).length === 0) {
        $('#customFieldsContainer').addClass('hidden');
    } else {
        $('#customFieldsContainer').removeClass('hidden');
    }
}

function submitContactForm() {
    clearErrors();
    
    let hasErrors = false;
    const name = $('#nameInput').val().trim();
    const email = $('#emailInput').val().trim();
    const phone = $('#phoneInput').val().trim();
    
    if (!name) {
        showFieldError('name', 'Name is required');
        hasErrors = true;
    } else if (name.length < 2) {
        showFieldError('name', 'Name must be at least 2 characters');
        hasErrors = true;
    }
    
    if (!email) {
        showFieldError('email', 'Email is required');
        hasErrors = true;
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        showFieldError('email', 'Please enter a valid email address');
        hasErrors = true;
    }
    
    if (phone && !/^[\d\s\-\+\(\)]{7,20}$/.test(phone)) {
        showFieldError('phone', 'Please enter a valid phone number');
        hasErrors = true;
    }
    
    if (hasErrors) {
        showError('Please fix the errors in the form', 'Some required fields are missing or invalid');
        return;
    }
    
    const formData = new FormData($('#contactForm')[0]);
    
    const customFieldValues = {};
    $('.custom-field-input').each(function() {
        const key = $(this).attr('id').replace('custom_', '');
        const value = $(this).val();
        if (value) {
            customFieldValues[key] = value;
        }
    });
    formData.append('custom_field_values', JSON.stringify(customFieldValues));
    
    $('#submitBtn').prop('disabled', true);
    $('#submitBtnLoader').removeClass('hidden');
    
    const contactId = $('#contactId').val();
    const url = contactId ? `/contacts/${contactId}` : "{{ route('contacts.store') }}";
    const method = contactId ? 'POST' : 'POST';
    
    if (contactId) {
        formData.append('_method', 'PUT');
    }
    
    $.ajax({
        url: url,
        method: method,
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.success) {
                showSuccess(contactId ? 'Contact Updated Successfully! ✅' : 'Contact Created Successfully! 🎉', 
                           contactId ? 'Your changes have been saved.' : 'The new contact has been added to your database.');
                closeContactModal();
                if (currentView === 'grid') {
                    loadContactsGrid();
                } else {
                    table.draw();
                }
            }
        },
        error: function(xhr) {
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;
                Object.keys(errors).forEach(field => {
                    showFieldError(field, errors[field][0]);
                });
                showError('Please fix the errors in the form', 'Some required fields are missing or invalid');
            } else {
                showError('Something went wrong', 'Please try again later.');
            }
        },
        complete: function() {
            $('#submitBtn').prop('disabled', false);
            $('#submitBtnLoader').addClass('hidden');
        }
    });
}

function showFieldError(field, message) {
    $(`#${field}Input`).addClass('border-red-500 focus:ring-red-500');
    $(`#${field}Error span`).text(message);
    $(`#${field}Error`).removeClass('hidden');
}

function clearErrors() {
    $('#nameInput, #emailInput, #phoneInput').removeClass('border-red-500 focus:ring-red-500');
    $('#nameError, #emailError, #phoneError').addClass('hidden');
}

function removeFile() {
    $('#additionalFileInput').val('');
    $('#fileUploadArea').removeClass('hidden');
    $('#filePreview').addClass('hidden');
}

function viewContact(id) {
    $.get(`/contacts/${id}`, function(response) {
        if (response.success) {
            const contact = response.contact;
            const customFields = response.customFields;
            
            let customFieldsHtml = '';
            if (contact.custom_field_values && Object.keys(contact.custom_field_values).length > 0) {
                customFieldsHtml = '<div class="mt-4 grid grid-cols-2 gap-3">';
                Object.keys(contact.custom_field_values).forEach(key => {
                    const field = customFields.find(f => f.field_key === key);
                    const label = field ? field.field_name : key;
                    customFieldsHtml += `
                        <div class="p-3 bg-slate-50 rounded-lg">
                            <p class="text-xs text-slate-500">${label}</p>
                            <p class="font-medium text-slate-900 mt-0.5">${contact.custom_field_values[key]}</p>
                        </div>
                    `;
                });
                customFieldsHtml += '</div>';
            }
            
            const genderColors = {
                male: 'bg-blue-50 text-blue-700 border-blue-200',
                female: 'bg-pink-50 text-pink-700 border-pink-200',
                other: 'bg-purple-50 text-purple-700 border-purple-200'
            };
            
            const initials = contact.name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2);
            
            const html = `
                <div class="p-6">
                    <div class="flex items-start gap-4">
                        <div class="h-20 w-20 rounded-full ring-4 ring-slate-100 bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center overflow-hidden">
                            ${contact.profile_image 
                                ? `<img src="/storage/${contact.profile_image}" class="h-full w-full object-cover">` 
                                : `<span class="text-white text-2xl font-semibold">${initials}</span>`}
                        </div>
                        <div class="flex-1">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h2 class="text-xl font-semibold text-slate-900">${contact.name}</h2>
                                    <div class="flex items-center gap-2 mt-1">
                                        ${contact.gender ? `<span class="px-2 py-1 rounded-full text-xs font-medium border capitalize ${genderColors[contact.gender] || ''}">${contact.gender}</span>` : ''}
                                        ${contact.status === 'merged' ? '<span class="px-2 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700">Merged</span>' : ''}
                                    </div>
                                </div>
                                <button onclick="closeViewModal(); openContactModal(${JSON.stringify(contact).replace(/"/g, '&quot;')})" class="flex items-center gap-1 px-3 py-1.5 border border-slate-200 rounded-lg text-sm hover:bg-slate-50">
                                    <i data-lucide="pencil" class="h-4 w-4"></i>
                                    Edit
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <hr class="my-6">
                    
                    <div class="space-y-4">
                        <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wide">Contact Information</h3>
                        
                        <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-lg">
                            <i data-lucide="mail" class="h-5 w-5 text-slate-400"></i>
                            <div>
                                <p class="text-sm text-slate-500">Email</p>
                                <p class="font-medium text-slate-900">${contact.email}</p>
                            </div>
                        </div>
                        
                        ${contact.additional_emails && contact.additional_emails.length > 0 ? `
                            <div class="ml-8 space-y-1">
                                <p class="text-xs text-slate-500">Additional emails:</p>
                                ${contact.additional_emails.map(e => `<span class="inline-block px-2 py-1 mr-1 mb-1 border border-slate-200 rounded-lg text-xs">${e}</span>`).join('')}
                            </div>
                        ` : ''}
                        
                        ${contact.phone ? `
                            <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-lg">
                                <i data-lucide="phone" class="h-5 w-5 text-slate-400"></i>
                                <div>
                                    <p class="text-sm text-slate-500">Phone</p>
                                    <p class="font-medium text-slate-900">${contact.phone}</p>
                                </div>
                            </div>
                        ` : ''}
                        
                        ${contact.additional_phones && contact.additional_phones.length > 0 ? `
                            <div class="ml-8 space-y-1">
                                <p class="text-xs text-slate-500">Additional phones:</p>
                                ${contact.additional_phones.map(p => `<span class="inline-block px-2 py-1 mr-1 mb-1 border border-slate-200 rounded-lg text-xs">${p}</span>`).join('')}
                            </div>
                        ` : ''}
                    </div>
                    
                    ${contact.additional_file ? `
                        <hr class="my-6">
                        <div class="space-y-3">
                            <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wide">Documents</h3>
                            <a href="/storage/${contact.additional_file}" target="_blank" class="flex items-center gap-3 p-3 bg-slate-50 rounded-lg hover:bg-slate-100 transition-colors">
                                <i data-lucide="file-text" class="h-5 w-5 text-indigo-500"></i>
                                <span class="flex-1 text-sm font-medium text-slate-700">View Document</span>
                                <i data-lucide="external-link" class="h-4 w-4 text-slate-400"></i>
                            </a>
                        </div>
                    ` : ''}
                    
                    ${customFieldsHtml ? `
                        <hr class="my-6">
                        <div class="space-y-3">
                            <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wide">Additional Information</h3>
                            ${customFieldsHtml}
                        </div>
                    ` : ''}
                </div>
            `;
            
            $('#viewModalContent').html(html);
            $('#viewModal').removeClass('hidden');
            lucide.createIcons();
        }
    });
}

function closeViewModal() {
    $('#viewModal').addClass('hidden');
}

function editContact(id) {
    $.get(`/contacts/${id}`, function(response) {
        if (response.success) {
            openContactModal(response.contact);
        }
    });
}

function deleteContact(id, name) {
    Swal.fire({
        title: 'Delete Contact',
        text: `Are you sure you want to delete "${name}"? This action cannot be undone.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#DC2626',
        cancelButtonColor: '#64748B',
        confirmButtonText: 'Delete',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/contacts/${id}`,
                method: 'DELETE',
                success: function(response) {
                    if (response.success) {
                        showSuccess('Contact Deleted! 🗑️', 'The contact has been removed from your database.');
                        if (currentView === 'grid') {
                            loadContactsGrid();
                        } else {
                            table.draw();
                        }
                    }
                },
                error: function() {
                    showError('Failed to delete contact', 'Please try again later.');
                }
            });
        }
    });
}

function openMergeModal() {
    renderMergeStep1();
}

function renderMergeStep1() {
    const html = `
        <div class="p-6">
            <div class="flex items-center gap-2 mb-6">
                <i data-lucide="git-merge" class="h-5 w-5 text-indigo-600"></i>
                <h2 class="text-xl font-semibold text-slate-900">Merge Contacts</h2>
            </div>
            
            <p class="text-slate-600 mb-6">Select the master contact that will remain after the merge.</p>
            
            <!-- Steps -->
            <div class="flex items-center gap-2 mb-6">
                <div class="flex-1 flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center text-sm font-medium">1</div>
                    <span class="text-sm font-medium text-indigo-600">Select Master</span>
                </div>
                <i data-lucide="arrow-right" class="h-4 w-4 text-slate-400"></i>
                <div class="flex-1 flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center text-sm font-medium">2</div>
                    <span class="text-sm text-slate-500">Review & Confirm</span>
                </div>
            </div>
            
            <!-- Warning -->
            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mb-6">
                <div class="flex items-start gap-3">
                    <i data-lucide="alert-triangle" class="h-5 w-5 text-amber-600 flex-shrink-0 mt-0.5"></i>
                    <div>
                        <p class="font-medium text-amber-900">Choose carefully</p>
                        <p class="text-sm text-amber-700 mt-1">The master contact will be the primary record. Data from the secondary contact will be added to it.</p>
                    </div>
                </div>
            </div>
            
            <!-- Contact Selection -->
            <div class="space-y-3 mb-6">
                ${selectedContacts.map((contact, index) => `
                    <label class="flex items-start gap-4 p-4 border-2 rounded-lg cursor-pointer hover:bg-slate-50 transition-colors ${index === 0 ? 'border-indigo-500 bg-indigo-50' : 'border-slate-200'}">
                        <input type="radio" name="master_contact" value="${contact.id}" class="mt-1 w-4 h-4 text-indigo-600" ${index === 0 ? 'checked' : ''}>
                        <div class="flex-1">
                            <div class="flex items-center gap-3">
                                <div class="h-12 w-12 rounded-full ring-2 ring-slate-100 bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center overflow-hidden">
                                    <span class="text-white font-semibold">${contact.name.substring(0, 2).toUpperCase()}</span>
                                </div>
                                <div class="flex-1">
                                    <p class="font-medium text-slate-900">${contact.name}</p>
                                    <p class="text-sm text-slate-500">Contact #${contact.id}</p>
                                </div>
                            </div>
                        </div>
                    </label>
                `).join('')}
            </div>
            
            <div class="flex justify-end gap-3 pt-4 border-t">
                <button onclick="closeMergeModal()" class="px-4 py-2 border border-slate-200 rounded-lg text-slate-600 hover:bg-slate-50">
                    Cancel
                </button>
                <button onclick="proceedToStep2()" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 flex items-center gap-2">
                    Continue
                    <i data-lucide="arrow-right" class="h-4 w-4"></i>
                </button>
            </div>
        </div>
    `;
    
    $('#mergeModalContent').html(html);
    $('#mergeModal').removeClass('hidden');
    lucide.createIcons();
}

function proceedToStep2() {
    const masterId = $('input[name="master_contact"]:checked').val();
    if (!masterId) {
        showWarning('Please select a master contact', 'You must choose which contact will remain after the merge.');
        return;
    }
    
    const secondaryId = selectedContacts.find(c => c.id != masterId).id;
    
    $.post("{{ route('contacts.merge-preview') }}", {
        master_id: masterId,
        secondary_id: secondaryId
    }, function(response) {
        if (response.success) {
            renderMergeStep2(response);
        }
    });
}

function renderMergeStep2(data) {
    const master = data.master;
    const secondary = data.secondary;
    const fieldsToAdd = data.fieldsToAdd;
    const conflicts = data.conflicts;
    
    const getInitials = (name) => name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2);
    
    let fieldsToAddHtml = '';
    if (fieldsToAdd.length > 0) {
        fieldsToAddHtml = `
            <div class="space-y-3">
                <h4 class="font-medium text-slate-800 flex items-center gap-2">
                    <i data-lucide="check-circle-2" class="h-4 w-4 text-green-600"></i>
                    Data to be added to master
                </h4>
                <div class="bg-green-50 border border-green-200 rounded-lg divide-y divide-green-200">
                    ${fieldsToAdd.map(item => `
                        <div class="p-3 flex items-center justify-between">
                            <span class="text-sm text-slate-600">${item.field}</span>
                            <span class="px-2 py-1 bg-white border border-green-200 rounded text-sm text-green-700">+ ${item.value}</span>
                        </div>
                    `).join('')}
                </div>
            </div>
        `;
    }
    
    let conflictsHtml = '';
    if (conflicts.length > 0) {
        conflictsHtml = `
            <div class="space-y-3">
                <h4 class="font-medium text-slate-800 flex items-center gap-2">
                    <i data-lucide="alert-triangle" class="h-4 w-4 text-amber-600"></i>
                    Conflicting values (master value will be kept)
                </h4>
                <div class="bg-amber-50 border border-amber-200 rounded-lg divide-y divide-amber-200">
                    ${conflicts.map(item => `
                        <div class="p-3">
                            <span class="text-sm font-medium text-slate-700">${item.field}</span>
                            <div class="mt-2 grid grid-cols-2 gap-2 text-xs">
                                <div class="p-2 bg-white rounded border border-amber-200">
                                    <span class="text-slate-500">Master:</span>
                                    <span class="ml-1 font-medium">${item.masterValue}</span>
                                </div>
                                <div class="p-2 bg-white rounded border border-amber-200 opacity-60">
                                    <span class="text-slate-500">Secondary:</span>
                                    <span class="ml-1 line-through">${item.secondaryValue}</span>
                                </div>
                            </div>
                        </div>
                    `).join('')}
                </div>
            </div>
        `;
    }
    
    const html = `
        <div class="p-6">
            <div class="flex items-center gap-2 mb-6">
                <i data-lucide="git-merge" class="h-5 w-5 text-indigo-600"></i>
                <h2 class="text-xl font-semibold text-slate-900">Merge Contacts</h2>
            </div>
            
            <p class="text-slate-600 mb-6">Review what data will be merged</p>
            
            <!-- Steps -->
            <div class="flex items-center gap-2 mb-6">
                <div class="flex-1 flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center text-sm font-medium">1</div>
                    <span class="text-sm text-slate-500">Select Master</span>
                </div>
                <i data-lucide="arrow-right" class="h-4 w-4 text-slate-400"></i>
                <div class="flex-1 flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center text-sm font-medium">2</div>
                    <span class="text-sm font-medium text-indigo-600">Review & Confirm</span>
                </div>
            </div>
            
            <!-- Visual representation -->
            <div class="flex items-center justify-center gap-4 py-6 bg-slate-50 rounded-xl mb-6">
                <div class="text-center">
                    <div class="h-16 w-16 mx-auto rounded-full ring-4 ring-slate-100 bg-slate-200 flex items-center justify-center overflow-hidden">
                        ${secondary.profile_image 
                            ? `<img src="/storage/${secondary.profile_image}" class="h-full w-full object-cover">` 
                            : `<span class="text-slate-600 font-semibold">${getInitials(secondary.name)}</span>`}
                    </div>
                    <p class="mt-2 text-sm font-medium text-slate-600">${secondary.name}</p>
                    <span class="px-2 py-1 bg-slate-200 text-slate-600 rounded text-xs">Secondary</span>
                </div>
                <i data-lucide="arrow-right" class="h-8 w-8 text-indigo-500"></i>
                <div class="text-center">
                    <div class="h-16 w-16 mx-auto rounded-full ring-4 ring-indigo-500 bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center overflow-hidden">
                        ${master.profile_image 
                            ? `<img src="/storage/${master.profile_image}" class="h-full w-full object-cover">` 
                            : `<span class="text-white font-semibold">${getInitials(master.name)}</span>`}
                    </div>
                    <p class="mt-2 text-sm font-medium text-slate-900">${master.name}</p>
                    <span class="px-2 py-1 bg-indigo-100 text-indigo-700 rounded text-xs flex items-center gap-1 justify-center">
                        <i data-lucide="crown" class="h-3 w-3"></i>
                        Master
                    </span>
                </div>
            </div>
            
            <div class="space-y-6">
                ${fieldsToAddHtml}
                ${conflictsHtml}
                
                ${fieldsToAdd.length === 0 && conflicts.length === 0 ? `
                    <div class="text-center py-8 text-slate-500">
                        <i data-lucide="check-circle-2" class="h-12 w-12 mx-auto text-green-500 mb-3"></i>
                        <p>No additional data to merge. The secondary contact will be marked as merged.</p>
                    </div>
                ` : ''}
                
                <div class="bg-slate-50 border border-slate-200 rounded-lg p-4 text-sm text-slate-600">
                    <p class="font-medium text-slate-700 mb-2">What will happen:</p>
                    <ul class="space-y-1 list-disc list-inside">
                        <li>Master contact will receive all additional data</li>
                        <li>Secondary contact will be marked as "merged" (not deleted)</li>
                        <li>A merge history record will be created for tracking</li>
                        <li>No data will be permanently lost</li>
                    </ul>
                </div>
            </div>
            
            <div class="flex justify-end gap-3 mt-6 pt-4 border-t">
                <button onclick="renderMergeStep1()" class="px-4 py-2 border border-slate-200 rounded-lg text-slate-600 hover:bg-slate-50">
                    Back
                </button>
                <button onclick="executeMerge(${master.id}, ${secondary.id})" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 flex items-center gap-2">
                    <i data-lucide="git-merge" class="h-4 w-4"></i>
                    Merge Contacts
                </button>
            </div>
        </div>
    `;
    
    $('#mergeModalContent').html(html);
    lucide.createIcons();
}

function closeMergeModal() {
    $('#mergeModal').addClass('hidden');
}

function executeMerge(masterId, secondaryId) {
    Swal.fire({
        title: 'Confirm Merge',
        text: 'Are you sure you want to merge these contacts? This action will combine the data and mark the secondary contact as merged.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#4F46E5',
        cancelButtonColor: '#64748B',
        confirmButtonText: 'Yes, Merge Contacts',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post("{{ route('contacts.merge') }}", {
                master_id: masterId,
                secondary_id: secondaryId
            }, function(response) {
                if (response.success) {
                    showInfo('Contacts Merged Successfully! 🔗', response.message);
                    closeMergeModal();
                    toggleSelectionMode(false);
                    if (currentView === 'grid') {
                        loadContactsGrid();
                    } else {
                        table.draw();
                    }
                }
            }).fail(function() {
                showError('Failed to merge contacts', 'Please try again later.');
            });
        }
    });
}
</script>
@endpush

