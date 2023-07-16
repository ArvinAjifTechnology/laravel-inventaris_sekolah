@extends('layouts.main')

@section('content')
<div class="container">
    <div class="row justify-content-center d-flex align-content-center mt-3">
        <div class="col-md-6">
            <div class="progress">
                <div class="progress-bar" role="progressbar" style="width: 66%;" aria-valuenow="66" aria-valuemin="0"
                    aria-valuemax="100"></div>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="row justify-content-center d-flex align-content-center">
        <div class="col-lg-6">
            <form method="POST" action="{{ url('/borrower/borrows/create/submit-borrow-request-verifiy/') }}" class="">
                @csrf
                <input type="hidden" name="item_id" value="{{ $request->item_id }}">
                <input type="hidden" name="user_id" value="{{ $request->user_id }}">
                <div class="form-group">
                    <label for="borrow_date">{{ __('borrowrequest.BorrowDate') }}</label>
                    <input type="date" id="borrow_date" name="borrow_date"
                        class="form-control @error('borrow_date') is-invalid @enderror"
                        value="{{ old('borrow_date', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}" required>
                    @error('borrow_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="return_date">{{ __('borrowrequest.ReturnDate') }}</label>
                    <input type="date" id="return_date" name="return_date"
                        class="form-control @error('return_date') is-invalid @enderror"
                        value="{{ old('return_date', date('Y-m-d')) }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                        required>
                    @error('return_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="borrow_quantity">{{ __('borrowrequest.BorrowQuantity') }}</label>
                    <input type="text" id="borrow_quantity" name="borrow_quantity"
                        class="form-control @error('borrow_quantity') is-invalid @enderror"
                        value="{{ old('borrow_quantity') }}" required>
                    @error('borrow_quantity')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary mt-4">{{ __('borrowrequest.Next') }}</button>
            </form>

        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $("form").on("submit", function() {
            updateProgressBar(2);
        });
    });
</script>
@endsection
