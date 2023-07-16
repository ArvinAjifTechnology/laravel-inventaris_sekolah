<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Dashboard | Hyper - Responsive Bootstrap 5 Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description">
    <meta content="Coderthemes" name="author">
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('') }}assets/images/favicon.ico">

    <!-- third party css -->
    <link href="{{ asset('') }}assets/css/vendor/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css">
    <!-- third party css end -->
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com" />
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <!-- Datatables css -->
    <link href="{{ asset('') }}assets/css/vendor/dataTables.bootstrap5.css" rel="stylesheet" type="text/css">
    <link href="{{ asset('') }}assets/css/vendor/responsive.bootstrap5.css" rel="stylesheet" type="text/css">
    <link href="{{ asset('') }}assets/css/vendor/buttons.bootstrap5.css" rel="stylesheet" type="text/css">
    <link href="{{ asset('') }}assets/css/vendor/select.bootstrap5.css" rel="stylesheet" type="text/css">
    <!-- App css -->
    <link href="{{ asset('') }}assets/css/icons.min.css" rel="stylesheet" type="text/css">
    <link href="{{ asset('') }}assets/css/app.min.css" rel="stylesheet" type="text/css" id="light-style">
    <link href="{{ asset('') }}assets/css/app-dark.min.css" rel="stylesheet" type="text/css" id="dark-style">

</head>

<body class="loading"
    data-layout-config='{"leftSideBarTheme":"dark","layoutBoxed":false, "leftSidebarCondensed":false, "leftSidebarScrollable":false,"darkMode":false, "showRightSidebarOnStart": true}'>
    <!-- Begin page -->
    <div class="wrapper">
        @auth
        @include('layouts.partials.sidebar')
        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

        <div class="content-page">
            <div class="content">
                @include('layouts.partials.topbar')

                @yield('content')

            </div>
            <!-- content -->
            @endauth
            @guest
            @yield('content')
            @endguest

            <!-- Footer Start -->
            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6">
                            <script>
                                document.write(new Date().getFullYear())
                            </script> © inventaris_sekolah.com
                        </div>
                        <div class="col-md-6">
                            <div class="text-md-end footer-links d-none d-md-block">
                                <a href="{{ url('/about') }}">About</a>
                                <a href="{{ url('/contact') }}">Contact Us</a>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
            <!-- end Footer -->

        </div>

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->


    </div>
    <!-- END wrapper -->

    <!-- Right Sidebar -->
    <div class="end-bar">

        <div class="rightbar-title">
            <a href="javascript:void(0);" class="end-bar-toggle float-end">
                <i class="dripicons-cross noti-icon"></i>
            </a>
            <h5 class="m-0">Settings</h5>
        </div>

        <div class="rightbar-content h-100" data-simplebar="">

            <div class="p-3">
                <div class="alert alert-warning" role="alert">
                    <strong>Customize </strong> the overall color scheme, sidebar menu, etc.
                </div>

                <!-- Settings -->
                <h5 class="mt-3">Color Scheme</h5>
                <hr class="mt-1">

                <div class="form-check form-switch mb-1">
                    <input class="form-check-input" type="checkbox" name="color-scheme-mode" value="light"
                        id="light-mode-check" checked="">
                    <label class="form-check-label" for="light-mode-check">Light Mode</label>
                </div>

                <div class="form-check form-switch mb-1">
                    <input class="form-check-input" type="checkbox" name="color-scheme-mode" value="dark"
                        id="dark-mode-check">
                    <label class="form-check-label" for="dark-mode-check">Dark Mode</label>
                </div>


                <!-- Width -->
                <h5 class="mt-4">Width</h5>
                <hr class="mt-1">
                <div class="form-check form-switch mb-1">
                    <input class="form-check-input" type="checkbox" name="width" value="fluid" id="fluid-check"
                        checked="">
                    <label class="form-check-label" for="fluid-check">Fluid</label>
                </div>

                <div class="form-check form-switch mb-1">
                    <input class="form-check-input" type="checkbox" name="width" value="boxed" id="boxed-check">
                    <label class="form-check-label" for="boxed-check">Boxed</label>
                </div>


                <!-- Left Sidebar-->
                <h5 class="mt-4">Left Sidebar</h5>
                <hr class="mt-1">
                <div class="form-check form-switch mb-1">
                    <input class="form-check-input" type="checkbox" name="theme" value="default" id="default-check">
                    <label class="form-check-label" for="default-check">Default</label>
                </div>

                <div class="form-check form-switch mb-1">
                    <input class="form-check-input" type="checkbox" name="theme" value="light" id="light-check"
                        checked="">
                    <label class="form-check-label" for="light-check">Light</label>
                </div>

                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="theme" value="dark" id="dark-check">
                    <label class="form-check-label" for="dark-check">Dark</label>
                </div>

                <div class="form-check form-switch mb-1">
                    <input class="form-check-input" type="checkbox" name="compact" value="fixed" id="fixed-check"
                        checked="">
                    <label class="form-check-label" for="fixed-check">Fixed</label>
                </div>

                <div class="form-check form-switch mb-1">
                    <input class="form-check-input" type="checkbox" name="compact" value="condensed"
                        id="condensed-check">
                    <label class="form-check-label" for="condensed-check">Condensed</label>
                </div>

                <div class="form-check form-switch mb-1">
                    <input class="form-check-input" type="checkbox" name="compact" value="scrollable"
                        id="scrollable-check">
                    <label class="form-check-label" for="scrollable-check">Scrollable</label>
                </div>

                <div class="d-grid mt-4">
                    <button class="btn btn-primary" id="resetBtn">Reset to Default</button>

                    <a href="../../product/hyper-responsive-admin-dashboard-template/index.htm"
                        class="btn btn-danger mt-3" target="_blank"><i class="mdi mdi-basket me-1"></i> Purchase Now</a>
                </div>
            </div> <!-- end padding-->

        </div>
    </div>

    <div class="rightbar-overlay"></div>
    <!-- /End-bar -->

    <!-- bundle -->
    <script src="{{ asset('') }}assets/js/vendor.min.js"></script>
    <script src="{{ asset('') }}assets/js/app.min.js"></script>

    <!-- third party js -->
    <script src="{{ asset('') }}assets/js/vendor/jquery.dataTables.min.js"></script>
    <script src="{{ asset('') }}assets/js/vendor/dataTables.bootstrap5.js"></script>
    <script src="{{ asset('') }}assets/js/vendor/dataTables.responsive.min.js"></script>
    <script src="{{ asset('') }}assets/js/vendor/responsive.bootstrap5.min.js"></script>
    <script src="{{ asset('') }}assets/js/vendor/dataTables.buttons.min.js"></script>
    <script src="{{ asset('') }}assets/js/vendor/buttons.bootstrap5.min.js"></script>
    <script src="{{ asset('') }}assets/js/vendor/buttons.html5.min.js"></script>
    <script src="{{ asset('') }}assets/js/vendor/buttons.flash.min.js"></script>
    <script src="{{ asset('') }}assets/js/vendor/buttons.print.min.js"></script>
    <script src="{{ asset('') }}assets/js/vendor/dataTables.keyTable.min.js"></script>
    <script src="{{ asset('') }}assets/js/vendor/dataTables.select.min.js"></script>
    <!-- third party js ends -->

    <!-- demo app -->
    <script src="{{ asset('') }}assets/js/pages/demo.datatable-init.js"></script>
    <script src="{{ asset('') }}assets/js/pages/demo.dashboard.js"></script>
    <script src="{{ asset('') }}assets/js/pages/demo.toastr.js"></script>
    <!-- end demo js-->
    @if(session('status'))
    <script>
        $.NotificationApp.send("Success", "{{ session('status') }}", "top-right", "success", "ti-check");
    </script>
    @endif

    @stack('js')

</body>

</html>
