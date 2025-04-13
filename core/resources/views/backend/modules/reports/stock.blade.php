@extends('backend.layouts.master')

@section('meta')
    <title>All Stocks - {{ get_option('title') }}</title>
@endsection

@section('content')
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h3>Product Stock  Report</h2>
                <p>List Of Products Report</p>
            </div>
        </div>
        <ul class="table-top-head">
            @include('backend.include.buttons')
        </ul>
        <div class="page-btn">
            <a href="{{route('trigger.stocks.update')}}" class="btn btn-added" ><i data-feather="refresh-cw" class="me-2"></i>Update
                Stocks</a>
        </div>
    </div>


    <div class="card">
        <div class="card-body p-2">
            <div class="row">
                <div class="col-lg-4 col-sm-6 col-12">
                    <label>Date Range</label>
                    <input type="text" name="datetimes" id="datetimes" class="form-control dateRangePredifined" value="">
                    <button type="button" class="btn btn-primary mt-2" onclick="applyFilters()">Search</button>
                    <button type="button" class="btn btn-secondary mt-2" onclick="resetFilters()">Reset</button>
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
                            <th class="no-sort" data-orderable="false" style="width: 5%">
                                <label class="checkboxs">
                                    <input type="checkbox" id="select-all" data-value="0">
                                    <span class="checkmarks"></span>
                                </label>
                            </th>
                            <th style="width: 5%">SN</th>
                            <th class="no-sort" style="width: 5%">Name</th>

                            <th class="no-sort" style="width: 15%">Return Qty & price</th>
                            <th class="no-sort" style="width: 15%">Purchase Qty & price</th>
                            <th class="no-sort" style="width: 15%">Order Qty & price</th>
                            <th class="no-sort" style="width: 15%">Current Stock</th>

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
        AJAX_URL = "{{ route('stocks.ajax') }}";

        function applyFilters() {
            var filters = {
                datetimes: $("#datetimes").val(),
            };
            updateDataTable(filters);
        }

        function updateDataTable(filters) {
            var dataTable = $('.AjaxDataTable').DataTable();
            var queryString = $.param(filters);
            var url = "{{ route('stocks.ajax') }}" + "?" + queryString;
            dataTable.ajax.url(url).load();
        }


        function resetFilters() {
            // Clear the date range input
            document.getElementById("datetimes").value = "";
            // Reload all data in the DataTable
            var url = "{{ route('stocks.ajax') }}";
            var dataTable = $('.AjaxDataTable').DataTable();
            dataTable.ajax.url(url).load();
            // You can add additional reset logic for other filters if needed
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

                // Get the action URL and campaign ID from the button's data attribute
                var actionUrl = $(this).attr('href');
                var campaignId = $(this).data('campaign-id');

                var selectedIds = [];

                // Collect selected product IDs
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
                                    campaign_id: campaignId // Pass the campaign ID
                                },
                                success: function(response) {
                                    if (response.message ===
                                        'Campaign products added successfully.') {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Success',
                                            text: response.message,
                                        }).then(function() {
                                            // Redirect to the campaigns index route
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
