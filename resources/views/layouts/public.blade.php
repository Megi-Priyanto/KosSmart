{{-- resources/views/layouts/public.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'KosSmart - Rumah Keduamu, Lebih Mudah')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('head-scripts')
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Fraunces:ital,wght@0,700;1,600;1,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    <style>
        :root {
            /* Light palette */
            --bg:#f8fafc;
            --surface:#ffffff;
            --card:#f1f5f9;
            --amber:#d97706; --amber2:#f59e0b;
            --green:#059669; --purple:#7c3aed;
            --text:#0f172a; --muted:#64748b;
            --border:rgba(15,23,42,0.08);
        }
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        main { flex: 1; }

        .material-symbols-rounded {
            font-variation-settings: 'FILL' 1,'wght' 400,'GRAD' 0,'opsz' 24;
            vertical-align: middle;
            line-height: 1;
        }

        /* ── SHARED SECTION HELPERS ── */
        .max-w { max-width: 1100px; margin: 0 auto; }
        .sec-lbl { font-size:0.74rem; font-weight:700; letter-spacing:0.12em; text-transform:uppercase; color:var(--amber); display:block; margin-bottom:0.55rem; }
        .sec-ttl { font-size:clamp(1.8rem,3.5vw,2.65rem); font-weight:800; line-height:1.2; margin-bottom:0.85rem; color:var(--text); }
        .sec-ttl em { font-family:'Fraunces',serif; font-style:italic; color:var(--amber); }
        .blob { position:absolute; border-radius:50%; filter:blur(100px); opacity:0.06; pointer-events:none; }

        @keyframes fadeUp { from { opacity:0; transform:translateY(22px) } to { opacity:1; transform:translateY(0) } }

        @stack('page-styles')
    </style>
    @stack('head')
</head>
<body>

    {{-- ══ NAVBAR ══ --}}
    @include('layouts.partials.public.navbar')

    {{-- ══ CONTENT ══ --}}
    <main>
        @yield('content')
    </main>

    {{-- ══ FOOTER ══ --}}
    @include('layouts.partials.public.footer')

    @stack('scripts')
</body>
</html>
