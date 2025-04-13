@extends('backend.layouts.master')
@section('meta')
    <title>Payments Reports-{{ get_option('title') }}</title>
@endsection

@section('content')
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4>Sales</h4>
                <h6>Payments Reports</h6>
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
        <!-- Date Range Filter -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body p-2">
                    <label>Date Range:</label>
                    <div class="d-flex align-items-center">
                        <input type="text" name="datetimes" id="datetimes" class="form-control dateRangePredifined">
                        <button style="margin-left:10px;" type="button" class="btn btn-primary"
                            onclick="applyFilters()">Search</button>
                        <button style="margin-left:10px;" type="button" class="btn btn-secondary"
                            onclick="resetFilters()">Reset</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Methods Filter -->
        <div class="col-md-2">
            <div class="card">
                <div class="card-body p-2">
                    <label for="payment_methods">Payment Methods:</label>
                    <select id="payment_methods" name="payment_methods[]" class="form-control selectSimple"
                        onchange="applyFilters()" multiple>
                        <option value="#">--All--</option>
                        @foreach ($paymentmethods as $pmethod)
                            <option value="{{ $pmethod->id }}">{{ ucfirst($pmethod->name) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card">
                <div class="card-body p-2">
                    <label for="order_types">Order Type:</label>
                    <select id="order_types" name="order_types[]" class="form-control selectSimple"
                        onchange="applyFilters()" multiple>
                        <option value="#">--All--</option>
                        @foreach ($order_types as $o_types)
                            <option value="{{ $o_types }}">{{ ucfirst($o_types) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Order Status Filter -->
        <div class="col-md-2">
            <div class="card">
                <div class="card-body p-2">
                    <label for="order_status">Order Status:</label>
                    <select id="order_status" name="order_status[]" class="form-control selectSimple"
                        onchange="applyFilters()" multiple>
                        <option value="#">--All--</option>
                        @foreach ($orderstatus as $o_status)
                            <option value="{{ $o_status->id }}">{{ ucfirst($o_status->name) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Payment Status Filter -->
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
                                    <th style="font-size: 14px; font-weight:600px; width:100px">SN</th>
                                    <th style="font-size: 14px; font-weight:600px; width:200px">Order Number</th>
                                    <th style="font-size: 14px; font-weight:600px; width:200px;">Order Type</th>
                                    <th style="font-size: 14px; font-weight:600px; width:200px;">Payment Status</th>
                                    <th style="font-size: 14px; font-weight:600px; width:200px;">Order Status</th>
                                    <th style="font-size: 14px; font-weight:600px; width:200px;">Payment Method</th>
                                    <th style="font-size: 14px; font-weight:600px; width:200px;">Sale Date</th>
                                    <th style="font-size: 14px; font-weight:600px; width:100px;">Total</th>
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
        // Set the base URL for AJAX
        const AJAX_URL = "{{ route('sales.payments-ajax') }}";

        function applyFilters() {
            var filters = {
                payment_status: $("#payment_status").val(),
                datetimes: $("#datetimes").val(),
                payment_methods: $("#payment_methods").val(),
                order_status: $("#order_status").val(),
                order_types: $("#order_types").val() // Added missing comma
            };
            updateDataTable(filters);
        }


        function updateDataTable(filters) {
            var dataTable = $('.AjaxDataTable').DataTable();
            var queryString = $.param(filters); // Convert filters to query string
            var url = AJAX_URL + "?" + queryString; // Append query string to AJAX URL
            dataTable.ajax.url(url).load(); // Reload DataTable with the updated URL
        }

        

        


        function resetFilters() {
            document.getElementById("datetimes").value = "";
            var url = "{{ route('sales.payments-ajax') }}";
            var dataTable = $('.AjaxDataTable').DataTable();
            dataTable.ajax.url(url).load();

        }
    </script>
@endsection