@extends('layouts.main')

@section('content')
<div class="container">
    <div class="row justify-content-center d-flex align-content-center mt-4">
        <div class="col-md-12">
            @can('admin')
            <a href="{{ url('/admin/borrows/create') }}" class="btn btn-primary btn-sm mb-2">{{ __('borrows.AddBorrow')
                }}</a>
            @endcan
            @can('operator')
            <a href="{{ url('/operator/borrows/create') }}" class="btn btn-primary btn-sm mb-2">{{
                __('borrows.AddBorrow') }}</a>
            @endcan
            <table id="basic-datatable" class="table table-striped dt-responsive nowrap w-100">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>{{ __('borrows.Actions') }}</th>
                        <th>{{ __('borrows.ItemName') }}</th>
                        <th>{{ __('borrows.BorrowerName') }}</th>
                        <th>{{ __('borrows.VerifyBorrowCode') }}</th>
                        <th>{{ __('borrows.BorrowCode') }}</th>
                        <th>{{ __('borrows.BorrowDate') }}</th>
                        <th>{{ __('borrows.ReturnDate') }}</th>
                        <th>{{ __('borrows.CountOfBorrow') }}</th>
                        <th>{{ __('borrows.BorrowStatus') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($borrows as $borrow)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            @can('admin')
                            <a href="{{ url('/admin/borrows', $borrow->id) }}" class="btn btn-info"><i
                                    class="fas fa-eye"></i> {{ __('borrows.Show') }}</a>
                            @endcan
                            @can('operator')
                            <a href="{{ url('/operator/borrows', $borrow->id) }}" class="btn btn-info"><i
                                    class="fas fa-eye"></i> {{ __('borrows.Show') }}</a>
                            @endcan

                            @if ($borrow->borrow_status == 'completed')
                            <button class="btn btn-success" disabled>
                                <i class="fas fa-check-circle"></i> {{ __('borrows.Completed') }}
                            </button>
                            @elseif ($borrow->borrow_status == 'pending')
                            @can('admin')
                            <a href="{{ url('admin/borrows/'.$borrow->borrow_code.'/submit-borrow-request') }}"
                                class="btn btn-warning">
                                <i class="fas fa-check"></i> {{ __('borrows.Verify') }}
                            </a>
                            <form action="{{ url('/admin/borrows/'. $borrow->id.'/reject-borrow-request') }}"
                                method="POST" style="display: inline-block">
                                @csrf @method('PUT')
                                <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('{{ __('borrows.ConfirmRejectBorrowRequest') }}')">
                                    <i class="fas fa-times"></i>
                                    {{ __('borrows.Reject') }}
                                </button>
                            </form>
                            @endcan
                            @can('operator')
                            <a href="{{ url('operator/borrows/'.$borrow->borrow_code.'/submit-borrow-request') }}"
                                class="btn btn-warning">
                                <i class="fas fa-check"></i> {{ __('borrows.Verify') }}
                            </a>
                            <form action="{{ url('/operator/borrows/'. $borrow->id.'/reject-borrow-request') }}"
                                method="POST" style="display: inline-block">
                                @csrf @method('PUT')
                                <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('{{ __('borrows.ConfirmRejectBorrowRequest') }}')">
                                    <i class="fas fa-times"></i>
                                    {{ __('borrows.Reject') }}
                                </button>
                            </form>
                            @endcan
                            @elseif ($borrow->borrow_status == 'borrowed')
                            @can('admin')
                            <form action="{{ url('/admin/borrows/'.$borrow->id.'/return') }}" method="POST"
                                style="display: inline-block">
                                @csrf
                                @method('PUT')
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-undo"></i> {{ __('borrows.Return') }}
                                </button>
                            </form>
                            <a href="{{ url('/admin/borrows/'.$borrow->id.'/edit') }}" class="btn btn-primary"><i
                                    class="fas fa-edit"></i> {{ __('borrows.Edit') }}</a>
                            @endcan
                            @can('operator')
                            <form action="{{ url('/operator/borrows/'.$borrow->id.'/return') }}" method="POST"
                                style="display: inline-block">
                                @csrf
                                @method('PUT')
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-undo"></i> {{ __('borrows.Return') }}
                                </button>
                            </form>
                            <a href="{{ url('/operator/borrows/'.$borrow->id.'/edit') }}" class="btn btn-primary"><i
                                    class="fas fa-edit"></i> {{ __('borrows.Edit') }}</a>
                            @endcan
                            @elseif($borrow->borrow_status==='rejected')
                            <button class="btn btn-danger" disabled>
                                <i class="fas fa-check-circle"></i> {{ __('borrows.Rejected') }}
                            </button>
                            @endif
                        </td>
                        <td>{{ $borrow->item_name }}</td>
                        <td>{{ $borrow->full_name }}</td>
                        <td>{{ $borrow->verification_code_for_borrow_request }}</td>
                        <td>{{ $borrow->borrow_code }}</td>
                        <td>{{ $borrow->borrow_date }}</td>
                        <td>{{ $borrow->return_date }}</td>
                        <td>{{ $borrow->borrow_quantity }}</td>
                        <td>
                            @if($borrow->borrow_status == 'completed')
                            <span class="badge bg-success">{{ __('borrows.Completed') }}</span>
                            @elseif($borrow->borrow_status == 'pending')
                            <span class="badge bg-warning">{{ __('borrows.Pending') }}</span>
                            @elseif($borrow->borrow_status == 'borrowed')
                            <span class="badge bg-primary">{{ __('borrows.Borrowed') }}</span>
                            @else
                            <span class="badge bg-danger">{{ __('borrows.Rejected') }}</span>
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