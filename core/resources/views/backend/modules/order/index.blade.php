@extends('backend.layouts.master')
@section('meta')
    <title>All Orders - {{ get_option('title') }}</title>
@endsection
@section('content')
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4>Order Management</h4>
                <h6>List of Orders</h6>
            </div>
        </div>
        <ul class="table-top-head">
            @include('backend.include.buttons')


            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="Delete" class="delete-btn-group"
                   href="{{route("orders.destroy.all")}}"><i data-feather="trash-2"
                                                               class="feather-trash-2 text-danger"></i></a>
            </li>



            

        </ul>
        
        
        <div class="page-btn d-flex">

           <ul class="table-top-head">
               <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="Update Status" class="orderStatusChange  bg-dark text-white "
                   href="{{ route('orders.updateStatus') }}"> Order Status
                </a>
            </li>
            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="Update Payment Status" class="paymentStatusChange  bg-info text-white"
                   href="{{ route('orders.payment.status') }}">Payment Status
                </a>

            </li>

            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="Assign Courier" class="assignCourier  bg-success text-white"
                   href="{{ route('orders.courier') }}">Assign Courier
                </a>

            </li>
           </ul>

        </div>
        <div class="page-btn d-flex">

            <a href="{{ route('pos.index') }}" class="btn btn-added me-2 ">
                <i data-feather="plus-circle" class="me-1"></i>Add New Order
            </a>

        </div>

    </div>
   

               <div class="card card-body py-3">
        <div class="row g-3">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="datetimes" class="form-label">Date Range</label>
                    <input type="text" name="datetimes"   id="datetimes" class="form-control dateRangePredifined " placeholder="Select Date Range" value="">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="order_status_id" class="form-label">Order Status</label>
                    <select name="order_status_id" id="order_status_id" class="form-select">
                        <option value="" selected>All Statuses</option>
                        @foreach ($orderStatuses as $cValue)
                            <option value="{{ $cValue->id }}">{{ $cValue->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            
             <div class="col-md-3">
                    <div class="form-group">
                           <label for="order_status_id" class="form-label">Payment Method</label>
    
                        <select name="method_id" id="method_id" class="form-control select">
                             <option value=" " selected>select</option>
                            @foreach ($paymentMethods as $method)
                                <option value="{{ $method->id }}">{{ $method->name }}</option>
                            @endforeach

                        </select>

                    </div>
                </div>
            
            <div class="col-md-3">

                <div class="page-btn d-flex align-items-center mt-4">
                    <ul class="table-top-head ">
                        <li>
                            <a data-bs-toggle="tooltip" data-bs-placement="top"  class=" bg-info text-white"
                               href="javascript:void(0)" onclick="applyFilters()"> Apply Filter
                            </a>

                        </li>
                        <li>
                            <a data-bs-toggle="tooltip" data-bs-placement="top"  class=" bg-warning text-white"
                               href="javascript:void(0)" onclick="resetFilters()"> Reset Filter
                            </a>
                        </li>


                    </ul>
                </div>

            </div>
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


            <div class="table-responsive  product-list">
                <table class="table   AjaxDataTable"
                       style="width:100%">
                    <thead>
                    <tr>
                        <th class="no-sort" data-orderable="false">
                            <label class="checkboxs">
                                <input type="checkbox" id="select-all" data-value="0">
                                <span class="checkmarks"></span>
                            </label>
                        </th>
                        <th class="no-sort" >Shipping Details</th>
                        <th >Order Info </th>
                        <th class="no-sort">CustomerNote</th>

                        <th class="no-sort">OrderAmount </th>

                        <th class="no-sort">PaymentStatus</th>

                        <th class="no-sort">PaymentMethods</th>
                       
                        <th class="no-sort">Invoice & Payment</th>


                        <th class="no-sort">OrderBy</th>


                        <th class="no-sort" width="10px">Action</th>
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
        var AJAX_URL = '{{ route('orders.ajax.index') }}';
        
        
   
          function applyFilters() {
            var filters = {
                order_status_id: $("#order_status_id").val(),
                method_id: $("#method_id").val(),
                date_range: $("#datetimes").val() // Include the date range value
            };
            updateDataTable(filters);
        }

        function updateDataTable(filters) {
            var dataTable = $('.AjaxDataTable').DataTable();
            var queryString = $.param(filters);
            var url = AJAX_URL + "?" + queryString;
            dataTable.ajax.url(url).load();
        }

        function resetFilters() {
            // Clear all filter inputs
            $("#order_status_id").val('');
            $("#datetimes").val('');
            $("#method_id").val('');
            applyFilters(); // Reapply filters to reset the data table
        }






        $(document).ready(function () {

            $(document).on("click", '#downloadInvoice', function(e) {
                e.preventDefault();

                var href = $(this).data('href');

                $.ajax({
                    type: "GET",
                    url: href,
                    success: function(response) {
                        // Assuming response contains the HTML content of the invoice
                        var htmlContent = response.htmlContent;
                        // Create a new window and write the HTML content to it
                        var newWindow = window.open();
                        newWindow.document.write(htmlContent);
                        newWindow.document.close(); // Important for some browsers to render the content
                    },
                    error: function(error) {
                        Swal.fire("Error!", 'Error generating invoice: ' + error.statusText, "error");
                    }
                });
            });
            
            
            
            
            $(document).on("click", '.orderStatusChange', function(e) {
    e.preventDefault();

    var checkedRows = $('tbody tr .checked-row:checked');

    if (checkedRows.length > 0) {
        var href = $(this).attr('href');

        Swal.fire({
            title: "Select Status",
            html: `
                <select class="form-control select2" id="status">
                    @foreach($orderStatuses as $status)
            <option value="{{ $status->id }}">{{ $status->name }}</option>
                    @endforeach
            </select>
`,
            showCancelButton: true,
            confirmButtonText: "Update",
            showLoaderOnConfirm: true,
            preConfirm: async () => {
                var selectedStatusId = $("#status").val();
                return selectedStatusId;
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed) {
                var selectedStatusId = result.value;

                // Collect the IDs of checked rows related to the order
                // var selectedOrderIds = [];
                // checkedRows.each(function() {
                //     selectedOrderIds.push($(this).closest('tr').data('order-id'));
                // });

                var selectedOrderIds = get_checked();

                $.ajax({
                    type: "GET",
                    url: `${href}?status_id=${selectedStatusId}&order_ids=${encodeURIComponent(JSON.stringify(selectedOrderIds))}`,
                    success: function(response) {
                        Swal.fire("Success!", response.message, "success");
                        $('.AjaxDataTable').DataTable().ajax.reload();
                    },
                    error: function(error) {
                        Swal.fire("Error!", 'Error updating: ' + error.statusText, "error");
                    }
                });
            }
        });
    } else {
        Swal.fire("Warning!", 'No checkbox is checked', "warning");
    }
});
            $(document).on("click", '.paymentStatusChange', function(e) {
                e.preventDefault();

                var checkedRows = $('tbody tr .checked-row:checked');

                if (checkedRows.length > 0) {
                    var href = $(this).attr('href');

                    Swal.fire({
                        title: "Select  Payment Status",
                        html: `
                <select class="form-control select" id="payment_status">

                        <option value="unpaid">Unpaid</option>
                        <option value="paid">Paid</option>
                        <option value="partial">Partial</option>

                        </select>
`,
                        showCancelButton: true,
                        confirmButtonText: "Update",
                        showLoaderOnConfirm: true,
                        preConfirm: async () => {
                            var selectedStatus=$("#payment_status").val();
                            return selectedStatus;
                        },
                        allowOutsideClick: () => !Swal.isLoading()
                    }).then((result) => {
                        if (result.isConfirmed) {
                            var selectedStatus = result.value;
                            var selectedOrderIds = get_checked();

                            $.ajax({
                                type: "GET",
                                url: `${href}?payment_status=${selectedStatus}&order_ids=${encodeURIComponent(JSON.stringify(selectedOrderIds))}`,
                                success: function(response) {
                                    Swal.fire("Success!", response.message, "success");
                                    $('.AjaxDataTable').DataTable().ajax.reload();
                                },
                                error: function(error) {
                                    Swal.fire("Error!", 'Error updating: ' + error.statusText, "error");
                                }
                            });
                        }
                    });
                } else {
                    Swal.fire("Warning!", 'No checkbox is checked', "warning");
                }
            });
            $(document).on("click", '.assignCourier', function(e) {
                e.preventDefault();

                var checkedRows = $('tbody tr .checked-row:checked');

                if (checkedRows.length > 0) {
                    var href = $(this).attr('href');

                    Swal.fire({
                        title: "Select  Courier",
                        html: `
                          <select class=" form-control select" id="courier_id">
                        @foreach($couriers as $courier)

                        <option value="{{$courier->id}}">{{$courier->name}}</option>

                        @endforeach

                        </select>
`,
                        showCancelButton: true,
                        confirmButtonText: "Update",
                        showLoaderOnConfirm: true,
                        preConfirm: async () => {
                            var selectedCourier =$("#courier_id").val();
                            return selectedCourier;
                        },
                        allowOutsideClick: () => !Swal.isLoading()
                    }).then((result) => {
                        if (result.isConfirmed) {
                            var selectedCourierId = result.value;
                            var selectedOrderIds = get_checked();

                            $.ajax({
                                type: "GET",
                                url: `${href}?courier_id=${selectedCourierId}&order_ids=${encodeURIComponent(JSON.stringify(selectedOrderIds))}`,
                                success: function(response) {
                                    Swal.fire("Success!", response.message, "success");
                                    $('.AjaxDataTable').DataTable().ajax.reload();
                                },
                                error: function(error) {
                                    Swal.fire("Error!", 'Error updating: ' + error.statusText, "error");
                                }
                            });
                        }
                    });
                } else {
                    Swal.fire("Warning!", 'No checkbox is checked', "warning");
                }
            });


            function get_checked() {
                var selected = [];
                $('.checked-row').each(function() {
                    if ($(this).is(":checked")) {

                        var num = $(this).attr('data-value');

                        if (num != '0')
                            selected.push($(this).attr('data-value'));
                    }
                });
                var url = (btoa(JSON.stringify(selected)));

                return url;

            }

        });
    </script>
@endsection

