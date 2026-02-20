<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Kostin</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Alpine Store -->
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

            confirmDelete(message, formId, title = 'Hapus Data?') {
                this.open({
                    type: 'delete',
                    title: title,
                    message: message,
                    formId: formId,
                    confirmText: 'Ya, Hapus'
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

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        @media (max-width: 768px) {
            body {
                padding-bottom: 80px;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-50">
    
    @include('layouts.partials.user.navbar')

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        @if(session('success') && !isset($pendingRent))
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-lg flex items-center">
            <p class="text-green-700 font-medium">{{ session('success') }}</p>
        </div>
        @endif

        @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg flex items-center">
            <p class="text-red-700 font-medium">{{ session('error') }}</p>
        </div>
        @endif

        @yield('content')

    </main>

    @include('layouts.partials.user.footer')

    @include('layouts.partials.user.bottom-nav')
    
    @include('components.modal')

    @stack('scripts')
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>