<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>{{ config("app.name", "Laravel") }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com" />
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link href="{{ asset('') }}assets/vendor/simple-datatables/style.css" rel="stylesheet">

    <!-- Scripts -->
    {{-- @vite(['resources/sass/app.scss', 'resources/sass/simple-datatables.scss', 'resources/js/app.js',
    'resources/js/simple-datatables.js']) --}}
    @vite(['resources/sass/app.scss', 'resources/js/app.js',])
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config("app.name", "Laravel") }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>
                @Auth
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        @can('admin')
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('admin/users*') ? 'active' : '' }}"
                                href="{{ route('admin.users.index') }}">{{ __("Users") }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('admin/rooms*') ? 'active' : '' }}"
                                href="{{ url('admin/rooms') }}">{{ __("Ruangan") }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('admin/items*') ? 'active' : '' }}"
                                href="{{ url('admin/items') }}">{{ __("Barang") }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('admin/borrows*') ? 'active' : '' }}"
                                href="{{ url('admin/borrows') }}">{{ __("Peminjaman") }}</a>
                        </li>
                        @endcan @can('operator')
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('operator/rooms*') ? 'active' : '' }}"
                                href="{{ url('operator/rooms') }}">
                                {{ __("Ruangan") }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('operator/items*') ? 'active' : '' }}"
                                href="{{ url('operator/items') }}">
                                {{ __("Barang") }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('operator/borrows*') ? 'active' : '' }}"
                                href="{{ url('operator/borrows') }}">
                                {{ __("Peminjaman") }}
                            </a>
                        </li>
                        @endcan @can('borrower')
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('borrower/borrows*') ? 'active' : '' }}"
                                href="{{ url('borrower/borrows') }}">
                                {{ __("Peminjaman") }}
                            </a>
                        </li>
                        @endcan
                    </ul>
                    @endauth
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        @auth
                        <li class="btn btn-primary">{{ __(ucfirst(Auth::user()->role)) }}</a>
                        </li>
                        @endauth
                        <!-- Authentication Links -->
                        @guest @if (Route::has('login'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __("Login") }}</a>
                        </li>
                        @endif @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __("Register") }}</a>
                        </li>
                        @endif @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->full_name }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('profile.index') }}">
                                    {{ __("Profile") }}
                                </a>
                                <a class="dropdown-item" href="{{ route('contact.index') }}">
                                    {{ __("Contact") }}
                                </a>
                                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                         document.getElementById('logout-form').submit();">
                                    {{ __("Logout") }}
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
            @yield('form')
        </main>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    {{-- <script>
        $(document).ready(function() {
        // Mengambil total jumlah halaman
        var totalPages = 3;

        // Mendapatkan URL halaman saat ini
        var currentUrl = window.location.href;

        // Menghitung persentase progress
        var progress = (currentUrl.split('/').length - 4) / totalPages * 100;

        // Mengupdate lebar progress bar sesuai dengan persentase
        $('.progress-bar').css('width', progress + '%');
        $('.progress-bar').attr('aria-valuenow', progress);
    });
    </script> --}}
    <script>
        // Ambil elemen progress bar
    var progressBar = $(".progress-bar");

    // Tentukan total jumlah langkah
    var totalSteps = 3;

    // Fungsi untuk mengupdate progress bar
    function updateProgressBar(step) {
        // Hitung persentase progres berdasarkan langkah saat ini
        var percentage = (step / totalSteps) * 100;

        // Update lebar dan teks pada progress bar
        progressBar.css("width", percentage + "%");
        progressBar.text(step + " / " + totalSteps);
    }
    </script>

    <script src="{{ asset('') }}assets/vendor/simple-datatables/simple-datatables.js"></script>
</body>

</html>
