@extends('backend.layouts.master')

@section('meta')
    <title>Update New Couriers - {{ get_option('title') }}</title>
@endsection

@section('content')
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4>Update Couriers</h4>
                <h6>Update new Couriers</h6>
            </div>
        </div>

        <ul class="table-top-head">
            <li>
                <div class="page-btn">
                    <a href="{{ route('couriers.index') }}" class="btn btn-secondary"><i data-feather="arrow-left"
                            class="me-2"></i>Back to Couriers</a>
                </div>
            </li>

            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i
                        data-feather="chevron-up" class="feather-chevron-up"></i></a>
            </li>

        </ul>

    </div>

    <form action="" id="editSliders" method="post" enctype="multipart/form-data">
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
                                    <div class="col-lg-6 col-sm-6 col-12 required">
                                        <div class="mb-3 add-product">
                                            <label class="form-label">Name</label>

                                            <input type="hidden" class="form-control" id="id" name="id"
                                                value="{{ $couriers->id ?? '' }}">

                                            <input type="text" class="form-control" id="name" name="name"
                                                value="{{ $couriers->name ?? '' }}">

                                        </div>

                                    </div>


                                    {{-- <div class="col-lg-3 col-sm-6 col-12">
                                        <div class="mb-3 add-product required">
                                            <label class="form-label">Inside Dhaka</label>
                                            <input type="text" class="form-control" id="name" name="in_dhaka" value="{{ $couriers->in_dhaka ?? '' }}">

                                        </div>

                                    </div>
                                    <div class="col-lg-3 col-sm-6 col-12 required">
                                        <div class="mb-3 add-product">
                                            <label class="form-label">OutSide Dhaka</label>
                                            <input type="text" class="form-control" id="name" name="outside" value="{{ $couriers->outside ?? '' }}">

                                        </div>

                                    </div> --}}

                                    <div class="col-lg-6 col-sm-4 col-12 required">
                                        <label class="form-label">Status</label>
                                        <select class="form-select select" name="status">
                                            <option disabled>-- Select --</option>
                                            <option value="1" {{ $couriers->status == 1 ? 'selected' : '' }}>Active
                                            </option>
                                            <option value="0" {{ $couriers->status == 0 ? 'selected' : '' }}>Inactive
                                            </option>

                                        </select>

                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-lg-3 col-sm-6 col-12">

                                        <div class="mb-3 add-product">
                                            <label class="form-label">Logo</label>
                                            <div class="form-group">
                                                <div class="row" id="logo">

                                                </div>
                                            </div>
                                        </div>

                                        @if (isset($couriers->logo))
                                            <div>
                                                <label class="form-label">Old Logo<span class="star-sign"></span></label>
                                                <br>
                                                <img style="height: 100px;width:300px" src="{{ image($couriers->logo) }}"
                                                    alt="">
                                            </div>
                                        @endif
                                    </div>




                                    <div class="col-lg-9 col-sm-6 col-12">
                                        <div class="mb-3 add-product">
                                            <label class="form-label">Description</label>
                                            <textarea class="form-control h-100" rows="8" name="description">{{ $couriers->description ?? '' }}</textarea>
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
                <button type="submit" class="btn btn-submit">Update Courier</button>
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

            function generateSlug(name) {

                var pattern = /[a-zA-Z0-9\u0980-\u09FF]+/g;

                return name.toLowerCase().match(pattern).join('_');
            }
            // Event listener for name field
            $('#name').on('input', function() {
                var name = $(this).val();
                var slug = name ? generateSlug(name) : null; // Generate slug only if name is not empty
                $('#slug').val(slug);
            });

            $('#editSliders').submit(function(e) {
                e.preventDefault(); // Prevent the form from submitting normally

                // Get form data
                var formData = new FormData(this);

                // Make AJAX request
                $.ajax({
                    url: '{{ route('couriers.update') }}',
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
                                    '{{ route('couriers.index') }}';
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

            $("#logo").spartanMultiImagePicker({
                fieldName: 'logo',
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

        $(document).ready(function() {
            $('.status').select2();
        });

    </script>
@endsection
