@extends('layouts.main')

@section('content')
<div class="container">
    <div class="row justify-content-center d-flex align-content-center mt-3">
        <div class="col-md-6">
            <div class="progress">
                <div class="progress-bar" role="progressbar" style="width: 33%" aria-valuenow="33" aria-valuemin="0"
                    aria-valuemax="100"></div>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="row justify-content-center d-flex align-content-center">
        <div class="col-lg-6">
            <form method="POST" action="{{ url('/borrower/borrows/create/submit-borrow-request-two') }}" class="">
                @csrf
                <div class="form-group">
                    <label for="item_id">{{ __('borrowrequest.ItemName') }}</label>
                    <input type="text" id="item_id" class="form-control"
                        value="{{ $item->item_name . '-' . $item->item_code }}" readonly />
                </div>
                <div class="form-group">
                    <label for="user_id">{{ __('borrowrequest.User') }}</label>
                    <input type="text" id="user_id" class="form-control"
                        value="{{ auth()->user()->full_name }}" readonly />
                </div>
                <input type="hidden" name="item_id" value="{{ $item->id }}" />
                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}" />
                <button type="submit" class="btn btn-primary mt-4">{{ __('borrowrequest.Next') }}</button>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $("form").on("submit", function () {
            updateProgressBar(1);
        });
    });
</script>
@endsection
