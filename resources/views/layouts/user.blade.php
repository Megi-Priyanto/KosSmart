<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Kostin</title>

    <!-- Tailwind CSS ONLY (Bootstrap dihapus untuk menghindari konflik) -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Bootstrap Icons (icons only, no CSS reset) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

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
                    this.type = 'info'; this.title = ''; this.message = '';
                    this.onConfirm = null; this.formId = null;
                }, 200);
            },
            confirm() {
                if (this.onConfirm && typeof this.onConfirm === 'function') {
                    this.onConfirm();
                } else if (this.formId) {
                    const form = document.getElementById(this.formId);
                    if (form) form.submit();
                }
                this.close();
            },
            confirmDelete(message, formId, title = 'Hapus Data?') {
                this.open({ type: 'delete', title, message, formId, confirmText: 'Ya, Hapus' });
            },
            alert(message, title = 'Pemberitahuan', type = 'info') {
                this.open({ type, title, message, showCancel: false, confirmText: 'Mengerti' });
            }
        });
    });
    </script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after { font-family: 'Inter', sans-serif; box-sizing: border-box; }

        body {
            background-color: #0f172a;
            color: #e2e8f0;
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }

        /* Dark scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #1e293b; }
        ::-webkit-scrollbar-thumb { background: #334155; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #475569; }

        /* Form elements - dark by default (no Bootstrap override) */
        input, select, textarea, button {
            font-family: 'Inter', sans-serif;
        }
        input:not([type="submit"]):not([type="button"]):not([type="checkbox"]):not([type="radio"]),
        select,
        textarea {
            background-color: #1e293b !important;
            border: 1px solid #334155 !important;
            color: #e2e8f0 !important;
            border-radius: 0.5rem;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        input:focus, select:focus, textarea:focus {
            border-color: #f59e0b !important;
            box-shadow: 0 0 0 2px rgba(245,158,11,0.2) !important;
        }
        input::placeholder, textarea::placeholder { color: #64748b !important; }
        select option { background-color: #1e293b; color: #e2e8f0; }

        /* Pagination dark override */
        nav[role="navigation"] span,
        nav[role="navigation"] a {
            background-color: #1e293b !important;
            border-color: #334155 !important;
            color: #94a3b8 !important;
        }
        nav[role="navigation"] [aria-current="page"] span {
            background-color: #f59e0b !important;
            border-color: #f59e0b !important;
            color: #0f172a !important;
            font-weight: 700;
        }
        nav[role="navigation"] a:hover {
            background-color: #334155 !important;
            color: #e2e8f0 !important;
        }

        @media (max-width: 768px) {
            body { padding-bottom: 80px; }
        }
    </style>

    @stack('styles')
</head>
<body>

    @include('layouts.partials.user.navbar')

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        @if(session('success') && !isset($pendingRent))
        <div class="mb-6 px-5 py-4 rounded-xl flex items-center gap-3"
             style="background:#1e293b; border:1px solid rgba(34,197,94,0.3);">
            <svg class="w-5 h-5 flex-shrink-0" style="color:#4ade80;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p style="color:#4ade80;" class="font-medium text-sm">{{ session('success') }}</p>
        </div>
        @endif

        @if(session('error'))
        <div class="mb-6 px-5 py-4 rounded-xl flex items-center gap-3"
             style="background:#1e293b; border:1px solid rgba(239,68,68,0.3);">
            <svg class="w-5 h-5 flex-shrink-0" style="color:#f87171;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p style="color:#f87171;" class="font-medium text-sm">{{ session('error') }}</p>
        </div>
        @endif

        @yield('content')

    </main>

    @include('layouts.partials.user.footer')
    @include('layouts.partials.user.bottom-nav')
    @include('components.modal')

    @stack('scripts')

</body>
</html>
