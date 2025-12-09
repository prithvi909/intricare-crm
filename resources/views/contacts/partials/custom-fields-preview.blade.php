@if($contact->custom_field_values && count($contact->custom_field_values) > 0)
    <div class="flex flex-wrap gap-2 py-2">
        @foreach(array_slice($contact->custom_field_values, 0, 3, true) as $key => $value)
            @php
                $field = $customFields->firstWhere('field_key', $key);
                $fieldName = $field ? $field->field_name : $key;
            @endphp
            <span class="inline-flex items-center px-2.5 py-1.5 bg-gradient-to-br from-slate-50 to-slate-100 border border-slate-200/60 rounded-lg text-xs font-medium text-slate-700 shadow-sm hover:shadow transition-shadow">
                <span class="font-semibold text-slate-500 mr-1.5">{{ $fieldName }}:</span>
                <span class="text-slate-900">{{ Str::limit($value, 18) }}</span>
            </span>
        @endforeach
        @if(count($contact->custom_field_values) > 3)
            <span class="inline-flex items-center px-2.5 py-1.5 bg-indigo-50 border border-indigo-200 rounded-lg text-xs font-semibold text-indigo-700 shadow-sm">
                +{{ count($contact->custom_field_values) - 3 }} more
            </span>
        @endif
    </div>
@else
    <div class="py-2">
        <span class="inline-flex items-center px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-400 italic">
            No custom fields
        </span>
    </div>
@endif

