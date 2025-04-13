@extends('backend.layouts.master')

@section('meta')
    <title>All Subscribers - {{ get_option('title') }}</title>
@endsection

@section('content')
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4>Subscriber Management</h4>
                <h6>List of All Subscriber Users</h6>
            </div>
        </div>
        <ul class="table-top-head">
            @include('backend.include.buttons')
            <li>
                <a data-bs-toggle="tooltip" id="" data-bs-placement="top" title="Delete" class="delete-btn-group"
                    href="#"><i data-feather="trash-2" class="feather-trash-2 text-danger"></i></a>
            </li>
        </ul>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-top">
                <div class="search-set">
                    <div class="search-input">
                        <a href="" class="btn btn-searchset mt-3"><i data-feather="search" class="feather-search"></i></a>
                    </div>
                    <div class="mt-3">
                        <select id="statusFilter" class="form-select">
                            <option value="">All Subscriber</option>
                            <option value="Active">Active Subscriber</option>
                            <option value="Inactive">Inactive Subscriber</option>
                        </select>
                    </div>
                 
                </div>

            </div>

            <div class="table-responsive">
                <table class="table AjaxDataTable" style="width:100%">
                    <thead>
                        <tr>
                            <th width="5px" data-orderable="false">
                                <label class="checkboxs"><input type="checkbox" id="select-all"><span
                                        class="checkmarks"></span></label>
                            </th>
                            <th>Sr</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th class="no-sort">Phone</th>
                            <th>Active Status</th>
                            <th>Subscription Start Date</th>
                            <th>End Date</th>
                            <th>Payment Status</th>
                           
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>

        var AJAX_URL = '{{ route('subscribers.ajax.index') }}';

        $(document).ready(function() {

            var table = $('.AjaxDataTable').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                ajax: {
                    url: AJAX_URL,
                    type: "POST",
                    data: function(d) {
                        d.status = $('#statusFilter').val();
                    }
                }
            });

            $('#statusFilter').change(function() {
                table.ajax.reload();
            });

        });


    </script>
@endsection
