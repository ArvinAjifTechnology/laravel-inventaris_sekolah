@extends('layouts.main')

@section('content')
<!-- resources/views/borrow-report/generate.blade.php -->
<div class="container">
    <div class="row justify-content-center d-flex align-content-center" style="height: 100vh;">
        <div>
            {{-- <a
                href="{{ route('borrow-report.export', ['type' => 'pdf', 'start_date' => $startDate, 'end_date' => $endDate, 'search' => $search]) }}"
                target="_blank" class="btn btn-primary">Export to PDF</a>
            <a href="{{ route('borrow-report.export', ['type' => 'excel', 'start_date' => $startDate, 'end_date' => $endDate, 'search' => $search]) }}"
                class="btn btn-primary">Export to Excel</a> --}}

            <div>
                <form action="{{ route('borrow-report.export') }}" method="POST">
                    @csrf
                    <input type="hidden" name="type" value="pdf">
                    <input type="hidden" name="start_date" value="{{ $startDate }}">
                    <input type="hidden" name="end_date" value="{{ $endDate }}">
                    <input type="hidden" name="search" value="{{ $search }}">
                    <button type="submit">Export ke PDF</button>
                </form>

                <form action="{{ route('borrow-report.export') }}" method="POST">
                    @csrf
                    <input type="hidden" name="type" value="excel">
                    <input type="hidden" name="start_date" value="{{ $startDate }}">
                    <input type="hidden" name="end_date" value="{{ $endDate }}">
                    <input type="hidden" name="search" value="{{ $search }}">
                    <button type="submit">Export ke Excel</button>
                </form>
            </div>

        </div>
        <div class="col">
            @if ($borrows->count() > 0)
            <table class="table table-primary">
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
                        <th>Sub Total</th>
                        <!-- Tambahkan field lainnya sesuai dengan struktur tabel "borrows" -->
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
                        <td>{{ convertToRupiah($borrow->sub_total) }}</td>
                        <!-- Tambahkan field lainnya sesuai dengan struktur tabel "borrows" -->
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="10" align="right"><strong>Jumlah Subtotal:</strong></td>
                        <td>{{ convertToRupiah($borrows->sum('sub_total')) }}</td>
                    </tr>
                </tbody>
            </table>
            @else
            <p>Tidak ada data peminjaman yang ditemukan.</p>
            @endif
        </div>
    </div>
</div>

@endsection
