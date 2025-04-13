@extends('backend.layouts.master')

@section('meta')
    <title>Firewall | {{ get_option('title') }}</title>
@endsection

@section('content')
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4>IP Black and White List</h4>
                <h6>Manage Your Firewall</h6>
            </div>
        </div>

        <ul class="table-top-head">
            {{-- @include('backend.include.buttons') --}}            
        </ul>

        <div class="page-btn d-flex">
           
        <a href="{{ route('settings.core') }}" class="btn btn-info"  data-size="md" data-select2="true" data-select2="true"><i data-feather="plus-circle" class="me-2"></i>Firewall Setting</a>
           
            <a href="#" class="btn btn-added AjaxModal" data-example='lg|xl|sm' data-size="md" data-select2="true"  data-ajax-modal="{{ route('modal.firewall.new') }}"
                data-select2="true"><i data-feather="plus-circle" class="me-2"></i>Add New</a>
        </div>
    </div>

    <div class="card table-list-card">
        <div class="card-body">
            <div class="table-top">
                <div class="search-set">
                    <div class="search-input">

                    </div>
                </div>

            </div>

            <div class="table-responsive">
                <table class="table AjaxDataTable" style="width:100%;">
                    <thead>
                        <tr>
                            <th>IP Address</th>
                            <th class="no-sort" data-orderable="false">Type</th>
                            <th class="no-sort" data-orderable="false">Note</th>
                            <th class="no-sort" data-orderable="false">Created</th>
                            <th class="no-sort" width="50px" data-orderable="false">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>

            </div>
        </div>
    </div>
    
@endsection

@section('script')
    <script>
        var AJAX_URL = "{{ route('firewall.list.ajax') }}";
    </script>
@endsection
