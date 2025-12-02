<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Tagihan - KosSmart</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #7C3AED;
            padding-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            color: #7C3AED;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .info {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f3f4f6;
            border-radius: 5px;
        }
        .info p {
            margin: 5px 0;
        }
        .stats {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .stat-item {
            display: table-cell;
            width: 25%;
            padding: 10px;
            text-align: center;
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
        }
        .stat-item .label {
            font-size: 10px;
            color: #6b7280;
            text-transform: uppercase;
        }
        .stat-item .value {
            font-size: 18px;
            font-weight: bold;
            color: #111827;
            margin-top: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table thead {
            background-color: #7C3AED;
            color: white;
        }
        table th {
            padding: 8px;
            text-align: left;
            font-size: 10px;
            text-transform: uppercase;
        }
        table td {
            padding: 8px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 11px;
        }
        table tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .status {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        .status-paid {
            background-color: #d1fae5;
            color: #065f46;
        }
        .status-unpaid {
            background-color: #fed7aa;
            color: #92400e;
        }
        .status-overdue {
            background-color: #fecaca;
            color: #991b1b;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
        }
        .total-row {
            background-color: #f3f4f6;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>LAPORAN TAGIHAN</h1>
        <p>KosSmart Management System</p>
        <p>Dicetak pada: {{ now()->format('d F Y, H:i') }} WIB</p>
    </div>
    
    <!-- Filter Info -->
    @if(count($filterInfo) > 0)
    <div class="info">
        <strong>Filter Laporan:</strong><br>
        @foreach($filterInfo as $info)
        {{ $info }}<br>
        @endforeach
    </div>
    @endif
    
    <!-- Statistics -->
    <div class="stats">
        <div class="stat-item">
            <div class="label">Total Tagihan</div>
            <div class="value">{{ $stats['total_billings'] }}</div>
        </div>
        <div class="stat-item">
            <div class="label">Lunas</div>
            <div class="value" style="color: #10b981;">{{ $stats['paid_count'] }}</div>
        </div>
        <div class="stat-item">
            <div class="label">Belum Dibayar</div>
            <div class="value" style="color: #f59e0b;">{{ $stats['unpaid_count'] }}</div>
        </div>
        <div class="stat-item">
            <div class="label">Terlambat</div>
            <div class="value" style="color: #ef4444;">{{ $stats['overdue_count'] }}</div>
        </div>
    </div>
    
    <!-- Table -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Penyewa</th>
                <th>Kamar</th>
                <th>Periode</th>
                <th>Jatuh Tempo</th>
                <th style="text-align: right;">Total</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalAmount = 0;
                $statusLabels = [
                    'paid' => 'Lunas',
                    'unpaid' => 'Belum Dibayar',
                    'overdue' => 'Terlambat',
                    'pending' => 'Pending',
                ];
            @endphp
            
            @foreach($billings as $billing)
            @php
                $totalAmount += $billing->total_amount;
                $statusClass = 'status-unpaid';
                if ($billing->status === 'paid') {
                    $statusClass = 'status-paid';
                } elseif ($billing->is_overdue) {
                    $statusClass = 'status-overdue';
                }
            @endphp
            <tr>
                <td>#{{ $billing->id }}</td>
                <td>{{ $billing->user->name }}</td>
                <td>{{ $billing->room->room_number }}</td>
                <td>{{ $billing->formatted_period }}</td>
                <td>{{ $billing->due_date->format('d M Y') }}</td>
                <td style="text-align: right;">Rp {{ number_format($billing->total_amount, 0, ',', '.') }}</td>
                <td>
                    <span class="status {{ $statusClass }}">
                        {{ $statusLabels[$billing->status] ?? $billing->status }}
                    </span>
                </td>
            </tr>
            @endforeach
            
            <!-- Total Row -->
            <tr class="total-row">
                <td colspan="5" style="text-align: right;">TOTAL:</td>
                <td style="text-align: right;">Rp {{ number_format($totalAmount, 0, ',', '.') }}</td>
                <td></td>
            </tr>
        </tbody>
    </table>
    
    <!-- Summary -->
    <div style="margin-top: 20px; padding: 15px; background-color: #f9fafb; border-radius: 5px;">
        <strong>Ringkasan Pembayaran:</strong><br>
        <table style="width: auto; margin-top: 10px;" border="0">
            <tr>
                <td style="border: none; padding: 5px;">Total Lunas:</td>
                <td style="border: none; padding: 5px; text-align: right; font-weight: bold;">
                    Rp {{ number_format($stats['paid_amount'], 0, ',', '.') }}
                </td>
            </tr>
            <tr>
                <td style="border: none; padding: 5px;">Total Belum Dibayar:</td>
                <td style="border: none; padding: 5px; text-align: right; font-weight: bold;">
                    Rp {{ number_format($stats['unpaid_amount'], 0, ',', '.') }}
                </td>
            </tr>
            <tr>
                <td style="border: none; padding: 5px;">Total Terlambat:</td>
                <td style="border: none; padding: 5px; text-align: right; font-weight: bold;">
                    Rp {{ number_format($stats['overdue_amount'], 0, ',', '.') }}
                </td>
            </tr>
        </table>
    </div>
    
    <!-- Footer -->
    <div class="footer">
        <p>Dokumen ini digenerate otomatis oleh sistem KosSmart</p>
        <p>&copy; {{ now()->year }} KosSmart. All rights reserved.</p>
    </div>
</body>
</html>