@extends('layouts.main')
@section('content')
<div class="container-fluid">
    <div class="row mt-3">
        <div class="col-md-12">
            @can('admin')
            <a href="{{ route('rooms.create') }}" class="btn btn-primary mb-2">{{ __('rooms.AddRoomsData') }}</a>
            @endcan
            <table id="basic-datatable" class="table table-striped dt-responsive nowrap w-100">
                <thead>
                    <tr>
                        <th>{{ __('rooms.No') }}</th>
                        <th>{{ __('rooms.RoomCode') }}</th>
                        <th>{{ __('rooms.RoomName') }}</th>
                        <th>{{ __('rooms.ResponsiblePerson') }}</th>
                        <th>{{ __('rooms.Description') }}</th>
                        <th>{{ __('rooms.Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rooms as $room)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $room->room_code }}</td>
                        <td>{{ $room->room_name }}</td>
                        <td>{{ $room->user_name }}</td>
                        <td>{{ $room->description }}</td>
                        <td>
                            <div class="btn-group" role="group" aria-label="Action buttons mx-2">
                                @can('operator')
                                <a href="{{ url('operator/rooms', $room->id) }}" class="btn btn-info mx-2">{{
                                    __('rooms.Show') }}</a>
                                @endcan
                                @can('admin')
                                <a href="{{ url('admin/rooms', $room->id) }}" class="btn btn-info mx-2">{{
                                    __('rooms.Show') }}</a>
                                <a href="{{ route('rooms.edit', $room->id) }}" class="btn btn-warning mx-2">{{
                                    __('rooms.Edit') }}</a>
                                <form action="{{ route('rooms.destroy', $room->id) }}" method="POST"
                                    onsubmit="return confirm('{{ __('rooms.ConfirmDeleteRoom') }}')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        {{ __('rooms.Delete') }}
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
