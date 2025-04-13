@extends('backend.layouts.master')
@section('meta')
    <title>All Yearly Sales- {{ get_option('title') }}</title>
@endsection

@section('content')
    <div id="app">


        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4>Sales</h4>
                    <h6>Manage Yearly Sales</h6>
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
                                        <th class="no-sort" style="font-size: 14px; font-weight:600px; width:100px">Year
                                        </th>
                                        <th style="font-size: 14px; font-weight:600px; width:200px;">Total Order</th>
                                        <th class="no-sort" style="font-size: 14px; font-weight:600px; width:200px;">Total
                                            Sale</th>
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
        AJAX_URL = "{{ route('sales.yearly-ajax') }}";
    </script>
@endsection
