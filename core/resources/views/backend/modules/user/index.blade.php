@extends('backend.layouts.master')

@section('meta')
    <title>All Users | {{ get_option('title') }}</title>
@endsection

@section('content')
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4>User List</h4>
                <h6>Manage Your Users</h6>
            </div>
        </div>

        <ul class="table-top-head">
            @include('backend.include.buttons')
            <li>
                <a href="{{ route('user.delete.all.ajax') }}" class="delete-btn-group" data-bs-toggle="tooltip"
                    data-bs-placement="top" title="Delete Selected"><img
                        src="{{ asset('theme/admin/assets/img/icons/delete.svg') }}" alt="img"></a>
            </li>
        </ul>

        <div class="page-btn">

            <a href="#" class="btn btn-added AjaxModal" data-example='lg|xl|sm' data-size="md" data-select2="true"
                data-ajax-modal="{{ route('modal.user.new') }}" data-select2="true"><i data-feather="plus-circle"
                    class="me-2"></i>Add New</a>
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
                            <th>User</th>
                            <th class="no-sort" data-orderable="false">Name</th>
                            <th class="no-sort" data-orderable="false">Username</th>
                            <th class="no-sort" data-orderable="false">Phone</th>
                            <th class="no-sort" data-orderable="false">Role</th>
                            <th class="no-sort" data-orderable="false">Make Customer</th>
                            <th class="no-sort" data-orderable="false">Last Login</th>
                            <th class="no-sort" data-orderable="false">Status</th>

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
        var AJAX_URL = "{{ route('user.list.ajax') }}";
    </script>


    <script>
        $(document).on('click', '#updateUserButton', function(e) {
            e.preventDefault();

            const userId = $(this).data('user-id');

            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to change the user role to Customer?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, update it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Make the AJAX request to update the user role
                    $.ajax({
                        url: '{{ route('user-to-customer') }}',
                        method: 'POST',
                        data: {
                            id: userId,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            Swal.fire(
                                'Updated!',
                                'The user role has been updated to Customer.',
                                'success'
                            ).then(() => {
                                // Reload the DataTable
                                $('.AjaxDataTable').DataTable().ajax.reload();
                            });
                        },
                        error: function() {
                            Swal.fire(
                                'Error!',
                                'There was an error updating the user role.',
                                'error'
                            );
                        }
                    });
                }
            });
        });
    </script>
@endsection
