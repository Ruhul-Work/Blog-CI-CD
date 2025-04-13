@extends('backend.layouts.master')
@section('meta')
    <title>All Subcategories - {{ get_option('title') }}</title>
@endsection
@section('content')
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4>Subcategory Management</h4>
                <h6>List of Subcategories</h6>
            </div>
        </div>
        <ul class="table-top-head">


            @include('backend.include.buttons')
            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="Delete" class="delete-btn-group "
                   href="{{route("subcategories.destroy.all")}}"><i data-feather="trash-2"
                                                                    class="feather-trash-2 text-danger"></i></a>
            </li>
        </ul>
        <div class="page-btn">
            <a href="{{ route('subcategories.create') }}" class="btn btn-added"><i data-feather="plus-circle"
                                                                                   class="me-2"></i>Add New Subcategory</a>
        </div>
    </div>

    <div class="card ">
        <div class="card-body">
            <div class="table-top">
                <div class="search-set">
                    <div class="search-input">
                        <a href="" class="btn btn-searchset"><i data-feather="search" class="feather-search"></i></a>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table  AjaxDataTable"
                       style="width:100%">
                    <thead>
                    <tr>
                        <th width="5px" class="no-sort" data-orderable="false">
                            <label class="checkboxs">
                                <input type="checkbox" id="select-all" data-value="0">
                                <span class="checkmarks"></span>
                            </label>
                        </th>
                        <th width="10px">Sr</th>
                        <th class="no-sort">Category</th>
                        <th class="no-sort"> Subcategory</th>
                        <th class="no-sort">Cover Image</th>
                        <th class="no-sort">Description</th>

                        <th class="no-sort" width="10px">Status</th>
                        <th class="no-sort" width="10px">Action</th>
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
        var AJAX_URL = '{{ route('subcategories.ajax.index') }}';
        $(document).ready(function () {
            $(document).on("click", '.changeStatus', function (e) {
                e.preventDefault();
                var subcategoryId = $(this).data('subcategory-id');

                // Send an AJAX request to update the status of the category
                $.ajax({
                    url: '{{ route('subcategories.updateStatus') }}',
                    type: 'POST',
                    data: {
                        id: subcategoryId,
                    },
                    success: function (response) {
                        // Show a success message using SweetAlert
                        Swal.fire({
                            title: "Success!",
                            text: response.message,
                            type: "success",
                            confirmButtonClass: "btn btn-success"
                        }).then(function () {
                            // Reload the AjaxDataTable
                            $('.AjaxDataTable').DataTable().ajax.reload();
                        });
                    },
                    error: function (xhr, status, error) {
                        // Handle errors if any
                        Swal.fire({
                            title: "Error!",
                            text: "An error occurred while updating the category status.",
                            type: "error",
                            confirmButtonClass: "btn btn-danger"
                        });
                    }
                });
            });

        });
    </script>
@endsection
