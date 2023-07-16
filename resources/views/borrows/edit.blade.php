@extends('layouts.main')

@section('content')
<div class="container">
    <div class="row justify-content-center d-flex align-content-center" style="height: 100vh;">
        <div class="col-lg-6">
            @can('admin')
            <form method="POST" action="{{ url('/admin/borrows/'. $borrow->id) }}" class="">
                @endcan
                @can('operator')
                <form method="POST" action="{{ url('/operator/borrows/'. $borrow->id) }}" class="">
                    @endcan
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="item_id">{{ __('borrows.Item') }}</label>
                        <select id="item_id" name="item_id"
                            class="form-control @error('item_id') is-invalid @enderror select2" data-toggle="select2">
                            <option value="">{{ __('borrows.SelectItem') }}</option>
                            @foreach($items as $item)
                            <option value="{{ $item->id }}" {{ old('item_id', $borrow->item_id)==$item->id ? 'selected'
                                : ''
                                }}>{{
                                $item->item_name . '-'. $item->item_code}}</option>
                            @endforeach
                        </select>
                        @error('item_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="user_id">{{ __('borrows.User') }}</label>
                        <select id="user_id" name="user_id"
                            class="form-control @error('user_id') is-invalid @enderror select2" data-toggle="select2">>
                            <option value="">{{ __('borrows.SelectUser') }}</option>
                            @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id', $borrow->user_id) == $user->id ?
                                'selected' :
                                '' }}>{{ $user->full_name }}</option>
                            @endforeach
                        </select>
                        @error('user_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="borrow_code">{{ __('borrows.BorrowCode') }}</label>
                        <input type="text" id="borrow_code" name="borrow_code"
                            class="form-control @error('borrow_code') is-invalid @enderror"
                            value="{{ old('borrow_code', $borrow->borrow_code) }}" required disabled>
                        @error('borrow_code')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="borrow_date">{{ __('borrows.BorrowDate') }}</label>
                        <input type="date" id="borrow_date" name="borrow_date"
                            class="form-control @error('borrow_date') is-invalid @enderror"
                            value="{{ old('borrow_date', date('Y-m-d', strtotime($borrow->borrow_date))) }}"
                            min="{{ date('Y-m-d') }}" required>
                        @error('borrow_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="return_date">{{ __('borrows.ReturnDate') }}</label>
                        <input type="date" id="return_date" name="return_date"
                            class="form-control @error('return_date') is-invalid @enderror"
                            value="{{ old('return_date', date('Y-m-d', strtotime($borrow->return_date))) }}"
                            min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                        @error('return_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="borrow_quantity">{{ __('borrows.JumlahPinjam') }}</label>
                        <input type="text" id="borrow_quantity" name="borrow_quantity"
                            class="form-control @error('borrow_quantity') is-invalid @enderror"
                            value="{{ old('borrow_quantity', $borrow->borrow_quantity) }}" required>
                        @error('borrow_quantity')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary mt-4">{{ __('borrows.Submit') }}</button>
                </form>

        </div>
    </div>
</div>
@endsection
