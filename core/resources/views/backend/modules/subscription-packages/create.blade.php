@extends('backend.layouts.master')

@section('meta')
    <title>Create New Subscription Package - {{ get_option('title') }}</title>
@endsection

@section('content')
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4>Subscription Package Management</h4>
                <h6>Create New Subscription Package</h6>
            </div>
        </div>
        <ul class="table-top-head">
            <li>
                <div class="page-btn">
                    <a href="{{ route('subscription-packages.index') }}" class="btn btn-secondary">
                        <i data-feather="arrow-left" class="me-2"></i>Back to Subscription Packages
                    </a>
                </div>
            </li>
        </ul>
    </div>

    <form action="" method="POST" id="subscriptionForm">
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
                                    <div class="col-lg-3 col-sm-6 col-12">
                                        <div class="mb-3 add-product">
                                            <label class="form-label">Title<span class="star-sign">*</span></label>
                                            <input type="text" class="form-control" name="title"
                                                value="{{ old('title') }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-6 col-12">
                                        <div class="mb-3 add-product">
                                            <label class="form-label">Name<span class="star-sign">*</span></label>
                                            <input type="text" class="form-control" name="name"
                                                value="{{ old('name') }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-6 col-12">
                                        <label class="form-label">Duration<span class="star-sign">*</span></label>
                                        <div class="mb-3 add-product input-group">
                                            <input type="number" step="1" class="form-control" name="duration"
                                                value="{{ old('duration') }}" placeholder="Enter Duration Days">
                                            <span class="input-group-text">Days</span>
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-sm-6 col-12">
                                        <div class="mb-3 add-product">
                                            <label class="form-label">Status<span class="star-sign">*</span></label>
                                            <select class="select" name="status">
                                                <option value="1"
                                                    {{ old('status', isset($data) ? $data->status : 1) == 1 ? 'selected' : '' }}>
                                                    Active</option>
                                                <option value="0"
                                                    {{ old('status', isset($data) ? $data->status : 1) == 0 ? 'selected' : '' }}>
                                                    Inactive</option>
                                            </select>
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-lg-3 col-sm-6 col-12">
                                        <label class="form-label">MRP Price<span class="star-sign">*</span></label>
                                        <div class="mb-3 add-product input-group">
                                            <span class="input-group-text">Tk</span>
                                            <input type="number" step="1.0" class="form-control" id="mrp_price"
                                                name="mrp_price" value="{{ old('mrp_price') }}">
                                            <span class="input-group-text">.00</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-6 col-12">
                                        <div class="mb-3 add-product">
                                            <label class="form-label">Discount Type<span class="star-sign">*</span></label>
                                            <select class="select" id="discount_type" name="discount_type">
                                                <option value="">Select Discount Type</option>
                                                <option value="percent"
                                                    {{ old('discount_type') == 'percent' ? 'selected' : '' }}>% (percent)
                                                </option>
                                                <option value="amount"
                                                    {{ old('discount_type') == 'amount' ? 'selected' : '' }}>Amount</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-6 col-12">
                                        <div class="mb-3 add-product">
                                            <label class="form-label">Discount Amount<span
                                                    class="star-sign">*</span></label>
                                            <input type="number" step="0.01" class="form-control" id="discount_amount"
                                                name="discount_amount" value="{{ old('discount_amount') }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-6 col-12">
                                        <div class="mb-3 add-product">
                                            <label class="form-label">Current Price<span
                                                    class="star-sign">*</span></label>
                                            <input type="number" step="0.01" class="form-control" id="current_price"
                                                name="current_price" value="{{ old('current_price') }}" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6 col-sm-6 col-12">
                                        <label class="form-label">Subscription Features</label>
                                        <div id="feature-fields">
                                            <div class="input-group mb-3">
                                                <input type="text" name="features[]" class="form-control"
                                                    placeholder="Enter Feature Name">
                                                <select name="features_icon[]" class="form-select">
                                                    <option value="1" selected>✔</option>
                                                    <option value="0">✖</option>
                                                </select>
                                                <button type="button" class="btn btn-primary" id="add-more-features">
                                                    <i class="ion-plus-round"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-lg-12 col-sm-6 col-12">
                                        <div class="mb-3 add-product">
                                            <label class="form-label">Description</label>
                                            <textarea class="form-control" name="description" rows="5">{{ old('description') }}</textarea>
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
                <button type="submit" class="btn btn-submit">Save All</button>
            </div>
        </div>
    </form>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            //calculate price value function
            function calculateCurrentPrice() {
                const mrp = parseFloat($('#mrp_price').val()) || 0;
                const discountType = $('#discount_type').val();
                const discountAmount = parseFloat($('#discount_amount').val()) || 0;

                let currentPrice = mrp;
                if (discountType === 'percent') {
                    currentPrice = mrp - (mrp * discountAmount / 100);
                } else if (discountType === 'amount') {
                    currentPrice = mrp - discountAmount;
                }

                $('#current_price').val(currentPrice.toFixed(2));
            }

            $('#mrp_price, #discount_type, #discount_amount').on('input change', function() {
                calculateCurrentPrice();
            });


            // Perform AJAX request function
            $('#subscriptionForm').submit(function(e) {
                e.preventDefault();

                var formData = new FormData(this);


                $.ajax({
                    url: '{{ route('subscription-packages.store') }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {

                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message,
                        }).then(() => {
                            window.location.href =
                                '{{ route('subscription-packages.index') }}';
                        });
                    },
                    error: function(xhr) {
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            let errors = xhr.responseJSON.errors;
                            let errorMessage = '<ul>';
                            for (let key in errors) {
                                if (errors.hasOwnProperty(key)) {
                                    errorMessage += `<li>${errors[key]}</li>`;
                                }
                            }
                            errorMessage += '</ul>';
                            Swal.fire({
                                icon: 'error',
                                title: 'Validation Errors',
                                html: errorMessage,
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'An unexpected error occurred.',
                            });
                        }
                    },
                });
            });


            // add feture field fiunction

            document.getElementById('add-more-features').addEventListener('click', function() {
                const newField = `
                    <div class="input-group mb-3">
                        <input type="text" name="features[]" class="form-control" placeholder="Enter Feature Name">
                        <select name="features_icon[]" class="form-select">
                            <option value="1" selected>✔</option>
                            <option value="0">✖</option>
                        </select>
                        <button type="button" class="btn btn-danger remove-feature">
                            <i class="ion-close-round"></i>
                        </button>
                    </div>                                
                `;
                document.getElementById('feature-fields').insertAdjacentHTML('beforeend', newField);
            });

            // Event delegation for dynamically added elements
            document.getElementById('feature-fields').addEventListener('click', function(e) {
                if (e.target && e.target.closest('.remove-feature')) {
                    e.target.closest('.input-group').remove();
                }
            });



        });
    </script>
@endsection
