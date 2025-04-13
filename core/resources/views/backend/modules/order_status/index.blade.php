@extends('backend.layouts.master')
@section('meta')
    <title>All Order Status - {{ get_option('title') }}</title>
@endsection

@section('content')
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4>Status</h4>
                <h6>Manage your Status</h6>
            </div>
        </div>
        <ul class="table-top-head">
            @include('backend.include.buttons')
            <li>
                <a href="{{ route('orderstatuses.all.delete') }}" class="delete-btn-group" data-bs-toggle="tooltip"
                    data-bs-placement="top" title="Delete Selected"><img
                        src="{{ asset('theme/admin/assets/img/icons/delete.svg') }}" alt="img"></a>
            </li>
        </ul>
        <div class="page-btn">
            <a href="javascript:void(0)" class="btn btn-primary me-2" data-bs-toggle="modal"
                data-bs-target="#statusModal"><i data-feather="plus-circle"></i>Add New</a>
        </div>
    </div>
    {{--  --}}

    <!-- Modal -->
    <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Order Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" id="createAuthor" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="card">
                            <div class="card-body add-product pb-0">
                                <div class="accordion-card-one accordion" id="accordionExample">
                                    <div class="accordion-item">
                                        <div class="accordion-header" id="headingOne">
                                            <div class="accordion-button" data-bs-toggle="collapse"
                                                data-bs-target="#collapseOne" aria-controls="collapseOne">
                                                <div class="addproduct-icon">
                                                    <h5><i data-feather="info" class="add-info"></i><span>Basic
                                                            Information</span></h5>
                                                    <a href="javascript:void(0);"><i data-feather="chevron-down"
                                                            class="chevron-down-add"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="collapseOne" class="accordion-collapse collapse show"
                                            aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <div class="row">

                                                    <div class="col-lg-12 col-sm-12 col-12">
                                                        <div class="mb-3 add-product required">
                                                            <label class="form-label">Name</label>
                                                            <input type="text" class="form-control" id="name"
                                                                name="name" placeholder="Enter text here">
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-12 col-sm-12 col-12 required">
                                                        <label class="form-label">Status</label>
                                                        <select class="form-select select" name="status" width="100%">
                                                            <option value="1">Active</option>
                                                            <option value="0">Inactive</option>
                                                        </select>
                                                    </div>

                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-lg-12">
                            <div class="btn-addproduct mb-4">
                                <button type="submit" class="btn btn-submit">create status</button>
                            </div>
                        </div>

                    </form>



                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="statusUpdateModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Order Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="updateOrderstatus">

                </div>
            </div>
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
                            <th class="no-sort" data-orderable="false">
                                <label class="checkboxs">
                                    <input type="checkbox" id="select-all" data-value="0">
                                    <span class="checkmarks"></span>
                                </label>
                            </th>
                            <th>SN</th>
                            <th class="no-sort">Name</th>
                            <th class="no-sort">Status</th>
                            <th class="no-sort">Created By</th>
                            {{-- <th class="no-sort">Action</th> --}}
                        </tr>
                    </thead>

                    <tbody>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
    <div class="modal fade" id="imageViewModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Image Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img src="" id="modalImage" class="img-fluid" alt="Image Preview">
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        AJAX_URL = "{{ route('orderstatuses.ajax') }}";

        $(document).on("click", '.changeStatus', function(e) {
            e.preventDefault();
            var authorId = $(this).data('author-id');
            // Send an AJAX request to update the status of the category
            $.ajax({
                url: '{{ route('orderstatuses.status') }}',
                type: 'POST',
                data: {
                    id: authorId,
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
                    // Handle errors if any
                    Swal.fire({
                        title: "Error!",
                        text: "An error occurred while updating the Author status.",
                        type: "error",
                        confirmButtonClass: "btn btn-danger"
                    });
                }
            });
        });

        // JavaScript to handle the image modal



        $(document).ready(function() {
            $(document).on('click', '.view-image-btn', function() {
                var imageUrl = $(this).data('cover-url');
                if (imageUrl) {
                    $('#modalImage').attr('src', imageUrl);
                    $('#imageViewModal').modal('show'); // Show the modal
                } else {
                    console.error('Image URL not found.');
                }
            });
        });
    </script>


    <script>
        $(document).ready(function() {

            $('#createAuthor').submit(function(e) {
                e.preventDefault(); // Prevent the form from submitting normally
                // Get form data
                var formData = new FormData(this);
                // Make AJAX request
                $.ajax({
                    url: '{{ route('orderstatuses.store') }}',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {

                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href =
                                    '{{ route('orderstatuses.index') }}';
                            }
                        });
                    },
                    error: function(xhr, status, error) {
                        // Parse the JSON response from the server
                        try {
                            var responseObj = JSON.parse(xhr.responseText);
                            var errorMessages = responseObj.errors ? Object.values(responseObj
                                .errors).flat() : [responseObj.message];
                            var errorMessageHTML = '<ul>' + errorMessages.map(errorMessage =>
                                '<li>' + errorMessage + '</li>').join('') + '</ul>';

                            // Show error messages using SweetAlert
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                html: errorMessageHTML,
                            });
                        } catch (e) {
                            console.error('Error parsing JSON response:', e);
                            // Show default error message using SweetAlert
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'An error occurred while processing your request. Please try again later.',
                            });
                        }
                    }

                });
            });

        });



        $(document).on("submit", "#StatuseditForm", function(e) {
            e.preventDefault(); // Prevent the form from submitting normally
            // Get form data
            var formData = new FormData(this);

            // Make AJAX request
            $.ajax({
                url: '{{ route('orderstatuses.updateStatus') }}', // Ensure this route points to your update method
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '{{ route('orderstatuses.index') }}';
                        }
                    });
                },
                error: function(xhr, status, error) {
                    // Parse the JSON response from the server
                    try {
                        var responseObj = JSON.parse(xhr.responseText);
                        var errorMessages = responseObj.errors ? Object.values(responseObj.errors)
                            .flat() : [responseObj.message];
                        var errorMessageHTML = '<ul>' + errorMessages.map(errorMessage => '<li>' +
                            errorMessage + '</li>').join('') + '</ul>';
                        // Show error messages using SweetAlert
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            html: errorMessageHTML,
                        });
                    } catch (e) {
                        console.error('Error parsing JSON response:', e);
                        // Show default error message using SweetAlert
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'An error occurred while processing your request. Please try again later.',
                        });
                    }
                }
            });
        });






        $(document).ready(function() {
            $('.status').select2();
        });



        $(document).ready(function() {
            $(document).on('click', '.edit_status', function(event) {
                event.preventDefault();
                var statusId = $(this).data('id');
                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    url: '{{ route('orderstatuses.edit') }}',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        id: statusId,
                        _token: csrfToken
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#updateOrderstatus').html(response.statData);
                            $('#statusUpdateModal').modal('show');
                        } else {
                            console.error('Error: ', response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error: ', error);
                    }
                });
            });
        });
    </script>
@endsection
