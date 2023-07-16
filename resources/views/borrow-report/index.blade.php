@extends('layouts.main')

@section('content')

<!-- resources/views/borrow-report/index.blade.php -->
<div class="container mb-1 mt-3">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <form action="{{ route('borrow-report.generate') }}" method="post" class="card-body">
                    @csrf
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="start_date" class="form-label">{{ __('borrowreport.StartDate') }}:</label>
                            <input type="date" id="start_date" name="start_date" class="form-control" />
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="end_date" class="form-label">{{ __('borrowreport.EndDate') }}:</label>
                            <input type="date" id="end_date" name="end_date" class="form-control" />
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="search" class="form-label">{{ __('borrowreport.Search') }}:</label>
                            <input type="text" id="search" name="search" class="form-control" />
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">{{ __('borrowreport.GenerateReport') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        {{-- <div> --}}
            {{-- <a
                href="{{ route('borrow-report.export', ['type' => 'pdf', 'start_date' => $startDate, 'end_date' => $endDate, 'search' => $search]) }}"
                target="_blank" class="btn btn-primary">Export to PDF</a>
            <a href="{{ route('borrow-report.export', ['type' => 'excel', 'start_date' => $startDate, 'end_date' => $endDate, 'search' => $search]) }}"
                class="btn btn-primary">Export to Excel</a> --}}
            {{-- <div>
                <form action="{{ route('borrow-report.export') }}" method="POST">
                    @csrf
                    <input type="hidden" name="type" value="pdf">
                    <input type="hidden" name="start_date" value="{{ $startDate }}">
                    <input type="hidden" name="end_date" value="{{ $endDate }}">
                    <input type="hidden" name="search" value="{{ $search }}">
                    <button type="submit">Export to PDF</button>
                </form>

                <form action="{{ route('borrow-report.export') }}" method="POST">
                    @csrf
                    <input type="hidden" name="type" value="excel">
                    <input type="hidden" name="start_date" value="{{ $startDate }}">
                    <input type="hidden" name="end_date" value="{{ $endDate }}">
                    <input type="hidden" name="search" value="{{ $search }}">
                    <button type="submit">Export to Excel</button>
                </form>
            </div> --}}

            {{--
        </div> --}}
        <div class="col">
            <div class="card">
                <div class="card-header">{{ __('borrowreport.Revenue') }}</div>
                <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                    {{ convertToRupiah($borrows->sum('sub_total')) }}
                </div>
            </div>
            @if (!empty($borrows))
            <table id="basic-datatable" class="table table-striped dt-responsive nowrap w-100">
                <thead>
                    <tr>
                        <th>{{ __('borrowreport.BorrowCode') }}</th>
                        <th>{{ __('borrowreport.BorrowDate') }}</th>
                        <th>{{ __('borrowreport.ReturnDate') }}</th>
                        <th>{{ __('borrowreport.BorrowStatus') }}</th>
                        <th>{{ __('borrowreport.BorrowerName') }}</th>
                        <th>{{ __('borrowreport.BorrowerEmail') }}</th>
                        <th>{{ __('borrowreport.ItemName') }}</th>
                        <th>{{ __('borrowreport.ItemCode') }}</th>
                        <th>{{ __('borrowreport.LateFee') }}</th>
                        <th>{{ __('borrowreport.TotalRentalPrice') }}</th>
                        <th>{{ __('borrowreport.SubTotal') }}</th>
                        <!-- Add other fields according to the structure of the "borrows" table -->
                    </tr>
                </thead>
                <tbody>
                    @foreach ($borrows as $borrow)
                    <tr>
                        <td>{{ $borrow->borrow_code }}</td>
                        <td>{{ $borrow->borrow_date }}</td>
                        <td>{{ $borrow->return_date }}</td>
                        <td>{{ $borrow->borrow_status }}</td>
                        <td>{{ $borrow->user_full_name }}</td>
                        <td>{{ $borrow->email }}</td>
                        <td>{{ $borrow->item_name }}</td>
                        <td>{{ $borrow->item_code }}</td>
                        <td>{{ convertToRupiah($borrow->late_fee) }}</td>
                        <td>{{ convertToRupiah($borrow->total_rental_price) }}</td>
                        <td>{{ convertToRupiah($borrow->sub_total) }}</td>
                        <!-- Add other fields according to the structure of the "borrows" table -->
                    </tr>
                    @endforeach
                    {{-- <tr>
                        <td colspan="10" align="right"><strong>Total Subtotal:</strong></td>
                        <td>{{ convertToRupiah($borrows->sum('sub_total')) }}</td>
                    </tr> --}}
                </tbody>
            </table>
            @else
            <p>{{ __('borrowreport.NoBorrowingDataFound') }}</p>
            @endif
        </div>
    </div>
</div>
@endsection