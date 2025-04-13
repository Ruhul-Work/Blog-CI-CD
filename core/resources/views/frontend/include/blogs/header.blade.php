    <!-- header starts -->
    <header class="main_header_area shadow-sm">
        <div class="top-navbar py-2" style="background: #0c685f;">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-12">
                        <div class="date-weather py-2 mb-lg-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <!-- Date Section -->
                                <div class="item d-flex align-items-center">
                                    <div class="icon me-2">
                                        <img src="{{ asset('theme/frontend/assets/images/icon/calendar.png') }}"
                                            alt="calendar icon">
                                    </div>
                                    <div class="inf text-white">
                                        <strong>{{ $headerDate['day'] }}</strong>
                                        <span>{{ $headerDate['date'] }}</span>
                                    </div>
                                </div>

                                <!-- Notification, Coins, and User Profile -->
                                <div class="item d-flex align-items-center">


                                    @auth
                                        <!-- mobile view search button -->
                                        {{-- <div class="notification-bell position-relative me-3">
                                            <div class="search-main search-main_mobile"><a href="#search1" class="mt_search"><i
                                                class="ri-search-line text-white"></i></a></div>  
                                        </div> --}}
                                        <!-- Coin Icon -->



                                        <div class="coin-icon me-3">
                                            <a href="{{ route('dashboard.index') }}">

                                                <i class="ri-dashboard-horizontal-line text-light"
                                                    style="font-size:36px !important"></i>
                                            </a>
                                        </div>
                                        <!-- User Profile Dropdown -->
                                        <div class="user-profile dropdown">
                                            <div class="profile-icon dropdown-toggle" id="dropdownMenuButton"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <img src="{{ Auth::user()->image ? asset(Auth::user()->image) : asset('theme/frontend/assets/images/user.png') }}"
                                                    alt="user-profile"
                                                    style="width: 35px; height: 35px; border-radius: 50%; object-fit: cover; cursor: pointer;">

                                            </div>
                                            <div class="dropdown-menu dropdown-menu-end"
                                                aria-labelledby="dropdownMenuButton">
                                                {{-- <div><a class="dropdown-item" href="#">Profile</a></div>
                                                <div><a class="dropdown-item" href="#">Settings</a></div> --}}
                                                <!--<div>-->
                                                <!--    <hr class="dropdown-divider">-->
                                                <!--</div>-->
                                                <div>
                                                    <a class="dropdown-item"
                                                        href="{{ route('dashboard.index') }}">Dashboard</a>
                                                    <a class="dropdown-item" href="{{ route('logout') }}">Logout</a>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <!-- Login/Register Buttons -->
                                        <div class="login-register-buttons">
                                            <a href="{{ route('login') }}">লগইন</a>
                                            <span>/</span>
                                            <a href="{{ route('register') }}">রেজিস্টার</a>
                                        </div>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('frontend.include.blogs.main_navbar')
    </header>
    <!-- header ends -->
