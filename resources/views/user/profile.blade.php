<x-main-layout>
    <div class="bg-white p-6 rounded-lg shadow">
        <h1 class="text-2xl font-bold mb-4">Profil Pengguna</h1>

        <div class="space-y-2">
            <p><strong>Nama:</strong> {{ Auth::user()->name }}</p>
            <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
            <p><strong>Tanggal Dibuat:</strong> {{ Auth::user()->created_at->format('d M Y') }}</p>
        </div>

        <div class="mt-6">
            <a href="{{ route('user.dashboard') }}" class="text-purple-600 hover:underline">← Kembali ke Dashboard</a>
        </div>
    </div>
</x-main-layout>
