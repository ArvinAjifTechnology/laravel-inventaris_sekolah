// app/Exports/BorrowExport.php
<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Borrow;

class BorrowExport implements FromCollection, WithHeadings
{
    protected $borrows;
    protected $startDate;
    protected $endDate;
    protected $search;

    public function __construct($borrows, $startDate, $endDate, $search)
    {
        $this->borrows = $borrows;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->search = $search;
    }

    public function collection()
    {
        return $this->borrows;
    }

    public function headings(): array
    {
        return [
            'Kode Peminjaman',
            'Tanggal Peminjaman',
            'Tanggal Pengembalian',
            'Status Peminjaman',
            'Nama Peminjam',
            'Email Peminjam',
            'Nama Barang',
            'Kode Barang',
            'Denda',
            'Total Harga Pinjam',
            'Subtotal',
            // Tambahkan field lainnya sesuai dengan struktur tabel "borrows"
        ];
    }
}