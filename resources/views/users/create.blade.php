@extends('layouts.main')
@section('content')

<!-- ... -->

<div class="container">
    <div class="row justify-content-center d-flex align-content-center" style="height:100vh">
        <div class="col-md-6">
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="username">{{ __('users.Username') }}</label>
                    <input type="text" id="username" name="username"
                        class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}"
                        unique>
                    @error('username')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">{{ __('users.Email') }}</label>
                    <input type="email" id="email" name="email"
                        class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                    @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="first_name">{{ __('users.FirstName') }}</label>
                    <input type="text" id="first_name" name="first_name" class="form-control"
                        value="{{ old('first_name') }}">
                </div>

                <div class="form-group">
                    <label for="last_name">{{ __('users.LastName') }}</label>
                    <input type="text" id="last_name" name="last_name" class="form-control"
                        value="{{ old('last_name') }}">
                </div>

                <div class="form-group">
                    <label for="role">{{ __('users.Role') }}</label>
                    <select id="role" name="role" class="form-control select2" data-toggle="select2">
                        <option value="admin" {{ old('role')=='admin' ? 'selected' : '' }}>
                            {{ __('users.Admin') }}</option>
                        <option value="operator" {{ old('role')=='operator' ? 'selected' : '' }}>
                            {{ __('users.Operator') }}</option>
                        <option value="borrower" {{ old('role')=='borrower' ? 'selected' : '' }}>
                            {{ __('users.Borrower') }}</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="gender">{{ __('users.Gender') }}</label>
                    <select id="gender" name="gender" class="form-control select2" data-toggle="select2">
                        <option value="laki-laki" {{ old('gender')=='laki-laki' ? 'selected' : '' }}>
                            {{ __('users.Male') }}</option>
                        <option value="perempuan" {{ old('gender')=='perempuan' ? 'selected' : '' }}>
                            {{ __('users.Female') }}</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary mt-3">{{ __('users.Save') }}</button>
            </form>
        </div>
    </div>
</div>
@endsection
