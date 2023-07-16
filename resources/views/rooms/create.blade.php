@extends('layouts.main')

@section('content')
<div class="container">
    <div class="row justify-content-center d-flex align-content-center" style="height: 100vh;">
        <div class="col-lg-6">
            <form method="POST" action="{{ route('rooms.store') }}" class="">
                @csrf
                <div class="form-group">
                    <label for="room_name">{{ __('rooms.RoomName') }}</label>
                    <input type="text" id="room_name" name="room_name"
                        class="form-control @error('room_name') is-invalid @enderror" value="{{ old('room_name') }}"
                        required>
                    @error('room_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="user_id">{{ __('rooms.ResponsiblePerson') }}</label>
                    <select id="user_id" name="user_id"
                        class="form-control  @error('user_id') is-invalid @enderror select2" data-toggle="select2">
                        <option value="">{{ __('rooms.SelectUser') }}</option>
                        @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('user_id')==$user->id ? 'selected' : '' }}>{{
                            $user->full_name }}</option>
                        @endforeach
                    </select>
                    @error('user_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="description">{{ __('rooms.Description') }}</label>
                    <textarea id="description" name="description"
                        class="form-control @error('description') is-invalid @enderror"
                        rows="3">{{ old('description') }}</textarea>
                    @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary mt-4">{{ __('rooms.Submit') }}</button>
            </form>

        </div>
    </div>
</div>
@endsection