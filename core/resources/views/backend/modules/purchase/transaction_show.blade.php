@extends('backend.layouts.master')
@section('meta')
    <title>Purchase Transaction Details - {{ get_option('title') }}</title>
@endsection
@section('content')
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4>Purchase Management</h4>
                <h6>Full details of Purchase</h6>
            </div>
        </div>
        <ul class="table-top-head">
            <li>
                <div class="page-btn d-flex">
                    <a href="{{route('orders.index')}}" class="btn btn-secondary me-2"><i data-feather="arrow-left"
                                                                                          class="me-2"></i>Back to
                        orders</a>

                    <a href="{{route('orders.edit',$purchase->id)}}" class="btn btn-info"><i data-feather="edit"
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

                        <div class="invoice-box table-height" style="max-width: 1600px;width:100%;overflow: auto;padding: 0;font-size: 14px;line-height: 24px;color: #555;">
                            <div class="sales-details-items d-flex">

                                <div class="details-item">
                                    <h6>Purchase Info</h6>
                                    <p>Purchase Number<br>
                                        Payment Status<br>

                                    </p>
                                </div>
                                <div class="details-item">
                                    <h5><span>{{$purchase->purchase_number}}</span> {{$purchase->payment_status }}<br></h5>
                                </div>
                            </div>
                            <h5 class="order-text">Purchase Transaction Summary</h5>



                         <form action="#" method="POST" id="orderPaymentUpdate" enctype="multipart/form-data">
                                            @csrf

                                                <div class="col-md-12 d-flex">
                                                    <div class="col-md-6 mt-3">
{{--                                                        <input type="hidden" name="id" value="{{$purchase->id}}">--}}
                                                        <div class="form-group">
                                                            <label for="customerName" class="fw-bold">Net Payable:{{formatPrice($purchase->total)}}</label>
                                                            <span class="ml-2" id="net_payable"></span>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="amount" class="fw-bold">Previous Payment:{{formatPrice($purchase->transactions->sum('amount'))}}</label>
                                                            <span class="ml-2" id="previous_payment"></span>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="pay" class="fw-bold">Due:{{formatPrice($purchase->total- $purchase->transactions->sum('amount'))}}</label>
                                                            <span class="ml-2" id="have_to_pay_now"></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="table-responsive">
                                                            <table class="table  ">
                                                                <thead>
                                                                <tr>
                                                                    <th>Method</th>
                                                                    <th>Date</th>
                                                                    <th>Amount</th>
                                                                    <th>Received By</th>
                                                                    <th class="no-sort">Action</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>


                                                                @if($purchase->transactions && !$purchase->transactions->isEmpty())
                                                                    @foreach($purchase->transactions as $transaction)
                                                                        <tr>
                                                                            <td>{{$transaction->paymentMethod->name}}</td>
                                                                            <td>{{ \Carbon\Carbon::parse($transaction->created_at)->format('Y/m/d h:i A') }}</td>

                                                                            <td>{{formatPrice($transaction->amount) }}</td>
                                                                            <td>{{$transaction->user->name }}</td>
                                                                            <td class="action-table-data">
                                                                                <div class="edit-delete-action">
                                                                                    <a class="confirm-text p-2 delete-btn" href="{{ route('purchases.transactions.destroy', encrypt($transaction->id)) }}">
                                                                                        <i data-feather="trash-2" class="feather-trash-2"></i>
                                                                                    </a>
                                                                                </div>
                                                                            </td>

                                                                        </tr>
                                                                    @endforeach
                                                                @else
                                                                    <tr>
                                                                        <td colspan="3">No  previous payment available</td>
                                                                    </tr>
                                                                @endif

                                                                </tbody>
                                                                <tfoot>
                                                                <tr>
                                                                    <th colspan="2" class="fw-bold">Total Paid Amount(TK)</th>
                                                                    <th class="fw-bold">{{formatPrice($purchase->transactions->sum('amount'))}}</th>
                                                                    <th colspan="2" > </th>
                                                                </tr>
                                                                </tfoot>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>

                                                <aside class="product-order-list">
                                                    <div class="block-section payment-method">
                                                        <h6>Choose Payment Method</h6>
                                                        <div class="row d-flex align-items-center justify-content-center methods">
                                                            @foreach($paymentMethods as $paymentMethod)
                                                                <div class="col-md-4 col-lg-3 paymentMethodSelect mb-3"  data-bs-toggle="modal" data-bs-target="#payment" data-payment-id="{{$paymentMethod->id}}">
                                                                    <div class="default-cover">
                                                                        <a href="javascript:void(0);">
                                                                            <img src="{{image('theme/admin/assets/img/icons/cash-pay.svg')}}" alt="Payment Method">
                                                                            <span>{{$paymentMethod->name}}</span>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>



                                                        <div class="summary">
                                                            @include('backend.modules.pos.payment_summary')
                                                        </div>
                                                    </div>

                                                </aside>

                                                <div class="text-center col-12 mb-2">
                                                    <button type="submit" class="btn btn-success fw-bolder py-3 shadow-lg">Save Payment</button>
                                                </div>

                                        </form>


                        </div>
                </div>
            </div>

        </div>
    </div>

    {{-- payment method modal--}}
    <div class="modal fade" id="payment" tabindex="-1" aria-labelledby="create" aria-hidden="true">
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
                            <div class="row">
                                <div class="col-md-4">
                                    <select class="select" id="payment_method_id" name="payment_method_id">

                                        @foreach($paymentMethods as $payment)
                                            <option value="{{ $payment->id }}"> {{ $payment->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <input type="number" class="form-control amount" name="amount" placeholder="Amount" required>
                                </div>
                                <div class="col-md-4">
                                    <input type="number" class="form-control transaction_id" name="transaction_id" placeholder="Transaction Id">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <textarea name="note" class="form-control" rows="1" placeholder="Comment"></textarea>
                                </div>
                            </div>
                        </div>


                    </div>

                    <div class="modal-footer d-sm-flex justify-content-end">
                        <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-submit me-2">
                            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                            <span class="text">Submit</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
@section('script')
        <script>

            function removeBTN(index){

                $.ajax({

                    type: 'POST',
                    url: '{{ route('pos.method.remove') }}',
                    data: { index: index},
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
                            text: xhr.responseJSON.errors ? Object.values(xhr.responseJSON.errors)[0][0] : 'An error occurred while removing the payment. Please try again.'
                        });
                    }
                });
            }
            $(document).ready(function() {

                // on click open payment method modal with selected method
                $(".paymentMethodSelect").click(function() {
                    var paymentId = $(this).data("payment-id");
                    $("#payment_method_id").val(paymentId).trigger("change");
                });

                // payment saving form using modal
                $('#orderPaymentMethod').on('submit', function(event) {
                    event.preventDefault();
                    var form_data = $(this).serialize();
                    $.ajax({
                        type: 'POST',
                        url: '{{ route('pos.method.store') }}',
                        data: form_data,
                        success: function(response) {

                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.success
                            });
                            $('.summary').html(response.html);

                            $('#payment').modal('toggle');

                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: xhr.responseJSON.errors ? Object.values(xhr.responseJSON.errors)[0][0] : 'An error occurred while submitting the form. Please try again.'
                            });
                        }
                    });
                });



                $('#orderPaymentUpdate').on('submit', function(event) {
                    event.preventDefault(); // Prevent default form submission behavior
                    var form_data = $(this).serialize(); // Create an object with the form data
                    $.ajax({
                        type: 'POST',
                        url: '{{ route('purchases.transactions.store',$purchase->id)}}',
                        data: form_data,
                        success: function(response) {
                            // Handle the response from the server
                            if (response.message) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: response.message
                                }).then(function() {
                                    window.location = "{{route('purchases.index')}}";
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            // Handle the error response from the server
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: xhr.responseJSON.errors ? Object.values(xhr.responseJSON.errors)[0][0] : 'An error occurred while submitting the form. Please try again.'
                            });
                        }
                    });
                });

                $(document).on("click", '.delete-btn', function(e) {
                    e.preventDefault();
                    var href = $(this).attr('href');
                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'You will not be able to recover this !',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'No, keep it'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Send AJAX delete request
                            $.ajax({
                                url: href, // Use the 'href' directly as the URL
                                type: 'post',
                                data: {
                                    "_token": "{{ csrf_token() }}",
                                },
                                success: function(response) {
                                    // Handle the response from the server
                                    if (response.message) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Success',
                                            text: response.message
                                        }).then(function() {
                                            window.location.reload();
                                        });
                                    }

                                },
                                error: function(xhr, status, error) {
                                    // Handle the error response from the server
                                    if (xhr.responseJSON && xhr.responseJSON.error) {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error',
                                            text: xhr.responseJSON.error,
                                        });
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error',
                                            text: xhr.responseJSON.errors ? Object.values(xhr.responseJSON.errors)[0][0] : 'An error occurred while submitting the form. Please try again.'
                                        });
                                    }
                                }

                            });
                        }
                    });
                });

            });
        </script>

@endsection





