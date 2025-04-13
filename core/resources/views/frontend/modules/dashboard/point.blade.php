@extends('frontend.layouts.master')
@section('meta')
    <title>Point | {{ get_option('title') }}</title>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">

            <!-- Sidebar -->
            @include('frontend.modules.dashboard.include.sidebar')
             <!-- Main Dashboard -->
             <div class="col-xl-10 col-lg-9 dashboard-section  dashboard-points">
                


                <div class="row">
                    <div class="col-md-12 mt-3">
                
                        <!-- Points Module -->
                        <div class="card points-card shadow-sm border-0 mb-4">
                            <div class="card-body d-flex justify-content-between align-items-center"
                                style="background: linear-gradient(to right, #0c685f, #05998a); color: white; border-radius: 8px;">
                                <div>
                                    <h5 class="mb-1 text-white">আপনার পয়েন্ট</h5>
                                    <h1 class="mb-0 text-white"><strong>{{ $users->points }}</strong> পয়েন্ট</h1>
                                    <p class="mt-2">সমমান মূল্য:100 <strong>৳ 100</strong></p>
                                </div>
                                <div>
                                    <img src="{{ asset('theme/frontend/assets/images/icon/coin-lg.png') }}" alt="Points Icon" style="width: 100px;">
                                </div>
                            </div>
                        </div>
                
                        <!-- Redeem Section -->
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">পয়েন্ট ব্যবহার করুন</h5>
                                <a href="{{ route('dashboard.coupon-users') }}" class="btn btn-outline-primary btn-sm">আরো দেখুন</a>
                            </div>
                            <div class="card-body">
                                <div class="row text-center">
                                    <!-- 100 Points -->
                                    <div class="col-md-3 mb-3">
                                        <div class="card border shadow-sm p-3">
                                            <img src="{{ asset('theme/frontend/assets/images/icon/coin-lg.png') }}" alt="Redeem Icon" style="width: 50px;"
                                                class="py-2">
                                            <h6>100 টাকা Discount</h6>
                                            <p class="text-muted mb-0"><strong>100 পয়েন্ট</strong></p>
                                        </div>
                                    </div>
                                    <!-- 300 Points -->
                                    {{-- <div class="col-md-3 mb-3">
                                        <div class="card border shadow-sm p-3">
                                            <img src="{{ asset('theme/frontend/assets/images/icon/coin-lg.png') }}" alt="Redeem Icon" style="width: 50px;"
                                                class="py-2">
                                            <h6>৩০ টাকা Discount</h6>
                                            <p class="text-muted mb-0"><strong>300 Points</strong></p>
                                        </div>
                                    </div>
                                    <!-- 500 Points -->
                                    <div class="col-md-3 mb-3">
                                        <div class="card border shadow-sm p-3">
                                            <img src="{{ asset('theme/frontend/assets/images/icon/coin-lg.png') }}" alt="Redeem Icon" style="width: 50px;"
                                                class="py-2">
                                            <h6>৫০ টাকা Discount</h6>
                                            <p class="text-muted mb-0"><strong>500 Points</strong></p>
                                        </div>
                                    </div>
                                    <!-- 1000 Points -->
                                    <div class="col-md-3 mb-3">
                                        <div class="card border shadow-sm p-3">
                                            <img src="{{ asset('theme/frontend/assets/images/icon/coin-lg.png') }}" alt="Redeem Icon" style="width: 50px;"
                                                class="py-2">
                                            <h6>১০০ টাকা Discount</h6>
                                            <p class="text-muted mb-0"><strong>1000 Points</strong></p>
                                        </div>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                
                        <!-- How to Earn Points -->
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">পয়েন্ট অর্জন করবেন কীভাবে</h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled">
                                    {{-- <li class="d-flex align-items-center mb-3">
                                        <i class="fa fa-check-circle text-success me-3"></i> দৈনিক Blogs পড়ে (সর্বোচ্চ <strong>10 Points</strong>).
                                    </li> --}}
                                    {{-- <li class="d-flex align-items-center mb-3">
                                        <i class="fa fa-thumbs-up text-primary me-3"></i> Blog লাইক করলে: <strong>5 Points</strong>।
                                    </li> --}}
                                    {{-- <li class="d-flex align-items-center mb-3">
                                        <i class="fa fa-comments text-info me-3"></i> Blog-এ কমেন্ট করলে: <strong>5 Points</strong>।
                                    </li> --}}
                                    <li class="d-flex align-items-center">
                                        <i class="fa fa-share text-warning me-3"></i> Blog শেয়ার করলে: <strong>1 Points</strong>।
                                    </li>
                                </ul>
                            </div>
                        </div>
                
                    </div>
                </div>
                

                


            </div>
 


            <style>
              
            </style>

        </div>
    </div>
</div>
@endsection
@section('scripts')
@endsection
