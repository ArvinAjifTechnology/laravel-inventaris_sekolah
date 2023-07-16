{{-- // resources/views/borrow-report/pdf.blade.php --}}

<!DOCTYPE html>
<html>

<head>
    <style>
        /* Tambahkan CSS styling sesuai kebutuhan Anda */
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
        }

        th {
            background-color: #ccc;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <h2>Laporan Peminjaman</h2>
    <p>Tanggal Peminjaman: {{ $startDate }} - {{ $endDate }}</p>
    <p>Pencarian: {{ $search }}</p>
    <table>
        <thead>
            <tr>
                <th>Kode Peminjaman</th>
                <th>Tanggal Peminjaman</th>
                <th>Tanggal Pengembalian</th>
                <th>Status Peminjaman</th>
                <th>Nama Peminjam</th>
                <th>Email Peminjam</th>
                <th>Nama Barang</th>
                <th>Kode Barang</th>
                <th>Denda</th>
                <th>Total Harga Pinjam</th>
                <!-- Tambahkan field lainnya sesuai dengan struktur tabel "borrows" -->
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($borrows as $borrow)
            <tr>
                <td>{{ $borrow->borrow_code }}</td>
                <td>{{ $borrow->borrow_date }}</td>
                <td>{{ $borrow->return_date }}</td>
                <td>{{ $borrow->borrow_status }}</td>
                <td>{{ $borrow->user->name }}</td>
                <td>{{ $borrow->user->email }}</td>
                <td>{{ $borrow->item->item_name }}</td>
                <td>{{ $borrow->item->item_code }}</td>
                <td>{{ convertToRupiah($borrow->late_fee) }}</td>
                <td>{{ convertToRupiah($borrow->total_rental_price) }}</td>
                <!-- Tambahkan field lainnya sesuai dengan struktur tabel "borrows" -->
                <td>{{ convertToRupiah($borrow->sub_total) }}</td>
            </tr>
            @endforeach
            <tr>
                <td colspan="10" align="right"><strong>Jumlah Subtotal:</strong></td>
                <td>{{ convertToRupiah($borrows->sum('sub_total')) }}</td>
            </tr>
        </tbody>
    </table>
</body>

</html>