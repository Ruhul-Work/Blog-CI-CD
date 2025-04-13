@extends('backend.layouts.master')

@section('meta')
    <title>All Coupons - {{ get_option('title') }}</title>
@endsection

@section('content')
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4>Coupons</h4>
                <h6>Manage Coupons</h6>
            </div>
        </div>
        <ul class="table-top-head">
            @include('backend.include.buttons')
            <li>
                <a href="{{ route('coupons.all.delete') }}" class="delete-btn-group" data-bs-toggle="tooltip"
                    data-bs-placement="top" title="Delete Selected"><img
                        src="{{ asset('theme/admin/assets/img/icons/delete.svg') }}" alt="img"></a>
            </li>
        </ul>
        <div class="page-btn">
            <a href="{{ route('coupons.create') }}" class="btn btn-added"><i data-feather="plus-circle"
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
                            <th style="width:3%" class="no-sort" data-orderable="false">
                                <label class="checkboxs">
                                    <input type="checkbox" id="select-all" data-value="0">
                                    <span class="checkmarks"></span>
                                </label>
                            </th>
                            <th class="no-sort" style="width: 3%">SN</th>
                            <th class="no-sort" style="width:10%">Type</th>
                            <th class="no-sort" style="width: 10%">Title</th>
                            <th class="no-sort" style="width:10%">Code</th>
                            <th class="no-sort" style="width:10%">Discount Type</th>
                            <th class="no-sort" style="width: 5%">Status</th>
                            <th class="no-sort" style="width: 10%">Validty</th>
                            <th class="no-sort" style="width: 5%">Stock</th>
                            {{-- <th class="no-sort" style="width: 5%">Max Use</th>
                            <th class="no-sort" style="width: 5%">Minimum Buy</th>
                            <th class="no-sort" style="width: 5%">Maximum Discount</th> --}}
                           
                          
                            <th class="no-sort" style="width: 5%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>



    <!-- Modal Markup -->
    <div class="modal fade" id="imageViewModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Image Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img src="" style="height: 100%;width:100%;" id="modalImage" class="img-fluid"
                        alt="Image Preview">
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        AJAX_URL = "{{ route('coupons.ajax') }}";
        $(document).on("click", '.changeStatus', function(e) {
            e.preventDefault();
            var slidersId = $(this).data('coupon-id');
            // Send an AJAX request to update the status of the category
            $.ajax({
                url: '{{ route('coupons.updateStatus') }}',
                type: 'POST',
                data: {
                    id: slidersId,
                },
                success: function(response) {
                    // Show a success message using SweetAlert
                    Swal.fire({
                        title: "Success!",
                        text: response.message,
                        icon: "success",
                        showConfirmButton: false,
                        timer: 2000 // Optional: automatically close after 2 seconds
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
                        icon: "error",
                        showConfirmButton: false,
                        timer: 2000 // Optional: automatically close after 2 seconds
                    });
                }
            });
        });

        $(document).on("click", '.changeValidStatus', function(e) {
            e.preventDefault();
            var slidersId = $(this).data('coupon-id');
            // Send an AJAX request to update the status of the category
            $.ajax({
                url: '{{ route('coupons.chnage-valid-status') }}',
                type: 'POST',
                data: {
                    id: slidersId,
                },
                success: function(response) {
                    // Show a success message using SweetAlert
                    Swal.fire({
                        title: "Success!",
                        text: response.message,
                        icon: "success",
                        showConfirmButton: false,
                        timer: 2000 // Optional: automatically close after 2 seconds
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
                        icon: "error",
                        showConfirmButton: false,
                        timer: 2000 // Optional: automatically close after 2 seconds
                    });
                }
            });
        });

        // JavaScript to handle the image modal
    </script>
@endsection
