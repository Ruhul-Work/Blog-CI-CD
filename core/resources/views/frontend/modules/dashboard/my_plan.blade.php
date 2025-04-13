@extends('frontend.layouts.master')
@section('meta')
    <title>My Plan | {{ get_option('title') }}</title>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">

            <!-- Sidebar -->
            @include('frontend.modules.dashboard.include.sidebar')
            <!-- Main Dashboard -->
            <div class="col-xl-10 col-lg-9 mt-2 dashboard-section">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card shadow-lg subscription-card border-0">
                            <!-- Card Header -->
                            <div class="card-header bg-gradient text-white d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><i class="ri-vip-crown-2-line me-2"></i> আপনার সাবস্ক্রিপশন পরিকল্পনা</h5>
                                {{-- <button class="btn btn-light btn-sm shadow-sm">
                                    <i class="ri-edit-line me-1"></i> পরিকল্পনা পরিবর্তন করুন
                                </button> --}}
                            </div>
                            <!-- Card Body -->
                            <div class="card-body p-4">
                                <div class="row gy-4">
                                    <div class="col-md-6">
                                        <div class="info-box d-flex align-items-center p-3 shadow-sm">
                                            <div class="icon-container text-primary me-3">
                                                <i class="ri-user-line"></i>
                                            </div>
                                            <div>
                                                <p class="fw-bold mb-1">সদস্য পদ:</p>
                                                @if ($subscriptionPackages)
                                                <p class="text-muted mb-0">প্রিমিয়াম</p>
                                                @else
                                                <p class="text-muted mb-0">Free</p>  
                                                @endif
                                                
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-box d-flex align-items-center p-3 shadow-sm">
                                            <div class="icon-container text-success me-3">
                                                <i class="ri-calendar-line"></i>
                                            </div>
                                            <div>
                                                <p class="fw-bold mb-1">মেয়াদ শেষ হওয়ার তারিখ:</p>
                                                <p class="text-muted mb-0">{{ $subscriptionPackages->end_date ?? '' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-box d-flex align-items-center p-3 shadow-sm">
                                            <div class="icon-container text-warning me-3">
                                                <i class="ri-refresh-line"></i>
                                            </div>
                                            <div>
                                                <p class="fw-bold mb-1">পরবর্তী পুনর্নবীকরণ:</p>
                                                <p class="text-muted mb-0">
                                                    {{ \Carbon\Carbon::parse($subscriptionPackages->end_date ?? '')->addDay()->format('F d, Y') }}
                                                </p>
                                                
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-box d-flex align-items-center p-3 shadow-sm">
                                            <div class="icon-container text-danger me-3">
                                                <i class="ri-money-dollar-circle-line"></i>
                                            </div>
                                            <div>
                                                <p class="fw-bold mb-1">মোট খরচ:</p>
                                                <p class="text-muted mb-0">৳  {{ $subscriptionPackages->package_price  ?? ''}}  ( {{ $subscriptionPackages->package->name  ?? '' }})</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Card Footer -->
                            <div class="card-footer bg-light p-3">
                                <p class="text-muted mb-0">
                                    <i class="ri-information-line me-2"></i>
                                    আপনার প্রিমিয়াম সদস্য পদ আপনাকে সকল প্রিমিয়াম ব্লগ পড়ার সুযোগ এবং এক্সক্লুসিভ কনটেন্ট অ্যাক্সেস দেয়।
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
 


        </div>
    </div>
</div>
@endsection
@section('scripts')
@endsection
