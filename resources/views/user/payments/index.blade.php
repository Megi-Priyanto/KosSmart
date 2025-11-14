<x-main-layout :title-page="__('Riwayat Pembayaran')">
    <div class="container py-4">
        <h2 class="text-lg font-bold mb-4">Riwayat Pembayaran</h2>

        @forelse($payments as $payment)
            <div class="border rounded p-3 mb-2 bg-gray-50">
                <p><strong>Tanggal:</strong> {{ $payment->created_at->format('d M Y') }}</p>
                <p><strong>Jumlah:</strong> Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                <p><strong>Status:</strong> 
                    <span class="text-{{ $payment->status === 'paid' ? 'green' : 'orange' }}-600">
                        {{ ucfirst($payment->status) }}
                    </span>
                </p>
            </div>
        @empty
            <p class="text-gray-500">Belum ada riwayat pembayaran.</p>
        @endforelse
    </div>
</x-main-layout>
