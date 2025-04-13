@extends('backend.layouts.master')
@section('meta')
    <title>Dashboard - {{ get_option('title') }}</title>
@endsection
@section('content')
    <div id="app">
        <div class="row">
            <div class="col-xl-2 col-sm-6 col-12 d-flex">
                <div class="dash-widget dash3 w-100 shadow">
                    <div class="dash-widgetimg">
                        <span><img src="{{ asset('theme/admin/assets/img/icons/profits.png') }}" alt="img"></span>
                    </div>
                    <div class="dash-widgetcontent">
                        <h5><span class="">@{{ todayOrders }}</span></h5>
                        <h6>Today Subscriber</h6>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-sm-6 col-12 d-flex">
                <div class="dash-widget w-100 shadow">
                    <div class="dash-widgetimg">
                        <span><img src="{{ asset('theme/admin/assets/img/icons/shopping-bag.png') }}" alt="img"></span>
                    </div>
                    <div class="dash-widgetcontent">
                        <h5><span class="">@{{ todaySales }}</span></h5>
                        <h6>Today Sales</h6>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-sm-6 col-12 d-flex">
                <div class="dash-widget dash1 w-100 shadow">
                    <div class="dash-widgetimg">
                        <span><img src="{{ asset('theme/admin/assets/img/icons/order.png') }}" alt="img"></span>
                    </div>
                    <div class="dash-widgetcontent">
                        <h5><span class="">@{{ totalOrders }}</span></h5>
                        <h6>Total Orders</h6>
                    </div>
                </div>
            </div>


            <div class="col-xl-2 col-sm-6 col-12 d-flex">
                <div class="dash-widget dash2 w-100 shadow">
                    <div class="dash-widgetimg">
                        <span><img src="{{ asset('theme/admin/assets/img/icons/growth.png') }}" alt="img"></span>
                    </div>
                    <div class="dash-widgetcontent">
                        <h5><span class="">@{{ totalSales }}</span></h5>
                        <h6>Total Sales</h6>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-sm-6 col-12 d-flex">
                <div class="dash-widget dash2 w-100 shadow">
                    <div class="dash-widgetimg">
                        <span><img src="{{ asset('theme/admin/assets/img/icons/pending.png') }}" alt="img"></span>
                    </div>
                    <div class="dash-widgetcontent">
                        <h5><span class="">@{{ pendingOrders }}</span></h5>
                        <h6>Total Post</h6>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-sm-6 col-12 d-flex">
                <div class="dash-widget dash2 w-100 shadow">
                    <div class="dash-widgetimg">
                        <span><img src="{{ asset('theme/admin/assets/img/icons/order-done.png') }}" alt="img"></span>
                    </div>
                    <div class="dash-widgetcontent">
                        <h5><span class="">@{{ completeOrders }}</span></h5>
                        <h6>Total Share</h6>
                    </div>
                </div>
            </div>
        </div>




        <div class="row">

            <div class="col-xl-12 col-sm-12 col-12">
                <div id="chart">
                    <div id="timeline-chart"></div>
                </div>
            </div>

            <div class="col-xl-12 col-sm-12 col-12 d-flex">
                <div class="card flex-fill default-cover mb-4 shadow">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Recent Posts</h4>
                        <div class="view-all-link">
                            <a href="javascript:void(0)" class="view-all d-flex align-items-center">
                                View All<span class="ps-2 d-flex align-items-center"><i data-feather="arrow-right"
                                        class="feather-16"></i></span>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive dataview">
                            <table class="table dashboard-recent-products">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Posts</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(blogs, index) in recentProducts" :key="blogs.id">
                                        <td>@{{ index + 1 }}</td>
                                        <td class="productimgname">
                                            <a href="#" class="product-img">
                                                <img :src="getImageUrl(blogs.thumbnail)" alt="blogs">
                                            </a>
                                            <a href="">@{{ blogs.title }}</a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- <div class="col-xl-6 col-sm-12 col-12 d-flex">
                <div class="card flex-fill default-cover mb-4 shadow">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Best Subscribed Posts</h4>
                        <div class="view-all-link">
                            <a href="javascript:void(0)" class="view-all d-flex align-items-center">
                                View All<span class="ps-2 d-flex align-items-center"><i data-feather="arrow-right"
                                        class="feather-16"></i></span>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive dataview">
                            <table class="table dashboard-recent-products">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Title</th>

                                    </tr>
                                </thead>
                                <tbody>

                                    <tr v-for="(top_selling, index) in topSellingPackages" :key="top_selling.id">
                                        <td>@{{ index + 1 }}</td>
                                        <td class="productimgname">
                                            <a href="#" class="product-img">
                                                <img :src="getImageUrl(top_selling.thumbnail)" alt="product">
                                            </a>
                                            <a class="fs-6 fw-bold" href="#">@{{ top_selling.title }}</a>
                                        </td>


                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div> --}}

        </div>
    </div>


    <style>
        .view-all-link .view-all {
            font-size: 16px;
            font-weight: 600;
            color: #4CAF50;
            transition: 0.6s ease-in-out
        }

        .view-all-link .view-all:hover {

            color: #ff002f;
        }

        .stock-alert {
            color: #fff;
            font-size: 25px;
            font-weight: 600;
            font-family: "Pacifico", cursive;
            height: 50px;
            width: 50px;
            padding: .1em;
            text-shadow: 0 0 2px #000;
            -moz-animation-duration: 1.5s;
            -webkit-animation-duration: 1.5s;
            -moz-animation-name: bgcolor;
            -webkit-animation-name: bgcolor;
            -moz-animation-iteration-count: infinite;
            -webkit-animation-iteration-count: infinite;
            border-radius: 50px;
            text-align: center;
            box-shadow: 0 5px 8px rgba(0, 0, 0, 0.6),
                inset 0 -5px 15px #777,
                inset 0 5px 20px rgba(255, 255, 255, 0),
                0 10px 20px rgba(255, 255, 255, 0),
                0 12px 30px rgba(255, 0, 0, 0);
            border-top: 1px #aaa solid;
        }

        @-moz-keyframes bgcolor {
            from {
                color: #ffffff;
                background: #444;
            }

            50% {
                color: #fff;
                background: #ff002f;
                box-shadow: 0 5px 8px rgba(0, 0, 0, 0.6),
                    inset 0 -5px 15px #777,
                    inset 0 5px 20px rgba(255, 255, 255, 0),
                    0 10px 20px rgba(255, 255, 255, 0),
                    0 12px 30px rgba(255, 0, 0, 0);
            }

            to {
                color: #ffffff;
                background: #444;
            }
        }

        @-webkit-keyframes bgcolor {
            from {
                color: #ffffff;
                background: #444;
            }

            50% {
                color: #fff;
                background: red;
                box-shadow: 0 5px 8px rgba(0, 0, 0, 0.6),
                    inset 0 -5px 15px #777,
                    inset 0 5px 20px rgba(255, 255, 255, 0),
                    0 10px 20px rgba(255, 255, 255, 0),
                    0 12px 30px rgba(255, 0, 0, 0);
            }

            to {
                color: #ffffff;
                background: #444;
            }
        }
    </style>
@endsection

@section('script')
    <style>
        @import url('https://fonts.googleapis.com/css?family=Poppins');

        * {
            font-family: 'Poppins', sans-serif;
        }

        #chart {

            margin: 35px auto;
            opacity: 0.9;
        }

        #timeline-chart .apexcharts-toolbar {
            opacity: 1;
            border: 0;
        }
    </style>




    {{-- vue js cdn --}}
    <script src="{{ asset('theme/admin/assets/vue/vue.js') }}" type="text/javascript"></script>
    <script src="{{ asset('theme/admin/assets/vue/axios.min.js') }}" type="text/javascript"></script>
    <script src="https://unpkg.com/vue-count-to@latest"></script>
    {{-- this page vue scripts --}}
    @include('backend.modules.dashboard.vue_script')
@endsection
