<div class="flex items-center gap-4 py-2">
    @if(isset($selectionMode) && $selectionMode)
        <input type="checkbox" 
               class="contact-checkbox h-5 w-5 rounded-md border-2 border-slate-300 text-indigo-600 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-1 cursor-pointer transition-all" 
               data-id="{{ $contact->id }}"
               onclick="toggleContactSelection({{ $contact->id }}, '{{ addslashes($contact->name) }}')">
    @endif
    <div class="relative flex-shrink-0">
        <div class="h-14 w-14 rounded-xl ring-2 ring-slate-100 shadow-sm bg-gradient-to-br from-indigo-500 via-indigo-600 to-purple-600 flex items-center justify-center overflow-hidden group-hover:ring-indigo-200 transition-all">
            @if($contact->profile_image)
                <img src="{{ asset('storage/' . $contact->profile_image) }}" alt="{{ $contact->name }}" class="h-full w-full object-cover">
            @else
                <span class="text-white font-bold text-lg">{{ $contact->initials }}</span>
            @endif
        </div>
        @if($contact->gender)
            @php
                $genderColors = [
                    'male' => 'bg-blue-50 text-blue-700 border-blue-200',
                    'female' => 'bg-pink-50 text-pink-700 border-pink-200',
                    'other' => 'bg-purple-50 text-purple-700 border-purple-200'
                ];
            @endphp
            <div class="absolute -bottom-0.5 -right-0.5 p-1.5 rounded-lg border-2 border-white shadow-sm {{ $genderColors[$contact->gender] ?? '' }}">
                <i data-lucide="user" class="h-3 w-3"></i>
            </div>
        @endif
    </div>
    <div class="min-w-0 flex-1">
        <div class="flex items-center gap-2 mb-1.5">
            <p class="font-semibold text-slate-900 text-base leading-tight">{{ $contact->name }}</p>
            @if($contact->status === 'merged')
                <span class="inline-flex items-center px-2 py-0.5 bg-amber-50 border border-amber-200 rounded-md text-xs font-semibold text-amber-700 shadow-sm">
                    <i data-lucide="git-merge" class="h-3 w-3 mr-1"></i>
                    Merged
                </span>
            @endif
        </div>
        @if($contact->gender)
            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium capitalize shadow-sm {{ $genderColors[$contact->gender] ?? '' }}">
                {{ $contact->gender }}
            </span>
        @endif
    </div>
</div>

