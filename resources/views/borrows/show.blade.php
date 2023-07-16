@extends('layouts.main')

@section('content')
<div class="container">
    <div class="row justify-content-center d-flex align-content-center" style="height: 100vh;">
        <div class="col-md-12">
            <div class="card-header">{{ __('borrows.BorrowDetails') }}</div>
            <div class="card-body">
                <h5 class="card-title">
                    {{ __('borrows.BorrowCode') }}: {{ $borrow->borrow_code }}
                </h5>
                <p class="card-text">
                    {{ __('borrows.ItemName') }}: {{ $borrow->item_name }}
                </p>
                <p class="card-text">
                    {{ __('borrows.ItemCode') }}: {{ $borrow->item_code }}
                </p>
                <p class="card-text">
                    {{ __('borrows.BorrowDate') }}: {{ $borrow->borrow_date }}
                </p>
                <p class="card-text">
                    {{ __('borrows.ReturnDate') }}: {{ $borrow->return_date }}
                </p>
                <p class="card-text">
                    {{ __('borrows.BorrowQuantity') }}: {{ $borrow->borrow_quantity }}
                </p>
                <p class="cborrows.ard-text">
                    {{ __('borrows.BorrowStatus') }}: {{ Str::ucfirst($borrow->borrow_status) }}
                </p>
                <p class="card-text">
                    {{ __('borrows.LateFee') }}: {{ convertToRupiah($borrow->late_fee) }}
                </p>
                <p class="card-text">
                    {{ __('borrows.TotalRentalPrice') }}: {{ convertToRupiah($borrow->total_rental_price) }}
                </p>
                <p class="card-text">
                    {{ __('borrows.SubTotal') }}: {{ convertToRupiah($borrow->sub_total) }}
                </p>
            </div>
            <div class="card-footer">
                @if($borrow->borrow_status==='completed' || $borrow->borrow_status==='rejected')
                @if ($borrow->borrow_status==='completed')
                <button type="submit" class="btn btn-success" disabled>
                    {{ __('borrows.Completed') }}
                </button>

                @elseif($borrow->borrow_status==='pending')
                <button type="submit" class="btn btn-warning" disabled>
                    {{ __('borrows.Pending') }}
                </button>
                @else
                <button type="submit" class="btn btn-danger" disabled>
                    {{ __('borrows.Rejected') }}
                </button>

                @endif
                @else @can('admin')
                <a href="{{ url('/admin/borrows/'. $borrow->id . '/edit') }}" class="btn btn-primary">{{
                    __('borrows.Edit')
                    }}</a>
                <form action="{{ url('/admin/borrows/'. $borrow->id. '/return') }}" method="POST"
                    style="display: inline-block">
                    @csrf
                    @method('PUT')
                    <button class="btn btn-primary" type="submit">
                        {{ __('borrows.Return') }}
                    </button>
                </form>
                @endcan @can('operator')
                <a href="{{ url('/operator/borrows/'. $borrow->id . '/edit') }}" class="btn btn-primary">{{
                    __('borrows.Edit')
                    }}</a>
                <form action="{{ url('/operator/borrows/'. $borrow->id. '/return') }}" method="POST"
                    style="display: inline-block">
                    @csrf
                    @method('PUT')
                    <button class="btn btn-primary" type="submit">
                        {{ __('borrows.Return') }}
                    </button>
                </form>
                @endcan @endif
                {{-- <form action="{{ route('/admin/borrows/', $borrow->id) }}" method="POST"
                    style="display: inline-block">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        {{ __('Delete') }}
                    </button>
                </form> --}}
            </div>
        </div>
    </div>
</div>
</div>
@endsection