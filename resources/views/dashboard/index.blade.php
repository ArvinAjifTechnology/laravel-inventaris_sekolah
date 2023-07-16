@extends('layouts.main')

@section('content')
<!-- Start Content-->
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <h4 class="page-title">{{ __('dashboard.Dashboard') }}</h4>
            <h4 class="page-title">{{ __('dashboard.Welcome') }}, {{ auth()->user()->full_name }}</h4>
        </div>
    </div>
</div>
<!-- end page title -->
@can('admin')

<div class="row">
    <div class="col-xl-5 col-lg-6">

        <div class="row">
            <div class="col-lg-6">
                <div class="card widget-flat">
                    <div class="card-body">
                        <div class="float-end">
                            <i class="mdi mdi-account-multiple widget-icon"></i>
                        </div>
                        <h5 class="text-muted fw-normal mt-0" title="{{ __('dashboard.NumberOfUsers') }}">{{
                            __('dashboard.Users') }}</h5>
                        <h3 class="mt-3 mb-3">{{ $user_count }}</h3>
                        <p class="mb-0 text-muted">
                            <span class="text-nowrap">{{ __('dashboard.CountOfUsers') }}</span>
                        </p>
                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col-->

            <div class="col-lg-6">
                <div class="card widget-flat">
                    <div class="card-body">
                        <div class="float-end">
                            <i class="mdi mdi-cart-plus widget-icon"></i>
                        </div>
                        <h5 class="text-muted fw-normal mt-0" title="{{ __('dashboard.NumberOfRooms') }}">{{
                            __('dashboard.Rooms') }}</h5>
                        <h3 class="mt-3 mb-3">{{ $room_count }}</h3>
                        <p class="mb-0 text-muted">
                            <span class="text-nowrap">{{ __('dashboard.CountOfRooms') }}</span>
                        </p>
                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col-->
        </div> <!-- end row -->

        <div class="row">
            <div class="col-lg-6">
                <div class="card widget-flat">
                    <div class="card-body">
                        <div class="float-end">
                            <i class="mdi mdi-currency-usd widget-icon"></i>
                        </div>
                        <h5 class="text-muted fw-normal mt-0" title="{{ __('dashboard.AverageRevenue') }}">{{
                            __('dashboard.Revenue') }}</h5>
                        @if (!empty($revenue))
                        <h3 class="mt-3 mb-3">{{ convertToRupiah($revenue) }}</h3>
                        @else
                        <h3 class="mt-3 mb-3">Rp 0</h3>
                        @endif
                        <p class="mb-0 text-muted">
                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col-->

            <div class="col-lg-6">
                <div class="card widget-flat">
                    <div class="card-body">
                        <div class="float-end">
                            <i class="mdi mdi-pulse widget-icon"></i>
                        </div>
                        <h5 class="text-muted fw-normal mt-0" title="{{ __('dashboard.Borrows') }}">{{
                            __('dashboard.Borrows') }}</h5>
                        <h3 class="mt-3 mb-3">{{ $borrowed_quantity }}</h3>
                        <p class="mb-0 text-muted">
                            <span class="text-nowrap">{{ __('dashboard.CountOfBorrows') }}</span>
                        </p>
                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col-->

            <div class="col-lg-6">
                <div class="card widget-flat">
                    <div class="card-body">
                        <div class="float-end">
                            <i class="mdi mdi-pulse widget-icon"></i>
                        </div>
                        <h5 class="text-muted fw-normal mt-0" title="{{ __('dashboard.Items') }}">{{
                            __('dashboard.Items') }}</h5>
                        <h3 class="mt-3 mb-3">{{ $item_count }}</h3>
                        <p class="mb-0 text-muted">
                            <span class="text-nowrap">{{ __('dashboard.CountOfItems') }}</span>
                        </p>
                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col-->
        </div> <!-- end row -->

    </div> <!-- end col -->

    <div class="col-xl-6 col-lg-5">
        <div class="card card-h-50">
            <div class="card-body">
                <div class="dropdown float-end">


                </div> <!-- end card-body-->
                <h1>{{ __('dashboard.UserChart') }}</h1>
                {!! $usersChart->container() !!}
            </div> <!-- end card-->

        </div> <!-- end col -->
    </div>
    <!-- end row -->

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">{{ __('dashboard.ItemsChart') }}</h4>
                    <div dir="ltr" class="mt-5">
                        {!! $itemsChart->container() !!}
                    </div>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col-->
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">{{ __('dashboard.Revenue') }}</h4>

                    <div class="chart-content-bg">
                        <div class="row text-center">
                            <div class="col-md-6">
                                <p class="text-muted mb-0 mt-3">{{ __('dashboard.CurrentWeek') }}</p>
                                <h2 class="fw-normal mb-3">
                                    <small class="mdi mdi-checkbox-blank-circle text-primary align-middle me-1"></small>
                                    <span>{{ convertToRupiah($currentWeekData) }}</span>
                                </h2>
                            </div>
                            <div class="col-md-6">
                                <p class="text-muted mb-0 mt-3">{{ __('dashboard.PreviousWeek') }}</p>
                                <h2 class="fw-normal mb-3">
                                    <small class="mdi mdi-checkbox-blank-circle text-success align-middle me-1"></small>
                                    <span>{{ convertToRupiah($previousWeekData) }}</span>
                                </h2>
                            </div>
                        </div>
                    </div>

                    <div class="dash-item-overlay d-none d-md-block mb-4" dir="ltr">
                        <h5>{{ __('dashboard.TodayEarning') }} {{ convertToRupiah($today) }}
                            </a>
                    </div>
                    <div dir="ltr" class="mt-5">
                        {!! $revenueChart->container() !!}
                    </div>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col-->
    </div>
    <!-- end row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">{{ __('dashboard.Borrows') }}</h4>

                    <div class="chart-content-bg">
                        <div class="row text-center">
                            <div dir="ltr" class="mt-5">
                                {!! $borrowsChart->container() !!}
                            </div>
                        </div> <!-- end card-body-->
                    </div> <!-- end card-->
                </div> <!-- end col-->
            </div>
            <!-- end row -->
        </div>
        <!-- container -->
        @endcan
        @can('operator')
        <div class="row">
            <div class="col-xl-4 col-lg-6">
                <div class="card widget-flat">
                    <div class="card-body">
                        <div class="float-end">
                            <i class="mdi mdi-cart-plus widget-icon"></i>
                        </div>
                        <h5 class="text-muted fw-normal mt-0" title="{{ __('dashboard.NumberOfRooms') }}">{{
                            __('dashboard.Rooms') }}</h5>
                        <h3 class="mt-3 mb-3">{{ $room_count }}</h3>
                        <p class="mb-0 text-muted">
                            <span class="text-nowrap">{{ __('dashboard.CountOfRooms') }}</span>
                        </p>
                    </div> <!-- end card-body -->
                </div> <!-- end card -->
            </div> <!-- end col -->

            <div class="col-xl-4 col-lg-6">
                <div class="card widget-flat">
                    <div class="card-body">
                        <div class="float-end">
                            <i class="mdi mdi-pulse widget-icon"></i>
                        </div>
                        <h5 class="text-muted fw-normal mt-0" title="{{ __('dashboard.Borrows') }}">{{
                            __('dashboard.Borrows') }}</h5>
                        <h3 class="mt-3 mb-3">{{ $borrowed_quantity }}</h3>
                        <p class="mb-0 text-muted">
                            <span class="text-nowrap">{{ __('dashboard.CountOfBorrows') }}</span>
                        </p>
                    </div> <!-- end card-body -->
                </div> <!-- end card -->
            </div> <!-- end col -->

            <div class="col-xl-4 col-lg-6">
                <div class="card widget-flat">
                    <div class="card-body">
                        <div class="float-end">
                            <i class="mdi mdi-pulse widget-icon"></i>
                        </div>
                        <h5 class="text-muted fw-normal mt-0" title="{{ __('dashboard.Items') }}">{{
                            __('dashboard.Items') }}</h5>
                        <h3 class="mt-3 mb-3">{{ $item_count }}</h3>
                        <p class="mb-0 text-muted">
                            <span class="text-nowrap">{{ __('dashboard.CountOfItems') }}</span>
                        </p>
                    </div> <!-- end card-body -->
                </div> <!-- end card -->
            </div> <!-- end col -->
        </div> <!-- end row -->
        @endcan
        @can('borrower')
        <div class="col-xl-4 col-lg-6">
            <div class="card widget-flat">
                <div class="card-body">
                    <div class="float-end">
                        <i class="mdi mdi-pulse widget-icon"></i>
                    </div>
                    <h5 class="text-muted fw-normal mt-0" title="{{ __('dashboard.Borrows') }}">{{
                        __('dashboard.Borrows') }}</h5>
                    <h3 class="mt-3 mb-3">{{ $borrowed_quantity }}</h3>
                    <p class="mb-0 text-muted">
                        <span class="text-nowrap">{{ __('dashboard.CountOfBorrows') }}</span>
                    </p>
                </div> <!-- end card-body -->
            </div> <!-- end card -->
        </div> <!-- end col -->
        @endcan
    </div>
    <!-- content -->
    @endsection

    @push('js')
    @can('admin')
    <script src="{{ $usersChart->cdn() }}"></script>
    {!! $usersChart->script() !!}
    <script src="{{ $borrowsChart->cdn() }}"></script>
    {!! $borrowsChart->script() !!}
    <script src="{{ $revenueChart->cdn() }}"></script>
    {!! $revenueChart->script() !!}
    <script src="{{ $itemsChart->cdn() }}"></script>
    {!! $itemsChart->script() !!}
    @endcan
    @endpush