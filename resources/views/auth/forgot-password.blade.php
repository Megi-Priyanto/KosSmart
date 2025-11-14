<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - KosSmart</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="max-w-md bg-white p-6 rounded-lg shadow-md w-full">
        <h2 class="text-2xl font-semibold mb-4">Lupa Password</h2>
        <p class="text-gray-600 mb-4">Masukkan email Anda untuk menerima tautan reset password.</p>

        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-3">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <input type="email" name="email" class="w-full border p-2 rounded mb-3" placeholder="Email Anda" required>
            @error('email')
                <p class="text-red-500 text-sm mb-3">{{ $message }}</p>
            @enderror
            <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded w-full">
                Kirim Tautan Reset
            </button>
        </form>
    </div>
</body>
</html>
