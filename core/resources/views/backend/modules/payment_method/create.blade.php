@extends('backend.layouts.master')

@section('meta')
    <title>Create Payment Method - {{ get_option('title') }}</title>
@endsection

@section('content')
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4>New Payment Method</h4>
                <h6>Create New Payment Method</h6>
            </div>
        </div>
        <ul class="table-top-head">
            <li>
                <div class="page-btn">
                    <a href="{{ route('paymentmethod.index') }}" class="btn btn-secondary"><i data-feather="arrow-left"
                            class="me-2"></i>Back to Payment Methods</a>
                </div>
            </li>
            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i
                        data-feather="chevron-up" class="feather-chevron-up"></i></a>
            </li>
        </ul>
    </div>

    <form action="" id="createPayments" method="post" enctype="multipart/form-data">
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
                                        <div class="mb-3 add-product required">
                                            <label class="form-label">Name</label>
                                            <input type="text" class="form-control" id="name" name="name"
                                                placeholder="Enter text here">
                                        </div>
                                    </div>


                                    <div class="col-lg-4 col-sm-4 col-12 required">
                                        <label class="form-label">Type</label>
                                        <select class="form-select select p_type" name="type" width="100%">
                                            <option value="MFS">MFS</option>
                                            <option value="Bank">Bank</option>
                                            <option value="Cash">Cash</option>
                                        </select>
                                    </div>

                                    <div class="col-lg-4 col-sm-4 col-12 required">
                                        <label class="form-label">Status</label>
                                        <select class="form-select select" name="status" width="100%">
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                    </div>

                                </div>


                                <div class="row">
                                    <div class="col-lg-4 col-sm-6 col-12 account_name">
                                        <div class="mb-3 add-product">
                                            <label class="form-label">Account name</label>
                                            <input type="text" class="form-control" id="name" name="account_name"
                                                placeholder="Enter text here">
                                        </div>
                                    </div>


                                    <div class="col-lg-4 col-sm-6 col-12 account_number">
                                        <div class="mb-3 add-product required">
                                            <label class="form-label">Account Number</label>
                                            <input type="text" class="form-control" id="name" name="account_number"
                                                placeholder="Enter text here">
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-sm-6 col-12 bank_name">
                                        <div class="mb-3 add-product required">
                                            <label class="form-label">Bank Name</label>
                                            <input type="text" class="form-control" id="name" name="bank_name"
                                                placeholder="Enter text here">
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-sm-6 col-12 bank_branch">
                                        <div class="mb-3 add-product required">
                                            <label class="form-label">Bank Branch</label>
                                            <input type="text" class="form-control" id="name" name="bank_branch"
                                                placeholder="Enter text here">
                                        </div>
                                    </div>




                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="accordion-card-one accordion" id="accordionExample2">
                    <div class="accordion-item">
                        <div class="accordion-header" id="headingTwo">
                            <div class="accordion-button" data-bs-toggle="collapse" data-bs-target="#collapseTwo"
                                aria-controls="collapseTwo">
                                <div class="text-editor add-list">
                                    <div class="addproduct-icon list icon">
                                        <h5><i data-feather="life-buoy" class="add-info"></i><span>Others</span></h5>
                                        <a href="javascript:void(0);"><i data-feather="chevron-down"
                                                class="chevron-down-add"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="collapseTwo" class="accordion-collapse collapse show" aria-labelledby="headingTwo"
                            data-bs-parent="#accordionExample2">
                            <div class="accordion-body">

                                <div class="tab-content" id="pills-tabContent">
                                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                                        aria-labelledby="pills-home-tab">
                                        <div class="row">

                                            <div class="col-lg-3 col-sm-6 col-12">

                                                <label class="form-label">Icon</label>
                                                <div class="form-group">
                                                    <div class="row" id="icon">

                                                    </div>
                                                </div>


                                            </div>
                                            <div class="col-lg-9 col-sm-6 col-12">
                                                <div class="add-product list">
                                                    <label class="form-label">Payment Process</label>
                                                    <textarea rows="8" cols="5" class="form-control h-100" name="payment_process"
                                                        placeholder="Enter text here"></textarea>
                                                </div>
                                            </div>
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
                <button type="submit" class="btn btn-submit">Save</button>
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

        .bank_name {
            display: none;

        }

        .bank_branch {
            display: none;

        }
    </style>
    <script src="{{ asset('theme/admin/assets/plugins/fileupload/spartan-multi-image-picker-min.js') }}"
        type="text/javascript"></script>
    <script>
        $(document).ready(function() {
            $('#createPayments').submit(function(e) {
                e.preventDefault();

                var formData = new FormData(this);


                $.ajax({
                    url: '{{ route('paymentmethod.store') }}',
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
                                window.location.href =
                                    '{{ route('paymentmethod.index') }}';
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


                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                html: errorMessageHTML,
                            });
                        } catch (e) {
                            console.error('Error parsing JSON response:', e);

                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'An error occurred while processing your request. Please try again later.',
                            });
                        }
                    }

                });
            });




            $("#icon").spartanMultiImagePicker({
                fieldName: 'icon',
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

        $('.p_type').change(function() {

            var selectedOption = $(this).val();
            if (selectedOption == "Bank") {
                $('.bank_name').show();
                $('.bank_branch').show();
            } else if (selectedOption == "Cash") {
                $('.account_name').hide();
                $('.account_number').hide();

            } else {
                $('.bank_name').hide();
                $('.bank_branch').hide();
            }
        });
    </script>
@endsection
