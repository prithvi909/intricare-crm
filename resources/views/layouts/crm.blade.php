<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ContactCRM - @yield('title')</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.tailwindcss.min.css">
    
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <style>
        .dataTables_wrapper {
            @apply p-6;
        }
        
        .dataTables_wrapper .dataTables_filter {
            @apply mb-4;
        }
        
        .dataTables_wrapper .dataTables_filter input {
            @apply border-2 border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all shadow-sm;
        }
        
        .dataTables_wrapper .dataTables_length {
            @apply mb-4;
        }
        
        .dataTables_wrapper .dataTables_length select {
            @apply border-2 border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all shadow-sm bg-white;
        }
        
        .dataTables_wrapper .dataTables_info {
            @apply text-sm text-slate-600 font-medium;
        }
        
        .dataTables_wrapper .dataTables_paginate {
            @apply mt-4;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            @apply px-4 py-2 mx-1 border-2 border-slate-200 rounded-lg text-sm font-medium text-slate-700 bg-white hover:bg-indigo-50 hover:text-indigo-700 hover:border-indigo-300 transition-all duration-150 shadow-sm;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            @apply bg-gradient-to-r from-indigo-600 to-purple-600 text-white border-indigo-600 shadow-md;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
            @apply opacity-50 cursor-not-allowed hover:bg-white hover:text-slate-700 hover:border-slate-200;
        }
        
        #contactsTable tbody tr {
            @apply transition-all duration-150;
        }
        
        #contactsTable tbody tr:hover {
            @apply bg-gradient-to-r from-indigo-50/30 to-purple-50/20;
        }
        
        #contactsTable tbody td {
            @apply px-6 py-5;
        }
        
        #contactsTable tbody tr:not(:last-child) {
            @apply border-b border-slate-100;
        }
    </style>
</head>
<body class="min-h-screen bg-slate-50">
    <!-- Header -->
    <header class="sticky top-0 z-50 bg-white/80 backdrop-blur-lg border-b border-slate-200/60">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <a href="{{ route('contacts.index') }}" class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-indigo-600 to-purple-600 flex items-center justify-center shadow-lg shadow-indigo-200">
                        <i data-lucide="layout-dashboard" class="h-5 w-5 text-white"></i>
                    </div>
                    <span class="font-bold text-xl text-slate-900 tracking-tight">
                        Contact<span class="text-indigo-600">CRM</span>
                    </span>
                </a>
                <!-- Navigation -->
                <nav class="hidden md:flex items-center gap-1">
                    <a href="{{ route('contacts.index') }}" 
                       class="flex items-center gap-2 px-4 py-2 rounded-lg transition-colors {{ request()->routeIs('contacts.*') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-50' }}">
                        <i data-lucide="users" class="h-4 w-4"></i>
                        Contacts
                    </a>
                    <a href="{{ route('custom-fields.index') }}" 
                       class="flex items-center gap-2 px-4 py-2 rounded-lg transition-colors {{ request()->routeIs('custom-fields.*') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-50' }}">
                        <i data-lucide="settings" class="h-4 w-4"></i>
                        Custom Fields
                    </a>
                    <a href="{{ route('merge-history.index') }}" 
                       class="flex items-center gap-2 px-4 py-2 rounded-lg transition-colors {{ request()->routeIs('merge-history.*') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-50' }}">
                        <i data-lucide="history" class="h-4 w-4"></i>
                        Merge History
                    </a>
                </nav>
                <!-- Mobile menu button -->
                <button id="mobile-menu-btn" class="md:hidden p-2 rounded-lg hover:bg-slate-100">
                    <i data-lucide="menu" class="h-5 w-5"></i>
                </button>
            </div>
        </div>
        <!-- Mobile Navigation -->
        <div id="mobile-menu" class="hidden md:hidden border-t border-slate-200 bg-white">
            <nav class="px-4 py-3 space-y-1">
                <a href="{{ route('contacts.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('contacts.*') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-50' }}">
                    <i data-lucide="users" class="h-5 w-5"></i>
                    Contacts
                </a>
                <a href="{{ route('custom-fields.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('custom-fields.*') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-50' }}">
                    <i data-lucide="settings" class="h-5 w-5"></i>
                    Custom Fields
                </a>
                <a href="{{ route('merge-history.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('merge-history.*') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-50' }}">
                    <i data-lucide="history" class="h-5 w-5"></i>
                    Merge History
                </a>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        lucide.createIcons();
        
        document.getElementById('mobile-menu-btn')?.addEventListener('click', function() {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });

        function showSuccess(message, description = '') {
            Toast.fire({
                icon: 'success',
                title: message,
                text: description,
                background: '#F0FDF4',
                color: '#166534',
                iconColor: '#22C55E'
            });
        }

        function showError(message, description = '') {
            Toast.fire({
                icon: 'error',
                title: message,
                text: description,
                background: '#FEF2F2',
                color: '#991B1B',
                iconColor: '#EF4444'
            });
        }

        function showWarning(message, description = '') {
            Toast.fire({
                icon: 'warning',
                title: message,
                text: description,
                background: '#FEF3C7',
                color: '#92400E',
                iconColor: '#F59E0B'
            });
        }

        function showInfo(message, description = '') {
            Toast.fire({
                icon: 'info',
                title: message,
                text: description,
                background: '#EEF2FF',
                color: '#3730A3',
                iconColor: '#6366F1'
            });
        }

        function confirmAction(title, text, confirmText = 'Yes, proceed!') {
            return Swal.fire({
                title: title,
                text: text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#4F46E5',
                cancelButtonColor: '#64748B',
                confirmButtonText: confirmText,
                cancelButtonText: 'Cancel'
            });
        }
    </script>
    
    @stack('scripts')
</body>
</html>

