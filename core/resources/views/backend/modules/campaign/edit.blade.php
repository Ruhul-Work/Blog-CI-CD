@extends('backend.layouts.master')

@section('meta')
    <title>Update Campaigns - {{ get_option('title') }}</title>
@endsection

@section('content')
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4>Update Campaigns</h4>
                <h6>Update Campaigns</h6>
            </div>
        </div>
        <ul class="table-top-head">
            <li>
                <div class="page-btn">
                    <a href="{{ route('campaigns.index') }}" class="btn btn-secondary"><i data-feather="arrow-left"
                            class="me-2"></i>Back To Campaigns</a>
                </div>
            </li>
            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i
                        data-feather="chevron-up" class="feather-chevron-up"></i></a>
            </li>
        </ul>
    </div>

    <form action="" id="updateCampaign" method="post" enctype="multipart/form-data">
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
                                            <input type="hidden" value="{{ $campaigns->id }}" name="id">
                                            <input type="text" class="form-control" id="name" name="name"
                                                placeholder="Enter text here" value="{{ $campaigns->name ?? '' }}">
                                        </div>
                                    </div>

                                    {{--
                                    <div class="col-lg-4 col-sm-6 col-12">
                                        <div class="mb-3 add-product required">
                                            <label class="form-label">Products</label>
                                            <select id="product-select" class="form-select selectSimple" name="product_id[]"
                                                width="100%" multiple>

                                            </select>
                                        </div>
                                    </div> --}}




                                    <div class="col-lg-4 col-sm-6 col-12">
                                        <div class="mb-3 add-product required">
                                            <label class="form-label">Discont</label>
                                            <input type="text" class="form-control" name="discount"
                                                placeholder="Enter discount here" value="{{ $campaigns->discount ?? '' }}">
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-sm-6 col-12">
                                        <div class="mb-3 add-product required">
                                            <label class="form-label">Discount Type</label>
                                            <select class="form-select select" name="discount_type" width="100%">
                                                @foreach ($discountenums as $enum)
                                                    <option value="{{ $enum->value }}"
                                                        {{ $campaigns->discount_type->value === $enum->value ? 'selected' : '' }}>
                                                        {{ $enum->value }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>


                                    <div class="col-lg-6 col-sm-6 col-12">
                                        <div class="mb-3 add-product required">
                                            <label class="form-label">Date Range</label>
                                            <input type="text" class="form-control dateranges" name="daterange"
                                                value="{{ $campaigns->start_date->format('Y-m-d') }} - {{ $campaigns->end_date->format('Y-m-d') }}">
                                        </div>
                                    </div>



                                    <div class="col-lg-6 col-sm-4 col-12 required">
                                        <label class="form-label">Status</label>
                                        <select class="form-select select" name="status" width="100%">
                                            <option disabled>-- Select --</option>
                                            <option value="1" {{ $campaigns->status == 1 ? 'selected' : '' }}>Active
                                            </option>
                                            <option value="0" {{ $campaigns->status == 0 ? 'selected' : '' }}>Inactive
                                            </option>
                                        </select>
                                    </div>

                                </div>

                                <div class="row">

                                    <div class="col-lg-3 col-sm-6 col-12">
                                        <div class="mb-3 add-product">
                                            <label class="form-label">Icon</label>
                                            <div class="form-group">
                                                <div class="row" id="icon">
                                                </div>
                                            </div>
                                        </div>

                                        @if (isset($campaigns->icon))
                                            <label class="form-label">Icon</label> <br>
                                            <img style="height: 100px;width:300px" src="{{ image($campaigns->icon) }}"
                                                alt="author">
                                        @endif
                                    </div>
                                    <div class="col-lg-3 col-sm-6 col-12">
                                        <div class="mb-3 add-product">
                                            <label class="form-label">Cover Image</label>
                                            <div class="form-group">
                                                <div class="row" id="cover_image">
                                                </div>
                                            </div>
                                        </div>

                                        @if (isset($campaigns->cover_image))
                                            <label class="form-label">Old Cover Image</label> <br>
                                            <img style="height: 100px;width:300px" src="{{ image($campaigns->cover_image) }}"
                                                alt="author">
                                        @endif
                                    </div>

                                    <div class="col-lg-9 col-sm-9 col-12">
                                        <div class="mb-3 add-product">
                                            <label class="form-label">Description</label>
                                            <textarea class="form-control h-100" rows="8" name="notes" placeholder="Enter text here">{{ $campaigns->description ?? '' }}</textarea>
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
                                        <h5><i data-feather="life-buoy" class="add-info"></i><span>Meta Section</span>
                                        </h5>
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
                                                <div class=" add-product">
                                                    <label class="form-label">Meta Title</label>
                                                    <input type="text" class="form-control" name="meta_title"
                                                        placeholder="Enter text here"
                                                        value="{{ $campaigns->meta_title ?? '' }}">
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-sm-6 col-12">
                                                <label class="form-label">Meta Image</label>
                                                <div class="form-group">
                                                    <div class="row" id="meta_image">
                                                    </div>
                                                </div>
                                                @if (isset($campaigns->meta_image))
                                                    <label class="form-label">Old Image</label> <br>
                                                    <img style="height: 100px;width:300px"
                                                        src="{{ image($campaigns->meta_image) }}" alt="author">
                                                @endif
                                            </div>
                                            <div class="col-lg-6 col-sm-6 col-12">
                                                <div class="add-product list">
                                                    <label class="form-label">Meta Description</label>
                                                    <textarea rows="8" cols="5" class="form-control h-100" name="meta_description"
                                                        placeholder="Enter text here">{{ $campaigns->meta_description ?? '' }}</textarea>

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
                <button type="submit" class="btn btn-submit">Update Campaign</button>
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
            $('#updateCampaign').submit(function(e) {
                e.preventDefault(); // Prevent the form from submitting normally
                // Get form data
                var formData = new FormData(this);
                // Make AJAX request
                $.ajax({
                    url: '{{ route('campaigns.update') }}',
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
                                    '{{ route('campaigns.index') }}';
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

            $("#cover_image").spartanMultiImagePicker({
                fieldName: 'cover_image',
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

            $("#meta_image").spartanMultiImagePicker({
                fieldName: 'meta_image',
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

        $(document).ready(function() {
            var selectSimple = $('#product-select');

            // Pre-selected products data passed from the controller
            var selectedProducts = @json($selectedProducts);
            selectSimple.select2({
                placeholder: 'Search for a product',
                minimumInputLength: 1,
                width: '100%',
                ajax: {
                    url: '{{ route('campaigns.get-products') }}',
                    dataType: 'json',
                    type: "GET",
                    delay: 50,
                    data: function(params) {
                        return {
                            q: params.term
                        };
                    },
                    processResults: function(data) {
                        var results = data.map(function(item) {
                            return {
                                id: item.id,
                                text: item.text
                            };
                        });

                        return {
                            results: results
                        };
                    }
                },
                templateResult: function(data) {
                    if (data.loading) return data.text;
                    return $('<div><strong>' + data.text + '</strong></div>');
                }
            });

            // Initialize the select2 with pre-selected values
            var preSelectedData = [];
            selectedProducts.forEach(function(product) {
                preSelectedData.push({
                    id: product.id,
                    text: product.text
                });
            });

            // Set the pre-selected values
            selectSimple.val(preSelectedData.map(p => p.id)).trigger('change');

            // Add the pre-selected options to Select2
            preSelectedData.forEach(function(product) {
                var option = new Option(product.text, product.id, true, true);
                selectSimple.append(option).trigger('change');
            });
        });
    </script>
@endsection
