@extends('backend.layouts.master')

@section('meta')
    <title>Role | {{ get_option('title') }}</title>
@endsection

@section('content')
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4>User Role List</h4>
                <h6>Manage Your User Role</h6>
            </div>
        </div>

        <ul class="table-top-head">
            @include('backend.include.buttons')
            
        </ul>

        <div class="page-btn">
            <a href="#" class="btn btn-added AjaxModal" data-example='lg|xl|sm' data-size="xl" data-select2="false"  data-ajax-modal="{{ route('modal.role.new') }}"
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
                            <th class="no-sort" width="20px" data-orderable="false">
                                <label class="checkboxs">
                                    <input type="checkbox" id="select-all">
                                    <span class="checkmarks"></span>
                                </label>
                            </th>
                            <th width="150px">Name</th>
                            <th class="no-sort" data-orderable="false" >Permissions</th>
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
        var AJAX_URL = "{{ route('role.list.ajax') }}";

        $(document).on('click', '.ckall', function() {
                


                let row = $(this).closest('tr');
                let checkboxes = row.find('.custom-check');
              
                
                
                checkboxes.each(function(index, checkbox) {
                    

                    if(checkbox.checked)
                      checkbox.checked = false;
                    else
                      checkbox.checked = true;
                });
            });
    </script>
@endsection
