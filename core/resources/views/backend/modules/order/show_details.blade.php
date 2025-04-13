@extends('backend.layouts.master')
@section('meta')
    <title>Order Details - {{ get_option('title') }}</title>
@endsection
@section('content')
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4>Order Management</h4>
                <h6>Full details of order</h6>
            </div>
        </div>
        <ul class="table-top-head">
            <li>
                <div class="page-btn d-flex">
                    <a href="{{route('orders.index')}}" class="btn btn-secondary me-2"><i data-feather="arrow-left"
                                                                          class="me-2"></i>Back to orders</a>
                    <a href="{{route('orders.edit',$order->id)}}" class="btn btn-info"><i data-feather="edit"
                                                                                       class="me-2 text-white"></i>Edit</a>
                </div>
            </li>
            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i
                        data-feather="chevron-up" class="feather-chevron-up"></i></a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <form action="#">
                        <div class="invoice-box table-height" style="max-width: 1600px; width:100%;overflow: auto;padding: 0;font-size: 14px; line-height: 24px; color: #555;">
                            <div class="sales-details-items d-flex">
                                <div class="details-item">
                                    <h6>Shipping Info.</h6>
                                    <p>{{ $order->shipping->name ?? "Null" }}<br>
                                        {{ $order->shipping->phone ?? "Null" }} <br>
                                        @if($order->shipping && $order->shipping->email)
                                            {{ $order->shipping->email }}<br>
                                        @endif
                                        {{ $order->shipping->address ?? "Null" }}<br>

                                    </p>
                                </div>
                                <div class="details-item">
                                    <h6>Invoice Info</h6>
                                    <p>Order Number<br>
                                        Payment Status<br>
                                        Status <br>
                                        Courier
                                    </p>
                                </div>
                                <div class="details-item">
                                    <h5 style="text-transform: uppercase;">
                                        <span>{{$order->order_number}}</span> {{ $order->payment_status }}<br>
                                        {{ $order->status->name ?? 'No Status' }}<br>
                                        <span>{{ $order->courier->name ?? "Null" }}</span>
                                    </h5>
                                </div>
                                
                            </div>
                            <h5 class="order-text">Order Summary</h5>
                            <div class="table-responsive no-pagination">

                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Order Number</th>
                                        <th>Publisher</th>
                                        <th>Qty</th>
                                        <th>Unit Cost(৳)</th>
                                        <th>Total Cost(৳)</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($order->orderItems as $item)
                                        <tr>
                                            <td>
                                                <div class="productimgname">
                                                    {{ $loop->iteration }}.&nbsp;&nbsp;
                                                    <a href="javascript:void(0);" class="product-img stock-img">
                                                        <img src="{{ image($item->product->thumb_image) }}" alt="product">
                                                    </a>
                                                    <a href="javascript:void(0);">{{ $item->product->bangla_name }}</a>
                                                </div>
                                            </td>
                                            <td>{{ $item->order_number }}</td>
                                            <td>{{ $item->publisher->name??'Unknown' }}</td>
                                            <td>{{ $item->qty }}</td>
                                            <td>{{ $item->price }}</td>
                                            <td>{{ $item->total }}</td>
                                        </tr>

                                        @if($item->product->isBundle == 1)
                                            <!-- Display the bundle products -->
                                            <tr>
                                                <td colspan="6"> <!-- Span all columns -->
                                                    <table class="nested-table"> <!-- Nested table for bundle products -->
                                                        @foreach($item->product->bundleProducts as $bundleProduct)
                                                            <tr>
                                                                <td>{{ $loop->parent->iteration }}.{{ $loop->iteration }}.</td>
                                                                <td>{{ $bundleProduct->name }} <span style="font-weight: bold">x{{ $bundleProduct->quantity * $item->qty }} Copy</span></td>
{{--                                                                <td>{{ $item->order_number }}</td>--}}
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach


                                    </tbody>
                                </table>

                            </div>
                        </div>

                            <div class="row">
                                <div class="col-lg-6 ms-auto">
                                    <div class="total-order w-100 max-widthauto m-auto mb-4">
                                        <ul>

                                            <li>
                                                <h4>Order Items</h4>
                                                <h5>{{formatPrice($order->subtotal)}}</h5>
                                            </li>

                                            <li>
                                                <h4>Shipping Charge</h4>
                                                <h5>{{formatPrice($order->shipping_charge)}}</h5>
                                            </li>

                                            @if($order->packing_charge)
                                                <li>
                                                    <h4>Wrapping charge</h4>
                                                    <h5>{{formatPrice($order->packing_charge)}}</h5>
                                                </li>
                                            @endif
                                            @if($order->tax)
                                            <li>
                                                <h4>Order Tax</h4>
                                                <h5>{{formatPrice($order->tax)}}</h5>
                                            </li>
                                            @endif
                                            <li>
                                                <h4>Discount</h4>
                                                <h5>-{{formatPrice($order->discount_amount)}}</h5>
                                            </li>
                                            @if($order->coupon_discount>0)
                                                <li>
                                                    <h4>Coupon({{$order->coupon->code}})</h4>
                                                    <h5>{{formatPrice($order->coupon_discount)}}</h5>
                                                </li>
                                            @endif

                                            @if($order->adjust_amount)
                                                <li>
                                                    <h4>Adjust Amount</h4>
                                                    <h5>{{formatPrice($order->adjust_amount)}}</h5>
                                                </li>
                                            @endif
                                            <li>
                                                <h4>Grand Total</h4>
                                                <h5>{{formatPrice($order->total)}}</h5>
                                            </li>
                                            <li>
                                                <h4>Paid</h4>
                                                <h5>{{formatPrice($order->transactions->sum('amount'))}}</h5>
                                            </li>
                                            <li>
                                                <h4>Due</h4>
                                                <h5>{{formatPrice($order->total- $order->transactions->sum('amount'))}}</h5>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                    </form>
                </div>
            </div>

        </div>
    </div>

@endsection






