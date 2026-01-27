<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

/**
 * Export class for Super Admin → Admin billing (admin_billings table)
 * This handles operational/subscription billing from Super Admin to Admin Kos
 */
class AdminBillingReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, WithColumnWidths
{
    protected $billings;

    public function __construct($billings)
    {
        $this->billings = $billings;
    }

    /**
     * Return the collection of data
     */
    public function collection()
    {
        return $this->billings;
    }

    /**
     * Define the headings for the Excel sheet
     */
    public function headings(): array
    {
        return [
            'ID',
            'Nama Admin',
            'Email',
            'Tempat Kos',
            'Periode',
            'Jatuh Tempo',
            'Jumlah',
            'Status',
            'Metode Pembayaran',
            'Tanggal Bayar',
            'Verifikasi Oleh',
            'Keterangan',
        ];
    }

    /**
     * Map each row of data
     */
    public function map($billing): array
    {
        $statusLabels = [
            'paid' => 'Lunas',
            'pending' => 'Menunggu Verifikasi',
            'unpaid' => 'Belum Dibayar',
            'overdue' => 'Terlambat',
        ];
        
        // Check if overdue
        if ($billing->status === 'unpaid' && $billing->due_date->isPast()) {
            $status = 'Terlambat';
        } else {
            $status = $statusLabels[$billing->status] ?? $billing->status;
        }
        
        // Get verifier name
        $verifiedBy = '-';
        if ($billing->verified_by && $billing->status === 'paid') {
            $verifier = \App\Models\User::find($billing->verified_by);
            $verifiedBy = $verifier ? $verifier->name : '-';
        }

        return [
            $billing->id,
            $billing->admin->name ?? '-',
            $billing->admin->email ?? '-',
            $billing->tempatKos->nama_kos ?? '-',
            \Carbon\Carbon::parse($billing->billing_period . '-01')->format('F Y'),
            $billing->due_date->format('d M Y'),
            number_format($billing->amount, 0, ',', '.'),
            $status,
            $billing->payment_method ?? '-',
            $billing->paid_at ? $billing->paid_at->format('d M Y H:i') : '-',
            $verifiedBy,
            $billing->description ?? '-',
        ];
    }

    /**
     * Style the worksheet
     */
    public function styles(Worksheet $sheet)
    {
        // Style the header row
        $sheet->getStyle('A1:L1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'F59E0B'], // Yellow color
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);

        // Add borders to all data
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle('A1:L' . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'DDDDDD'],
                ],
            ],
        ]);

        // Center align specific columns (ID, Status)
        $sheet->getStyle('A2:A' . $lastRow)->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('H2:H' . $lastRow)->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        return $sheet;
    }

    /**
     * Set the sheet title
     */
    public function title(): string
    {
        return 'Laporan Tagihan Operasional';
    }
    
    /**
     * Set column widths
     */
    public function columnWidths(): array
    {
        return [
            'A' => 8,   // ID
            'B' => 25,  // Nama Admin
            'C' => 30,  // Email
            'D' => 25,  // Tempat Kos
            'E' => 15,  // Periode
            'F' => 15,  // Jatuh Tempo
            'G' => 15,  // Jumlah
            'H' => 20,  // Status
            'I' => 20,  // Metode Pembayaran
            'J' => 18,  // Tanggal Bayar
            'K' => 20,  // Verifikasi Oleh
            'L' => 30,  // Keterangan
        ];
    }
}