@extends('backend.layouts.master')
@section('meta')
    <title>All Sales- {{ get_option('title') }}</title>
@endsection

@section('content')
    <div id="app">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4>Sales</h4>
                    <h6>Manage Sales</h6>
                </div>
            </div>

            <div class="page-btn">
                <a href="{{ route('sales.report') }}" class="btn btn-primary me-2">
                    <i data-feather="plus-circle"></i>All Sales
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary">
                        <h5 class="mb-0 text-white">Daily Sales Report</h5>
                    </div>
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-lg-12 col-sm-6 col-12">
                                <a href="{{ route('sales.daily') }}" class="text-decoration-none">
                                    <div class="d-flex align-items-center">
                                        <span class="icon me-2"><i class="fas fa-chart-line"></i></span>
                                        <span class="text-primary">Daily Sales</span>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary">
                        <h5 class="mb-0 text-white">Monthly Sales Report</h5>
                    </div>
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-lg-12 col-sm-6 col-12">
                                <a href="{{ route('sales.monthly') }}" class="text-decoration-none">
                                    <div class="d-flex align-items-center">
                                        <span class="icon me-2"><i class="fas fa-chart-line"></i></span>
                                        <span class="text-primary">Monthly Sales</span>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary">
                        <h5 class="mb-0 text-white">Yearly Sales Report</h5>
                    </div>
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-lg-12 col-sm-6 col-12">
                                <a href="{{ route('sales.yearly') }}" class="text-decoration-none">
                                    <div class="d-flex align-items-center">
                                        <span class="icon me-2"><i class="fas fa-chart-line"></i></span>
                                        <span class="text-primary">Yearly Sales</span>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!--<div class="col-md-4">-->
            <!--    <div class="card shadow-sm">-->
            <!--        <div class="card-header bg-primary">-->
            <!--            <h5 class="mb-0 text-white">Report By Payments</h5>-->
            <!--        </div>-->
            <!--        <div class="card-body p-3">-->
            <!--            <div class="row">-->
            <!--                <div class="col-lg-12 col-sm-6 col-12">-->
            <!--                    <a href="{{ route('sales.payments') }}" class="text-decoration-none">-->
            <!--                        <div class="d-flex align-items-center">-->
            <!--                            <span class="icon me-2"><i class="fas fa-chart-line"></i></span>-->
            <!--                            <span class="text-primary">Payment Reports</span>-->
            <!--                        </div>-->
            <!--                    </a>-->
            <!--                </div>-->
            <!--            </div>-->
            <!--        </div>-->
            <!--    </div>-->
            <!--</div>-->
        </div>

    </div>
@endsection
@section('script')
    <script></script>
@endsection
