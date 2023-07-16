@extends('layouts.main')

@section('content')
<div class="container">
    <div class="row justify-content-center d-flex align-content-center" style="height: 100vh;">
        <div class="col-lg-6">
            @can('admin')
            <form method="POST" action="{{ url('/admin/items/'. $item[0]->id) }}" class="">
            @endcan
            @can('operator')
            <form method="POST" action="{{ url('/operator/items/'. $item[0]->id) }}" class="">
            @endcan
                @csrf
                @method('PUT')
                <input type="hidden" name="id" value="{{ $id }}">
                <div class="form-group">
                    <label for="item_code">{{ __('items.ItemCode') }}</label>
                    <input type="text" id="item_code" name="item_code" class="form-control @error('item_code') is-invalid @enderror" value="{{ old('item_code', $item[0]->item_code) }}" required readonly>
                    @error('item_code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="item_name">{{ __('items.ItemName') }}</label>
                    <input type="text" id="item_name" name="item_name" class="form-control @error('item_name') is-invalid @enderror" value="{{ old('item_name', $item[0]->item_name) }}" required>
                    @error('item_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="room_id">{{ __('items.Room') }}</label>
                    <select id="room_id" name="room_id" class="form-control @error('room_id') is-invalid @enderror">
                        <option value="">{{ __('items.ChooseRoom') }}</option>
                        @foreach($rooms as $room)
                            <option value="{{ $room->id }}" {{ old('room_id', $item[0]->room_id) == $room->id ? 'selected' : '' }}>{{ $room->room_name }}</option>
                        @endforeach
                    </select>
                    @error('room_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="description">{{ __('items.Description') }}</label>
                    <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description', $item[0]->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="condition">{{ __('items.Condition') }}</label>
                    <select id="condition" name="condition" class="form-control @error('condition') is-invalid @enderror">
                        <option value="">{{ __('items.ChooseCondition') }}</option>
                        <option value="good" {{ old('condition', $item[0]->condition) == 'good' ? 'selected' : '' }}>{{ __('items.Good') }}</option>
                        <option value="fair" {{ old('condition', $item[0]->condition) == 'fair' ? 'selected' : '' }}>{{ __('items.Fair') }}</option>
                        <option value="bad" {{ old('condition', $item[0]->condition) == 'bad' ? 'selected' : '' }}>{{ __('items.Bad') }}</option>
                    </select>
                    @error('condition')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="rental_price">{{ __('items.RentalPrice') }}</label>
                    <input type="number" id="rental_price" name="rental_price" class="form-control @error('rental_price') is-invalid @enderror" value="{{ old('rental_price', $item[0]->rental_price) }}" required>
                    @error('rental_price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="late_fee_per_day">{{ __('items.LateFeePerDay') }}</label>
                    <input type="number" id="late_fee_per_day" name="late_fee_per_day" class="form-control @error('late_fee_per_day') is-invalid @enderror" value="{{ old('late_fee_per_day', $item[0]->late_fee_per_day) }}" required>
                    @error('late_fee_per_day')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="quantity">{{ __('items.Quantity') }}</label>
                    <input type="number" id="quantity" name="quantity" class="form-control @error('quantity') is-invalid @enderror" value="{{ old('quantity', $item[0]->quantity) }}" required>
                    @error('quantity')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary mt-4">{{ __('items.Submit') }}</button>
            </form>

        </div>
    </div>
</div>
@endsection
