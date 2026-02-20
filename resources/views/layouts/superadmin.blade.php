<!DOCTYPE html>
<html lang="id" x-data="{ sidebarOpen: true }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Super Admin') - Kostin</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Alpine Store (GLOBAL MODAL STATE) -->
    <script>
    document.addEventListener('alpine:init', () => {
        Alpine.store('modal', {
            show: false,
            type: 'info',
            title: '',
            message: '',
            confirmText: 'Oke',
            showCancel: false,
            onConfirm: null,
            formId: null,

            open(options) {
                this.type = options.type || 'info';
                this.title = options.title || 'Konfirmasi';
                this.message = options.message || '';
                this.confirmText = options.confirmText || (this.type === 'delete' ? 'Ya, Hapus' : 'Oke');
                this.showCancel = options.showCancel !== undefined ? options.showCancel : (this.type === 'delete');
                this.onConfirm = options.onConfirm || null;
                this.formId = options.formId || null;
                this.show = true;
            },

            close() {
                this.show = false;
                setTimeout(() => {
                    this.type = 'info';
                    this.title = '';
                    this.message = '';
                    this.onConfirm = null;
                    this.formId = null;
                }, 200);
            },

            confirm() {
                if (this.onConfirm && typeof this.onConfirm === 'function') {
                    this.onConfirm();
                } else if (this.formId) {
                    const form = document.getElementById(this.formId);
                    if (form) {
                        form.submit();
                    }
                }
                this.close();
            },

            confirmDelete(message, formId, title = 'Hapus User?') {
                this.open({
                    type: 'delete',
                    title: title,
                    message: message,
                    formId: formId,
                    confirmText: 'Hapus'
                });
            },

            alert(message, title = 'Pemberitahuan', type = 'info') {
                this.open({
                    type: type,
                    title: title,
                    message: message,
                    showCancel: false,
                    confirmText: 'Mengerti'
                });
            }
        });
    });
    </script>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-50">
    
    <div class="flex h-screen overflow-hidden">
        
        @include('layouts.partials.superadmin.sidebar')
        
        <div class="flex-1 flex flex-col overflow-hidden">
            
            @include('layouts.partials.superadmin.header')
            
            <main class="flex-1 overflow-y-auto p-6 bg-slate-900">
                
                @if(session('success'))
                <div class="mb-6 px-6 py-4 
                            bg-slate-800 border border-green-500/40
                            rounded-xl flex items-center gap-3 shadow-sm">
                    <svg class="w-5 h-5 text-green-400 flex-shrink-0"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-green-300 font-medium text-sm">
                        {{ session('success') }}
                    </p>
                </div>
                @endif
                
                @if(session('error'))
                <div class="mb-6 px-6 py-4 
                            bg-slate-800 border border-red-500/40
                            rounded-xl flex items-center gap-3 shadow-sm">
                    <svg class="w-5 h-5 text-red-400 flex-shrink-0"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-red-300 font-medium text-sm">
                        {{ session('error') }}
                    </p>
                </div>
                @endif
                
                @yield('content')
                
            </main>
            
        </div>
        
    </div>

    @include('components.modal')
    
    @stack('scripts')
    
</body>
</html>