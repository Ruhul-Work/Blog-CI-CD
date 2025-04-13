@extends('backend.layouts.master')
@section('meta')
    <title>All blog - {{ get_option('title') }}</title>
@endsection
@section('content')
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4>Blog Management</h4>
                <h6>Blog List</h6>
            </div>
        </div>
        <ul class="table-top-head">

            @include('backend.include.buttons')


            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="Delete" class="delete-btn-group"
                    href="{{ route('blogs.destroyAll') }}"><i data-feather="trash-2"
                        class="feather-trash-2 text-danger"></i></a>
            </li>



        </ul>
        <div class="page-btn">
            <a href="{{ route('blogs.create') }}" class="btn btn-added"><i data-feather="plus-circle" class="me-2"></i>Add
                New Blog</a>
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
                <table class="table  AjaxDataTable" style="width:100%">
                    <thead>
                        <tr>
                            <th width="5px" class="no-sort" data-orderable="false">
                                <label class="checkboxs">
                                    <input type="checkbox" id="select-all" data-value="0">
                                    <span class="checkmarks"></span>
                                </label>
                            </th>
                            <th width="10px">Sn</th>
                            <th class="no-sort">Title</th>
                            <th class="no-sort">slug</th>
                            <th class="no-sort">Thumbnail</th>
                            <th class="no-sort">Category</th>
                            <th class="no-sort">Tags</th>
                            <th class="no-sort">Authors</th>
                            <th class="no-sort">Blog Type</th>
                            <th class="no-sort" width="10px">Status</th>
                            <th class="no-sort" width="10px">Publish Status</th>
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
        var AJAX_URL = '{{ route('blogs.ajax.index') }}';

        $(document).ready(function() {
            $(document).on("click", '.changeBlogType, .changeStatus, .changePublishStatus', function(e) {
                e.preventDefault();
                var blogId = $(this).data('blog-id');
                var field = $(this).data('field');

                $.ajax({
                    url: '{{ route('blogs.updateField') }}',
                    type: 'POST',
                    data: {
                        id: blogId,
                        field: field,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Swal.fire({
                            title: "Success!",
                            text: response.message,
                            icon: "success",
                            confirmButtonClass: "btn btn-success"
                        }).then(function() {
                            $('.AjaxDataTable').DataTable().ajax.reload();
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            title: "Error!",
                            text: "An error occurred while updating the field.",
                            icon: "error",
                            confirmButtonClass: "btn btn-danger"
                        });
                    }
                });
            });
        });
    </script>
@endsection
