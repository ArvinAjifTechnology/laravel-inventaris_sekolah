<div class="leftside-menu">
    <!-- LOGO -->
    <a href="{{ url('/dashborad') }}" class="logo text-center logo-light">
        <span class="logo-lg">
            <img
                src="{{ asset('') }}assets/images/logo.png"
                alt=""
                height="16"
            />
        </span>
        <span class="logo-sm">
            <img
                src="{{ asset('') }}assets/images/logo_sm.png"
                alt=""
                height="16"
            />
        </span>
    </a>

    <!-- LOGO -->
    <a href="{{ url('/') }}" class="logo text-center logo-dark">
        <span class="logo-lg">
            <img
                src="{{ asset('') }}assets/images/logo-dark.png"
                alt=""
                height="16"
            />
        </span>
        <span class="logo-sm">
            <img
                src="{{ asset('') }}assets/images/logo_sm_dark.png"
                alt=""
                height="16"
            />
        </span>
    </a>

    <div class="h-100" id="leftside-menu-container" data-simplebar="">
        <ul class="side-nav">
            <li class="side-nav-item">
                <a href="{{ route('dashboard.index') }}" class="side-nav-link">
                    <i class="uil-home-alt"></i>
                    <span>{{ __("menu.Dashboard") }}</span>
                </a>
            </li>

            @can('admin')
            <li class="side-nav-title side-nav-item">
                {{ __("menu.MasterData") }}
            </li>
            <li class="side-nav-item">
                <a
                    href="{{ route('admin.users.index') }}"
                    class="side-nav-link"
                >
                    <i class="uil-user"></i>
                    <span>{{ __("menu.Users") }}</span>
                </a>
            </li>

            <li class="side-nav-item">
                <a href="{{ url('admin/rooms') }}" class="side-nav-link">
                    <i class="uil-window"></i>
                    <span>{{ __("menu.Rooms") }}</span>
                </a>
            </li>

            <li class="side-nav-item">
                <a href="{{ url('admin/items') }}" class="side-nav-link">
                    <i class="uil-clipboard-alt"></i>
                    <span>{{ __("menu.Items") }}</span>
                </a>
            </li>

            @endcan @can('operator')
                <li class="side-nav-title side-nav-item">
                    {{ __("menu.MasterData") }}
                </li>
            <li class="side-nav-item">
                <a href="{{ url('operator/rooms') }}" class="side-nav-link">
                    <i class="uil-window"></i>
                    <span>{{ __("menu.Rooms") }}</span>
                </a>
            </li>

            <li class="side-nav-item">
                <a href="{{ url('operator/items') }}" class="side-nav-link">
                    <i class="uil-clipboard-alt"></i>
                    <span>{{ __("menu.Items") }}</span>
                </a>
            </li>

            @endcan

            <li class="side-nav-title side-nav-item">
                {{ __("menu.MainSystem") }}
            </li>
            @can('admin')
            <li class="side-nav-item">
                <a href="{{ url('admin/borrows') }}" class="side-nav-link">
                    <i class="uil-shopping-cart-alt"></i>
                    <span>{{ __("menu.Borrow") }}</span>
                </a>
            </li>
            <li class="side-nav-item">
                <a href="{{ url('/borrow-report') }}" class="side-nav-link">
                    <i class="uil-chart"></i>
                    <span>{{ __("menu.BorrowReport") }}</span>
                </a>
            </li>
            @endcan @can('operator')
            <li class="side-nav-item">
                <a href="{{ url('operator/borrows') }}" class="side-nav-link">
                    <i class="uil-shopping-cart-alt"></i>
                    <span>{{ __("menu.Borrow") }}</span>
                </a>
            </li>
            @endcan @can('borrower')
            <li class="side-nav-item">
                <a href="{{ url('borrower/borrows') }}" class="side-nav-link">
                    <i class="uil-shopping-cart-alt"></i>
                    <span>{{ __("menu.Borrow") }}</span>
                </a>
            </li>
            @endcan
        </ul>
    </div>
</div>
