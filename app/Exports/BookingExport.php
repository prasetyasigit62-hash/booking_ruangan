<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class BookingExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $bookings;
    private $rowNumber = 0;

    public function __construct($bookings)
    {
        $this->bookings = $bookings;
    }

    public function collection()
    {
        return $this->bookings;
    }

    // ==========================================
    // 1. STRUKTUR KEPALA LAPORAN (JUDUL & HEADER)
    // ==========================================
    public function headings(): array
    {
        return [
            ['LAPORAN DATA PEMINJAMAN RUANGAN'], // Baris 1: Judul Laporan Utama
            ['Dicetak pada: ' . \Carbon\Carbon::now()->timezone('Asia/Jakarta')->format('d F Y, H:i')], // Baris 2: Tanggal Cetak
            [''], // Baris 3: Kosong (Sebagai Spasi)
            [     // Baris 4: Header Tabel Data Asli
                'NO',
                'KODE BOOKING',
                'PEMINJAM',
                'RUANGAN',
                'WAKTU MULAI',
                'TOTAL BAYAR (Rp)',
                'STATUS'
            ]
        ];
    }

    // ==========================================
    // 2. ISIAN DATA YANG DILEMPARKAN KE EXCEL
    // ==========================================
    public function map($row): array
    {
        $this->rowNumber++;
        return [
            $this->rowNumber,
            $row->kode_booking,
            $row->user->name ?? 'Admin',
            $row->ruangan->nama_ruangan ?? '-',
            \Carbon\Carbon::parse($row->waktu_mulai)->format('d M Y, H:i'),
            number_format($row->total_bayar, 0, ',', '.'),
            $row->status_booking
        ];
    }

    // ==========================================
    // 3. GAYA DESAIN (BORDER, WARNA, MERGE CELL)
    // ==========================================
    public function styles(Worksheet $sheet)
    {
        // Deteksi baris terakhir yang ada datanya secara otomatis
        $highestRow = $sheet->getHighestRow();

        // A. Styling Judul Laporan (Merge Cell & Center)
        $sheet->mergeCells('A1:G1');
        $sheet->mergeCells('A2:G2');
        $sheet->getStyle('A1:A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A2')->getFont()->setItalic(true);

        // B. Styling Header Tabel (Baris 4)
        $sheet->getStyle('A4:G4')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF0D6EFD'] // Biru Modern
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // C. Berikan Garis Tepi (Border) ke Seluruh Tabel (Dari baris 4 sampai data terakhir)
        $sheet->getStyle('A4:G' . $highestRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'], // Hitam
                ],
            ],
        ]);

        // D. Merapikan Posisi Teks (Alignment)
        // Rata Tengah untuk kolom NO, KODE, dan STATUS
        $sheet->getStyle('A5:B' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('G5:G' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Rata Kanan untuk kolom Total Bayar (agar angka terlihat rapi)
        $sheet->getStyle('F5:F' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        return [];
    }
}
