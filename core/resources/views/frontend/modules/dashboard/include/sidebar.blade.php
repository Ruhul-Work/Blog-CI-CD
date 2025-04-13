<div class="col-xl-2 col-lg-3 sidebar d-none d-md-block bg-light shadow-sm">
    <div class="my-4">
        <a href="{{ route('home') }}">
            <img style="width:180px;" src="{{ asset('logo/logo.png') }}" alt="Logo" class="img-fluid">
        </a>
    </div>

    {{-- <a href="{{ route('dashboard.index') }}"
        class="d-block py-3 px-4 text-dark border-bottom {{ request()->routeIs('dashboard.index') ? 'active' : '' }}">
        <i class="ri-home-7-line"></i> ড্যাশবোর্ড
    </a> --}}

    @if (Auth::user()->user_role === '1')
        <!-- Show Admin Dashboard Menu -->
        <a href="{{ route('dash.home') }}"
            class="d-block py-3 px-4 text-dark border-bottom {{ request()->routeIs('dash.home') ? 'active' : '' }}">
            <i class="ri-shield-user-line"></i> অ্যাডমিন ড্যাশবোর্ড
        </a>
        <a href="{{ route('dashboard.index') }}"
            class="d-block py-3 px-4 text-dark border-bottom {{ request()->routeIs('dashboard.index') ? 'active' : '' }}">
            <i class="ri-home-7-line"></i> ড্যাশবোর্ড
        </a>
    @else
        <!-- Show User Dashboard Menu -->
        <a href="{{ route('dashboard.index') }}"
            class="d-block py-3 px-4 text-dark border-bottom {{ request()->routeIs('dashboard.index') ? 'active' : '' }}">
            <i class="ri-home-7-line"></i> ড্যাশবোর্ড
        </a>
    @endif

    <a href="{{ route('dashboard.myAccount') }}"
        class="d-block py-3 px-4 text-dark border-bottom {{ request()->routeIs('dashboard.myAccount') ? 'active' : '' }}">
        <i class="ri-account-box-line"></i> আমার অ্যাকাউন্ট
    </a>

    @php
        $activePlan = auth()->user()->packageshow;
    @endphp
    <a href="{{ $activePlan ? route('dashboard.myPlan') : route('subscriptions.index') }}"
        class="d-block py-3 px-4 text-dark border-bottom {{ request()->routeIs('dashboard.myPlan') ? 'active' : '' }}">
        <i class="ri-star-line"></i> আমার সাবস্ক্রিপশন
    </a>

    <a href="{{ route('dashboard.point') }}"
        class="d-block py-3 px-4 text-dark border-bottom {{ request()->routeIs('dashboard.point') ? 'active' : '' }}">
        <i class="ri-copper-diamond-line"></i> আমার পয়েন্ট
    </a>

    <a href="{{ route('dashboard.coupon-users') }}"
        class="d-block py-3 px-4 text-dark border-bottom {{ request()->routeIs('dashboard.coupon-users') ? 'active' : '' }}">
        <i class="ri-coupon-line"></i> কুপন
    </a>

    <a href="{{ route('logout') }}" class="d-block py-3 px-4 text-dark">
        <i class="ri-logout-circle-line"></i> লগআউট
    </a>
</div>


{{-- for mobile menu --}}
<div class="col-12 d-lg-none">
    <div class="d-flex align-items-center justify-content-between my-3">
        <!-- Logo -->
        <a href="{{ route('home') }}">
            <img style="width:180px;" src="{{ asset('logo/logo.png') }}" alt="Logo" class="img-fluid">
        </a>
        <!-- Menu Button -->
        <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasSidebar"
            aria-controls="offcanvasSidebar">
            <i class="ri-menu-line"></i>
        </button>
    </div>
    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasSidebar" aria-labelledby="offcanvasSidebarLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasSidebarLabel"><a href="{{ route('home') }}">
                <img style="width:180px;" src="{{ asset('logo/logo.png') }}" alt="Logo" class="img-fluid">
            </a></h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            {{-- <a href="{{ route('dashboard.index') }}" class="d-block py-2 active"><i class="ri-home-7-line"></i>
                ড্যাশবোর্ড</a>
            <a href="{{ route('dashboard.myAccount') }}" class="d-block py-2"><i class="ri-account-box-line"></i> আমার
                অ্যাকাউন্ট</a>
            <a href="{{ route('dashboard.myPlan') }}" class="d-block py-2"><i class="ri-star-line"></i> আমার
                সাবস্ক্রিপশন</a>
            <a href="{{ route('dashboard.point') }}" class="d-block py-2"><i class="ri-copper-diamond-line"></i> আমার
                পয়েন্ট</a>
            <a href="{{ route('dashboard.coupon') }}" class="d-block py-3 px-4 text-dark border-bottom"><i
                    class="ri-copper-diamond-line"></i> Generate
                Coupon</a>

            <a href="{{ route('dashboard.coupon-users') }}" class="d-block py-3 px-4 text-dark border-bottom"><i
                    class="ri-copper-diamond-line"></i>Coupons</a>

            <a href="{{ route('logout') }}" class="d-block py-2"><i class="ri-logout-circle-line"></i> লগআউট</a> --}}
            @if (Auth::user()->user_role === '1')
                <!-- Show Admin Dashboard Menu -->
                <a href="{{ route('dash.home') }}"
                    class="d-block py-3 px-4 text-dark border-bottom {{ request()->routeIs('dash.home') ? 'active' : '' }}">
                    <i class="ri-shield-user-line"></i> অ্যাডমিন ড্যাশবোর্ড
                </a>
                <a href="{{ route('dashboard.index') }}"
                    class="d-block py-3 px-4 text-dark border-bottom {{ request()->routeIs('dashboard.index') ? 'active' : '' }}">
                    <i class="ri-home-7-line"></i> ড্যাশবোর্ড
                </a>
            @else
                <!-- Show User Dashboard Menu -->
                <a href="{{ route('dashboard.index') }}"
                    class="d-block py-3 px-4 text-dark border-bottom {{ request()->routeIs('dashboard.index') ? 'active' : '' }}">
                    <i class="ri-home-7-line"></i> ড্যাশবোর্ড
                </a>
            @endif

            <a href="{{ route('dashboard.myAccount') }}"
                class="d-block py-3 px-4 text-dark border-bottom {{ request()->routeIs('dashboard.myAccount') ? 'active' : '' }}">
                <i class="ri-account-box-line"></i> আমার অ্যাকাউন্ট
            </a>

            @php
                $activePlan = auth()->user()->packageshow;
            @endphp
            <a href="{{ $activePlan ? route('dashboard.myPlan') : route('subscriptions.index') }}"
                class="d-block py-3 px-4 text-dark border-bottom {{ request()->routeIs('dashboard.myPlan') ? 'active' : '' }}">
                <i class="ri-star-line"></i> আমার সাবস্ক্রিপশন
            </a>

            <a href="{{ route('dashboard.point') }}"
                class="d-block py-3 px-4 text-dark border-bottom {{ request()->routeIs('dashboard.point') ? 'active' : '' }}">
                <i class="ri-copper-diamond-line"></i> আমার পয়েন্ট
            </a>

            <a href="{{ route('dashboard.coupon-users') }}"
                class="d-block py-3 px-4 text-dark border-bottom {{ request()->routeIs('dashboard.coupon-users') ? 'active' : '' }}">
                <i class="ri-coupon-line"></i> কুপন
            </a>

            <a href="{{ route('logout') }}" class="d-block py-3 px-4 text-dark">
                <i class="ri-logout-circle-line"></i> লগআউট
            </a>
        </div>
        <div class="offcanvas-footer">
            <div class="profile d-flex align-items-center">
                <img src="{{ Auth::user()->image ? asset(Auth::user()->image) : asset('theme/frontend/assets/images/user.png') }}" alt="Profile" class="profile-image">
                <div class="profile-info">
                    <p class="profile-name">{{ Auth::user()->name ? Auth::user()->name : 'user' }} <i class="ri-circle-fill" style="color: #28a745;"></i></p>
                    <p class="profile-location">{{ Auth::user()->address ? Auth::user()->address : ' ' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
 window.onload = function() {
    setTimeout(() => {
        document.body.classList.remove("night-mode");
    }, 500); // Removes after 500ms
};
</script>