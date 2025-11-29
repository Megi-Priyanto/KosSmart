@extends('admin.layouts.app')

@section('title', 'Laporan Keuangan')

@section('content')
<div class="p-6">

    <!-- Header -->
    <h1 class="text-3xl font-bold mb-6">📊 Laporan Keuangan</h1>

    <!-- Filter Periode -->
    <div class="bg-white p-5 rounded-xl shadow mb-6">
        <form method="GET" action="{{ route('admin.reports.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="font-semibold">Dari Tanggal</label>
                <input type="date" name="start_date"
                       value="{{ request('start_date') }}"
                       class="w-full mt-1 p-2 border rounded">
            </div>

            <div>
                <label class="font-semibold">Sampai Tanggal</label>
                <input type="date" name="end_date"
                       value="{{ request('end_date') }}"
                       class="w-full mt-1 p-2 border rounded">
            </div>

            <div class="flex items-end">
                <button type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded w-full">
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Info Ringkas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">

        <div class="bg-green-500 text-white p-5 rounded-xl shadow">
            <h2 class="text-lg">Total Pemasukan</h2>
            <p class="text-3xl font-bold mt-1">
                Rp {{ number_format($totalIncome ?? 0, 0, ',', '.') }}
            </p>
        </div>

        <div class="bg-yellow-500 text-white p-5 rounded-xl shadow">
            <h2 class="text-lg">Total Tagihan Dibayar</h2>
            <p class="text-3xl font-bold mt-1">
                {{ $paidCount ?? 0 }}
            </p>
        </div>

        <div class="bg-red-500 text-white p-5 rounded-xl shadow">
            <h2 class="text-lg">Tagihan Belum Dibayar</h2>
            <p class="text-3xl font-bold mt-1">
                {{ $unpaidCount ?? 0 }}
            </p>
        </div>

    </div>

    <!-- Tabel Data -->
    <div class="bg-white p-5 rounded-xl shadow">
        <h2 class="text-xl font-bold mb-4">Data Transaksi</h2>

        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-100 border-b">
                        <th class="p-3 text-left">ID</th>
                        <th class="p-3 text-left">Penyewa</th>
                        <th class="p-3 text-left">Kamar</th>
                        <th class="p-3 text-left">Tanggal Tagihan</th>
                        <th class="p-3 text-left">Status</th>
                        <th class="p-3 text-left">Total</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($billings as $billing)
                        <tr class="border-b">
                            <td class="p-3">{{ $billing->id }}</td>
                            <td class="p-3">{{ $billing->user->name }}</td>
                            <td class="p-3">{{ $billing->room->room_number }}</td>
                            <td class="p-3">{{ $billing->due_date }}</td>
                            <td class="p-3">
                                @if($billing->status === 'paid')
                                    <span class="text-green-600 font-semibold">Lunas</span>
                                @elseif($billing->status === 'unpaid')
                                    <span class="text-red-600 font-semibold">Belum Dibayar</span>
                                @else
                                    <span class="text-yellow-600 font-semibold">Terlambat</span>
                                @endif
                            </td>
                            <td class="p-3">Rp {{ number_format($billing->amount, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-4 text-center text-gray-500">
                                Tidak ada data untuk periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $billings->links() }}
        </div>
    </div>

</div>
@endsection
