<x-mail::message>
    # Dari Inventaris Sekolah
    Halo {{ $borrow->user->full_name }},

    Rincian Pengajuan Peminjaman Anda dengan Kode Verifkasi Peminjaman :  {{ $borrow->verification_code_for_borrow_request }}
    yaitu sebagai berikut:

    - Nama Peminjam             : {{ $borrow->user->full_name }}
    - Nama Barang               : {{ $borrow->item->item_name }}
    - Kode Barang               : {{ $borrow->item->item_code }}
    - Tanggal Peminjaman        : {{ $borrow->borrow_date }}
    - Tanggal Pengembalian      : {{ $borrow->return_date }}
    - Jumlah Barang             : {{ $borrow->borrow_quantity }}

    Telah berhasil Verifkasi oleh {{ auth()->user()->name }}.

    Terima kasih,
{{ config('app.name') }}
</x-mail::message>
