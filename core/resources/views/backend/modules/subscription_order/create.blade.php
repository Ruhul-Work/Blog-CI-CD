{{-- @extends('backend.modules.pos.layouts.app') --}}
@extends('backend.layouts.master')
@section('meta')
    <title>Create Order</title>
@endsection
@section('content')
    {{--    <div class="page-wrapper pos-pg-wrapper ms-0"> --}}
    <div class="content pos-design p-0">

        <div class="row align-items-start pos-wrapper">
            <div class="col-md-4 col-padding">
                <div class="card card-body pos-card-bg gutter-b  border-0 shadow-sm" style="margin-bottom:0px;">
                    <div class="form-group row mb-0">
                        <div class="col-md-12">
                            <fieldset class="form-group mb-0 d-flex barcodeselection">
                                <input type="text" class="form-control package_search" id="package_search"
                                    placeholder="Search Your Subscription Package" oninput="fetchPackages()">
                            </fieldset>
                        </div>
                    </div>
                </div>

                <div class="pos-categories tabs_wrapper">
                    <div class="pos-products">
                        <div class="tabs_container">
                            <div class="tab_content active" data-tab="all">
                                <div class="row" id="packageList" style="max-height: 700px; overflow-y: scroll;">
                                    <!-- Dynamic package list will be loaded here -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="row">

                    <div class="col-md-6 mb-3">
                        <aside class="product-order-list shadow">
                            <div class="product-added block-section">
                                <div class="head-text d-flex align-items-center justify-content-between">
                                    <h6 class="d-flex align-items-center mb-0">
                                        Total Package Added <i class="fas fa-shopping-cart fa-2x ml-2"></i>
                                        <span class="count-badge count-cart">0</span>
                                    </h6>
                                    <a href="javascript:void(0);" class="d-flex align-items-center text-danger"
                                        onclick="clearCart()">
                                        <span class="me-1"><i data-feather="x" class="feather-16"></i></span>Clear all</a>
                                </div>
                                <hr style="margin: 5px 5px;">
                                <hr style="margin: 5px 5px;">
                                <div class="product-wrap" id="cart">
                                    <!-- Cart items will be displayed here dynamically -->
                                </div>
                            </div>
                        </aside>
                    </div>

                    <!--payment Cart displayed -->
                    <div class="col-md-6">
                        <div class="selling-info">
                            <div class="row">
                                <div class="col-12 col-sm-6">
                                    <div class="mb-2 row">
                                        <div class="input-group">
                                            <span class="input-group-text">Discount</span>
                                            <input type="text" class="form-control" id="cart_discount"
                                                name="cart_discount" oninput="calculateCartTotal()">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div class="mb-2 row">
                                        <div class="input-group">
                                            <input type="hidden" name="coupon_discount" id="coupon_discount"
                                                value="">
                                            <span class="input-group-text">Coupon</span>
                                            <select name="coupon_code" id="coupon_code" class="form-control"
                                                onchange="applyCoupon()">
                                                <option value="">Select Coupon</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="block-section cart-summary-view bg-white p-2">
                            <div class="order-total">
                                <table class="table table-responsive table-borderless fw-bold">
                                    <tbody>
                                        <tr>
                                            <td>Total Item Price</td>
                                            <td class="text-end itemTotal">0.00</td>
                                        </tr>
                                        <tr>
                                            <td>Sub Total</td>
                                            <td class="text-end subtotalAmount">0.00</td>
                                        </tr>
                                        <tr>
                                            <td class="danger">Discount</td>
                                            <td class="danger text-end discount">0.00</td>
                                        </tr>
                                        <tr>
                                            <td class="danger">Coupon Discount</td>
                                            <td class="danger text-end coupon_discount">0.00</td>
                                        </tr>
                                        <tr class="total-border">
                                            <td>Total</td>
                                            <td class="text-end totalAmount">0.00</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="btn-row d-sm-flex align-items-center justify-content-between ">
                            <a href="javascript:void(0);" class="btn btn-info btn-icon flex-fill" data-bs-toggle="modal"
                                data-bs-target="#payment">
                                <span class="me-1 d-flex align-items-center">
                                    <i data-feather="credit-card" class="feather-16"></i>
                                </span>Payment
                            </a>
                        </div>
                        <div class="block-section payment-method text-center mb-2">
                            <div class="summary">
                                @include('backend.modules.subscription_order.payment_summary')
                            </div>
                        </div>
                    </div>


                    <div class="col-md-12 mb-5">
                        <aside class="product-order-list shadow">

                            <h3 class="offcanvas-title text-center mb-2 text-success" id="offcanvasBottomLabel">Subscriber
                                Information</h3>



                            <div class="customer-info block-section">
                                <h5 style="color: #007BFF; font-size: 1.2em; font-weight: bold;">
                                    Customer Information<i class="fa fa-asterisk" style="color:red"></i>
                                </h5>
                                <p style="font-size: 1em; color: #555;">
                                    <em>For a new customer, please create a customer first and then select it.</em>
                                </p>

                                <div class="input-block d-flex align-items-center">

                                    <div class="flex-grow-1">
                                        <select class="select" name="customer_id" id="customer_id">

                                            <!-- User options will be loaded dynamically -->
                                        </select>
                                    </div>
                                    <a href="#" class="btn btn-success d-flex align-items-center"
                                        data-bs-toggle="modal" data-bs-target="#create">
                                        <i data-feather="user-plus" class="me-1"></i>
                                        <span>Create</span>
                                    </a>
                                </div>
                            </div>



                            <!--<div class="customer-info block-section">-->

                            <!--    <div class="input-block d-flex align-items-center">-->
                            <!--        <div class="flex-grow-1">-->
                            <!--            <select class="select" name="customer_id" id="customer_id">-->


                            <!--            </select>-->
                            <!--        </div>-->
                            <!--        <a href="#" class="btn btn-success btn-icon" data-bs-toggle="modal"-->
                            <!--            data-bs-target="#create"><i data-feather="user-plus" class="feather-16"></i></a>-->
                            <!--    </div>-->

                            <!--</div>-->

                            {{-- <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body shippingInfo">
                                        <form action="{{ route('pos.cart.store') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="cart_data" id="cart_data">
                                            <input type="hidden" name="itemTotal" id="itemTotal">
                                            <input type="hidden" name="discount" id="discount">

                                            <input type="hidden" name="coupon_code" id="couponCode">
                                            <input type="hidden" name="coupon_discount" id="couponDiscount">
                                            <input type="hidden" name="subtotal" id="subtotal">
                                            <input type="hidden" name="total" id="total">



                                            <div class="row mt-2">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Full Name: <i class="fa fa-asterisk"
                                                                style="color:red"></i></label>
                                                        <input id="name" type="text" class="form-control  "
                                                            name="name" required="">
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Mobile Number: <i class="fa fa-asterisk"
                                                                style="color:red"></i></label>
                                                        <input id="phone" type="text" class="form-control px-2"
                                                            name="phone" required>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>subscription Date: <i class="fa fa-asterisk"
                                                                style="color:red"></i></label>
                                                        <input id="sale_date" type="date" class="form-control "
                                                            name="sale_date">
                                                    </div>
                                                </div>


                                                <div class="col-md-9">
                                                    <div class="form-group">
                                                        <label>Address <i class="fa fa-asterisk"
                                                                style="color:red"></i></label>
                                                        <textarea id="address" class="form-control " name="address" required=""
                                                            placeholder="Enter Flat No./ House/ Road"></textarea>
                                                    </div>
                                                </div>


                                                <div class="col-md-12 mt-5">

                                                    <div class="btn-div"
                                                        style="position: fixed;right:-13px;bottom:0;width:100%">
                                                        <button id="placeOrder" type="submit"
                                                            class="btn btn-danger w-100 mt-2 py-3"> PLACE ORDER</button>
                                                    </div>

                                                </div>
                                            </div>


                                        </form>
                                    </div>
                                </div>
                            </div> --}}


                            <!--Shipping card start-->
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body shippingInfo">
                                        <form class="mb-5" action="{{ route('subscription-orders.store') }}"
                                            id="orderForm" method="POST">
                                            @csrf
                                            <!-- Hidden Inputs -->
                                            <input type="hidden" name="cart_data" id="cart_data">
                                            <input type="hidden" name="subtotal" id="subtotal">
                                            <input type="hidden" name="discount" id="discount">
                                            <input type="hidden" name="coupon_id" id="coupon_id">
                                            <input type="hidden" name="coupon_discount" id="couponDiscount">
                                            <input type="hidden" name="total" id="total">
                                            <input type="hidden" name="pay_method" id="pay_method">
                                            <input type="hidden" name="pay_amount" id="pay_amount">
                                            <input type="hidden" name="user_id" id="user_id">

                                            <!-- Shipping Info -->
                                            <div class="row mt-2 mb-5">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Full Name: <i class="fa fa-asterisk"
                                                                style="color:red"></i></label>
                                                        <input id="name" type="text" class="form-control"
                                                            name="name" required="">
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Mobile Number: <i class="fa fa-asterisk"
                                                                style="color:red"></i></label>
                                                        <input id="mobile_number" type="text" class="form-control"
                                                            name="mobile_number" required>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Subscription Date: <i class="fa fa-asterisk"
                                                                style="color:red"></i></label>
                                                        <input id="subscription_start_date" type="date"
                                                            class="form-control" name="subscription_start_date" required>
                                                    </div>
                                                </div>

                                                <div class="col-md-9">
                                                    <div class="form-group">
                                                        <label>Address <i class="fa fa-asterisk"
                                                                style="color:red"></i></label>
                                                        <textarea id="address" class="form-control" name="address" required=""
                                                            placeholder="Enter Flat No./ House/ Road"></textarea>
                                                    </div>
                                                </div>


                                            </div>
                                            <div class="col-md-12 mt-5">
                                                <div class="btn-div"
                                                    style="position: fixed; right: -13px; bottom: 0; width: 100%;">
                                                    <button id="placeOrder" type="submit"
                                                        class="btn btn-danger w-100 mt-2 py-3">
                                                        PLACE ORDER
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>


                        </aside>

                    </div>
                </div>
            </div>
        </div>

    </div>


    {{-- <div class="sticky-div" id="stickyDiv">

        </div> --}}
    </div>
    {{--    </div> --}}

    {{-- customer add modal --}}
    <div class="modal fade" id="create" tabindex="-1" aria-labelledby="create" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="customerForm">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6 col-sm-12 col-12">
                                <div class="input-blocks">
                                    <label>Customer Name <span class="star-sign">*</span></label>
                                    <input type="text" name="name" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12 col-12">
                                <div class="input-blocks">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12 col-12">
                                <div class="input-blocks">
                                    <label>Phone <span class="star-sign">*</span></label>
                                    <input type="text" name="phone" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12 col-12">
                                <div class="input-blocks">
                                    <label>Address <span class="star-sign">*</span></label>
                                    <input type="text" name="address" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer d-sm-flex justify-content-end">
                            <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-submit">
                                <span class="spinner-border spinner-border-sm d-none" role="status"
                                    aria-hidden="true"></span>
                                <span class="text">Submit</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- payment method modal --}}
    {{-- <div class="modal fade" id="payment" tabindex="-1" aria-labelledby="create" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Save Payment Method Info</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>

                <form id="orderPaymentMethod" action=" " method="POST">
                    @csrf
                    <div class="modal-body">

                        <div class="form-group">
                            <div class="row ">
                                <div class="col-md-4 mb-3">
                                    <select class="select" id="payment_method_id" name="payment_method_id">


                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <input type="number" class="form-control amount" name="amount"
                                        placeholder="Amount" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <input type="text" class="form-control transaction_id" name="transaction_id"
                                        placeholder="Transaction Id">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <textarea name="note" class="form-control" rows="1" placeholder="Comment"></textarea>
                                </div>
                            </div>
                        </div>


                    </div>

                    <div class="modal-footer d-sm-flex justify-content-end">
                        <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-submit me-2">
                            <span class="spinner-border spinner-border-sm d-none" role="status"
                                aria-hidden="true"></span>
                            <span class="text">Submit</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div> --}}
    <div class="modal fade" id="payment" tabindex="-1" aria-labelledby="create" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Save Payment Method Info</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>

                <form id="orderPaymentMethod" method="POST">
                    @csrf
                    <input type="hidden" name="order_number" id="order_number"
                        value="{{ $order->order_number ?? '' }}">
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="payment_method_id">Payment Method</label>
                                    <select class="form-control select" id="payment_method_id" name="payment_method_id"
                                        required></select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="amount">Amount</label>
                                    <input type="number" class="form-control amount" name="amount"
                                        placeholder="Amount" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="transaction_id">Transaction ID</label>
                                    <input type="text" class="form-control transaction_id" name="transaction_id"
                                        placeholder="Transaction ID">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="note">Note</label>
                                    <textarea name="note" class="form-control" rows="2" placeholder="Enter any additional comments"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer d-sm-flex justify-content-end">
                        <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-submit me-2">
                            <span class="spinner-border spinner-border-sm d-none" role="status"
                                aria-hidden="true"></span>
                            <span class="text">Submit</span>
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>


    {{-- current price updaete modal --}}
    <div class="modal fade modal-default pos-modal" id="products" aria-labelledby="products">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header p-4 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <h5 class="me-4">Product Details</h5>
                        {{--                    <span class="badge bg-info d-inline-block mb-0"></span> --}}
                    </div>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body p-4">
                    <div id="modal-product-details">





                    </div>
                    <div class="form-group mt-3">
                        <label for="modal-product-price">Current Price</label>
                        <input type="text" class="form-control" id="modal-product-price">
                    </div>
                </div>
                <div class="modal-footer d-sm-flex justify-content-end">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="save-modal-product-details">Save</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <style>
        .col-padding {
            padding-right: 1rem;
        }

        .star-sign {
            color: red;
            font-weight: bold;
        }

        .page-wrapper .content {
            background-color: #f7fff7;
        }

        .pos-wrapper {
            background-color: #f7fff7 !important;
        }

        .product-wrap {
            height: 38vh !important;
            overflow: auto
        }

        aside {
            padding: 15px !important
        }

        .page-wrapper .content {
            padding: 10px !important;
            padding-bottom: 0;
        }

        .count-badge {
            margin-top: -28px;
            margin-left: -11px;
            /* Adjust as needed */
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background-color: red;
            color: white;
            font-size: 12px;
            z-index: 1;
            /* Ensure badge is on top */
        }

        .pos-card-bg {
            background-color: #e4f6ff;
        }

        .pos-categories {
            background-color: #ffffff !important;
            padding: 24px;
            border-collapse: collapse;
        }

        .pos-cart-img img {
            height: 80px;
        }


        aside.product-order-list .block-section {
            margin: 0 !important;
            padding: 0 0 10px;

        }

        aside.product-order-list .order-total {
            padding: 5px !important;
        }

        aside.product-order-list .product-added .head-text {
            margin-bottom: 10px !important;
        }

        .pos-design .btn-row {
            padding: 0 !important;
            margin-bottom: 10px;
        }

        .table th,
        .table td {

            padding: 5px !important;
            line-height: 1.2 !important;
        }

        .total-border {
            border-top: 1px solid #001067;

        }

        .total-border td {
            font-weight: 600;
            font-size: 18px !important;
            color: crimson !important;
        }

        .selling-info {
            background-color: #e4f6ff;
            padding: 10px;
        }

        .input-group-text {
            font-size: 0.8rem !important;
        }

        .product-wrap .product-list .info span {
            font-size: 12px !important;
        }

        .product-wrap .product-list .info h6 {
            font-size: 13px;
        }

        .product-wrap .product-list .info p {
            font-size: 13px;
        }

        .card .card-body {
            padding: .7rem .7rem !important;
        }

        .shippingInfo .form-group label {
            font-size: 13px;
        }

        .shippingInfo .form-control {
            border-color: #e4e4e4 !important;
            height: 2.5rem;
        }


        .package-stock-badge {
            position: absolute;
            top: 5px;
            right: 6px;
            padding: 3px 3px;
        }

        .package-card {
            padding: 10px;
            margin: 10px;
            text-align: center;
            position: relative;
            background-color: white;
            border-radius: 10px;
            border: 1px solid #ffe7e7;
        }

        .package-image {
            height: 120px;
        }

        .package-detail {
            padding: 5px;
        }

        .package-title {
            font-size: 12px;
            font-weight: bold;
        }

        .package-description {
            font-size: 10px;
        }

        .package-price {
            color: #4CAF50;
            font-size: 15px;
            font-weight: 600;
        }
    </style>
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            // $('#coupon_code').change(function() {
            //     var couponCode = $(this).val();

            //     var cartData = JSON.parse(sessionStorage.getItem('cart'));

            //     $.ajax({
            //         url: '{{ route('apply.coupon') }}',
            //         method: 'POST',
            //         data: {
            //             coupon_code: couponCode,
            //             cartData: JSON.stringify(cartData)
            //         },
            //         success: function(response) {
            //             if (response.error) {
            //                 // Handle the case where there is an error with the coupon
            //                 console.error('Error applying coupon:', response.error);
            //             } else {
            //                 // Update the cart UI with the new cart data and display the discount amount and total
            //                 var updatedCart = response.cart;
            //                 var discountAmount = response.discount_amount;

            //                 // Store the updated cart data in sessionStorage
            //                 sessionStorage.setItem('cart', JSON.stringify(updatedCart));

            //                 // Call the renderCart() function to update the cart UI
            //                 renderCart();

            //                 $('.coupon_discount').text(discountAmount);
            //                 $('#coupon_discount').val(discountAmount);


            //                 // Call calculateCartTotal() function to recalculate the total
            //                 calculateCartTotal();
            //             }
            //         },
            //         error: function(xhr, status, error) {
            //             // Handle errors here
            //             console.error(xhr.responseText);

            //             // Show toastr alert for the error
            //             toastr.error(xhr.responseJSON.error);
            //         }

            //     });
            // });
            // $('#coupon_discount').on('change', function() {
            //     // Get the selected discount value
            //     var discountValue = $(this).val();

            //     if (discountValue === '00') {
            //         $('#coupon_code').val('');
            //     }
            // });

        });

        function removeBTN(index) {
            $.ajax({
                type: 'POST',
                url: '{{ route('pos.method.remove') }}',
                data: {
                    index: index
                },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.success
                    });

                    $('.summary').html(response.html);
                },
                error: function(xhr, status, error) {
                    // Handle the error response from the server
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON.errors ? Object.values(xhr.responseJSON.errors)[0][0] :
                            'An error occurred while removing the payment. Please try again.'
                    });
                }
            });
        }

        // $(document).ready(function() {
        //     // payment saving form using modal
        //     $('#orderPaymentMethod').on('submit', function(event) {
        //         event.preventDefault();
        //         var form_data = $(this).serialize();
        //         $.ajax({
        //             type: 'POST',
        //             url: '{{ route('pos.method.store') }}',
        //             data: form_data,
        //             success: function(response) {

        //                 Swal.fire({
        //                     icon: 'success',
        //                     title: 'Success',
        //                     text: response.success
        //                 });
        //                 $('.summary').html(response.html);

        //                 $('#payment').modal('toggle');

        //             },
        //             error: function(xhr, status, error) {
        //                 Swal.fire({
        //                     icon: 'error',
        //                     title: 'Error',
        //                     text: xhr.responseJSON.errors ? Object.values(xhr
        //                             .responseJSON.errors)[0][0] :
        //                         'An error occurred while submitting the form. Please try again.'
        //                 });
        //             }
        //         });
        //     });

        // });




        // Function to submit cart data
        function submitCartData() {
            // Get cart data from sessionStorage
            const cartData = sessionStorage.getItem('cart');
            let itemTotal = 0;
            cart.forEach(item => {
                itemTotal += item.subtotal;
            });

            // Get other data from the page


            const shipping = $('.shipping').text();
            const packing = $('.packingCharge').text();
            const discount = $('.discount').text();
            const couponCode = $('#coupon_code').val();
            const couponDiscount = $('.coupon_discount').text();
            const subtotal = $('.subtotalAmount').text();
            const total = $('.totalAmount').text();

            // Set values of hidden input fields
            $('#cart_data').val(cartData);
            $('#itemTotal').val(itemTotal);
            $('#shipping').val(shipping);
            $('#packingCharge').val(packing);
            $('#discount').val(discount);
            $('#couponCode').val(couponCode);
            $('#couponDiscount').val(couponDiscount);
            $('#subtotal').val(subtotal);
            $('#total').val(total);
        }
        // Call submitCartData function before submitting the form
        $('#placeOrder').on('click', function(e) {
            e.preventDefault(); // Prevent default form submission
            submitCartData(); // Set cart data and other data to hidden input fields
            $(this).closest('form').submit(); // Submit the form
        });

        function duplicateItem(itemId) {
            // Find the item to be duplicated in the cart array
            const itemToDuplicate = cart.find(item => item.id == itemId);

            if (itemToDuplicate) {
                // Clone the item object
                const duplicatedItem = {
                    ...itemToDuplicate
                };

                // For example, you can update the ID or reset the quantity
                duplicatedItem.id = itemToDuplicate.id; // Keep the same ID as the original item

                // duplicatedItem.unique_id = generateUniqueId(itemId);
                duplicatedItem.quantity = 1;
                duplicatedItem.isDuplicate = 1;


                // Check if the original item has a non-zero price
                if (itemToDuplicate.current_price > 0) {
                    // Set the price of the duplicated item to zero
                    duplicatedItem.current_price = 0;
                    duplicatedItem.subtotal = 0; // Reset subtotal
                }

                // Add the duplicated item to the cart
                cart.push(duplicatedItem);

                // Save the updated cart to sessionStorage
                sessionStorage.setItem('cart', JSON.stringify(cart));

                // Render the updated cart UI
                renderCart();

                // Show a success message
                toastr.success('Item duplicated successfully.');
            } else {
                toastr.error('Item not found in cart.');
            }
        }


        function updateSingleItemSubtotal(productId, newSubtotal) {
            // Find the product in the cart array based on the product ID
            var productIndex = cart.findIndex(item => item.id == productId);
            if (productIndex !== -1) {
                // Update the current price and subtotal of the product in the cart
                cart[productIndex].current_price = 0;
                cart[productIndex].subtotal = newSubtotal;
                // Re-render the cart UI
                renderCart();
            }
        }

        // product add to cart
        $(document).ready(function() {
            // Check if sessionStorage is available
            if (typeof(Storage) === "undefined") {
                console.error("SessionStorage is not supported in this browser.");
                // return;
            }

            $(document).on('click', '.product', function() {
                // Extract product data from the data-product attribute

                const productData = $(this).data('product');

                // Check if the product already exists in the cart
                const existingProductIndex = cart.findIndex(item => item.id === productData.id);

                if (existingProductIndex !== -1) {
                    // If the product already exists, update its quantity
                    cart[existingProductIndex].quantity += 1;
                    // Recalculate the subtotal based on the updated quantity
                    cart[existingProductIndex].subtotal = cart[existingProductIndex].current_price * cart[
                        existingProductIndex].quantity;
                } else {
                    // If the product doesn't exist, add it to the cart
                    // Calculate the subtotal for the product
                    const subtotal = productData.current_price * productData.quantity;
                    productData.subtotal = subtotal; // Store the subtotal in productData

                    cart.push(productData);
                }

                // Store the updated cart in sessionStorage
                sessionStorage.setItem('cart', JSON.stringify(cart));
                // Render the updated cart
                renderCart();
                // Show a success message using toastr
                toastr.success('Item added to cart successfully.');
            });

        });




        // for quntity increase  +/- button click
        function updateQuantity(itemId, change, index) {
            const itemIndex = index; //cart.findIndex(item => item.id == itemId);

            if (itemIndex !== -1) {
                // Update the quantity
                cart[itemIndex].quantity += change;

                // If the quantity becomes 0 or negative, or 1, remove the item from the cart
                if (cart[itemIndex].quantity <= 0) {
                    toastr.error('Quantity cannot be less than 1.');
                    // removeItem(itemId);
                    cart[itemIndex].quantity = 1; // Reset quantity to 1
                } else {
                    // Calculate and update subtotal
                    const subtotal = cart[itemIndex].current_price * cart[itemIndex].quantity;
                    cart[itemIndex].subtotal = subtotal;

                    // Update cart in sessionStorage
                    sessionStorage.setItem('cart', JSON.stringify(cart));

                    // Render the updated cart
                    renderCart();
                }
            }
        }


        // on input value
        function UpdateCart(itemId, index) {

            const quantityInput = $(`#quantity${index}`);
            const quantityValue = quantityInput.val();
            const quantity = parseInt(quantityValue);
            // Check if the quantity is a valid number
            if (!isNaN(quantity)) {
                // Find the item in the cart
                // const itemIndex = cart.findIndex(item => item.id == itemId);

                const itemIndex = index;

                // Update the quantity if the item is found
                if (itemIndex !== -1) {
                    cart[itemIndex].quantity = quantity;

                    // Calculate and update the subtotal for the item
                    cart[itemIndex].subtotal = cart[itemIndex].current_price * quantity;

                    // Update cart in sessionStorage
                    sessionStorage.setItem('cart', JSON.stringify(cart));

                    // Render the updated cart
                    renderCart();
                }
            }
        }

        // remove  single product
        function removeSingleItem(itemId, index) {
            // const itemIndex = cart.findIndex(item => item.id == itemId);
            const itemIndex = index;
            if (itemIndex !== -1) {
                // Remove the item from the cart
                cart.splice(itemIndex, 1)

                sessionStorage.setItem('cart', JSON.stringify(cart));
                // Render the updated cart
                renderCart();
                toastr.success('Item removed from cart.');
            } else {
                toastr.error('Item not found in cart.');
            }
        }
        // claer all products



        // function calculateCartTotal() {
        //     let itemTotal = 0;
        //     cart.forEach(item => {
        //         itemTotal += item.subtotal;
        //     });

        //     const shipping = parseFloat($('#cart_shipping').val() || 0); // Default to 0 if shipping is empty
        //     let discount = parseFloat($('#cart_discount').val());
        //     let couponDiscount = parseFloat($('#coupon_discount').val() || 0);
        //     const packing = parseFloat($('#packing_charge').val() || 0);


        //     // Check if discount is entered as a percentage
        //     if ($('#cart_discount').val().endsWith('%')) {
        //         const discountPercentage = parseFloat(discount) / 100;
        //         discount = itemTotal * discountPercentage;
        //     }

        //     // Ensure discount is a valid number
        //     discount = isNaN(discount) ? 0 : discount;

        //     const subtotal = itemTotal + shipping + packing;

        //     const total = subtotal - (discount + couponDiscount); // Corrected calculation

        //     // Format shipping, discount, subtotal, and total
        //     const formattedShipping = formatPrice(shipping);
        //     const formattedPacking = formatPrice(packing);
        //     const formattedDiscount = formatPrice(discount);
        //     const formattedCouponDiscount = formatPrice(couponDiscount);
        //     const formattedSubtotal = formatPrice(subtotal);
        //     const formattedTotal = formatPrice(total);

        //     // Update shipping, discount, subtotal, and total in table rows
        //     $('.shipping').text(formattedShipping);
        //     $('.packingCharge').text(formattedPacking);
        //     $('.discount').text(formattedDiscount);
        //     $('.coupon_discount').text(formattedCouponDiscount);
        //     $('.subtotalAmount').text(formattedSubtotal);
        //     $('.totalAmount').text(formattedTotal);
        // }

        // // on input discount or shipping calculation amount
        // $('#cart_shipping, #cart_discount, #packing_charge').on('input', function() {

        //     calculateCartTotal();
        // });




        // Event listener for showing product details when the modal is opened
        $('#products').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const itemIndex = button.data('item-index');
            showProductDetailsInModal(itemIndex);
        });

        // Function to show  single product details in the modal
        // function showProductDetailsInModal(itemIndex) {


        //     const modalBody = $('#modal-product-details');

        //     const modalPriceInput = $('#modal-product-price');

        //     const storedCart = JSON.parse(sessionStorage.getItem('cart'));

        //     if (storedCart && itemIndex >= 0 && itemIndex < storedCart.length) {
        //         const item = storedCart[itemIndex];

        //         modalBody.html(`
    //     <div class="product-list d-flex align-items-center justify-content-between">
    //         <div class="d-flex align-items-center flex-fill">
    //             <a href="javascript:void(0);" class="img-bg me-2" >
    //                 <img src="${item.thumb_image || ''}" alt="img" style="height: 50px;">
    //             </a>
    //             <div class="info d-flex align-items-center justify-content-between flex-fill">
    //                 <div>
    //                    <span style="background-color: var(--bintel-color);border-radius: 3px;font-weight: 600;color: #fff; font-size: 14px;padding: 0 10px; min-width: 64px;" >${item.product_code || ''}</span>
    //                     <h6 style="font-size: 12px;font-weight: 600;margin-bottom: 0;">${truncateText(item.english_name || '', 45)}</h6>
    //                 </div>
    //                 <div>
    //                  <p style=" font-size: 13px; font-weight: 600; color: #5b6670;">
    //                     ${formatPrice(item.current_price || 0)}${item.mrp_price > item.current_price ? ` (<del>${formatPrice(item.mrp_price)}</del>)` : ''}</p>
    //                 </div>
    //             </div>
    //         </div>
    //     </div>
    // `);

        //         modalPriceInput.val(item.current_price || '');
        //         modalPriceInput.attr('data-item-index', itemIndex);
        //     } else {
        //         console.error('Item is undefined or itemIndex is out of bounds. Cannot display details.');
        //     }
        // }
        // Save button event listener in the modal
        $('#save-modal-product-details').on('click', function() {
            const newPrice = $('#modal-product-price').val();
            const itemIndex = $('#modal-product-price').attr('data-item-index');
            updateCurrentPrice(itemIndex, newPrice);
            renderCart();
            $('#products').modal('hide');
        });
        // Function to update product price in the cart array
        function updateCurrentPrice(itemIndex, newPrice) {
            if (itemIndex !== undefined && itemIndex >= 0 && itemIndex < cart.length) {
                const parsedPrice = parseFloat(newPrice);
                if (!isNaN(parsedPrice)) {
                    cart[itemIndex].current_price = parsedPrice;
                    cart[itemIndex].subtotal = cart[itemIndex].current_price * cart[itemIndex].quantity;
                    sessionStorage.setItem('cart', JSON.stringify(cart));
                } else {
                    console.error('Invalid price value. Cannot update product price.');
                }
            } else {
                console.error('Item is undefined or itemIndex is out of bounds. Cannot update product price.');
            }
        }



        // function truncateText(text, maxLength) {
        //     if (text.length > maxLength) {
        //         return text.substring(0, maxLength) + '...';
        //     } else {
        //         return text;
        //     }
        // }

        function truncateText(text, maxLength) {
            if (typeof text === 'string' && text.length > maxLength) {
                return text.substring(0, maxLength) + '...';
            } else {
                return text || ''; // Return an empty string if text is undefined
            }
        }


        // function formatPrice(price) {
        //     return ' ৳ ' + price.toFixed(2); // Assuming price is a numerical value
        // }
        function formatPrice(price) {
            if (typeof price === 'number' && !isNaN(price)) {
                return '৳ ' + price.toFixed(2);
            } else {
                return '৳ 0.00'; // Return a default price if the input is invalid
            }
        }

        window.onload = function() {
            renderCart();
            //sessionStorage.removeItem('cart');
        };



        // $(document).ready(function() {

        //     $.ajaxSetup({
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         }
        //     });

        //     try {
        //         var selectSimple = $('#publisher');

        //         selectSimple.select2({
        //             placeholder: ' Search Publications',
        //             minimumInputLength: 1,
        //             allowClear: true,
        //             ajax: {
        //                 url: '{{ route('publishers.search') }}', // route in admin.php
        //                 dataType: 'json',
        //                 type: "GET",
        //                 quietMillis: 50,
        //                 data: function(term) {
        //                     return {
        //                         q: term.term
        //                     }
        //                 },
        //                 processResults: function(data) {
        //                     return {
        //                         results: data
        //                     };
        //                 }

        //             }



        //         });


        //     } catch (err) {
        //         console.log(err);
        //     }

        //     try {
        //         var category = $('#category_id');

        //         category.select2({
        //             placeholder: 'Search Category...',
        //             minimumInputLength: 1,
        //             allowClear: true,
        //             ajax: {
        //                 url: '{{ route('categories.search') }}', // route in admin.php
        //                 dataType: 'json',
        //                 type: "GET",
        //                 quietMillis: 50,
        //                 data: function(term) {
        //                     return {
        //                         q: term.term
        //                     }
        //                 },
        //                 processResults: function(data) {
        //                     return {
        //                         results: data
        //                     };
        //                 }

        //             }

        //         });


        //     } catch (err) {
        //         console.log(err);
        //     }

        // });



        //feach all package list--- and start here new function
        function fetchPackages() {
            var search = $('.package_search').val();


            $.ajax({
                url: '{{ route('subscription-orders.search.package') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    search: search
                },
                success: function(response) {
                    if (response.error === "no") {
                        $('#packageList').html(response.dataView);
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Warning',
                            text: response.message,
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred while loading packages.',
                        timer: 1500,
                        showConfirmButton: false
                    });
                    console.error(error);
                }
            });
        }
        // Fetch all packages initially when the page loads
        $(document).ready(function() {
            fetchPackages();
        });


        //add cart functonality bellow

        const cart = JSON.parse(sessionStorage.getItem('cart')) || [];

        // Add package to cart
        // $(document).on('click', '.package', function() {
        //     const packageData = $(this).data('package');

        //     // Check if the package is already in the cart
        //     const existingPackageIndex = cart.findIndex(item => item.id === packageData.id);

        //     if (existingPackageIndex !== -1) {
        //         // If the package already exists, increase its quantity
        //         cart[existingPackageIndex].quantity += 1;
        //         cart[existingPackageIndex].subtotal = cart[existingPackageIndex].quantity * cart[
        //             existingPackageIndex].current_price;
        //     } else {
        //         // If the package doesn't exist, calculate the subtotal and add it to the cart
        //         packageData.subtotal = packageData.current_price * packageData.quantity;
        //         cart.push(packageData);
        //     }

        //     // Save updated cart to sessionStorage
        //     sessionStorage.setItem('cart', JSON.stringify(cart));

        //     // Update the cart UI
        //     renderCart();

        //     // Display success message
        //     toastr.success('Package added to cart successfully.');
        // });

        // Add package to cart
        $(document).on('click', '.package', function() {
            const packageData = $(this).data('package');

            // Clear the cart before adding the new package
            cart.length = 0;

            // Calculate the subtotal for the package
            packageData.subtotal = packageData.current_price * packageData.quantity;

            cart.push(packageData);

            sessionStorage.setItem('cart', JSON.stringify(cart));

            renderCart();
            toastr.success('Package added to cart successfully.');
        });

        function renderCart() {
            const cartContainer = $('#cart');
            cartContainer.empty();

            let itemTotal = 0;

            if (cart.length === 0) {
                cartContainer.append('<p class="text-center text-danger">No packages added to the cart.</p>');
            } else {
                cart.forEach((item, index) => {
                    const subtotal = item.current_price * item.quantity;
                    itemTotal += subtotal;
                    // Use the default image if `thumb_image` is missing or empty
                    const thumbImage = item.thumb_image ||
                        '{{ asset('theme/admin/assets/img/package/default-package.png') }}';

                    cartContainer.append(`
    <div class="product-list d-flex align-items-center justify-content-between" id="package-${index}">
        <div class="d-flex align-items-center">
            <a href="javascript:void(0);" class="img-bg me-2">
                <img src="${thumbImage}" alt="${item.title}" class="img-fluid" style="height: 80px;">
            </a>
            <div class="info">
                <h6>${item.title} <span class="badge bg-info ms-2">${item.duration} Days</span></h6>
                <p>৳${item.current_price.toFixed(2)} × ${item.quantity} = ৳${subtotal.toFixed(2)}</p>
            </div>
        </div>
        <div class="qty-item d-flex align-items-center">
            <button class="btn btn-sm btn-danger me-2" onclick="removePackage(${index})"><i class="fas fa-trash-alt"></i></button>
        </div>
    </div>
`);
                });
            }

            // Update cart totals
            $('.itemTotal').text(itemTotal.toFixed(2));
            calculateCartTotal(); // Update the total calculations
        }

        // Remove a package from the cart
        function removePackage(index) {
            cart.splice(index, 1);
            sessionStorage.setItem('cart', JSON.stringify(cart));
            renderCart();
            toastr.success('Package removed from cart.');
        }

        // Clear all packages from the cart
        function clearCart() {
            sessionStorage.removeItem('cart');
            cart.length = 0;
            renderCart();
            toastr.success('Cart cleared successfully.');
        }

        // Initialize cart on page load
        $(document).ready(function() {
            renderCart();
        });


        function calculateCartTotal() {
            let itemTotal = 0;
            cart.forEach(item => {
                itemTotal += item.subtotal || 0;
            });

            const discount = parseFloat($('#cart_discount').val()) || 0;
            const couponDiscount = parseFloat($('#coupon_discount').val()) || 0;

            const subtotal = itemTotal;
            const total = subtotal - discount - couponDiscount;

            // Update UI
            $('.itemTotal').text(itemTotal.toFixed(2));
            $('.subtotalAmount').text(subtotal.toFixed(2));
            $('.discount').text(discount.toFixed(2));
            $('.coupon_discount').text(couponDiscount.toFixed(2));
            $('.totalAmount').text(total.toFixed(2));
        }

        // Event listener for input fields
        $('#cart_discount').on('input', calculateCartTotal);
        $('#coupon_code').on('change', applyCoupon);

        // Function to apply coupon
        function applyCoupon() {
            const couponCode = $('#coupon_code').val();
            if (!couponCode) {
                $('#coupon_discount').val(0);
                calculateCartTotal();
                return;
            }

            $.ajax({
                url: '{{ route('apply.coupon') }}',
                method: 'POST',
                data: {
                    coupon_code: couponCode,
                    cart: JSON.stringify(cart),
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.error) {
                        toastr.error(response.message);
                    } else {
                        $('#coupon_discount').val(response.discount);
                        calculateCartTotal();
                    }
                },
                error: function(xhr) {
                    toastr.error('Error applying coupon. Please try again.');
                }
            });
        }

        //store order place data function

        $(document).ready(function() {
            $('#orderForm').on('submit', function(e) {
                e.preventDefault();

                // Collect form data
                const formData = {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    user_id: $('#user_id').val(),
                    cart_data: JSON.stringify(sessionStorage.getItem('cart') ? JSON.parse(sessionStorage
                        .getItem('cart')) : []),
                    subtotal: $('#subtotal').val(),
                    discount: $('#discount').val(),
                    coupon_id: $('#coupon_id').val(),
                    coupon_discount: $('#couponDiscount').val(),
                    total: $('#total').val(),
                    pay_method: $('#pay_method').val(),
                    pay_amount: $('#pay_amount').val(),
                    name: $('#name').val(),
                    mobile_number: $('#mobile_number').val(),
                    subscription_start_date: $('#subscription_start_date').val(),
                    address: $('#address').val(),
                };

                $.ajax({
                    url: '{{ route('subscription-orders.store') }}',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.error === "no") {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.message,
                            }).then(() => {
                                sessionStorage.removeItem('cart');
                                window.location.href = response.redirect_url;
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
                        const errorResponse = xhr.responseJSON;
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: errorResponse?.message ||
                                'An error occurred while placing the order. Please try again.',
                        });

                        console.error(errorResponse?.errors || error);
                    },
                });
            });

        });

        //New user or customer add funtion
        $(document).ready(function() {
            $('#customerForm').submit(function(event) {
                event.preventDefault();

                var formData = $(this).serialize();
                var $form = $(this);

                var $button = $form.find('.btn-submit');
                var $spinner = $button.find('.spinner-border');
                var $text = $button.find('.text');

                // Show loading indicator and disable button
                $spinner.removeClass('d-none');
                $text.hide();
                $button.prop('disabled', true);

                $.ajax({
                    url: '{{ route('subscription-orders.user.store') }}',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        // Hide loading indicator and enable button
                        $spinner.addClass('d-none');
                        $text.show();
                        $button.prop('disabled', false);

                        // Show success notification
                        toastr.success(response.message);
                        $('#create').modal('hide');
                        $('#customerForm')[0].reset();

                        loadCustomers();
                    },
                    error: function(xhr, status, error) {
                        // Hide loading indicator and enable button
                        $spinner.addClass('d-none');
                        $text.show();
                        $button.prop('disabled', false);

                        // Show error notification
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            let errorMessages = Object.values(xhr.responseJSON.errors).map(
                                (errorArray) => errorArray.join('<br>')
                            );
                            toastr.error(errorMessages.join('<br>'));
                        } else {
                            toastr.error('An error occurred. Please try again.');
                        }
                    },
                });
            });
        });

        //user serach and show data below table 
        $(document).ready(function() {
            // Initialize select2 for customer dropdown
            $('#customer_id').select2({
                placeholder: 'Search Customer / Shop...',
                minimumInputLength: 1,
                allowClear: true,
                ajax: {
                    url: '{{ route('subscription-orders.user.search') }}',
                    dataType: 'json',
                    type: 'GET',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term,
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    id: item.id,
                                    text: item.name + ' (' + item.phone + ')',
                                };
                            }),
                        };
                    },
                },
            });

            // Fetch user info on selection

            $('#customer_id').on('change', function() {
                const userId = $(this).val();

                if (userId) {
                    // Populate the hidden input field with the user ID
                    $('#user_id').val(userId);

                    // Fetch additional user data and populate the shipping form
                    $.ajax({
                        url: '{{ route('subscription-orders.user.info') }}',
                        method: 'GET',
                        data: {
                            userId: userId
                        },
                        success: function(response) {
                            if (response.success) {
                                const user = response.data;
                                $('#name').val(user.name);
                                $('#mobile_number').val(user.phone);
                                $('#address').val(user.address);
                            }
                        },
                        error: function() {
                            alert('Failed to fetch user data.');
                        },
                    });
                }
            });

            // Populate Payment Methods Dropdown
            $.ajax({
                url: '{{ route('subscription-orders.payment-methods.list') }}',
                method: 'GET',
                success: function(response) {
                    if (response.error === "no") {
                        const paymentMethods = response.data;
                        const paymentDropdown = $('#payment_method_id');
                        paymentDropdown.empty(); // Clear existing options
                        paymentDropdown.append('<option value="">Select Payment Method</option>');
                        paymentMethods.forEach(method => {
                            paymentDropdown.append(
                                `<option value="${method.id}">${method.name}</option>`);
                        });
                    }
                },
                error: function(xhr) {
                    console.error('Failed to load payment methods:', xhr.responseText);
                }
            });






            //payment information store
            // $('#orderPaymentMethod').on('submit', function(e) {
            //     e.preventDefault();

            //     const formData = $(this).serialize();
            //     console.log(formData); // Debug to check if order_number is present

            //     $.ajax({
            //         url: '{{ route('subscription-orders.payment.store') }}',
            //         type: 'POST',
            //         data: formData,
            //         success: function(response) {
            //             if (response.error === 'no') {
            //                 Swal.fire({
            //                     icon: 'success',
            //                     title: 'Success',
            //                     text: response.message,
            //                 }).then(() => {
            //                     $('#payment').modal('hide'); // Hide the modal
            //                     location.reload(); // Reload the page to update the UI
            //                 });
            //             } else {
            //                 Swal.fire({
            //                     icon: 'error',
            //                     title: 'Error',
            //                     text: response.message,
            //                 });
            //             }
            //         },
            //         error: function(xhr) {
            //             const errorMessage = xhr.responseJSON?.message ||
            //                 'An error occurred while saving the payment.';
            //             Swal.fire({
            //                 icon: 'error',
            //                 title: 'Error',
            //                 text: errorMessage,
            //             });
            //         },
            //     });
            // });
            $('#orderPaymentMethod').on('submit', function(e) {
                e.preventDefault();

                // Collect form data
                const formData = {
                    _token: '{{ csrf_token() }}',
                    payment_method_id: $('#payment_method_id').val(),
                    amount: $('.amount').val(),
                    transaction_id: $('.transaction_id').val(),
                    note: $('textarea[name="note"]').val(),
                };

                $.ajax({
                    url: '{{ route('subscription-orders.payment.save') }}',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.error === 'no') {
                            Swal.fire('Success!', response.message, 'success');
                            $('#payment').modal('hide'); // Hide the modal
                            $('.summary').html(response.html); // Update the payment summary
                        } else {
                            Swal.fire('Error!', response.message, 'error');
                        }
                    },
                    error: function(xhr) {
                        Swal.fire('Error!', 'Failed to save payment details.', 'error');
                        console.error(xhr.responseJSON.errors);
                    },
                });
            });


            function removeBTN(index) {
                $.ajax({
                    url: '{{ route('subscription-orders.payment.remove') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        index: index,
                    },
                    success: function(response) {
                        if (response.error === 'no') {
                            Swal.fire('Removed!', response.message, 'success');
                            $('.summary').html(response
                                .payment_details_html); // Re-render payment summary
                        } else {
                            Swal.fire('Error!', response.message, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error!', 'Failed to remove payment.', 'error');
                    },
                });
            }




        });
    </script>
@endsection
