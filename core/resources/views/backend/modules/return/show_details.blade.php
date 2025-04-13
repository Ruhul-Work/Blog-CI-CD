@extends('backend.layouts.master')
@section('meta')
    <title>Details - {{ get_option('title') }}</title>
@endsection
@section('content')
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4>Return Management</h4>
                <h6>Full details of Return</h6>
            </div>
        </div>
        <ul class="table-top-head">
            <li>
                <div class="page-btn d-flex">
                    <a href="{{route('returns.index')}}" class="btn btn-secondary me-2"><i data-feather="arrow-left"
                                                                                          class="me-2"></i>Back to
                        Returns</a>

                    <a href="{{route('returns.edit',$singleReturn->id)}}" class="btn btn-info"><i data-feather="edit"
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
                        <div class="invoice-box" style="max-width: 1600px;width:100%;overflow: auto;padding: 0;font-size: 14px;line-height: 24px;color: #555;">
                            <div class="sales-details-items d-flex">
                                <div class="details-item">
                                    <h6>Return Info</h6>
                                    <p>Return Number<br>
                                        Payment Status<br>

                                        {{-- Courier --}}
                                    </p>
                                </div>
                                <div class="details-item">
                                    <h5><span>{{ $singleReturn->return_number }}</span> {{ $singleReturn->payment_status }}<br>
                                        {{-- <br><span>    {{ $singleReturn->courier->name ?? "Null" }}</span> </h5> --}}
                                </div>
                                <div class="details-item">

                                </div>
                            </div>

                        </div>

                        <h5 class="order-text">Return Summary</h5>
                        <div class="table-responsive no-pagination">

                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Return Number</th>
                                    <th>Publisher</th>
                                    <th>Qty</th>
                                    <th>Unit Cost(৳)</th>
                                    <th>Total Cost(৳)</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($singleReturn->returnItems as $item)
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
                                        <td>{{ $item->return_number }}</td>
                                        <td>{{ $item->publisher->name }}</td>
                                        <td>{{ $item->qty }}</td>
                                        <td>{{ $item->price }}</td>
                                        <td>{{ $item->total }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                        </div>

                        <div class="row">
                                <div class="col-lg-6 ms-auto">
                                    <div class="total-order w-100 max-widthauto m-auto mb-4">
                                        <ul>

                                            <li>
                                                <h4>Return Items</h4>
                                                <h5>{{formatPrice($singleReturn->subtotal)}}</h5>
                                            </li>

                                            <li>
                                                <h4>Shipping Charge</h4>
                                                <h5>{{formatPrice($singleReturn->shipping_charge)}}</h5>
                                            </li>

                                            @if($singleReturn->packing_charge)
                                                <li>
                                                    <h4>Wrapping charge</h4>
                                                    <h5>{{formatPrice($singleReturn->packing_charge)}}</h5>
                                                </li>
                                            @endif
                                            @if($singleReturn->tax)
                                                <li>
                                                    <h4>Order Tax</h4>
                                                    <h5>{{formatPrice($singleReturn->tax)}}</h5>
                                                </li>
                                            @endif
                                            <li>
                                                <h4>Discount</h4>
                                                <h5>-{{formatPrice($singleReturn->discount_amount)}}</h5>
                                            </li>
                                            @if($singleReturn->coupon_discount>0)
                                                <li>
                                                    <h4>Coupon({{$singleReturn->coupon->code}})</h4>
                                                    <h5>{{formatPrice($singleReturn->coupon_discount)}}</h5>
                                                </li>
                                            @endif

                                            @if($singleReturn->adjust_amount)
                                                <li>
                                                    <h4>Adjust Amount</h4>
                                                    <h5>{{formatPrice($singleReturn->adjust_amount)}}</h5>
                                                </li>
                                            @endif
                                            <li>
                                                <h4>Grand Total</h4>
                                                <h5>{{formatPrice($singleReturn->total)}}</h5>
                                            </li>
                                            <li>
                                                <h4>Paid</h4>
                                                <h5>{{formatPrice($singleReturn->transactions->sum('amount'))}}</h5>
                                            </li>
                                            <li>
                                                <h4>Due</h4>
                                                <h5>{{formatPrice($singleReturn->total- $singleReturn->transactions->sum('amount'))}}</h5>
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

@section('script')
<script>

window.onload = function() {
    clearCart();
};

function clearCart() {
    // Clear the 'cart' key from sessionStorage
    sessionStorage.removeItem('cart');
    // Show success message
    toastr.success('Cart cleared successfully.');
    // Optionally reload the page if needed
    // location.reload();
}

</script>
@endsection






