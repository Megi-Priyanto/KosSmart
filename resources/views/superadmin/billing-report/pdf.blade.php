<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Pendapatan Platform</title>
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
        .fee-highlight {
            color: #155724;
            font-weight: bold;
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
        <h1>LAPORAN PENDAPATAN PLATFORM (FEE PENCAIRAN DANA)</h1>
        <p>Dicetak pada: {{ now()->format('d F Y H:i:s') }}</p>
    </div>

    <div class="stats">
        <div class="stat-box">
            <div class="stat-label">Total Pencairan</div>
            <div class="stat-value">{{ $stats['total_disbursement_count'] }}</div>
            <div class="stat-amount">transaksi</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Total Gross Amount</div>
            <div class="stat-value" style="font-size: 11px;">Rp {{ number_format($stats['total_gross_amount'], 0, ',', '.') }}</div>
            <div class="stat-amount">sebelum fee</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Total Fee Platform</div>
            <div class="stat-value" style="font-size: 11px; color: #155724;">Rp {{ number_format($stats['total_fee_amount'], 0, ',', '.') }}</div>
            <div class="stat-amount">pendapatan platform</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Total Diterima Admin</div>
            <div class="stat-value" style="font-size: 11px;">Rp {{ number_format($stats['total_admin_amount'], 0, ',', '.') }}</div>
            <div class="stat-amount">setelah fee</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 4%;">No</th>
                <th style="width: 14%;">Admin Kos</th>
                <th style="width: 14%;">Tempat Kos</th>
                <th style="width: 7%;" class="text-center">Jml Pay.</th>
                <th style="width: 14%;" class="text-right">Gross Amount</th>
                <th style="width: 7%;" class="text-center">Fee %</th>
                <th style="width: 14%;" class="text-right">Fee Platform</th>
                <th style="width: 14%;" class="text-right">Diterima Admin</th>
                <th style="width: 12%;" class="text-center">Tgl Cairkan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($disbursements as $index => $disbursement)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>
                    <div class="font-bold">{{ $disbursement->admin->name }}</div>
                    <div style="font-size: 8px; color: #666;">{{ $disbursement->admin->email }}</div>
                </td>
                <td>{{ $disbursement->tempatKos->nama_kos ?? '-' }}</td>
                <td class="text-center">{{ $disbursement->payment_count }}</td>
                <td class="text-right">Rp {{ number_format($disbursement->gross_amount, 0, ',', '.') }}</td>
                <td class="text-center">{{ $disbursement->fee_percent }}%</td>
                <td class="text-right fee-highlight">Rp {{ number_format($disbursement->fee_amount, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($disbursement->total_amount, 0, ',', '.') }}</td>
                <td class="text-center">
                    {{ $disbursement->processed_at ? $disbursement->processed_at->format('d M Y') : '-' }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center" style="padding: 20px;">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr style="background-color: #f0f0f0; font-weight: bold;">
                <td colspan="4" class="text-right">TOTAL:</td>
                <td class="text-right">Rp {{ number_format($disbursements->sum('gross_amount'), 0, ',', '.') }}</td>
                <td></td>
                <td class="text-right" style="color: #155724;">Rp {{ number_format($disbursements->sum('fee_amount'), 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($disbursements->sum('total_amount'), 0, ',', '.') }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Dokumen ini dicetak secara otomatis oleh sistem KosSmart</p>
        <p>{{ config('app.name') }} - {{ config('app.url') }}</p>
    </div>
</body>
</html>