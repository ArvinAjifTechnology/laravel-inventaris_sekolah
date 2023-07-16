@extends('layouts.main')

@section('content')
<div class="container">
    <div class="row justify-content-center align-content-center mt-3">
        <div class=" col-md-12">
            @can('admin')
            <a href="{{ url('/admin/items/create') }}" class="btn btn-primary mb-2">{{ __('items.AddItem') }}</a>
            @endcan
            @can('operator')
            <a href="{{ url('/operator/items/create') }}" class="btn btn-primary mb-2">{{ __('items.AddItem') }}</a>
            @endcan

            <table id="basic-datatable" class="table table-striped dt-responsive nowrap w-100">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('items.ItemCode') }}</th>
                        <th>{{ __('items.ItemName') }}</th>
                        <th>{{ __('items.Room') }}</th>
                        <th>{{ __('items.Description') }}</th>
                        <th>{{ __('items.Condition') }}</th>
                        <th>{{ __('items.Amount') }}</th>
                        <th>{{ __('items.Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->item_code }}</td>
                        <td>{{ $item->item_name }}</td>
                        <td>{{ $item->room_name }}</td>
                        <td>{{ $item->description }}</td>
                        <td>{{ ucfirst($item->condition)}}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>
                            @can('admin')
                            <a href=" {{ url('/admin/items', $item->id) }}" class="btn btn-sm btn-primary">{{
                                __('items.View') }}</a>
                            <a href="{{ url('admin/items/'. $item->id.'/edit') }}" class="btn btn-sm btn-warning">{{
                                __('items.Edit') }}</a>
                            <form action="{{ url('/admin/items/'. $item->id) }}" method="POST"
                                style="display: inline-block">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('{{ __('items.DeleteItemConfirmation') }}')">
                                    {{ __('items.Delete') }}
                                </button>
                            </form>
                            @endcan
                            @can('operator')
                            <a href="{{ route('items.show', $item->id) }}" class="btn btn-sm btn-primary">{{
                                __('items.View') }}</a>
                            <a href="{{ route('items.edit', $item->id) }}" class="btn btn-sm btn-warning">{{
                                __('items.Edit') }}</a>
                            <form action="{{ route('items.destroy', $item->id) }}" method="POST"
                                style="display: inline-block">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('{{ __('items.DeleteItemConfirmation') }}')">
                                    {{ __('items.Delete') }}
                                </button>
                            </form>
                            @endcan
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection