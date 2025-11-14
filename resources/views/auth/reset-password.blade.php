<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atur Ulang Password - KosSmart</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="max-w-md bg-white p-6 rounded-lg shadow-md w-full">
        <h2 class="text-2xl font-semibold mb-4">Atur Ulang Password</h2>

        <!-- Tampilkan error jika ada -->
        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded mb-3">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Tampilkan status sukses -->
        @if (session('status'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-3">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <input 
                type="email" 
                name="email" 
                value="{{ request('email') }}" 
                placeholder="Email" 
                class="w-full border p-2 rounded mb-3" 
                required
            >

            <input 
                type="password" 
                name="password" 
                placeholder="Password Baru" 
                class="w-full border p-2 rounded mb-3" 
                required
            >
            <input 
                type="password" 
                name="password_confirmation" 
                placeholder="Konfirmasi Password" 
                class="w-full border p-2 rounded mb-3" 
                required
            >

            <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded w-full">
                Simpan Password Baru
            </button>
        </form>
    </div>
</body>
</html>
