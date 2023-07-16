@extends('layouts.main')

@section('content')
<div class="container">
    <div class="row justify-content-center d-flex align-content-center mt-3">
        <div class="col-md-12">
            <a href="{{ url('/borrower/borrows/create/search-item') }}"
                class="btn btn-primary btn-sm mb-2">{{__('borrows.BorrowRequest')}}</a>
            <table id="basic-datatable" class="table table-striped dt-responsive nowrap w-100">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>{{ __('borrows.ItemName') }}</th>
                        <th>{{ __('borrows.BorrowerName') }}</th>
                        <th>{{ __('borrows.BorrowCode') }}</th>
                        <th>{{ __('borrows.BorrowDate') }}</th>
                        <th>{{ __('borrows.ReturnDate') }}</th>
                        <th>{{ __('borrows.BorrowQuantity') }}</th>
                        <th>{{ __('borrows.BorrowStatus') }}</th>
                        <th>{{ __('borrows.Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($borrows as $borrow)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $borrow->item_name }}</td>
                        <td>{{ $borrow->full_name }}</td>
                        <td>{{ $borrow->borrow_code }}</td>
                        <td>{{ $borrow->borrow_date }}</td>
                        <td>{{ $borrow->return_date }}</td>
                        <td>{{ $borrow->borrow_quantity }}</td>
                        <td>
                            @if($borrow->borrow_status == 'completed')
                            <span class="badge bg-success">{{ __('borrows.CompletedStatus') }}</span>
                            @elseif($borrow->borrow_status == 'pending')
                            <span class="badge bg-warning">{{ __('borrows.PendingStatus') }}</span>
                            @elseif($borrow->borrow_status == 'borrowed')
                            <span class="badge bg-primary">{{ __('borrows.BorrowedStatus') }}</span>
                            @else
                            <span class="badge bg-danger">{{ __('borrows.Rejected') }}</span>
                            @endif
                        </td>
                        <td>
                            @can('borrower')
                            <a href="{{ url('/borrower/borrows', $borrow->id) }}" class="btn btn-info"><i
                                    class="fas fa-eye"></i></a>
                            @endcan
                            @if($borrow->borrow_status == 'completed')
                            <button class="btn btn-success" disabled>
                                {{ __('borrows.CompletedStatus') }}
                            </button>
                            @elseif($borrow->borrow_status==='rejected')
                            <button class="btn btn-danger" disabled>
                                <i class="fas fa-check-circle"></i> {{ __('borrows.Rejected') }}
                            </button>
                            @endif
                        </td>
                    </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection