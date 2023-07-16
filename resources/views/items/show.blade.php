@extends('layouts.main')

@section('content')
<div class="container">
    <div class="row justify-content-center d-flex align-content-center" style="height: 100vh;">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    {{ __('items.ItemDetails') }}
                </div>
                <div class="card-body">
                    <h5 class="card-title">{{ __('items.ItemName') }}: {{ $item->item_name }}</h5>
                    <p class="card-text">{{ __('items.ItemCode') }}: {{ $item->item_code }}</p>
                    <p class="card-text">{{ __('items.Description') }}: {{ $item->description }}</p>
                    <p class="card-text">{{ __('items.Condition') }}: {{ $item->condition }}</p>
                    <p class="card-text">{{ __('items.RentalPrice') }}: {{ convertToRupiah($item->rental_price) }}</p>
                    <p class="card-text">{{ __('items.LateFeePerDay') }}: {{ convertToRupiah($item->late_fee_per_day) }}</p>
                    <p class="card-text">{{ __('items.Quantity') }}: {{ $item->quantity }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
