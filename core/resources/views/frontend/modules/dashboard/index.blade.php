@extends('frontend.layouts.master')
@section('meta')
    <title>User Dashboard | {{ get_option('title') }}</title>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">

            <!-- Sidebar -->
            @include('frontend.modules.dashboard.include.sidebar')
            <!-- Main Dashboard -->
            <div class="col-xl-10 col-lg-9 dashboard-section ">


                <div class="row mt-5 mb-5">
                    <!-- Quick Action Cards -->
                    <div class="col-md-3">
                        <a href="{{ route('bloglist.index') }}">
                            <div class="card p-3 text-center custom-bg-success custom-card-hover">
                                <i class="ri-book-read-line"></i>
                                <p>ব্লগ পড়ুন</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('dashboard.coupon-users') }}">
                            <div class="card p-3 text-center custom-bg-info custom-card-hover">
                                <i class="ri-heart-3-fill"></i>
                                <p>কুপন</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('dashboard.point') }}">
                            <div class="card p-3 text-center custom-bg-warning custom-card-hover">
                                <i class="ri-copper-diamond-line"></i>
                                <p>পয়েন্ট রিডিম</p>
                            </div>
                        </a>
                    </div>
                    @php
                        $activePlan = auth()->user()->packageshow; 
                    @endphp

                    <div class="col-md-3">
                        <a href="{{ $activePlan ? route('dashboard.myPlan') : route('subscriptions.index') }}">
                            <div class="card p-3 text-center custom-bg-danger custom-card-hover">
                                <i class="ri-star-fill"></i>
                                <p>প্রিমিয়াম সাবস্ক্রিপশন</p>
                            </div>
                        </a>
                    </div>

                </div>

                <div class="row mt-5">
                    <!-- Accuracy Chart -->
                    <div class="col-md-12">
                        <div class="card chart-card p-3 bg-gradient-light shadow-sm">
                            <h5>কার্যকারিতা</h5>
                            <div id="accuracyChart" class="chart-container"></div>
                        </div>
                    </div>

                    <!-- Leaderboard -->
                    {{-- <div class="col-md-6">
                        <div class="card shadow-sm">
                            <div class="card-header text-white"
                                style="background: linear-gradient(45deg, #73eea6, #72ffd0); padding: 10px;">
                                <h5 class="mb-0"><i class="ri-trophy-line me-2"></i> লিডারবোর্ড</h5>
                            </div>
                            <div class="card-body">
                           

                              
                                <div class="custom-table-wrapper">
                                    <table class="table table-hover">
                                        <thead class="custom-table-header">
                                            <tr>
                                                <th scope="col">Comments</th>
                                                <th scope="col">Share</th>
                                                <th scope="col">Point</th>
                                             
                                              
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>{{ $blogsComments }}</td>
                                                <td>{{ $blogsShared }}</td>
                                                <td>{{ Auth::user()->points }}</td>

                                              
                                                
                                            </tr>
                                           
                                        </tbody>
                                    </table>
                                </div>
                                <div class="user-position-wrapper">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <span class="me-3">আপনার অবস্থান:</span>
                                            <img src="{{ asset('theme/frontend/assets/images/about_5.png') }}"
                                                class="rounded-circle me-2" width="30" height="30">
                                            <span>আপনি</span>
                                        </div>
                                        <div>
                                           
                                            <span class="ms-2"> <td>{{ Auth::user()->points }}</td></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                </div>


            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        // ApexCharts Example
        // var options = {
        //     chart: {
        //         type: 'pie',
        //         height: 300,
        //     },
        //     series: [230, 508, 52],
        //     labels: ['Blogs Read', 'Blogs Shared'],
        // };
        // var chart = new ApexCharts(document.querySelector("#accuracyChart"), options);
        // chart.render();

        document.addEventListener("DOMContentLoaded", function() {
            // Pass data directly from the backend
            var chartData = @json($chartData);

            var options = {
                chart: {
                    type: 'pie',
                    height: 300,
                },
                series: chartData.series,
                labels: chartData.labels,
            };
            var chart = new ApexCharts(document.querySelector("#accuracyChart"), options);
            chart.render();
        });
    </script>
@endsection
