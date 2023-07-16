@extends('layouts.main')

@section('content')
<div class="container">
    <div class="row justify-content-center d-flex align-content-center" style="height: 100vh;">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    {{ __('rooms.RoomDetails') }}
                </div>
                <div class="card-body">
                    <h5 class="card-title">{{ __('rooms.RoomName') }}: {{ $room->room_name }}</h5>
                    <p class="card-text">{{ __('rooms.RoomCode') }}: {{ $room->room_code }}</p>
                    <p class="card-text">{{ __('rooms.Description') }}: {{ $room->description }}</p>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    {{ __('rooms.ItemsInTheRoom') }}
                </div>
                <div class="card-body">
                    @if ($room->items->count() > 0)
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>{{ __('rooms.ItemCode') }}</th>
                                <th>{{ __('rooms.ItemName') }}</th>
                                <th>{{ __('rooms.Description') }}</th>
                                <th>{{ __('rooms.Condition') }}</th>
                                <th>{{ __('rooms.RentalPrice') }}</th>
                                <th>{{ __('rooms.LateFeePerDay') }}</th>
                                <th>{{ __('rooms.Quantity') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($room->items as $item)
                            <tr>
                                <td>{{ $item->item_code }}</td>
                                <td>{{ $item->item_name }}</td>
                                <td>{{ $item->description }}</td>
                                <td>{{ $item->condition }}</td>
                                <td>{{ $item->rental_price }}</td>
                                <td>{{ $item->late_fee_per_day }}</td>
                                <td>{{ $item->quantity }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <p>{{ __('rooms.NoItemsFound') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
