@extends('backend.layouts.master')

@section('meta')
    <title>Options - {{ get_option('title') }}</title>
@endsection

@section('content')
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4>Options</h4>
                <h6>Manage Options</h6>
            </div>
        </div>
        <div class="page-btn">
            <a href="javascript:void(0)" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#statusModal">
                <i data-feather="plus-circle"></i>Options
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="settings-wrapper d-flex">

                @include('backend.modules.setting.option.sidebar')

                <div class="settings-page-wrap">
                    <form action="{{ route('settings.order.store') }}" method="POST" enctype="multipart/form-data"
                        id="createOrderSetting">
                        @csrf
                        <div class="setting-title">
                            <h4>Order Settings</h4>
                        </div>
                        <div class="company-info">
                            <div class="card-title-head">
                                <h6><span><i data-feather="zap"></i></span>Order Information</h6>
                            </div>
                            <div class="row">
                           

                                <div class="col-xl-4 col-lg-6 col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label"> Cash on Shipping Charge</label>
                                        <input type="text" class="form-control" name="shipping_charge" value="{{ $shipping_charge ?? '' }}">
                                    </div>
                                </div>

                    

                                <div class="col-xl-4 col-lg-6 col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label"> Advance Pay Shipping Charge</label>
                                        <input type="text" class="form-control" name="advance_pay" value="{{ $advance_pay ?? '' }}">
                                    </div>
                                </div>

                
                                <div class="col-xl-4 col-lg-6 col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Delivery Time</label>
                                        <input type="text" class="form-control" name="delivery_time" value="{{ $delivery_time ?? '' }}">
                                    </div>
                                </div>

                                <div class="col-xl-4 col-lg-6 col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Payments Text</label>
                                        <textarea class="form-control" id="exampleTextarea" rows="5" name="payment_text" placeholder="Enter your text here...">{{ $payment_text ?? '' }}</textarea>



                                    </div>
                                </div>

                                <div class="col-xl-4 col-lg-6 col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Order Success Email</label>
                                        <textarea class="form-control" id="exampleTextarea" rows="5" name="success_email" placeholder="Enter your text here...">{{ $success_email ?? '' }}</textarea>

                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="modal-footer-btn">
                            <button type="button" class="btn btn-cancel me-2" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-submit">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $('#createOrderSetting').submit(function(e) {
            e.preventDefault(); // Prevent the form from submitting normally
            // Get form data
            var formData = new FormData(this);

            // Make AJAX request
            $.ajax({
                url: '{{ route('settings.order.store') }}',
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
                            window.location.href = '{{ route('settings.order') }}';
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
    </script>
@endsection
