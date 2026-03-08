{{-- resources/views/layouts/user.blade.php --}}
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $favicon = \App\Models\AppSetting::get('app_favicon', 'images/favicon.png');
        $faviconUrl = str_starts_with($favicon, 'settings/') ? asset('storage/'.$favicon) : asset($favicon);
    @endphp
    <link rel="icon" type="image/png" href="{{ $faviconUrl }}">
    <link rel="shortcut icon" href="{{ $faviconUrl }}">
    
    <title>@yield('title', 'Dashboard') - {{ app_name() }}</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

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
                    if (this.onConfirm && typeof this.onConfirm === 'function') this.onConfirm();
                    else if (this.formId) {
                        const f = document.getElementById(this.formId);
                        if (f) f.submit();
                    }
                    this.close();
                },
                confirmDelete(message, formId, title = 'Hapus Data?') {
                    this.open({
                        type: 'delete',
                        title,
                        message,
                        formId,
                        confirmText: 'Ya, Hapus'
                    });
                },
                alert(message, title = 'Pemberitahuan', type = 'info') {
                    this.open({
                        type,
                        title,
                        message,
                        showCancel: false,
                        confirmText: 'Mengerti'
                    });
                }
            });
        });
    </script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        *,
        *::before,
        *::after {
            font-family: 'Inter', sans-serif;
            box-sizing: border-box;
        }

        body {
            background-color: #f8fafc;
            color: #0f172a;
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }

        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        input,
        select,
        textarea,
        button {
            font-family: 'Inter', sans-serif;
        }

        input:not([type="submit"]):not([type="button"]):not([type="checkbox"]):not([type="radio"]),
        select,
        textarea {
            background-color: #ffffff !important;
            border: 1px solid #e2e8f0 !important;
            color: #0f172a !important;
            border-radius: 0.5rem;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        input:focus,
        select:focus,
        textarea:focus {
            border-color: #f59e0b !important;
            box-shadow: 0 0 0 2px rgba(245, 158, 11, 0.15) !important;
        }

        input::placeholder,
        textarea::placeholder {
            color: #94a3b8 !important;
        }

        select option {
            background-color: #ffffff;
            color: #0f172a;
        }

        nav[role="navigation"] span,
        nav[role="navigation"] a {
            background-color: #ffffff !important;
            border-color: #e2e8f0 !important;
            color: #64748b !important;
        }

        nav[role="navigation"] [aria-current="page"] span {
            background-color: #f59e0b !important;
            border-color: #f59e0b !important;
            color: #ffffff !important;
            font-weight: 700;
        }

        nav[role="navigation"] a:hover {
            background-color: #f1f5f9 !important;
            color: #0f172a !important;
        }

        @media (max-width: 768px) {
            body {
                padding-bottom: 80px;
            }
        }
    </style>

    @stack('styles')
</head>

<body>

    @include('layouts.partials.user.navbar')

    <main class="max-w-6xl mx-auto px-6 lg:px-10 py-8">

        @if(session('success') && !isset($pendingRent))
        <div class="mb-6 px-5 py-4 rounded-xl flex items-center gap-3"
            style="background:#f0fdf4; border:1px solid rgba(34,197,94,0.3);">
            <svg class="w-5 h-5 flex-shrink-0" style="color:#16a34a;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p style="color:#16a34a;" class="font-medium text-sm">{{ session('success') }}</p>
        </div>
        @endif

        @if(session('error'))
        <div class="mb-6 px-5 py-4 rounded-xl flex items-center gap-3"
            style="background:#fef2f2; border:1px solid rgba(239,68,68,0.3);">
            <svg class="w-5 h-5 flex-shrink-0" style="color:#dc2626;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p style="color:#dc2626;" class="font-medium text-sm">{{ session('error') }}</p>
        </div>
        @endif

        @yield('content')

    </main>

    @include('layouts.partials.user.footer')
    @include('layouts.partials.user.bottom-nav')
    @include('components.modal')

    {{-- AI Chat Widget --}}
    <x-ai-chat />

    @stack('scripts')

</body>

</html>