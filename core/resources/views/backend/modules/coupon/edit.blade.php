@extends('backend.layouts.master')

@section('meta')
    <title>Update Coupons - {{ get_option('title') }}</title>
@endsection

@section('content')

    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4>Edit Coupons</h4>
                <h6>Edit New Coupons</h6>
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
                                            <label class="form-label">Type<span class="star-sign">*</span></label>
                                            <input type="hidden" name="id" value="{{ $coupons->id }}">
                                            <select name="c_type" id="" class="form-control coupon_type" disabled>
                                                <option value="#" disabled>--select--</option>
                                                <option value="cart_base"
                                                    {{ $coupons->c_type == 'cart_base' ? 'selected' : '' }}>Cart Base
                                                </option>
                                                <option value="product_base"
                                                    {{ $coupons->c_type == 'product_base' ? 'selected' : '' }}>Product Base
                                                </option>
                                                <option value="offer_base"
                                                    {{ $coupons->c_type == 'offer_base' ? 'selected' : '' }}>Offer Base
                                                </option>
                                                <option value="shipping_base"
                                                    {{ $coupons->c_type == 'shipping_base' ? 'selected' : '' }}>Shipping
                                                    Base</option>
                                            </select>

                                        </div>
                                    </div>

                                    {{-- @if ($coupons->c_type == 'cart_base')
                                        <div class="col-lg-4 col-sm-6 col-12 cart_base">
                                            <div class="mb-3 add-product">
                                                <label class="form-label">Cart Type Details</label>
                                                <input type="text" class="form-control" id="name"
                                                    name="type_details">
                                            </div>
                                        </div>
                                    @endif --}}
                                    {{--
                                    @if ($coupons->c_type == 'product_base' || $coupons->c_type == 'shipping_base' || $coupons->c_type == 'offer_base')
                                        <div class="col-lg-4 col-sm-6 col-12" id="show_product">
                                            <div class="mb-3 add-product required">
                                                <label class="form-label">Type Details</label>
                                                <select name="type_details[]" id=""
                                                    class="form-control coupon_type selectSimple" multiple>
                                                    <option value="#" disabled>--select--</option>
                                                    @if ($coupons->c_type == 'product_base' || $coupons->c_type == 'shipping_base' || $coupons->c_type == 'offer_base')
                                                        @php
                                                            $productsId = json_decode($coupons->type_details, true);
                                                            $matchedProducts = [];

                                                            if (is_array($productsId) && !empty($productsId)) {
                                                                $productIds = array_values($productsId);

                                                                $products = \App\Models\Product::whereIn(
                                                                    'id',
                                                                    $productIds,
                                                                )->get();

                                                                foreach ($products as $product) {
                                                                    if (in_array($product->id, $productIds)) {
                                                                        $matchedProducts[] = $product;
                                                                    }
                                                                }
                                                            }
                                                        @endphp

                                                        @foreach ($allProducts as $pItem)
                                                            @php

                                                                $selected =
                                                                    isset($productIds) &&
                                                                    is_array($productIds) &&
                                                                    in_array($pItem->id, $productIds)
                                                                        ? 'selected'
                                                                        : '';
                                                            @endphp

                                                            <option value="{{ $pItem->id }}" {{ $selected }}>
                                                                {{ $pItem->english_name }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>








                                            </div>

                                        </div>
                                    @endif --}}

                                    <div class="col-lg-4 col-sm-6 col-12">
                                        <div class="mb-3 add-product">
                                            <label class="form-label">Code<span class="star-sign">*</span></label>
                                            <input type="text" class="form-control" id="name" name="code"
                                                value="{{ $coupons->code ?? '' }}">
                                        </div>

                                    </div>

                                    <div class="col-lg-4 col-sm-6 col-12">
                                        <div class="mb-3 add-product">
                                            <label class="form-label">Title<span class="star-sign"></span></label>
                                            <input type="text" class="form-control" id="name" name="title"
                                                value="{{ $coupons->title ?? '' }}">

                                        </div>

                                    </div>

                                    <div class="col-lg-4 col-sm-6 col-12">
                                        <div class="mb-3 add-product">
                                            <label class="form-label">Discount<span class="star-sign">*</span></label>
                                            <input type="text" class="form-control" id="name" name="discount"
                                                value="{{ $coupons->discount ?? '' }}">

                                        </div>

                                    </div>
                                    <div class="col-lg-4 col-sm-6 col-12">
                                        <div class="mb-3 add-product">
                                            <label class="form-label">Discount Type<span class="star-sign">*</span></label>
                                            <input type="text" class="form-control" id="name" name="discount_type"
                                                value="{{ $coupons->discount_type ?? '' }}">
                                        </div>

                                    </div>





                                    <div class="col-lg-4 col-sm-6 col-12 minimum">
                                        <div class="mb-3 add-product required">
                                            <label class="form-label">Minimum Buy</label>
                                            <input type="number" class="form-control" name="min_buy"
                                                placeholder="Enter text here" value="{{ $coupons->min_buy ?? '' }}">
                                        </div>
                                    </div>


                                    <div class="col-lg-4 col-sm-6 col-12 max_discount">
                                        <div class="mb-3 add-product required">
                                            <label class="form-label">Max Discount</label>
                                            <input type="text" class="form-control" name="max_discount"
                                                placeholder="Enter text here" value="{{ $coupons->max_discount ?? '' }}">
                                        </div>
                                    </div>


                                    <!--<div class="col-lg-4 col-sm-4 col-12 required is_valid">-->
                                    <!--    <label class="form-label">Valid for first order</label>-->
                                    <!--    <select class="form-select select" name="is_valid_first_order">-->
                                    <!--        <option value="1"-->
                                    <!--            {{ $coupons->is_valid_first_order == 1 ? 'selected' : '' }}>Yes</option>-->
                                    <!--        <option value="0"-->
                                    <!--            {{ $coupons->is_valid_first_order == 0 ? 'selected' : '' }}>No</option>-->
                                    <!--    </select>-->
                                    <!--</div>-->


                                    <div class="col-lg-4 col-sm-4 col-12">
                                        <label class="form-label">Status</label>
                                        <select class="form-select select" name="status">
                                            <option disabled>-- Select --</option>
                                            <option value="1" {{ $coupons->status == 1 ? 'selected' : '' }}>Active
                                            </option>
                                            <option value="0" {{ $coupons->status == 0 ? 'selected' : '' }}>Inactive
                                            </option>
                                        </select>
                                    </div>
                                    
                                     <div class="col-lg-4 col-sm-6 col-12">
                                        <div class="mb-3 add-product required">
                                            <label class="form-label">Date Range</label>
                                            
                                            <input type="text" class="form-control dateRangePredifined" name="daterange" value="{{ $coupons->start_date->format('Y-m-d') }} - {{ $coupons->end_date->format('Y-m-d') }}">
                                        </div>
                                    </div>



                                </div>

                                <div class="row">

                                   

                                    <!--<div class="col-lg-3 col-sm-4 col-12 required">-->
                                    <!--    <label class="form-label">User Type</label>-->
                                    <!--    <select class="form-select select" name="user_type">-->
                                    <!--        <option disabled>-- Select --</option>-->

                                    <!--        <option value="customer"-->
                                    <!--            {{ $coupons->user_type == 'customer' ? 'selected' : '' }}>Customer</option>-->

                                    <!--        <option value="shop" {{ $coupons->user_type == 'shop' ? 'selected' : '' }}>-->
                                    <!--            Shop</option>-->
                                    <!--        <option value="admin" {{ $coupons->user_type == 'admin' ? 'selected' : '' }}>-->
                                    <!--            Admin</option>-->
                                    <!--        <option value="guest" {{ $coupons->user_type == 'guest' ? 'selected' : '' }}>-->
                                    <!--            Guest</option>-->
                                    <!--    </select>-->
                                    <!--</div>-->

                                    <div class="col-lg-3 col-sm-6 col-12">
                                        <div class="mb-3 add-product">
                                            <label class="form-label">Stock</label>
                                            <input type="text" class="form-control" id="name" name="stock"
                                                value="{{ $coupons->stock ?? '' }}">
                                        </div>

                                    </div>

                                    <div class="col-lg-3 col-sm-6 col-12">
                                        <div class="mb-3 add-product required">
                                            <label class="form-label">Individual Max Use</label>
                                            <input type="text" class="form-control" id="name"
                                                name="individual_max_use"
                                                value="{{ $coupons->individual_max_use ?? '' }}">
                                        </div>
                                    </div>

                                    <div class="col-lg-12 col-sm-6 col-12">
                                        <div class="mb-3 add-product">
                                            <label class="form-label">Notes</label>
                                            <textarea class="form-control h-100" rows="8" name="notes">{{ $coupons->notes ?? '' }}</textarea>
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
                <button type="submit" class="btn btn-submit">Update Coupons</button>
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
    </style>
    <script src="{{ asset('theme/admin/assets/plugins/fileupload/spartan-multi-image-picker-min.js') }}"
        type="text/javascript"></script>
    <script>
        $(document).ready(function() {

            $('#createSliders').submit(function(e) {
                e.preventDefault();
                // Get form data
                var formData = new FormData(this);
                // Make AJAX request
                $.ajax({
                    url: '{{ route('coupons.update') }}',
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


        $('#coupon_type').change(function() {
            var selectedOption = $(this).val();
            if (selectedOption == 'product_base') {
                $('#show_product').show();
                $('.cart_base').hide();
            } else if (selectedOption == 'offer_base') {
                $('#show_product').show();
                $('.cart_base').hide();
            } else {
                $('#show_product').hide();
                $('.cart_base').show();
            }
        });
    </script>
@endsection
