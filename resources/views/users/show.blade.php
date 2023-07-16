@extends('layouts.main')

@section('content')
<div class="container">
    <div class="row justify-content-center d-flex align-content-center" style="height: 100vh;">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-text">{{ $user->full_name }}</h5>
                </div>
                <div class="card-body">
                    <p class="card-text">Username: {{ $user->username }}</p>
                    <p class="card-text">User Code: {{ $user->user_code }}</p>
                    <p class="card-text">Email: {{ $user->email }}</p>
                    <p class="card-text">First Name: {{ $user->first_name }}</p>
                    <p class="card-text">Last Name: {{ $user->last_name }}</p>
                    <p class="card-text">Role: {{ $user->role }}</p>
                    <p class="card-text">Gender: {{ $user->gender }}</p>
                    {{-- <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary">Edit</a> --}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
