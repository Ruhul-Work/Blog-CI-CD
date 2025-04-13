@extends('backend.layouts.master')
@section('meta')
    <title>All Shops - {{ get_option('title') }}</title>
@endsection
@section('content')
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4>Shop Management</h4>
                <h6>List of Shops</h6>
            </div>
        </div>
        <ul class="table-top-head">
            @include('backend.include.buttons')
            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="Delete" class="delete-btn-group"
                    href="{{ route('users.destroy.all') }}"><i data-feather="trash-2"
                        class="feather-trash-2 text-danger"></i></a>
            </li>
        </ul>
        <div class="page-btn">
            <a href="#" data-bs-toggle="modal" data-bs-target="#create" class="btn btn-added"><i
                    data-feather="plus-circle" class="me-2"></i>Add New Shops</a>
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
                            <th>User</th>
                            <th class="no-sort" data-orderable="false">Name</th>
                            {{--                        <th class="no-sort" data-orderable="false">Username</th> --}}
                            <th class="no-sort" data-orderable="false">Phone</th>
                            {{-- <th class="no-sort" data-orderable="false">Role</th> --}}
                            <th class="no-sort" data-orderable="false">Last Login</th>
                            <th class="no-sort" data-orderable="false">Make Admin</th>
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



    {{-- add customer --}}
    <div class="modal fade" id="create" tabindex="-1" aria-labelledby="create" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="createForm" action="{{ route('shop.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            {{-- <div class="col-12 col-sm-6">
                                <div class="input-block">
                                    <label>User Type<span class="star-sign">*</span></label>
                                    <select name="user_type" id="user_type" class="select">
                                        <option value="">Select User Type</option>
                                        @foreach ($userTypes as $userType)
                                            <option value="{{ $userType }}">{{ $userType }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div> --}}

                            <div class="col-lg-6 col-sm-12 col-12">
                                <div class="input-blocks">
                                    <label>Customer Name <span class="star-sign">*</span></label>
                                    <input type="text" name="name" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12 col-12">
                                <div class="input-blocks">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12 col-12">
                                <div class="input-blocks">
                                    <label>Phone <span class="star-sign">*</span></label>
                                    <input type="text" name="phone" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12 col-12">
                                <div class="input-blocks">
                                    <label>Alternative Phone</label>
                                    <input type="text" name="phone_alt" class="form-control">
                                </div>
                            </div>

                            <div class="col-lg-6 col-sm-12 col-12 pe-0">
                                <div class="mb-3">
                                    <label class="form-label">Gender</label>
                                    <select class="selectSimple" name="gender" required>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                    </select>
                                </div>
                            </div>

                            {{-- <div class="col-lg-6 col-sm-12 col-12 pe-0">
                                <div class="mb-3 required">
                                    <label class="form-label">User Role</label>
                                    <select class="selectSimple" name="user_role" required>
                                        <option>Choose</option>
                                        @foreach ($role as $single)
                                            <option value="{{$single->id}}" >{{Str::title($single->name)}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div> --}}

                            <div class="col-lg-6 col-sm-12 col-12 pe-0">
                                <div class="mb-3 required">
                                    <label class="form-label">Password</label>
                                    <input type="password" name="password" class="form-control"
                                        autocomplete="new-password" required>
                                </div>
                            </div>

                            <div class="col-lg-6 col-sm-12 col-12">
                                <div class="mb-3 add-product">
                                    <label class="form-label">Image</label>

                                    <div class="form-group">
                                        <div class="row" id="image">

                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="modal-footer d-sm-flex justify-content-end">
                            <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-submit me-2">
                                <span class="spinner-border spinner-border-sm d-none" role="status"
                                    aria-hidden="true"></span>
                                <span class="text">Submit</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade text-left" id="editModal" data-bs-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel4" aria-hidden="true">
        <div class="modal-dialog  modal-lg modal-dialog-centered " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel4">Edit Form
                    </h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <div class="modal-body edit-modal-body">

                </div>
                <div class="modal-footer d-sm-flex justify-content-end">
                    <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Cancel</button>

                </div>
            </div>
        </div>
    </div>

    <!-- Role Selection Modal -->
    <div class="modal fade" id="roleModal" tabindex="-1" aria-labelledby="roleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="roleModalLabel">Select User Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="roleForm">
                        <input type="hidden" name="user_id" id="modalUserId">
                        <div class="mb-3">
                            <label for="user_role" class="form-label">User Role</label>
                            <select class="form-select" name="user_role" id="user_role">
                                @foreach ($role as $single)
                                    <option value="{{ $single->id }}">{{ Str::title($single->name) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        var AJAX_URL = '{{ route('users.shop.ajax') }}';
        $(document).ready(function() {

            $("#image").spartanMultiImagePicker({
                fieldName: 'image',
                maxCount: 1,
                rowHeight: '200px',
                groupClassName: 'col',
                maxFileSize: '',
                dropFileLabel: "Drop Here",
                onExtensionErr: function(index, file) {
                    console.log(index, file, 'extension err');
                    alert('Please only input png or jpg type file')
                },
                onSizeErr: function(index, file) {
                    console.log(index, file, 'file size too big');
                    alert('File size too big max:250KB');
                }
            });

            $('#createForm').submit(function(event) {
                event.preventDefault();
                var formData = new FormData(this);

                var $form = $(this);
                var $button = $form.find('.btn-submit');
                var $spinner = $button.find('.spinner-border');
                var $text = $button.find('.text');

                // Show loading indicator and disable button
                $spinner.removeClass('d-none');
                $text.hide();
                $button.prop('disabled', true);

                $.ajax({
                    url: $form.attr('action'),
                    type: 'POST',
                    data: formData,
                    processData: false, // Required for FormData
                    contentType: false, // Required for FormData
                    success: function(response) {
                        $spinner.addClass('d-none');
                        $text.show();
                        $button.prop('disabled', false);

                        // Show a success message using SweetAlert
                        Swal.fire({
                            title: "Success!",
                            text: response.message,
                            icon: "success",
                            confirmButtonClass: "btn btn-success"
                        }).then(function() {
                            $('.AjaxDataTable').DataTable().ajax.reload();
                            $('#create').modal('hide');
                            $('#create')[0].reset();
                        });
                    },
                    error: function(xhr, status, error) {
                        // Hide loading indicator and enable button
                        $spinner.addClass('d-none');
                        $text.show();
                        $button.prop('disabled', false);

                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: xhr.responseText
                        });
                    }
                });
            });

            $(document).on("click", ".openEditModal", function(e) {
                e.preventDefault();
                $('#editModal').modal('show'); // Show the modal with the ID "editModal"
                var href = $(this).attr('href');
                $.ajax({
                    type: 'GET',

                    url: href,

                    data: {
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function(response) {
                        $('.edit-modal-body').html(response.html);

                    },

                    error: function(xhr, status, error) {
                        // Handle the error response from the server
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'An error occurred. Please try again later.'
                        });
                    }
                });
            });

            $(document).on("submit", '#updateForm', function(event) {
                event.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    type: 'POST',
                    url: '{{ route('shop.update') }}',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        // Handle the response from the server
                        if (response.message) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.message
                            });
                        }
                        $('.modal').modal('hide');
                        $('.AjaxDataTable').DataTable().ajax.reload();
                    },
                    error: function(xhr, status, error) {
                        // Handle the error response from the server
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: xhr.responseJSON.errors ? Object.values(xhr
                                    .responseJSON.errors)[0][0] :
                                'An error occurred while submitting the form. Please try again.'
                        });
                    }
                });
            });

            $(document).on('click', '.make-admin', function() {
                var userId = $(this).data('user-id');
                $('#modalUserId').val(userId);
                $('#roleModal').modal('show');
            });

            $('#roleForm').submit(function(event) {
                event.preventDefault();

                var formData = {
                    id: $('#modalUserId').val(),
                    user_role: $('#user_role').val(),
                    _token: '{{ csrf_token() }}'
                };

                $.ajax({
                    url: '{{ route('users.makeAdmin') }}',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        $('#roleModal').modal('hide');
                        if (response.success) {
                            Swal.fire({
                                title: "Success!",
                                text: "User role has been updated.",
                                icon: "success"
                            }).then(function() {
                                $('.AjaxDataTable').DataTable().ajax.reload();
                            });
                        } else {
                            Swal.fire({
                                title: "Error!",
                                text: response.message,
                                icon: "error"
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        $('#roleModal').modal('hide');
                        Swal.fire({
                            title: "Error!",
                            text: xhr.responseText,
                            icon: "error"
                        });
                    }
                });
            });


            {{-- $(document).on('click', '.make-admin', function() { --}}
            {{--    alert(1) --}}

            {{--    var userId = $(this).data('user-id'); --}}
            {{--    $.ajax({ --}}
            {{--        url: '{{ route('users.makeAdmin') }}', --}}
            {{--        type: 'POST', --}}
            {{--        data: { --}}
            {{--            id: userId, --}}
            {{--            _token: '{{ csrf_token() }}' --}}
            {{--        }, --}}
            {{--        success: function(response) { --}}
            {{--            if (response.success) { --}}
            {{--                Swal.fire({ --}}
            {{--                    title: "Success!", --}}
            {{--                    text: "User has been made an admin.", --}}
            {{--                    icon: "success" --}}
            {{--                }).then(function() { --}}
            {{--                    $('.AjaxDataTable').DataTable().ajax.reload(); --}}
            {{--                }); --}}
            {{--            } else { --}}
            {{--                Swal.fire({ --}}
            {{--                    title: "Error!", --}}
            {{--                    text: response.message, --}}
            {{--                    icon: "error" --}}
            {{--                }); --}}
            {{--            } --}}
            {{--        }, --}}
            {{--        error: function(xhr, status, error) { --}}
            {{--            Swal.fire({ --}}
            {{--                title: "Error!", --}}
            {{--                text: xhr.responseText, --}}
            {{--                icon: "error" --}}
            {{--            }); --}}
            {{--        } --}}
            {{--    }); --}}
            {{-- }); --}}


            $(document).on("click", '.changeStatus', function(e) {

                e.preventDefault();
                var userId = $(this).data('user-id');

                $.ajax({
                    url: '{{ route('users.updateStatus') }}',
                    type: 'POST',
                    data: {
                        id: userId,
                    },
                    success: function(response) {
                        // Show a success message using SweetAlert
                        Swal.fire({
                            title: "Success!",
                            text: response.message,
                            type: "success",
                            confirmButtonClass: "btn btn-success"
                        }).then(function() {
                            // Reload the AjaxDataTable
                            $('.AjaxDataTable').DataTable().ajax.reload();
                        });
                    },

                    error: function(xhr, status, error) {
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
