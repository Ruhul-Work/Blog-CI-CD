@extends('backend.layouts.master')
@section('meta')
    <title>All products - {{ get_option('title') }}</title>
@endsection
@section('content')

    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4>Product Management</h4>
                <h6>List of Products</h6>
            </div>
        </div>
        <ul class="table-top-head">
            @include('backend.include.buttons')


            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="Delete" class="delete-btn-group"
                   href="{{route("products.destroy.all")}}"><i data-feather="trash-2"
                                                               class="feather-trash-2 text-danger"></i></a>
            </li>


        </ul>
        
         <div class="page-btn d-flex">
             
             <ul class="table-top-head">
                 
            <li>
              
               <a data-bs-toggle="tooltip" data-bs-placement="top" title="Update Stock Status"
                   class="StockStatusChange  bg-info text-white"
                   href="{{ route('products.stock.status') }}">Stock Status
                </a>

            </li>


            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="Update discount"
                   class="discount bg-secondary text-white"
                   href="{{ route('products.discount.multiple') }}">Discount
                </a>

            </li>
             </ul>
              
           
        </div>
        <div class="page-btn d-flex">
            <a href="{{ route('products.create') }}" class="btn btn-added me-2 ">
                <i data-feather="plus-circle" class="me-1"></i>Add New Product
            </a>

            <a href="{{ route('products.bundle.create') }}" class="btn btn-added">
                <i data-feather="plus-circle" class="me-1"></i>Add Bundle
            </a>
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
                        <th class="no-sort">Product</th>
                        <th class="no-sort">Code</th>
                        <th class="no-sort">Category</th>

                        <th class="no-sort">Price</th>
                        <th class="no-sort">S.Status</th>
                        <th class="no-sort">Stock</th>
                        <th class="no-sort">isBundle</th>

                        <th class="no-sort">Created By</th>
                        <th class="no-sort" width="10px">Status</th>
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
        var AJAX_URL = '{{ route('products.ajax.index') }}';

        $(document).ready(function () {

            $(document).on("click", '.changeStatus', function (e) {
                e.preventDefault();
                var productId = $(this).data('product-id');

                // Send an AJAX request to update the status of the category
                $.ajax({
                    url: '{{ route('products.updateStatus') }}',
                    type: 'POST',
                    data: {
                        id: productId,
                    },
                    success: function (response) {
                        // Show a success message using SweetAlert
                        Swal.fire({
                            title: "Success!",
                            text: response.message,
                            type: "success",
                            confirmButtonClass: "btn btn-success"
                        }).then(function () {
                            // Reload the AjaxDataTable
                            $('.AjaxDataTable').DataTable().ajax.reload();
                        });
                    },
                    error: function (xhr, status, error) {
                        // Handle errors if any
                        Swal.fire({
                            title: "Error!",
                            text: "An error occurred while updating the category status.",
                            type: "error",
                            confirmButtonClass: "btn btn-danger"
                        });
                    }
                });
            });


            $(document).on("click", '.StockStatusChange', function (e) {
                e.preventDefault();

                var checkedRows = $('tbody tr .checked-row:checked');

                if (checkedRows.length > 0) {
                    var href = $(this).attr('href');

                    Swal.fire({
                        title: "Select  Stock Status",
                        html: `
                <select class="form-control select" id="stock_status">

                        @foreach($enumStatusValues as $value)
                        <option
                            value="{{ $value }}" {{ old('stock_status') == $value ? 'selected' : '' }}>{{ ucfirst($value) }}</option>
               @endforeach

                        </select>
`,
                        showCancelButton: true,
                        confirmButtonText: "Update",
                        showLoaderOnConfirm: true,
                        preConfirm: async () => {
                            var selectedStatus = $("#stock_status").val();
                            return selectedStatus;
                        },
                        allowOutsideClick: () => !Swal.isLoading()
                    }).then((result) => {
                        if (result.isConfirmed) {
                            var selectedStatus = result.value;
                            var selectedProductIds = get_checked();

                            $.ajax({
                                type: "GET",
                                url: `${href}?stock_status=${selectedStatus}&product_ids=${encodeURIComponent(JSON.stringify(selectedProductIds))}`,
                                success: function (response) {
                                    Swal.fire("Success!", response.message, "success");
                                    $('.AjaxDataTable').DataTable().ajax.reload();
                                },
                                error: function (error) {
                                    Swal.fire("Error!", 'Error updating: ' + error.statusText, "error");
                                }
                            });
                        }
                    });
                } else {
                    Swal.fire("Warning!", 'No checkbox is checked', "warning");
                }
            });

            $(document).on("click", '.discount', function (e) {
                e.preventDefault();

                var checkedRows = $('tbody tr .checked-row:checked');

                if (checkedRows.length > 0) {
                    var href = $(this).attr('href');

                    Swal.fire({
                        title: "Input Your discount Amount or  Percentage(%)",
                        input: "text",
                        inputAttributes: {
                            autocapitalize: "off"
                        },
                        showCancelButton: true,
                        confirmButtonText: "Submit",
                        showLoaderOnConfirm: true,

                        preConfirm: async (discount) => {
                            try {
                                return discount;
                            } catch (error) {
                                Swal.showValidationMessage(`Request failed: ${error}`);
                            }
                        },
                        allowOutsideClick: () => !Swal.isLoading()
                    }).then((result) => {
                        if (result.isConfirmed) {
                            var discountValue = result.value;

                            // Inline logic to get selected product IDs
                            var selectedProductIds = [];
                            $('.checked-row:checked').each(function () {
                                var num = $(this).attr('data-value');
                                if (num !== '0') {
                                    selectedProductIds.push(num);
                                }
                            });
                            // Base64 Encode selected product IDs using btoa
                            var encodedProductIds = btoa(JSON.stringify(selectedProductIds));
                            var encodedDiscount = encodeURIComponent(discountValue);
                            $.ajax({
                                type: "GET",
                                url: `${href}?discount=${encodedDiscount}&token=${encodedProductIds}`,
                                success: function (response) {
                                    Swal.fire("Success!", response.message, "success");
                                    $('.AjaxDataTable').DataTable().ajax.reload();
                                },
                                error: function (error) {
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
                $('.checked-row').each(function () {
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
