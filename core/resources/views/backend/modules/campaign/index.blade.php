@extends('backend.layouts.master')

@section('meta')
    <title>All Campaigns - {{ get_option('title') }}</title>
@endsection

@section('content')
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4>Campaigns</h4>
                <h6>All Campaigns</h6>
            </div>
        </div>
        <ul class="table-top-head">
            @include('backend.include.buttons')
            <li>
                <a href="{{ route('campaigns.all.delete') }}" class="delete-btn-group" data-bs-toggle="tooltip"
                    data-bs-placement="top" title="Delete Selected"><img
                        src="{{ asset('theme/admin/assets/img/icons/delete.svg') }}" alt="img"></a>
            </li>

        </ul>
        <div class="page-btn">
            <a href="{{ route('campaigns.create') }}" class="btn btn-added"><i data-feather="plus-circle"
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
                            <th>SN</th>
                            <th class="no-sort">Name</th>
                            <th class="no-sort">Products</th>
                            <th class="no-sort">Discount</th>
                            <th class="no-sort">Discount Type</th>
                            <th class="no-sort">Date Range</th>
                            <th class="no-sort">Status</th>
                            <th class="no-sort">is_featured</th>
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
        AJAX_URL = "{{ route('campaigns.ajax') }}";

        $(document).on("click", '.changeStatus', function(e) {
            e.preventDefault();
            var $label = $(this);
            var authorId = $label.data('campaign-id');
            var checkboxId = $label.attr('for');
            var $checkbox = $('#' + checkboxId);

            // Determine the new status based on the checkbox state
            var newStatus = $checkbox.prop('checked') ? 0 : 1;

            // Send an AJAX request to update the status of the campaign
            $.ajax({
                url: '{{ route('campaigns.updateStatus') }}',
                type: 'POST',
                data: {
                    id: authorId,
                    status: newStatus,
                },
                success: function(response) {
                    // Toggle the checkbox state
                    $checkbox.prop('checked', newStatus === 1);

                    // Show a success message using SweetAlert
                    Swal.fire({
                        title: "Success!",
                        text: response.message,
                        icon: "success",
                        showConfirmButton: false, // Remove the confirmation button
                        timer: 1500 // Automatically close after 1.5 seconds
                    }).then(function() {
                        // Reload the AjaxDataTable
                        $('.AjaxDataTable').DataTable().ajax.reload();
                    });
                },
                error: function(xhr, status, error) {
                    // Handle errors if any
                    Swal.fire({
                        title: "Error!",
                        text: "An error occurred while updating the campaign status.",
                        icon: "error",
                        showConfirmButton: false, // Remove the confirmation button
                        timer: 1500 // Automatically close after 1.5 seconds
                    });
                }
            });
        });

        $(document).on("click", '.change_is_featured', function(e) {
            e.preventDefault();
            var $label = $(this);
            var authorId = $label.data('campaign-id');
            var checkboxId = $label.attr('for');
            var $checkbox = $('#' + checkboxId);
            // Determine the new status based on the checkbox state
            var newStatus = $checkbox.prop('checked') ? 0 : 1;
            // Send an AJAX request to update the status of the campaign
            $.ajax({
                url: '{{ route('campaigns-is-featured') }}',
                type: 'POST',
                data: {
                    id: authorId,
                    status: newStatus,
                },
                success: function(response) {
                    // Toggle the checkbox state
                    $checkbox.prop('checked', newStatus === 1);
                    // Show a success message using SweetAlert
                    Swal.fire({
                        title: "Success!",
                        text: response.message,
                        icon: "success",
                        showConfirmButton: false, // Remove the confirmation button
                        timer: 1500 // Automatically close after 1.5 seconds
                    }).then(function() {
                        // Reload the AjaxDataTable
                        $('.AjaxDataTable').DataTable().ajax.reload();
                    });
                },
                error: function(xhr, status, error) {
                    // Handle errors if any
                    Swal.fire({
                        title: "Error!",
                        text: "An error occurred while updating the campaign status.",
                        icon: "error",
                        showConfirmButton: false, // Remove the confirmation button
                        timer: 1500 // Automatically close after 1.5 seconds
                    });
                }
            });
        });


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
