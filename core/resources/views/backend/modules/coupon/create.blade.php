@extends('backend.layouts.master')

@section('meta')
    <title>Create New Coupons - {{ get_option('title') }}</title>
@endsection

@section('content')
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4>New Coupons</h4>
                <h6>Create New Coupons</h6>
            </div>
        </div>
        <ul class="table-top-head">

            <li>
                <div class="page-btn">
                    <a href="{{ route('coupons.index') }}" class="btn btn-secondary"><i data-feather="arrow-left"
                            class="me-2"></i>Back To Coupons</a>
                </div>
            </li>

            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i
                        data-feather="chevron-up" class="feather-chevron-up"></i></a>
            </li>

        </ul>
    </div>

    <form action="" id="createSliders" method="post" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-body add-product pb-0">
                <div class="accordion-card-one accordion" id="accordionExample">
                    <div class="accordion-item">
                        <div class="accordion-header" id="headingOne">
                            <div class="accordion-button" data-bs-toggle="collapse" data-bs-target="#collapseOne"
                                aria-controls="collapseOne">
                                <div class="addproduct-icon">
                                    <h5><i data-feather="info" class="add-info"></i><span>Basic Information</span></h5>
                                    <a href="javascript:void(0);"><i data-feather="chevron-down"
                                            class="chevron-down-add"></i></a>
                                </div>
                            </div>
                        </div>
                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <div class="row">
                                    <div class="col-lg-4 col-sm-6 col-12">

                                        <div class="mb-3 add-product">
                                            <label class="form-label">Type</label>
                                            <select name="c_type" id="coupon_type" class="form-control coupon_type select">
                                                <option value="#" disabled>--select--</option>
                                                <option value="cart_base">Cart Base</option>
                                                <option value="product_base">Product Base</option>

                                            </select>
                                        </div>

                                    </div>


                                    <div class="col-lg-4 col-sm-6 col-12">
                                        <div class="mb-3 add-product">
                                            <label class="form-label">Title</label>
                                            <input type="text" class="form-control" id="name" name="title"
                                                placeholder="Enter text here">
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-sm-6 col-12">
                                        <div class="mb-3 add-product required">
                                            <label class="form-label">Code</label>
                                            <input type="text" class="form-control" id="name" name="code"
                                                placeholder="Enter text here">
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-sm-6 col-12">
                                        <div class="mb-3 add-product required">
                                            <label class="form-label">Discount</label>
                                            <input type="text" class="form-control" id="name" name="discount"
                                                placeholder="Enter text here" value="0">
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-sm-6 col-12">
                                        <div class="mb-3 add-product required">
                                            <label class="form-label">Discount Type</label>
                                            <select class="form-select select" name="discount_type">
                                                <option value="percent">Percent</option>
                                                <option value="amount">Amount</option>
                                            </select>
                                        </div>
                                    </div>


                                    <div class="col-lg-4 col-sm-6 col-12 minimum">
                                        <div class="mb-3 add-product required">
                                            <label class="form-label">Minimum Buy</label>
                                            <input type="number" class="form-control" name="min_buy"
                                                placeholder="Enter text here" value="0">
                                        </div>
                                    </div>


                                    <div class="col-lg-4 col-sm-6 col-12 max_discount">
                                        <div class="mb-3 add-product required">
                                            <label class="form-label">Max Discount</label>
                                            <input type="text" class="form-control" name="max_discount"
                                                placeholder="Enter text here" value="0">
                                        </div>
                                    </div>


                                    <!--<div class="col-lg-4 col-sm-4 col-12 required is_valid">-->
                                    <!--    <label class="form-label">Valid for first order</label>-->
                                    <!--    <select class="form-select select" name="is_valid_first_order">-->
                                    <!--        <option value="1">Yes</option>-->
                                    <!--        <option value="0">No</option>-->
                                    <!--    </select>-->
                                    <!--</div>-->

                                    <div class="col-lg-4 col-sm-4 col-12 required">
                                        <label class="form-label">Status</label>
                                        <select class="form-select select" name="status">
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                    </div>


                                </div>

                                <div class="row">
                                    <div class="col-lg-4 col-sm-6 col-12">
                                        <div class="mb-3 add-product required">
                                            <label class="form-label">Date Range</label>
                                            <input type="text" class="form-control dateranges" name="daterange">
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-sm-6 col-12">
                                        <div class="mb-3 add-product required">
                                            <label class="form-label">Stock</label>
                                            <input type="text" class="form-control" id="name" name="stock"
                                                placeholder="Enter text here">
                                        </div>
                                    </div>

                                    <!--<div class="col-lg-4 col-sm-4 col-12 required">-->
                                    <!--    <label class="form-label">User Type</label>-->
                                    <!--    <select class="form-select select" name="user_type">-->
                                    <!--        <option disabled>-- Select --</option>-->
                                    <!--        <option value="customer">Customer</option>-->
                                    <!--        <option value="shop">Shop</option>-->
                                    <!--        <option value="admin">Admin</option>-->
                                    <!--        <option value="guest">Guest</option>-->
                                    <!--    </select>-->
                                    <!--</div>-->

                                    <div class="col-lg-4 col-sm-6 col-12">
                                        <div class="mb-3 add-product required">
                                            <label class="form-label">Max Use</label>
                                            <input type="text" class="form-control" id="name"
                                                name="individual_max_use" placeholder="Enter text here">
                                        </div>
                                    </div>

                                    <div class="col-lg-12 col-sm-6 col-12">
                                        <div class="mb-3 add-product">
                                            <label class="form-label">Notes</label>
                                            <textarea class="form-control h-100" rows="8" name="notes" placeholder="Enter text here"></textarea>
                                        </div>
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
                <button type="submit" class="btn btn-submit">Create Coupons</button>
            </div>
        </div>

    </form>
@endsection
@section('script')
    <style>
        .star-sign {
            color: red;
            font-weight: bold;
        }

        #show_product {
            display: none;
        }
    </style>
    <script src="{{ asset('theme/admin/assets/plugins/fileupload/spartan-multi-image-picker-min.js') }}"
        type="text/javascript"></script>
    <script>
        $(document).ready(function() {
            $('.user_type').select2();
        });


        $(document).ready(function() {
            // Event listener for name field
            $('#createSliders').submit(function(e) {
                e.preventDefault(); // Prevent the form from submitting normally
                // Get form data
                var formData = new FormData(this);
                // Make AJAX request
                $.ajax({
                    url: '{{ route('coupons.store') }}',
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
                                window.location.href = '{{ route('coupons.index') }}';
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


        });


        // $('#coupon_type').change(function() {

        //     var selectedOption = $(this).val();

        //     if (selectedOption == 'product_base') {
        //         $('#show_product').show();
        //         $('.cart_base').hide();
        //     } else if (selectedOption == 'offer_base') {
        //         $('#show_product').show();
        //         $('.cart_base').hide();
        //     } else {
        //         $('#show_product').hide();
        //         $('.cart_base').show();
        //     }
        // });




        $(document).ready(function() {
            $('.coupon_type').change(function() {
                var selectedOption = $(this).val();
                if (selectedOption == 'product_base') {
                    $('.max_discount').show();
                    $('.minimum').show();
                    $('.is_valid').show();
                    // $('.cart_base').hide(); // If you have other elements to hide/show, manage them similarly
                } else if (selectedOption == 'cart_base') {
                    $('.max_discount').hide();
                    $('.minimum').hide();
                    $('.is_valid').hide();
                } else {
                    $('.maximum').hide();
                    $('.minimum').hide();
                    $('.is_valid').hide();

                }
            });
            $('.maximum').hide();
            $('.minimum').hide();
            $('.is_valid').hide();
        });
    </script>
@endsection
