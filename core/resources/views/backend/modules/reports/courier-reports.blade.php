@extends('backend.layouts.master')
@section('meta')
    <title>Couriers Reports-{{ get_option('title') }}</title>
@endsection

@section('content')
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4>Couriers</h4>
                <h6>Couriers Reports</h6>
            </div>
        </div>
        <ul class="table-top-head">
            @include('backend.include.buttons')

        </ul>
        <div class="page-btn">
            <a href="javascript:void(0)" class="btn btn-primary me-2">
                <i data-feather="arrow-right"></i>Couriers
            </a>
        </div>
    </div>

    <div class="row">

        <div class="col-md-4">
            <div class="card">
                <div class="card-body p-2">
                    <label>Date Range:</label>
                    <div class="d-flex align-items-center">
                        <input type="text" name="datetimes" id="datetimes" class="form-control dateRangePredifined"
                            placeholder="select date" value="{{ date('Y-m-d') }} - {{ date('Y-m-d') }}">
                        <button style="margin-left:10px;" type="button" class="btn btn-primary"
                            onclick="applyFilters()">Search</button>
                        <button style="margin-left:10px;" type="button" class="btn btn-secondary"
                            onclick="resetFilters()">Reset</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="card">
                <div class="card-body p-2">
                    <label for="order_status">Order Status:</label>
                    <select id="order_status" name="order_status" class="form-control select" onchange="applyFilters()"
                        multiple>
                        <option value="#">--All--</option>
                        @foreach ($orderstatus as $o_status)
                            <option value="{{ $o_status->id }}">{{ ucfirst($o_status->name) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="card">
                <div class="card-body p-2">
                    <label for="courier">Couriers:</label>
                    <select id="courier" name="courier" class="form-control selectSimple" onchange="applyFilters()"
                        multiple>
                        <option value="#">--All--</option>
                        @foreach ($couriers as $courier)
                            <option value="{{ $courier->id }}">{{ ucfirst($courier->name) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>


        <div class="col-md-2">
            <div class="card">
                <div class="card-body p-2">
                    <label for="payment_status">Payment Status:</label>
                    <select id="payment_status" name="payment_status[]" class="form-control selectSimple"
                        onchange="applyFilters()" multiple>
                        <option value="#">--All--</option>
                        @foreach ($payment_status as $status)
                            <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>



    </div>

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
                                    <th style="font-size: 14px; font-weight:600px; width:100px">Invoice</th>
                                    <th style="font-size: 14px; font-weight:600px; width:200px">Name</th>
                                    <th style="font-size: 14px; font-weight:600px; width:300px;">Address</th>
                                    <th style="font-size: 14px; font-weight:600px; width:100px;">Phone</th>
                                    <th style="font-size: 14px; font-weight:600px; width:100px;">Amount</th>
                                    <th style="font-size: 14px; font-weight:600px; width:200px;">Note</th>
                                    <th style="font-size: 14px; font-weight:600px; width:100px;">Contact Name</th>
                                    <th style="font-size: 14px; font-weight:600px; width:100px;">Contact Phone</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        const AJAX_URL = "{{ route('couriers.reports-ajax') }}";

        if ($('.dateRangePredifined2').length > 0) {
            $(function() {

                //$('.dateRangePredifined2').val('');

                $('.dateRangePredifined2').daterangepicker({
                    opens: 'right',
                    autoUpdateInput: false,
                    ranges: {
                        'Today': [moment(), moment()],
                        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                            'month').endOf('month')],
                    },
                }, function(start, end, label) {

                    //$('.dateRangePredifined2').val(start.format('MM/DD/YYYY') + ' - ' + end.format(
                        //'MM/DD/YYYY'));
                });


                $('.dateRangePredifined2').attr('placeholder', 'Select date range');
            });
        }

        function applyFilters() {
            var filters = {
                payment_status: $("#payment_status").val(),
                datetimes: $("#datetimes").val(),
                payment_methods: $("#payment_methods").val(),
                order_status: $("#order_status").val(),
                courier: $("#courier").val()
            };
            updateDataTable(filters);
        }

        function updateDataTable(filters) {
            var dataTable = $('.AjaxDataTable').DataTable();
            var queryString = $.param(filters);
            var url = AJAX_URL + "?" + queryString;
            dataTable.ajax.url(url).load();
        }

        function resetFilters() {
            document.getElementById("datetimes").value = "";
            var url = "{{ route('couriers.reports-ajax') }}";
            var dataTable = $('.AjaxDataTable').DataTable();
            $('.dateRangePredifined').attr('placeholder', 'Select date range');
            dataTable.ajax.url(url).load();
        }
    </script>
@endsection
