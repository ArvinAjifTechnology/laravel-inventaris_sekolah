<x-mail::message>
    # Halo Kami Dari Invetaris Sekolah Terimakasih Telah memminjam Barang
    Berikut Detail Peminjaman:

    Borrow Details

    BorrowCode: {{ $borrow->borrow_code }}

    Item Name: {{ $borrow->item->item_name }}

    Item Code: {{ $borrow->item->item_code }}

    Borrow Date: {{ $borrow->borrow_date }}

    Return Date: {{ $borrow->return_date }}

    Borrow Quantity: {{ $borrow->borrow_quantity }}

    Borrow Status: {{ $borrow->borrow_status }}

    Late Fee: {{ convertToRupiah($borrow->late_fee) }}

    Total Rental Price: {{ convertToRupiah($borrow->total_rental_price) }}

    Sub Total: {{ convertToRupiah($borrow->sub_total) }}

    {{-- <x-mail::button :url="''"> Button Text </x-mail::button> --}}
    Thanks,
    {{ config("app.name") }}
</x-mail::message>
