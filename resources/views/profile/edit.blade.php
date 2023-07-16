@extends('layouts.main')


@section('content')
<div class="container">
    <div class="row justify-content-center d-flex align-content-center" style="height: 100vh;">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Edit Profile</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf @method('PUT')
                        <div class="mb-3">
                            <label for="username" class="form-label">Username:</label>
                            <input type="text" class="form-control" name="username" value="{{ $user->username }}" />
                        </div>
                        <div class="mb-3">
                            <label for="user_code" class="form-label">User Code:</label>
                            <input type="text" class="form-control" name="user_code" value="{{ $user->user_code }}"
                                readonly />
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email:</label>
                            <input type="email" class="form-control" name="email" value="{{ $user->email }}" />
                        </div>
                        <div class="mb-3">
                            <label for="first_name" class="form-label">First Name:</label>
                            <input type="text" class="form-control" name="first_name" value="{{ $user->first_name }}" />
                        </div>
                        <div class="mb-3">
                            <label for="last_name" class="form-label">Last Name:</label>
                            <input type="text" class="form-control" name="last_name" value="{{ $user->last_name }}" />
                        </div>
                        <div class="mb-3">
                            <label for="gender" class="form-label">Gender:</label>
                            <select class="form-select" name="gender">
                                <option value="laki-laki" @if ($user->gender === 'laki-laki') selected
                                    @endif>Laki-laki
                                </option>
                                <option value="perempuan" @if ($user->gender === 'perempuan') selected
                                    @endif>Perempuan
                                </option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
