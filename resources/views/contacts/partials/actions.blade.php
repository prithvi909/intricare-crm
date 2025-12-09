<div class="flex items-center justify-end gap-1.5 py-2">
    <button onclick="viewContact({{ $contact->id }})" 
            class="p-2.5 hover:bg-indigo-50 rounded-lg transition-all duration-150 group" title="View">
        <i data-lucide="eye" class="h-4 w-4 text-slate-500 group-hover:text-indigo-600 transition-colors"></i>
    </button>
    <button onclick="editContact({{ $contact->id }})" 
            class="p-2.5 hover:bg-indigo-50 rounded-lg transition-all duration-150 group" title="Edit">
        <i data-lucide="pencil" class="h-4 w-4 text-slate-500 group-hover:text-indigo-600 transition-colors"></i>
    </button>
    <button onclick="deleteContact({{ $contact->id }}, '{{ addslashes($contact->name) }}')" 
            class="p-2.5 hover:bg-red-50 rounded-lg transition-all duration-150 group" title="Delete">
        <i data-lucide="trash-2" class="h-4 w-4 text-slate-500 group-hover:text-red-600 transition-colors"></i>
    </button>
</div>

