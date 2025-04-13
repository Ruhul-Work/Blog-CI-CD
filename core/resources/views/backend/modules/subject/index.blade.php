@extends('backend.layouts.master')
@section('meta')
    <title>All subjects - {{ get_option('title') }}</title>
@endsection
@section('content')
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4>Subject Management</h4>
                <h6>List of Subjects</h6>
            </div>
        </div>
        <ul class="table-top-head">
            @include('backend.include.buttons')
            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="Delete" class="delete-btn-group"
                   href="{{route("subjects.destroy.all")}}"><i data-feather="trash-2"
                                                                    class="feather-trash-2 text-danger"></i></a>
            </li>


        </ul>
        <div class="page-btn">
            <a href="{{ route('subjects.create') }}" class="btn btn-added"><i data-feather="plus-circle"
                                                                                   class="me-2"></i>Add New Subject</a>
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
                        <th width="10px">Sl</th>

                        <th class="no-sort">Subject</th>
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
        var AJAX_URL = '{{ route('subjects.ajax.index') }}';

        $(document).ready(function () {

            $(document).on("click", '.changeStatus', function (e) {

                e.preventDefault();
                var subjectId = $(this).data('subject-id');

                $.ajax({
                    url: '{{ route('subjects.updateStatus') }}',
                    type: 'POST',
                    data: {
                        id: subjectId,
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
                        Swal.fire({
                            title: "Error!",
                            text: "An error occurred while updating the  status.",
                            type: "error",
                            confirmButtonClass: "btn btn-danger"
                        });
                    }
                });
            });

        });
    </script>
@endsection
