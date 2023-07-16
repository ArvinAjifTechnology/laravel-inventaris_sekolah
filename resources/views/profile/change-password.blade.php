@extends('layouts.main')

@section('content')

<div class="container">
    <div class="row d-flex justify-content-center align-items-center" style="height: 100vh;">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">{{ __('profile.ChangePassword') }}</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        <div class="form-group">
                            <label for="current_password">{{ __('profile.CurrentPassword') }}</label>
                            <input type="password" id="current_password" name="current_password"
                                class="form-control @error('current_password') is-invalid @enderror" required>
                            @error('current_password')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password">{{ __('profile.NewPassword') }}</label>
                            <input type="password" id="password" name="password"
                                class="form-control @error('password') is-invalid @enderror" required>
                        </div>
                        @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <div class="form-group">
                            <label for="password_confirmation">{{ __('profile.ConfirmNewPassword') }}</label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-primary mt-3">{{ __('profile.UpdatePassword') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
