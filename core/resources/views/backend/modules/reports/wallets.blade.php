@extends('backend.layouts.master')
@section('meta')
    <title>All Wallets- {{ get_option('title') }}</title>
@endsection

@section('content')
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4>Wallets</h4>
                <h6>Manage Wallets</h6>
            </div>
        </div>
        <ul class="table-top-head">
            @include('backend.include.buttons')
        </ul>

    </div>



    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body p-2">
                    <div class="row">
                        <form>

                            <select id="type" name="" class="form-control">
                                <option value="all">All</option>
                                <option value="debit">Debit</option>
                                <option value="credit">Credit</option>
                            </select>
                        </form>
                    </div>
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
                                    <th class="no-sort" style="font-size: 14px; font-weight:600px; width:100px">SN</th>
                                    <th style="font-size: 14px; font-weight:600px; width:100px">User</th>
                                    <th class="no-sort" style="font-size: 14px; font-weight:600px; width:200px;">Date</th>
                                    <th class="no-sort" style="font-size: 14px; font-weight:600px; width:200px;">Type</th>
                                    <th class="no-sort" style="font-size: 14px; font-weight:600px; width:200px;">Wallet Type
                                    </th>
                                    <th class="no-sort" style="font-size: 14px; font-weight:600px; width:200px;">Amount</th>
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
@endsection
@section('script')
    <script>
        var AJAX_URL = "{{ route('wallets.ajax') }}";

        function applyFilters() {
            var filters = {
                type: $("#type").val(),
            };
            updateDataTable(filters);
        }

        function updateDataTable(filters) {
            var dataTable = $('.AjaxDataTable').DataTable();
            var queryString = $.param(filters);
            var url = AJAX_URL + "?" + queryString;
            dataTable.ajax.url(url).load();
        }

        
        $("#type").on('change', function() {
            applyFilters();
        });
    </script>
@endsection
