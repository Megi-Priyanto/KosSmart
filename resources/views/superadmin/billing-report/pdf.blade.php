<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Tagihan</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #333;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }
        .header p {
            font-size: 10px;
            color: #666;
        }
        .stats {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .stat-box {
            display: table-cell;
            width: 25%;
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
            background-color: #f5f5f5;
        }
        .stat-label {
            font-size: 9px;
            color: #666;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .stat-value {
            font-size: 16px;
            font-weight: bold;
            color: #333;
            margin-bottom: 3px;
        }
        .stat-amount {
            font-size: 9px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        thead {
            background-color: #333;
            color: white;
        }
        th {
            padding: 8px 5px;
            text-align: left;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            border: 1px solid #333;
        }
        td {
            padding: 6px 5px;
            border: 1px solid #ddd;
            font-size: 10px;
        }
        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .status {
            padding: 2px 8px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            text-align: center;
            display: inline-block;
        }
        .status-paid {
            background-color: #d4edda;
            color: #155724;
        }
        .status-pending {
            background-color: #d1ecf1;
            color: #0c5460;
        }
        .status-unpaid {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-overdue {
            background-color: #f8d7da;
            color: #721c24;
        }
        .footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 9px;
            color: #666;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .font-bold {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN TAGIHAN OPERASIONAL</h1>
        <p>Dicetak pada: {{ now()->format('d F Y H:i:s') }}</p>
    </div>

    <div class="stats">
        <div class="stat-box">
            <div class="stat-label">Total Tagihan</div>
            <div class="stat-value">{{ $stats['total_count'] }}</div>
            <div class="stat-amount">Rp {{ number_format($stats['total_amount'], 0, ',', '.') }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Lunas</div>
            <div class="stat-value">{{ $stats['paid_count'] }}</div>
            <div class="stat-amount">Rp {{ number_format($stats['paid_amount'], 0, ',', '.') }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Belum Dibayar</div>
            <div class="stat-value">{{ $stats['unpaid_count'] }}</div>
            <div class="stat-amount">Rp {{ number_format($stats['unpaid_amount'], 0, ',', '.') }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Total Pendapatan</div>
            <div class="stat-value font-bold">Rp {{ number_format($stats['paid_amount'], 0, ',', '.') }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 15%;">Penyewa</th>
                <th style="width: 15%;">Tempat Kos</th>
                <th style="width: 12%;">Periode</th>
                <th style="width: 12%;">Jatuh Tempo</th>
                <th style="width: 15%;" class="text-right">Jumlah</th>
                <th style="width: 12%;">Tgl Bayar</th>
                <th style="width: 14%;" class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($billings as $index => $billing)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>
                    <div class="font-bold">{{ $billing->admin->name }}</div>
                    <div style="font-size: 8px; color: #666;">{{ $billing->admin->email }}</div>
                </td>
                <td>{{ $billing->tempatKos->nama_kos ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($billing->billing_period . '-01')->format('M Y') }}</td>
                <td>{{ $billing->due_date->format('d M Y') }}</td>
                <td class="text-right font-bold">Rp {{ number_format($billing->amount, 0, ',', '.') }}</td>
                <td>
                    @if($billing->paid_at)
                        {{ $billing->paid_at->format('d M Y') }}
                    @else
                        -
                    @endif
                </td>
                <td class="text-center">
                    @if($billing->status === 'paid')
                        <span class="status status-paid">Lunas</span>
                    @elseif($billing->status === 'pending')
                        <span class="status status-pending">Pending</span>
                    @elseif($billing->isOverdue())
                        <span class="status status-overdue">Terlambat</span>
                    @else
                        <span class="status status-unpaid">Belum Dibayar</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center" style="padding: 20px;">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr style="background-color: #f0f0f0; font-weight: bold;">
                <td colspan="5" class="text-right">TOTAL:</td>
                <td class="text-right">Rp {{ number_format($billings->sum('amount'), 0, ',', '.') }}</td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Dokumen ini dicetak secara otomatis oleh sistem KosSmart</p>
        <p>{{ config('app.name') }} - {{ config('app.url') }}</p>
    </div>
</body>
</html>