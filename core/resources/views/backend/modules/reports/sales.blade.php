@extends('backend.layouts.master')
@section('meta')
    <title>All Daily Sales- {{ get_option('title') }}</title>
@endsection

@section('content')
    <div id="app">

        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4>Daily Sales Report </h4>
                    <h6>Manage Daily Sales</h6>
                </div>
            </div>
            <ul class="table-top-head">
                @include('backend.include.buttons')

            </ul>
            <div class="page-btn">
                <a href="{{ route('sales.report') }}" class="btn btn-primary me-2">
                    <i data-feather="arrow-left"></i>Back
                </a>
            </div>
        </div>


<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body p-2">
                <div class="row">
                    <div class="col-lg-4 col-sm-12 col-12">
                        <label>Date Range</label>
                        <input type="text" name="datetimes" id="datetimes" class="form-control dateRangePredifined" value="">
                    </div>
                    <div class="col-lg-4 col-sm-12 col-12 ">
                        <label>Year</label>
                        <select id="year" class="form-control">
                            <option value="">All</option>
                            @for ($i = date('Y'); $i >= date('Y') - 5; $i--)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-lg-4 col-sm-12 col-12 ">
                        <label>Month</label>
                        <select id="month" class="form-control">
                            <option value="">All</option>
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}">{{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-lg-12 mt-2 text-center">
                        <button type="button" class="btn btn-primary mt-2" onclick="applyFilters()">Search</button>
                        <button type="button" class="btn btn-secondary mt-2" onclick="resetFilters()">Reset</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


        <!--<div class="row">-->
        <!--    <div class="col-md-4">-->
        <!--        <div class="card">-->
        <!--            <div class="card-body p-2">-->
        <!--                <div class="row">-->
        <!--                    <div class="col-lg-12 col-sm-6 col-12">-->
        <!--                        <label>Date Range</label>-->
        <!--                        <input type="text" name="datetimes" id="datetimes" class="form-control dateRangePredifined" value="">-->
        <!--                        <button type="button" class="btn btn-primary mt-2" onclick="applyFilters()">Search</button>-->
        <!--                        <button type="button" class="btn btn-secondary mt-2"-->
        <!--                            onclick="resetFilters()">Reset</button>-->
        <!--                    </div>-->
        <!--                </div>-->
        <!--            </div>-->
        <!--        </div>-->
        <!--    </div>-->
        <!--</div>-->




        <div class="row">
            <div class="col-md-12">
                <div class="card table-list-card">
                    <div class="card-body p-4">
                        <div class="table-top">
                            <div class="search-set">
                                <div class="search-input">
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover AjaxDataTable" style="width:100%;">
                                <thead>
                                    <tr>

                                        <th  style="font-size: 14px; font-weight:600px; width:100px">SN</th>
                                        <th class="no-sort" style="font-size: 14px; font-weight:600px; width:100px">Date</th>
                                        
                                                 <th style="font-size: 14px; font-weight:600px; width:200px;">Total Order</th>
                                                          <th style="font-size: 14px; font-weight:600px; width:200px;">Paid Order</th>
                                                                   <th style="font-size: 14px; font-weight:600px; width:200px;">Unpaid Order</th>
                                        
                                        
                                        <!--<th class="no-sort" style="font-size: 14px; font-weight:600px; width:150px">Name</th>-->
                                        <!--<th style="font-size: 14px; font-weight:600px; width:200px;">Total Order</th>-->
                                        <!--<th style="font-size: 14px; font-weight:600px; width:200px;">Total Sale</th>-->

                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')





<script>
    AJAX_URL = "{{ route('sales.daily-report-ajax') }}";

    // Apply filters
    function applyFilters() {
        var filters = {
            datetimes: $("#datetimes").val(),
            year: $("#year").val(),
            month: $("#month").val(),
        };
        updateDataTable(filters);
    }

    // Update DataTable with new filters
    function updateDataTable(filters) {
        var dataTable = $('.AjaxDataTable').DataTable();
        var queryString = $.param(filters);
        var url = "{{ route('sales.daily-report-ajax') }}" + "?" + queryString;
        dataTable.ajax.url(url).load();
    }

    // Reset filters
    function resetFilters() {
        $("#datetimes").val("");
        $("#year").val("");
        $("#month").val("");
        var url = "{{ route('sales.daily-report-ajax') }}";
        var dataTable = $('.AjaxDataTable').DataTable();
        dataTable.ajax.url(url).load();
    }
</script>


    // <script>
    //     AJAX_URL = "{{ route('sales.daily-report-ajax') }}";

    //     function applyFilters() {
    //         var filters = {
    //             datetimes: $("#datetimes").val(),
    //         };
    //         updateDataTable(filters);
    //     }

    //     function updateDataTable(filters) {
    //         var dataTable = $('.AjaxDataTable').DataTable();
    //         var queryString = $.param(filters);
    //         var url = "{{ route('sales.daily-report-ajax') }}" + "?" + queryString;
    //         dataTable.ajax.url(url).load();
    //     }

    //     function resetFilters() {
    //         document.getElementById("datetimes").value = "";
    //         var url = "{{ route('sales.daily-report-ajax') }}";
    //         var dataTable = $('.AjaxDataTable').DataTable();
    //         dataTable.ajax.url(url).load();

    //     }
    // </script>
@endsection
