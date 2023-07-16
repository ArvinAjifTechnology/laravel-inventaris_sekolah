@extends('layouts.main')

@section('content')
<!-- resources/views/item-report/index.blade.php -->
<div class="container mb-5 mt-3">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <form action="{{ route('borrower.borrow.search-item') }}" method="post" class="card-body">
                    @csrf
                    <div class="row">
                        <div class="col-md-10 mb-3">
                            <input type="text" id="search" name="search" class="form-control"
                                placeholder="{{ __('borrowrequest.SearchItemName') }}" value="{{ old('search') }}" />
                        </div>
                        <div class="col-md-2 my-0">
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">{{ __('borrowrequest.SearchItemName') }}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="rowr">
        <div class="col">
            @if (!empty($items))
            <table class="table table-primary">
                <thead>
                    <tr>
                        <th>{{ __('borrowrequest.ItemCode') }}</th>
                        <th>{{ __('borrowrequest.ItemName') }}</th>
                        <th>{{ __('borrowrequest.RoomName') }}</th>
                        <th>{{ __('borrowrequest.Description') }}</th>
                        <th>{{ __('borrowrequest.Condition') }}</th>
                        <th>{{ __('borrowrequest.RentalPrice') }}</th>
                        <th>{{ __('borrowrequest.LateFeePerDay') }}</th>
                        <th>{{ __('borrowrequest.StockQuantity') }}</th>
                        <th>{{ __('borrowrequest.Action') }}</th>
                        <!-- Add more fields according to the structure of the "items" table -->
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                    <tr>
                        <td>{{ $item->item_code }}</td>
                        <td>{{ $item->item_name }}</td>
                        <td>{{ $item->room_name }}</td>
                        <td>{{ $item->description }}</td>
                        <td>{{ $item->condition }}</td>
                        <td>{{ convertToRupiah($item->rental_price) }}</td>
                        <td>{{ convertToRupiah($item->late_fee_per_day) }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>
                            <a href="{{ url('/borrower/borrows/create/'.$item->item_code.'/submit-borrow-request') }}"
                                class="btn btn-primary">{{ __('borrowrequest.SubmitBorrowRequest') }}</a>
                        </td>
                        <!-- Add more fields according to the structure of the "items" table -->
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <p>{{ __('borrowrequest.NoItemsFound') }}</p>
            @endif
        </div>
    </div>
</div>
@endsection
