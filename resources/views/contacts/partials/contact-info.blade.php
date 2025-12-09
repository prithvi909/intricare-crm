<div class="space-y-2.5 py-2">
    <div class="flex items-center gap-2.5 group">
        <div class="p-1.5 rounded-lg bg-slate-50 group-hover:bg-indigo-50 transition-colors">
            <i data-lucide="mail" class="h-4 w-4 text-slate-500 group-hover:text-indigo-600 transition-colors"></i>
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-sm font-medium text-slate-900 truncate">{{ $contact->email }}</p>
            @if($contact->additional_emails && count($contact->additional_emails) > 0)
                <span class="inline-flex items-center px-2 py-0.5 mt-1 bg-indigo-50 text-indigo-700 rounded-md text-xs font-medium">
                    +{{ count($contact->additional_emails) }} more
                </span>
            @endif
        </div>
    </div>
    @if($contact->phone)
        <div class="flex items-center gap-2.5 group">
            <div class="p-1.5 rounded-lg bg-slate-50 group-hover:bg-indigo-50 transition-colors">
                <i data-lucide="phone" class="h-4 w-4 text-slate-500 group-hover:text-indigo-600 transition-colors"></i>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-slate-900">{{ $contact->phone }}</p>
                @if($contact->additional_phones && count($contact->additional_phones) > 0)
                    <span class="inline-flex items-center px-2 py-0.5 mt-1 bg-indigo-50 text-indigo-700 rounded-md text-xs font-medium">
                        +{{ count($contact->additional_phones) }} more
                    </span>
                @endif
            </div>
        </div>
    @endif
</div>

