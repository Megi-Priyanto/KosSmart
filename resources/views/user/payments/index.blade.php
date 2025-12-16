@extends('layouts.user')

@section('title', 'Riwayat Pembayaran')

@section('content')

<!-- Header Section -->
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-2">Riwayat Pembayaran</h1>
    <p class="text-gray-600">Kelola dan lihat semua transaksi pembayaran Anda</p>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Total Pembayaran Terkonfirmasi -->
    <div class="relative bg-white rounded-xl border-l-4 border-green-500 p-6 overflow-hidden shadow-sm">
        <!-- decorative circle -->
        <div class="absolute -top-6 -right-6 w-24 h-24 rounded-full border-2 border-green-300 opacity-60"></div>
        <div class="absolute -top-2 -right-2 w-20 h-20 rounded-full border-2 border-green-400 opacity-40"></div>

        <div class="flex items-center gap-4 relative z-10">
            <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>

            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase">
                    Total Terkonfirmasi
                </p>
                <p class="text-3xl font-bold text-gray-800">
                    {{ $payments->where('status', 'confirmed')->count() }}
                </p>
            </div>
        </div>
    </div>

    <!-- Total Pending -->
    <div class="relative bg-white rounded-xl border-l-4 border-yellow-500 p-6 overflow-hidden shadow-sm">
        <div class="absolute -top-6 -right-6 w-24 h-24 rounded-full border-2 border-yellow-300 opacity-60"></div>
        <div class="absolute -top-2 -right-2 w-20 h-20 rounded-full border-2 border-yellow-400 opacity-40"></div>

        <div class="flex items-center gap-4 relative z-10">
            <div class="w-12 h-12 bg-yellow-500 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>

            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase">
                    Total Pending
                </p>
                <p class="text-3xl font-bold text-gray-800">
                    {{ $payments->where('status', 'pending')->count() }}
                </p>
            </div>
        </div>
    </div>

    <!-- Total Nilai Pembayaran -->
    <div class="relative bg-white rounded-xl border-l-4 border-purple-500 p-6 overflow-hidden shadow-sm">
        <div class="absolute -top-6 -right-6 w-24 h-24 rounded-full border-2 border-purple-300 opacity-60"></div>
        <div class="absolute -top-2 -right-2 w-20 h-20 rounded-full border-2 border-purple-400 opacity-40"></div>

        <div class="flex items-center gap-4 relative z-10">
            <div class="w-12 h-12 bg-purple-600 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>

            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase">
                    Total Pembayaran
                </p>
                <p class="text-2xl font-bold text-gray-800">
                    Rp {{ number_format($payments->where('status', 'confirmed')->sum('amount'), 0, ',', '.') }}
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Filter & Search -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
    <form id="searchForm" class="flex flex-col md:flex-row gap-4">
        <div class="flex-1">
            <label class="block text-sm font-medium text-gray-700 mb-2">Cari Pembayaran</label>
            <div class="relative">
                <input type="text" 
                       id="searchPayment"
                       placeholder="Cari berdasarkan bulan, jumlah..."
                       class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                <svg class="absolute left-3 top-3 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
        </div>
        <div class="w-full md:w-48">
            <label class="block text-sm font-medium text-gray-700 mb-2">Filter Status</label>
            <select id="filterStatus" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                <option value="">Semua Status</option>
                <option value="confirmed">Terkonfirmasi</option>
                <option value="pending">Pending</option>
                <option value="rejected">Ditolak</option>
            </select>
        </div>
        <div class="w-full md:w-auto flex items-end">
            <button type="submit" 
                    class="w-full md:w-auto px-6 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-lg font-semibold hover:shadow-lg transition-all flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <span>Cari</span>
            </button>
        </div>
    </form>
</div>

<!-- Payment List -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        @forelse($payments as $payment)
        <div class="payment-item border-b border-gray-200 hover:bg-gray-50 transition-colors" 
             data-status="{{ $payment->status }}"
             data-search="{{ strtolower($payment->billing->month ?? '') }} {{ number_format($payment->amount, 0, ',', '.') }}">
            <div class="p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <!-- Payment Info -->
                    <div class="flex items-start space-x-4 flex-1">
                        <div class="w-14 h-14 rounded-xl flex items-center justify-center flex-shrink-0
                                    {{ $payment->status === 'confirmed' ? 'bg-green-100' : ($payment->status === 'rejected' ? 'bg-red-100' : 'bg-yellow-100') }}">
                            <svg class="w-7 h-7 {{ $payment->status === 'confirmed' ? 'text-green-600' : ($payment->status === 'rejected' ? 'text-red-600' : 'text-yellow-600') }}" 
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                @if($payment->status === 'confirmed')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                @elseif($payment->status === 'rejected')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                @else
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                @endif
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-3 mb-2">
                                <h3 class="text-lg font-bold text-gray-800">
                                    Pembayaran {{ $payment->billing->month ?? 'N/A' }}
                                </h3>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                           {{ $payment->status === 'confirmed' ? 'bg-green-100 text-green-700' : ($payment->status === 'rejected' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                                    {{ $payment->status_label }}
                                </span>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-sm">
                                <div class="flex items-center text-gray-600">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span>{{ $payment->payment_date->format('d M Y') }}</span>
                                </div>
                                <div class="flex items-center text-gray-600">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                    </svg>
                                    <span>{{ $payment->payment_method_label }}</span>
                                </div>
                                @if($payment->verified_at)
                                <div class="flex items-center text-gray-600">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>Diverifikasi {{ $payment->verified_at->format('d M Y') }}</span>
                                </div>
                                @endif
                            </div>
                            @if($payment->notes)
                            <p class="mt-2 text-sm text-gray-600 italic">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                </svg>
                                {{ $payment->notes }}
                            </p>
                            @endif
                            @if($payment->status === 'rejected' && $payment->rejection_reason)
                            <div class="mt-3 p-3 bg-red-50 border border-red-200 rounded-lg">
                                <p class="text-sm text-red-700 font-medium">Alasan Penolakan:</p>
                                <p class="text-sm text-red-600 mt-1">{{ $payment->rejection_reason }}</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Amount & Action -->
                    <div class="flex flex-col items-end space-y-3 md:ml-4">
                        <div class="text-right">
                            <p class="text-2xl font-bold text-gray-800">
                                Rp {{ number_format($payment->amount, 0, ',', '.') }}
                            </p>
                        </div>
                        
                        @if($payment->payment_proof)
                        <button onclick="showProofModal('{{ asset('storage/' . $payment->payment_proof) }}')"
                                class="px-4 py-2 bg-purple-100 text-purple-700 rounded-lg text-sm font-medium hover:bg-purple-200 transition-colors flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Lihat Bukti
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="p-12 text-center">
            <svg class="w-20 h-20 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Belum Ada Riwayat Pembayaran</h3>
            <p class="text-gray-600 mb-6">Pembayaran Anda akan muncul di sini setelah melakukan transaksi</p>
            <a href="{{ route('user.billings') }}" 
               class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-lg font-semibold hover:shadow-lg transition-all">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                Lihat Tagihan
            </a>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($payments->hasPages())
    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
        {{ $payments->links() }}
    </div>
    @endif
</div>

<!-- Image Modal -->
<div id="proofModal" class="fixed inset-0 bg-black bg-opacity-75 hidden items-center justify-center z-50 p-4">
    <div class="relative max-w-4xl w-full">
        <button onclick="closeProofModal()" 
                class="absolute -top-12 right-0 text-white hover:text-gray-300 transition-colors">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        <img id="proofImage" src="" alt="Bukti Pembayaran" class="w-full h-auto rounded-lg shadow-2xl">
    </div>
</div>

@endsection

@push('scripts')
<script>
// Image Modal Functions
function showProofModal(imageUrl) {
    document.getElementById('proofImage').src = imageUrl;
    document.getElementById('proofModal').classList.remove('hidden');
    document.getElementById('proofModal').classList.add('flex');
}

function closeProofModal() {
    document.getElementById('proofModal').classList.add('hidden');
    document.getElementById('proofModal').classList.remove('flex');
}

// Search Form Submit Handler
document.getElementById('searchForm').addEventListener('submit', function(e) {
    e.preventDefault();
    filterPayments();
});

function filterPayments() {
    const searchTerm = document.getElementById('searchPayment').value.toLowerCase();
    const statusFilter = document.getElementById('filterStatus').value;
    const items = document.querySelectorAll('.payment-item');
    
    let visibleCount = 0;
    
    items.forEach(item => {
        const searchData = item.dataset.search;
        const status = item.dataset.status;
        
        const matchesSearch = searchData.includes(searchTerm);
        const matchesStatus = !statusFilter || status === statusFilter;
        
        if (matchesSearch && matchesStatus) {
            item.style.display = '';
            visibleCount++;
        } else {
            item.style.display = 'none';
        }
    });
    
    // Show no results message if needed
    const noResultsMsg = document.getElementById('noResultsMessage');
    if (visibleCount === 0 && items.length > 0) {
        if (!noResultsMsg) {
            const container = document.querySelector('.payment-item').parentElement;
            const msg = document.createElement('div');
            msg.id = 'noResultsMessage';
            msg.className = 'p-12 text-center';
            msg.innerHTML = `
                <svg class="w-20 h-20 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Tidak Ada Hasil</h3>
                <p class="text-gray-600">Coba kata kunci atau filter lain</p>
            `;
            container.appendChild(msg);
        }
    } else if (noResultsMsg) {
        noResultsMsg.remove();
    }
}

// Close modal on ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeProofModal();
    }
});

// Close modal on click outside
document.getElementById('proofModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeProofModal();
    }
});
</script>
@endpush