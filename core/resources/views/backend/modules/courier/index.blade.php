@extends('backend.layouts.master')

@section('meta')
    <title>All Couriers - {{ get_option('title') }}</title>
@endsection

@section('content')
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4>Couriers</h4>
                <h6>Manage Couriers</h6>
            </div>
        </div>

        <ul class="table-top-head">

            @include('backend.include.buttons')
            <li>
                <a href="{{ route('couriers.all.delete') }}" class="delete-btn-group" data-bs-toggle="tooltip"
                    data-bs-placement="top" title="Delete Selected">
                    <img src="{{ asset('theme/admin/assets/img/icons/delete.svg') }}" alt="img"></a>
            </li>
        </ul>


        <div class="page-btn">
            <a href="{{ route('couriers.create') }}" class="btn btn-added"><i data-feather="plus-circle"
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
                            <th class="no-sort" data-orderable="false" style="width: 5%">
                                <label class="checkboxs">
                                    <input type="checkbox" id="select-all" data-value="0">
                                    <span class="checkmarks"></span>
                                </label>
                            </th>
                            <th style="width: 5%">SN</th>
                            <th class="no-sort" style="width: 10%">Name</th>
                            {{-- <th class="no-sort" style="width: 10%">In Dhaka</th>
                            <th class="no-sort" style="width: 10%">Outside</th> --}}
                            <th class="no-sort" style="width: 5%">Image</th>
                            <th class="no-sort" style="width: 15%">Description</th>
                            <th class="no-sort" style="width: 5%">Status</th>
                            <th class="no-sort" style="width: 5%">Created By</th>
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
        AJAX_URL = "{{ route('couriers.ajax') }}";

        $(document).on("click", '.changeStatus', function(e) {
            e.preventDefault();
            var slidersId = $(this).data('sliders-id');

            $.ajax({
                url: '{{ route('couriers.updateStatus') }}',
                type: 'POST',
                data: {
                    id: slidersId,
                },
                success: function(response) {

                    Swal.fire({
                        title: "Success!",
                        text: response.message,
                        type: "success",
                        confirmButtonClass: "btn btn-success"
                    }).then(function() {

                        $('.AjaxDataTable').DataTable().ajax.reload();
                    });
                },
                error: function(xhr, status, error) {

                    Swal.fire({
                        title: "Error!",
                        text: "An error occurred while updating the Couriers status.",
                        type: "error",
                        confirmButtonClass: "btn btn-danger"
                    });
                }
            });
        });



        $(document).ready(function() {
            $(document).on('click', '.view-image-btn', function() {
                var imageUrl = $(this).data('image-url');
                if (imageUrl) {
                    $('#modalImage').attr('src', imageUrl);
                    $('#imageViewModal').modal('show');
                } else {
                    console.error('Image URL not found.');
                }
            });
        });
    </script>
@endsection
