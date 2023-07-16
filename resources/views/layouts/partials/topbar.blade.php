<!-- Topbar Start -->
<div class="navbar-custom">
    <ul class="list-unstyled topbar-menu float-end mb-0">
        <li class="dropdown notification-list d-lg-none">
            <a class="nav-link dropdown-toggle arrow-none" data-bs-toggle="dropdown" href="#" role="button"
                aria-haspopup="false" aria-expanded="false">
                <i class="dripicons-search noti-icon"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-animated dropdown-lg p-0">
                <form class="p-3">
                    <input type="text" class="form-control" placeholder="{{ __('menu.Search') }}"
                        aria-label="Recipient's username">
                </form>
            </div>
        </li>
        <li class="dropdown notification-list topbar-dropdown">
            <a class="nav-link dropdown-toggle arrow-none" data-bs-toggle="dropdown" href="#" role="button"
                aria-haspopup="false" aria-expanded="false">
                <img src="{{ asset('assets/images/flags/'.app()->getLocale().'.png') }}" alt="user-image" class="me-0
                me-sm-1" height="12">
                <span class="align-middle d-none d-sm-inline-block"></span> <i
                    class="mdi mdi-chevron-down d-none d-sm-inline-block align-middle"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated topbar-dropdown-menu">

                <!-- item-->
                <a href="{{ url('locale/en') }}" class="dropdown-item notify-item">
                    <img src="{{ asset('') }}assets/images/flags/en.png" alt="user-image" class="me-1" height="12">
                    <span class="align-middle">English</span>
                </a>

                <!-- item-->
                <a href="{{ url('locale/id') }}" class="dropdown-item notify-item">
                    <img src="{{ asset('') }}assets/images/flags/id.png" alt="user-image" class="me-1" height="12">
                    <span class="align-middle">Indonesia</span>
                </a>
            </div>
        </li>

        <li class="dropdown notification-list">
            <a class="nav-link dropdown-toggle nav-user arrow-none me-0" data-bs-toggle="dropdown" href="#"
                role="button" aria-haspopup="false" aria-expanded="false">
                <span class="account-user-avatar">
                    {{-- <img src="assets/images/users/avatar-1.jpg" alt="user-image" class="rounded-circle"> --}}
                </span>
                <span>
                    <span class="account-user-name">{{ auth()->user()->full_name }}</span>
                    <span class="account-position">{{ Str::ucfirst(auth()->user()->role) }}</span>
                </span>
            </a>
            <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated topbar-dropdown-menu profile-dropdown">
                <!-- item-->
                <div class=" dropdown-header noti-title">
                    <h6 class="text-overflow m-0">{{ __('menu.Welcome') }}</h6>
                </div>

                <!-- item-->
                <a href="{{ url('/profile') }}" class="dropdown-item notify-item">
                    <i class="mdi mdi-account-circle me-1"></i>
                    <span>{{ __('menu.MyAccount') }}</span>
                </a>

                <!-- item-->
                <a href="{{ url('contact') }}" class="dropdown-item notify-item">
                    <i class="mdi mdi-smart-card me-1"></i>
                    <span>{{ __('menu.Contact') }}</span>
                </a>

                <!-- item-->
                <a class="dropdown-item notify-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                         document.getElementById('logout-form').submit();">
                    <i class="mdi mdi-logout me-1"></i>
                    {{ __("menu.Logout") }}
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </li>

    </ul>
    <button class="button-menu-mobile open-left">
        <i class="mdi mdi-menu"></i>
    </button>
    <div class="app-search dropdown d-none d-lg-block">
        <form>
            <div class="input-group">
                <input type="text" class="form-control dropdown-toggle" placeholder="{{ __('menu.Search') }}"
                    id="top-search">
                <span class="mdi mdi-magnify search-icon"></span>
                <button class="input-group-text btn-primary" type="submit">{{ __('menu.Search') }}</button>
            </div>
        </form>

        <div class="dropdown-menu dropdown-menu-animated dropdown-lg" id="search-dropdown">
            <!-- item-->
            <div class="dropdown-header noti-title">
                <h5 class="text-overflow mb-2">{{ __('menu.Results') }} <span class="text-danger">17</span> {{
                    __('menu.Results') }}</h5>
            </div>

            <!-- item-->
            <a href="javascript:void(0);" class="dropdown-item notify-item">
                <i class="uil-notes font-16 me-1"></i>
                <span>{{ __('menu.AnalyticsReport') }}</span>
            </a>

            <!-- item-->
            <a href="javascript:void(0);" class="dropdown-item notify-item">
                <i class="uil-life-ring font-16 me-1"></i>
                <span>{{ __('menu.HowCanIHelpYou') }}</span>
            </a>

            <!-- item-->
            <a href="javascript:void(0);" class="dropdown-item notify-item">
                <i class="uil-cog font-16 me-1"></i>
                <span>{{ __('menu.UserProfileSettings') }}</span>
            </a>

            <!-- item-->
            <div class="dropdown-header noti-title">
                <h6 class="text-overflow mb-2 text-uppercase">{{ __('menu.Users') }}</h6>
            </div>

            <div class="notification-list">
                <!-- item-->
                <a href="javascript:void(0);" class="dropdown-item notify-item">
                    <div class="d-flex">
                        <img class="d-flex me-2 rounded-circle" src="assets/images/users/avatar-2.jpg"
                            alt="Generic placeholder image" height="32">
                        <div class="w-100">
                            <h5 class="m-0 font-14">Erwin Brown</h5>
                            <span class="font-12 mb-0">UI Designer</span>
                        </div>
                    </div>
                </a>

                <!-- item-->
                <a href="javascript:void(0);" class="dropdown-item notify-item">
                    <div class="d-flex">
                        <img class="d-flex me-2 rounded-circle" src="assets/images/users/avatar-5.jpg"
                            alt="Generic placeholder image" height="32">
                        <div class="w-100">
                            <h5 class="m-0 font-14">Jacob Deo</h5>
                            <span class="font-12 mb-0">Developer</span>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
<!-- end Topbar -->
