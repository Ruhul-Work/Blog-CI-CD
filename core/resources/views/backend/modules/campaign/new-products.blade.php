@extends('backend.layouts.master')

@section('meta')
    <title>All Products - {{ get_option('title') }}</title>
@endsection

@section('content')
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">

                <h3><span class="badge bg-primary">{{ $campaign->name }}</span></h2>

            </div>
        </div>
        <ul class="table-top-head">
            @include('backend.include.buttons')
        </ul>
        <div class="page-btn">
            <a href="{{ route('campaigns.products.view', ['id' => $campaign->id]) }}" class="btn btn-added"><i
                    data-feather="plus-circle" class="me-2"></i>Back
                To Campaigns Products</a>
        </div>
    </div>


    <div class="card">
        <div class="card-body p-2">
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label>Category</label>

                        <select name="category_id" id="category_id" class="form-control selectSimple"
                            onchange="applyFilters()" multiple="multiple">

                            @foreach ($categories as $cValue)
                                <option value="{{ $cValue->id }}">{{ $cValue->name }}</option>
                            @endforeach

                        </select>

                    </div>
                </div>

                {{-- <div class="col-md-3">
                    <div class="mb-3">
                        <label>Subcategory</label>
                        <select name="subcategory_id" id="subcategory_id" class="form-control selectSimple"
                            onchange="applyFilters()" multiple="multiple">

                            @foreach ($subcategory as $sValue)
                                <option value="{{ $sValue->id }}">{{ $sValue->name }}</option>
                            @endforeach

                        </select>


                    </div>
                </div> --}}

                <div class="col-md-4">
                    <div class="mb-3">
                        <label>Author</label>
                        <select name="author_id" id="author_id" class="form-control selectSimple" onchange="applyFilters()"
                            multiple="multiple">

                            @foreach ($authors as $aValue)
                                <option value="{{ $aValue->id }}">{{ $aValue->name }}</option>
                            @endforeach

                        </select>

                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">

                        <label>Publisher</label>

                        <select name="publisher_id" id="publisher_id" class="form-control selectSimple"
                            onchange="applyFilters()" multiple="multiple">

                            @foreach ($publishers as $pValue)
                                <option value="{{ $pValue->id }}">{{ $pValue->name }}</option>
                            @endforeach

                        </select>

                    </div>
                </div>

                <div class="mb-3 text-center">
                    <a href="{{ route('campaigns.all.create') }}" class="campaign-btn" data-bs-toggle="tooltip"
                        data-bs-placement="top" title="Add to Campaign" data-campaign-id="{{ $campaign->id }}"><button
                            type="button" class="btn btn-success">Add
                            To
                            Campaign</button></a>
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
                <table id="items" class="table AjaxDataTable" style="width:100%;">
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
                            <th class="no-sort">Category</th>
                            {{-- <th class="no-sort">SubCategory</th> --}}
                            <th class="no-sort">Author</th>
                            <th class="no-sort">Publisher</th>
                            <th class="no-sort">Price</th>
                            <th class="no-sort">Stock</th>
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
        AJAX_URL = "{{ route('campaigns-product.ajax', ['id' => $campaign->id]) }}";

        function applyFilters() {
            var filters = {
                category_id: $("#category_id").val(),
                subcategory_id: $("#subcategory_id").val(),
                author_id: $("#author_id").val(),
                publisher_id: $("#publisher_id").val(),
                searchValue: $("#searchValue").val()
            };
            updateDataTable(filters);
        }

        function updateDataTable(filters) {
            var dataTable = $('.AjaxDataTable').DataTable();
            var queryString = $.param(filters);
            var url = "{{ route('campaigns-product.ajax') }}" + "?" + queryString;
            dataTable.ajax.url(url).load();
        }



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


        $(document).ready(function() {
            $(document).on('click', '.campaign-btn', function(event) {
                event.preventDefault();

                var actionUrl = $(this).attr('href');
                var campaignId = $(this).data('campaign-id');
                var selectedIds = [];


                $('table#items tbody input[type="checkbox"]:checked').each(function() {
                    selectedIds.push($(this).data('value'));
                });

                if (selectedIds.length === 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Please select Product.',
                    });
                } else {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'You are about to create a campaign with selected products!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, create it!',
                        cancelButtonText: 'No, cancel',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: actionUrl,
                                method: 'POST',
                                dataType: 'json',
                                data: {
                                    ids: selectedIds,
                                    campaign_id: campaignId
                                },
                                success: function(response) {
                                    if (response.message ===
                                        'Campaign products added successfully.') {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Success',
                                            text: response.message,
                                        }).then(function() {

                                            window.location.href =
                                                "{{ route('campaigns.index') }}";
                                        });
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error',
                                            text: response.message,
                                        });
                                    }
                                },
                                error: function(xhr, status, error) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: 'An error occurred: ' + error,
                                    });
                                }
                            });
                        }
                    });
                }
            });
        });

      
    </script>
@endsection
