@extends('backend.layouts.master')

@section('meta')
    <title>All Payment Methods - {{ get_option('title') }}</title>
@endsection

@section('content')
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4>Payment Methods</h4>
                <h6>Manage your Payment Methods</h6>
            </div>
        </div>
        <ul class="table-top-head">
            @include('backend.include.buttons')
            <li>
                <a href="{{ route('paymentmethod.all.delete') }}" class="delete-btn-group" data-bs-toggle="tooltip"
                    data-bs-placement="top" title="Delete Selected"><img
                        src="{{ asset('theme/admin/assets/img/icons/delete.svg') }}" alt="img"></a>
            </li>
        </ul>
        <div class="page-btn">
            <a href="{{ route('paymentmethod.create') }}" class="btn btn-added"><i data-feather="plus-circle"
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
                            <th class="no-sort" data-orderable="false">
                                <label class="checkboxs">
                                    <input type="checkbox" id="select-all" data-value="0">
                                    <span class="checkmarks"></span>
                                </label>
                            </th>
                            {{-- <th>SN</th> --}}
                            <th class="no-sort">Name</th>
                            <th class="no-sort">Type</th>
                            <th class="no-sort">Account Name</th>
                            <th class="no-sort">Account Number</th>
                            <th class="no-sort">Status</th>
                            <th class="no-sort">Process</th>
                            <th class="no-sort">Created By</th>
                            <th class="no-sort">Action</th>
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
        AJAX_URL = "{{ route('paymentmethod.ajax') }}";

        $(document).on("click", '.changeStatus', function(e) {
            e.preventDefault();
            var authorId = $(this).data('author-id');
            // Send an AJAX request to update the status of the category
            $.ajax({
                url: '{{ route('paymentmethod.updateStatus') }}',
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
@endsection
