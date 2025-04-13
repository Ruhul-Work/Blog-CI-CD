@extends('backend.layouts.master')

@section('meta')
    <title>All Tags - {{ get_option('title') }}</title>
@endsection

@section('content')
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4>Tag Management</h4>
                <h6>List of Tags</h6>
            </div>
        </div>
        <ul class="table-top-head">

            @include('backend.include.buttons')


            <li>
                <a data-bs-toggle="tooltip" id="" data-bs-placement="top" title="Delete" class="delete-btn-group"
                href="{{route("tags.destroy.all")}}"><i data-feather="trash-2"
                                                              class="feather-trash-2 text-danger"></i></a>
            </li>



        </ul>
        <div class="page-btn">
            <a href="{{ route('tags.create') }}" class="btn btn-added"><i data-feather="plus-circle" class="me-2"></i>Add
                New Tag</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-top">
                <div class="search-set">
                    <div class="search-input">
                        <a href="" class="btn btn-searchset"><i data-feather="search" class="feather-search"></i></a>
                    </div>
                </div>

            </div>
            <div class="table-responsive">
                <table class="table AjaxDataTable" style="width:100%">
                    <thead>
                        <tr >
                            <th width="5px" class="no-sort" data-orderable="false">
                                <label class="checkboxs"><input type="checkbox" id="select-all"><span
                                        class="checkmarks"></span></label></th>
                            <th>Sr</th>
                            <th>Tag Name</th>
                            <th>Action</th>
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
        var AJAX_URL = '{{ route('tags.ajax.index') }}';

        $(document).ready(function() {

            // Delete Tag with SweetAlert
            $(document).on('click', '.delete-btn', function(e) {
                e.preventDefault();
                var deleteUrl = $(this).attr('href');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: deleteUrl,
                            type: 'GET',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                Swal.fire(
                                    'Deleted!',
                                    response.message,
                                    'success'
                                );
                                $('.AjaxDataTable').DataTable().ajax.reload();
                            },
                            error: function(xhr) {
                                Swal.fire(
                                    'Error!',
                                    'An error occurred while deleting the tag. Please try again.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });


        });
    </script>
@endsection
