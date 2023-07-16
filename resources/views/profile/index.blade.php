@extends('layouts.main')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="card-title">Profile</h5>
    </div>
    <div class="card-body">
        <p class="card-text">Username: {{ $user->username }}</p>
        <p class="card-text">User Code: {{ $user->user_code }}</p>
        <p class="card-text">Email: {{ $user->email }}</p>
        <p class="card-text">First Name: {{ $user->first_name }}</p>
        <p class="card-text">Last Name: {{ $user->last_name }}</p>
        <p class="card-text">Role: {{ $user->role }}</p>
        <p class="card-text">Gender: {{ $user->gender }}</p>
    </div>
    <div class="card-footer">
        <a href="{{ route('profile.edit') }}" class="btn btn-primary"> <i class="fas fa-edit"></i> Edit Your Profile</a>
        <a href="{{ route('password.update') }}" class="btn btn-primary"> <i class="fas fa-edit"></i> Ubah Password</a>
    </div>
</div>
@endsection
