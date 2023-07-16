@extends('layouts.main')

@section('content')
<div class="container">
    <div class="row justify-content-center d-flex align-content-center mt-3">
        <div class="col-md-6">
            <div class="progress">
                <div class="progress-bar" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0"
                    aria-valuemax="100"></div>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="row justify-content-center d-flex align-content-center">
        <div class="col-lg-6">
            <form method="POST" action="{{ url('/borrower/borrows/') }}" class="">
                @csrf
                <input type="hidden" name="uniqid" value="{{ uniqid() }}">
                <div class="form-group">
                    <label for="item_id">{{ __('borrowrequest.Item') }}</label>
                    <input type="text" id="item_id" class="form-control"
                        value="{{ $item->item_name . '-' . $item->item_code }}" readonly>
                </div>
                <div class="form-group">
                    <label for="user_id">{{ __('borrowrequest.User') }}</label>
                    <input type="text" id="user_id" class="form-control" value="{{ auth()->user()->full_name }}"
                        readonly>
                </div>
                <input type="hidden" name="item_id" value="{{ $request->item_id }}">
                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                <div class="form-group">
                    <label for="borrow_date">{{ __('borrowrequest.BorrowDate') }}</label>
                    <input type="date" id="borrow_date" name="borrow_date"
                        class="form-control @error('borrow_date') is-invalid @enderror"
                        value="{{ old('borrow_date', \Carbon\Carbon::parse($request->input('borrow_date'))->format('Y-m-d')) }}"
                        min="{{ date('Y-m-d') }}" required readonly>
                    @error('borrow_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="return_date">{{ __('borrowrequest.ReturnDate') }}</label>
                    <input type="date" id="return_date" name="return_date"
                        class="form-control @error('return_date') is-invalid @enderror"
                        value="{{ old('borrow_date', \Carbon\Carbon::parse($request->input('return_date'))->format('Y-m-d')) }}"
                        min="{{ date('Y-m-d', strtotime('+1 day')) }}" required readonly>
                    @error('return_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="borrow_quantity">{{ __('borrowrequest.BorrowQuantity') }}</label>
                    <input type="text" id="borrow_quantity" name="borrow_quantity"
                        class="form-control @error('borrow_quantity') is-invalid @enderror"
                        value="{{ old('borrow_quantity', $request->borrow_quantity) }}" required readonly>
                    @error('borrow_quantity')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary mt-4">{{ __('borrowrequest.SubmitBorrowRequest') }}</button>
            </form>

        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $("form").on("submit", function() {
            updateProgressBar(3);
        });
    });
</script>
@endsection
