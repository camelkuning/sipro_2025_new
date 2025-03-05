<?php

namespace App\Exports;

use App\Models\Keuangan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Events\AfterSheet;

class KeuanganExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithEvents, WithCustomStartCell,WithDrawings
{
    public function collection()
    {
        return Keuangan::all();
    }
    public function startCell(): string
    {
        return 'D6'; // Tabel dimulai dari D6
    }


    public function headings(): array
    {
        return [
            'Tanggal Awal Penerimaan',
            'Tanggal Akhir Penerimaan',
            'Penerimaan Derma Konvensional',
            'Penerimaan Derma Inkonvensional',
            'Total Penerimaan',
            'Waktu Input',
        ];
    }

    public function map($keuangan): array
    {
        return [
            $keuangan->tanggal_awal,
            $keuangan->tanggal_akhir,
            number_format($keuangan->konveksional, 0, ',', '.'),
            number_format($keuangan->inkonveksional, 0, ',', '.'),
            number_format($keuangan->total_penerimaan, 0, ',', '.'),
            $keuangan->created_at->format('Y-m-d H:i:s'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Merge cells untuk judul laporan
        $sheet->mergeCells('D3:I3');
        $sheet->mergeCells('D4:I4');

        // Set judul laporan
        $sheet->setCellValue('D3', 'Laporan Penerimaan Keuangan per Bulan');
        $sheet->setCellValue('D4', 'Jemaat GKI Via Dolorosa Bintuni');

        // Styling judul laporan
        $sheet->getStyle('D3:D4')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Set header tabel di D6
        $sheet->fromArray($this->headings(), null, 'D6');

        // Styling header tabel
        $sheet->getStyle('D6:I6')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN],
            ],
        ]);
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;

                // Ambil jumlah baris data
                $highestRow = $sheet->getHighestRow();

                // Pastikan data mulai dari D7
                $dataRange = 'D7:I' . $highestRow;

                // Border dan align center semua data
                $sheet->getStyle($dataRange)->applyFromArray([
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN],
                    ],
                ]);
            },
        ];
    }
    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo Gereja');
        $drawing->setDescription('Logo Jemaat GKI Via Dolorosa Bintuni');
        $drawing->setPath(public_path('assets/img/logo.jpg')); // Ubah path sesuai lokasi file logo
        $drawing->setHeight(100); // Ukuran logo
        $drawing->setCoordinates('D3'); // Posisi logo di Excel
        return $drawing;
    }
}
