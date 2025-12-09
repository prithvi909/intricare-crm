@php
    $genderColors = [
        'male' => 'bg-blue-50 text-blue-700 border-blue-200',
        'female' => 'bg-pink-50 text-pink-700 border-pink-200',
        'other' => 'bg-purple-50 text-purple-700 border-purple-200'
    ];
@endphp
<div class="bg-white rounded-xl border border-slate-200 hover:border-indigo-300 hover:shadow-lg transition-all duration-200 overflow-hidden group {{ isset($selectionMode) && $selectionMode ? 'cursor-pointer' : '' }}" 
     data-contact-id="{{ $contact->id }}"
     onclick="{{ isset($selectionMode) && $selectionMode ? 'toggleContactSelection(' . $contact->id . ', \'' . addslashes($contact->name) . '\')' : '' }}">
    <div class="p-6">
        <!-- Header with Avatar and Checkbox -->
        <div class="flex items-start gap-4 mb-4">
            @if(isset($selectionMode) && $selectionMode)
                <input type="checkbox" 
                       class="contact-checkbox h-5 w-5 rounded-md border-2 border-slate-300 text-indigo-600 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-1 cursor-pointer transition-all mt-1" 
                       data-id="{{ $contact->id }}"
                       onclick="event.stopPropagation(); toggleContactSelection({{ $contact->id }}, '{{ addslashes($contact->name) }}')">
            @endif
            <div class="relative flex-shrink-0">
                <div class="h-16 w-16 rounded-xl ring-2 ring-slate-100 shadow-sm bg-gradient-to-br from-indigo-500 via-indigo-600 to-purple-600 flex items-center justify-center overflow-hidden group-hover:ring-indigo-200 transition-all">
                    @if($contact->profile_image)
                        <img src="{{ asset('storage/' . $contact->profile_image) }}" alt="{{ $contact->name }}" class="h-full w-full object-cover">
                    @else
                        <span class="text-white font-bold text-xl">{{ $contact->initials }}</span>
                    @endif
                </div>
                @if($contact->gender)
                    <div class="absolute -bottom-1 -right-1 p-1.5 rounded-lg border-2 border-white shadow-sm {{ $genderColors[$contact->gender] ?? '' }}">
                        <i data-lucide="user" class="h-3 w-3"></i>
                    </div>
                @endif
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-start justify-between gap-2">
                    <div class="flex-1 min-w-0">
                        <h3 class="font-semibold text-slate-900 text-lg leading-tight mb-1.5 truncate">{{ $contact->name }}</h3>
                        <div class="flex items-center gap-2 flex-wrap">
                            @if($contact->gender)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium capitalize shadow-sm {{ $genderColors[$contact->gender] ?? '' }}">
                                    {{ $contact->gender }}
                                </span>
                            @endif
                            @if($contact->status === 'merged')
                                <span class="inline-flex items-center px-2 py-0.5 bg-amber-50 border border-amber-200 rounded-md text-xs font-semibold text-amber-700 shadow-sm">
                                    <i data-lucide="git-merge" class="h-3 w-3 mr-1"></i>
                                    Merged
                                </span>
                            @endif
                        </div>
                    </div>
                    @if(!isset($selectionMode) || !$selectionMode)
                        <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button onclick="event.stopPropagation(); viewContact({{ $contact->id }})" 
                                    class="p-2 hover:bg-indigo-50 rounded-lg transition-all duration-150" title="View">
                                <i data-lucide="eye" class="h-4 w-4 text-slate-500 hover:text-indigo-600"></i>
                            </button>
                            <button onclick="event.stopPropagation(); editContact({{ $contact->id }})" 
                                    class="p-2 hover:bg-indigo-50 rounded-lg transition-all duration-150" title="Edit">
                                <i data-lucide="pencil" class="h-4 w-4 text-slate-500 hover:text-indigo-600"></i>
                            </button>
                            <button onclick="event.stopPropagation(); deleteContact({{ $contact->id }}, '{{ addslashes($contact->name) }}')" 
                                    class="p-2 hover:bg-red-50 rounded-lg transition-all duration-150" title="Delete">
                                <i data-lucide="trash-2" class="h-4 w-4 text-slate-500 hover:text-red-600"></i>
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Contact Info -->
        <div class="space-y-2.5 mb-4">
            <div class="flex items-center gap-2.5">
                <div class="p-1.5 rounded-lg bg-slate-50">
                    <i data-lucide="mail" class="h-4 w-4 text-slate-500"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-slate-900 truncate">{{ $contact->email }}</p>
                    @if($contact->additional_emails && count($contact->additional_emails) > 0)
                        <span class="inline-flex items-center px-2 py-0.5 mt-1 bg-indigo-50 text-indigo-700 rounded-md text-xs font-medium">
                            +{{ count($contact->additional_emails) }}
                        </span>
                    @endif
                </div>
            </div>
            @if($contact->phone)
                <div class="flex items-center gap-2.5">
                    <div class="p-1.5 rounded-lg bg-slate-50">
                        <i data-lucide="phone" class="h-4 w-4 text-slate-500"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-slate-900">{{ $contact->phone }}</p>
                        @if($contact->additional_phones && count($contact->additional_phones) > 0)
                            <span class="inline-flex items-center px-2 py-0.5 mt-1 bg-indigo-50 text-indigo-700 rounded-md text-xs font-medium">
                                +{{ count($contact->additional_phones) }}
                            </span>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Custom Fields -->
        @if($contact->custom_field_values && count($contact->custom_field_values) > 0)
            <div class="pt-4 border-t border-slate-100">
                <div class="flex flex-wrap gap-2 mb-2">
                    @foreach(array_slice($contact->custom_field_values, 0, 3, true) as $key => $value)
                        @php
                            $field = $customFields->firstWhere('field_key', $key);
                            $fieldName = $field ? $field->field_name : $key;
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-1 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium text-slate-700">
                            <span class="text-slate-500 mr-1.5">{{ $fieldName }}:</span>
                            <span class="text-slate-900">{{ Str::limit($value, 20) }}</span>
                        </span>
                    @endforeach
                </div>
                @if(count($contact->custom_field_values) > 3)
                    <button class="text-xs font-medium text-indigo-600 hover:text-indigo-700 flex items-center gap-1">
                        +{{ count($contact->custom_field_values) - 3 }} more
                        <i data-lucide="chevron-down" class="h-3 w-3"></i>
                    </button>
                @endif
            </div>
        @endif
    </div>
</div>



