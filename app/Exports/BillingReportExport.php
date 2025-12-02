<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;

class BillingReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $billings;
    
    public function __construct($billings)
    {
        $this->billings = $billings;
    }
    
    /**
     * Return collection of billings
     */
    public function collection()
    {
        return $this->billings;
    }
    
    /**
     * Define headings
     */
    public function headings(): array
    {
        return [
            'ID',
            'Nama Penyewa',
            'Email',
            'Nomor Kamar',
            'Periode',
            'Jatuh Tempo',
            'Sewa Kamar',
            'Listrik',
            'Air',
            'Biaya Lain',
            'Diskon',
            'Total',
            'Status',
            'Tanggal Bayar',
        ];
    }
    
    /**
     * Map data for each row
     */
    public function map($billing): array
    {
        $statusLabels = [
            'paid' => 'Lunas',
            'unpaid' => 'Belum Dibayar',
            'overdue' => 'Terlambat',
            'pending' => 'Menunggu Konfirmasi',
        ];
        
        return [
            $billing->id,
            $billing->user->name ?? '-',
            $billing->user->email ?? '-',
            $billing->room->room_number ?? '-',
            $billing->formatted_period ?? '-',
            $billing->due_date ? $billing->due_date->format('d M Y') : '-',
            number_format($billing->rent_amount, 0, ',', '.'),
            number_format($billing->electricity_cost, 0, ',', '.'),
            number_format($billing->water_cost, 0, ',', '.'),
            number_format($billing->other_costs, 0, ',', '.'),
            number_format($billing->discount, 0, ',', '.'),
            number_format($billing->total_amount, 0, ',', '.'),
            $statusLabels[$billing->status] ?? $billing->status,
            $billing->paid_date ? $billing->paid_date->format('d M Y') : '-',
        ];
    }
    
    /**
     * Style the worksheet
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Header style
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '7C3AED'],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ],
        ];
    }
    
    /**
     * Set column widths
     */
    public function columnWidths(): array
    {
        return [
            'A' => 8,   // ID
            'B' => 25,  // Nama
            'C' => 30,  // Email
            'D' => 12,  // Kamar
            'E' => 15,  // Periode
            'F' => 15,  // Jatuh Tempo
            'G' => 15,  // Sewa
            'H' => 12,  // Listrik
            'I' => 12,  // Air
            'J' => 12,  // Lain
            'K' => 12,  // Diskon
            'L' => 15,  // Total
            'M' => 20,  // Status
            'N' => 15,  // Tanggal Bayar
        ];
    }
}