@extends('layouts.crm')

@section('title', 'Merge History')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-indigo-50/30">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight flex items-center gap-3">
                <i data-lucide="history" class="h-8 w-8 text-indigo-600"></i>
                Merge History
            </h1>
            <p class="text-slate-500 mt-1">Track all contact merges and view merged data</p>
        </div>

        <!-- History List -->
        @forelse($history as $record)
            <div class="bg-white rounded-xl border border-slate-200 mb-4 overflow-hidden">
                <details class="group">
                    <summary class="flex items-center gap-4 p-4 cursor-pointer hover:bg-slate-50">
                        <!-- Visual -->
                        <div class="flex items-center gap-2">
                            <div class="h-10 w-10 rounded-full ring-2 ring-slate-100 bg-slate-200 flex items-center justify-center">
                                <span class="text-slate-600 text-sm font-medium">
                                    {{ substr($record->merged_contact_name, 0, 1) }}
                                </span>
                            </div>
                            <i data-lucide="arrow-right" class="h-4 w-4 text-indigo-500"></i>
                            <div class="h-10 w-10 rounded-full ring-2 ring-indigo-200 bg-indigo-100 flex items-center justify-center">
                                <span class="text-indigo-700 text-sm font-medium">
                                    {{ substr($record->master_contact_name, 0, 1) }}
                                </span>
                            </div>
                        </div>
                        <!-- Info -->
                        <div class="flex-1">
                            <p class="font-medium text-slate-900">
                                {{ $record->merged_contact_name }} → {{ $record->master_contact_name }}
                            </p>
                            <div class="flex items-center gap-3 mt-1 text-sm text-slate-500">
                                <span class="flex items-center gap-1">
                                    <i data-lucide="calendar" class="h-3.5 w-3.5"></i>
                                    {{ $record->created_at->format('M d, Y h:i A') }}
                                </span>
                                @if($record->fields_added && count($record->fields_added) > 0)
                                    <span class="px-2 py-0.5 bg-slate-100 rounded text-xs">
                                        {{ count($record->fields_added) }} fields added
                                    </span>
                                @endif
                            </div>
                        </div>
                        <i data-lucide="chevron-down" class="h-5 w-5 text-slate-400 transition-transform group-open:rotate-180"></i>
                    </summary>
                    <div class="px-4 pb-4 border-t border-slate-100">
                        <div class="pt-4">
                            <h4 class="font-medium text-slate-700 mb-3 flex items-center gap-2">
                                <i data-lucide="file-text" class="h-4 w-4"></i>
                                Merged Contact Data (Preserved)
                            </h4>
                            
                            @if($record->merged_data)
                                <div class="grid grid-cols-2 gap-3">
                                    @if(isset($record->merged_data['name']))
                                        <div class="p-3 bg-slate-50 rounded-lg">
                                            <p class="text-xs text-slate-500">Name</p>
                                            <p class="font-medium text-slate-900">{{ $record->merged_data['name'] }}</p>
                                        </div>
                                    @endif
                                    @if(isset($record->merged_data['email']))
                                        <div class="p-3 bg-slate-50 rounded-lg">
                                            <p class="text-xs text-slate-500">Email</p>
                                            <p class="font-medium text-slate-900">{{ $record->merged_data['email'] }}</p>
                                        </div>
                                    @endif
                                    @if(isset($record->merged_data['phone']))
                                        <div class="p-3 bg-slate-50 rounded-lg">
                                            <p class="text-xs text-slate-500">Phone</p>
                                            <p class="font-medium text-slate-900">{{ $record->merged_data['phone'] }}</p>
                                        </div>
                                    @endif
                                    @if(isset($record->merged_data['gender']))
                                        <div class="p-3 bg-slate-50 rounded-lg">
                                            <p class="text-xs text-slate-500">Gender</p>
                                            <p class="font-medium text-slate-900 capitalize">{{ $record->merged_data['gender'] }}</p>
                                        </div>
                                    @endif
                                    @if(isset($record->merged_data['custom_field_values']) && is_array($record->merged_data['custom_field_values']))
                                        @foreach($record->merged_data['custom_field_values'] as $key => $value)
                                            <div class="p-3 bg-slate-50 rounded-lg">
                                                <p class="text-xs text-slate-500 capitalize">{{ str_replace('_', ' ', $key) }}</p>
                                                <p class="font-medium text-slate-900">{{ $value }}</p>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            @endif
                            @if($record->fields_added && count($record->fields_added) > 0)
                                <div class="mt-4">
                                    <p class="text-sm text-slate-500 mb-2">Fields added to master:</p>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($record->fields_added as $field)
                                            <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-sm">
                                                + {{ $field }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </details>
            </div>
        @empty
            <div class="bg-white rounded-xl border border-slate-200 p-12 text-center">
                <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="git-merge" class="h-8 w-8 text-slate-400"></i>
                </div>
                <h3 class="text-lg font-medium text-slate-900">No merge history</h3>
                <p class="text-slate-500 mt-1">When you merge contacts, the history will appear here</p>
            </div>
        @endforelse
    </div>
</div>
@endsection



