<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'KosSmart' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen">
    <nav class="bg-purple-700 text-white p-4">
        <div class="container mx-auto flex justify-between">
            <h1 class="font-bold">KosSmart</h1>
            <div>
                <a href="{{ route('user.dashboard') }}" class="px-3 hover:underline">Dashboard</a>
                <a href="{{ route('user.payments') }}" class="px-3 hover:underline">Pembayaran</a>
                <a href="{{ route('user.profile') }}" class="px-3 hover:underline">Profil</a>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-3 hover:underline">Keluar</button>
                </form>
            </div>
        </div>
    </nav>

    <main class="container mx-auto mt-6">
        {{ $slot }}
    </main>
</body>
</html>
