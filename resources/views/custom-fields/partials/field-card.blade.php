@php
    $typeIcons = [
        'text' => 'type',
        'number' => 'hash',
        'date' => 'calendar',
        'textarea' => 'align-left',
        'select' => 'list'
    ];
    $typeLabels = [
        'text' => 'Text',
        'number' => 'Number',
        'date' => 'Date',
        'textarea' => 'Long Text',
        'select' => 'Dropdown'
    ];
@endphp
<div class="bg-white rounded-xl border border-slate-200 p-4 {{ !$field->is_active ? 'opacity-60 bg-slate-50' : '' }}">
    <div class="flex items-center gap-4">
        <div class="p-2 bg-indigo-50 rounded-lg">
            <i data-lucide="{{ $typeIcons[$field->field_type] ?? 'type' }}" class="h-5 w-5 text-indigo-600"></i>
        </div>
        <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2">
                <h3 class="font-medium text-slate-900">{{ $field->field_name }}</h3>
                <span class="px-2 py-0.5 bg-slate-100 text-slate-600 rounded text-xs">
                    {{ $typeLabels[$field->field_type] ?? 'Text' }}
                </span>
                @if(!$field->is_active)
                    <span class="px-2 py-0.5 bg-slate-200 text-slate-600 rounded text-xs">Inactive</span>
                @endif
            </div>
            <p class="text-sm text-slate-500 mt-0.5">
                Key: {{ $field->field_key }}
                @if($field->field_type === 'select' && $field->options)
                    • {{ count($field->options) }} options
                @endif
            </p>
        </div>
        <div class="flex items-center gap-2">
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" class="sr-only peer" {{ $field->is_active ? 'checked' : '' }} 
                       onchange="toggleFieldActive({{ $field->id }})">
                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
            </label>
            <button onclick='editField(@json($field))' class="p-2 hover:bg-slate-100 rounded-lg">
                <i data-lucide="pencil" class="h-4 w-4 text-slate-500"></i>
            </button>
            <button onclick="deleteField({{ $field->id }}, '{{ addslashes($field->field_name) }}')" class="p-2 hover:bg-red-50 rounded-lg">
                <i data-lucide="trash-2" class="h-4 w-4 text-red-500"></i>
            </button>
        </div>
    </div>
</div>



