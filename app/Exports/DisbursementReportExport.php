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
 * Export class untuk Laporan Pendapatan Platform (Fee Disbursement)
 * Digunakan oleh SuperAdminBillingReportController
 */
class DisbursementReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, WithColumnWidths
{
    protected $disbursements;

    public function __construct($disbursements)
    {
        $this->disbursements = $disbursements;
    }

    public function collection()
    {
        return $this->disbursements;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Admin Kos',
            'Email Admin',
            'Tempat Kos',
            'Jml Payment',
            'Gross Amount',
            'Fee (%)',
            'Fee Platform (Rp)',
            'Diterima Admin (Rp)',
            'Metode Transfer',
            'No. Rekening',
            'Diproses Oleh',
            'Tanggal Cairkan',
            'Keterangan',
        ];
    }

    public function map($d): array
    {
        return [
            $d->id,
            $d->admin->name ?? '-',
            $d->admin->email ?? '-',
            $d->tempatKos->nama_kos ?? '-',
            $d->payment_count,
            number_format($d->gross_amount, 0, ',', '.'),
            $d->fee_percent . '%',
            number_format($d->fee_amount, 0, ',', '.'),
            number_format($d->total_amount, 0, ',', '.'),
            $d->transfer_method ?? '-',
            $d->transfer_account ?? '-',
            $d->processor->name ?? '-',
            $d->processed_at ? $d->processed_at->format('d M Y H:i') : '-',
            $d->description ?? '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:N1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '047857'], // Green — identitas pendapatan platform
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);

        $lastRow = $sheet->getHighestRow();

        // Border semua data
        $sheet->getStyle('A1:N' . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color'       => ['rgb' => 'DDDDDD'],
                ],
            ],
        ]);

        // Highlight kolom Fee Platform (H) dengan warna hijau muda
        $sheet->getStyle('H2:H' . $lastRow)->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => '065F46']],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'D1FAE5'],
            ],
        ]);

        // Center align kolom ID, Jml Payment, Fee %
        foreach (['A', 'E', 'G'] as $col) {
            $sheet->getStyle($col . '2:' . $col . $lastRow)
                ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        return $sheet;
    }

    public function title(): string
    {
        return 'Laporan Pendapatan Platform';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 8,   // ID
            'B' => 25,  // Admin Kos
            'C' => 30,  // Email
            'D' => 25,  // Tempat Kos
            'E' => 12,  // Jml Payment
            'F' => 18,  // Gross Amount
            'G' => 10,  // Fee %
            'H' => 20,  // Fee Platform
            'I' => 20,  // Diterima Admin
            'J' => 18,  // Metode Transfer
            'K' => 20,  // No. Rekening
            'L' => 20,  // Diproses Oleh
            'M' => 20,  // Tanggal Cairkan
            'N' => 30,  // Keterangan
        ];
    }
}