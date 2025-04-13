@extends('backend.layouts.master')

@section('meta')
    <title>Options - {{ get_option('title') }}</title>
@endsection

@section('content')
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4>Options</h4>
                <h6>Website Settings</h6>
            </div>
        </div>
        <div class="page-btn">
            <a href="javascript:void(0)" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#statusModal">
                <i data-feather="plus-circle"></i>Website Settings
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="settings-wrapper d-flex">

                @include('backend.modules.setting.option.sidebar')

                <div class="settings-page-wrap">
                    <form action="{{ route('settings.website.store') }}" method="POST" enctype="multipart/form-data"
                        id="createWebSetting">
                        @csrf
                        <div class="setting-title">
                            <h4>Company Settings</h4>
                        </div>

                        <div class="company-info">
                            <div class="card-title-head">
                                <h6><span><i data-feather="zap"></i></span>Company Information</h6>
                            </div>
                            <div class="row">
                                <div class="col-xl-4 col-lg-6 col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Company Name</label>
                                        <input type="text" class="form-control" name="company_name"
                                            value="{{ $company_name ?? '' }}">
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-6 col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Company Title</label>
                                        <input type="text" class="form-control" name="title"
                                            value="{{ $title ?? '' }}">
                                    </div>
                                </div>

                                <div class="col-xl-4 col-lg-6 col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Company Email Address</label>
                                        <input type="text" class="form-control" name="email"
                                            value="{{ $email ?? '' }}">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Phone Number</label>
                                        <input type="text" class="form-control" name="phone_number"
                                            value="{{ $phone_number ?? '' }}">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Website</label>
                                        <input type="text" class="form-control" name="website_url"
                                            value="{{ $website_url ?? '' }}">
                                    </div>
                                </div>


                                

                                <div class="col-xl-4 col-lg-6 col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Description</label>
                                        <textarea class="form-control" id="exampleTextarea" rows="5" name="description"
                                            placeholder="Enter your text here...">{{ $description ?? '' }}</textarea>

                                    </div>
                                </div>

                                <!--<div class="col-xl-4 col-lg-6 col-md-4">-->
                                <!--    <div class="mb-3">-->
                                <!--        <label class="form-label">Show stationary</label>-->

                                <!--        <select name="show_stationary" class="form-control" id="show_stationary">-->
                                <!--            <option value="1" {{ $show_stationary == '1' ? 'selected' : '' }}>Yes-->
                                <!--            </option>-->
                                <!--            <option value="0" {{ $show_stationary == '0' ? 'selected' : '' }}>No</option>-->
                                <!--        </select>-->
                                <!--    </div>-->
                                <!--</div>-->
                            </div>
                        </div>
                        <div class="company-info company-images">

                            <div class="card-title-head">
                                <h6><span><i data-feather="image"></i></span>Company Images</h6>
                            </div>

                            <ul class="logo-company">
                                <li class="d-flex align-items-center">
                                    <div class="col-lg-3 col-sm-6 col-12">
                                        <div class="mb-3 add-product">
                                            <label class="form-label">Icon</label>
                                            <div class="form-group">
                                                <div class="row" id="icon">
                                                </div>
                                            </div>
                                        </div>
                                        <img src="{{ asset($icon) }}" alt="" style="height: 100px;width:100%">
                                    </div>
                                    <div class="col-lg-3 col-sm-6 col-12">
                                        <div class="mb-3 add-product">
                                            <label class="form-label">Logo</label>
                                            <div class="form-group">
                                                <div class="row" id="logo">
                                                </div>
                                            </div>
                                        </div>

                                        <img src="{{ asset($logo) }}" alt="" style="height: 100px;width:100%">
                                    </div>

                                    <div class="col-lg-3 col-sm-6 col-12">
                                        <div class="mb-3 add-product">
                                            <label class="form-label">Meta Image</label>
                                            <div class="form-group">
                                                <div class="row" id="meta_image">
                                                </div>
                                            </div>
                                        </div>

                                        <img src="{{ asset($meta_image) }}" alt=""
                                            style="height: 100px;width:100%">
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="company-address">
                            <div class="card-title-head">
                                <h6><span><i data-feather="map-pin"></i></span>Address</h6>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">Address</label>
                                        <input type="text" class="form-control" name="address"
                                            value="{{ $address ?? '' }}">
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-4 col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label">Country</label>
                                        <input type="text" class="form-control" name="country"
                                            value="{{ $country ?? '' }}">
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-4 col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label">State</label>
                                        <input type="text" class="form-control" name="state"
                                            value="{{ $state ?? '' }}">
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-4 col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label">City</label>
                                        <input type="text" class="form-control" name="city"
                                            value="{{ $city ?? '' }}">
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-4 col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label">Postal Code</label>
                                        <input type="text" class="form-control" name="postal_code"
                                            value="{{ $postal_code ?? '' }}">
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
        $('#createWebSetting').submit(function(e) {
            e.preventDefault(); // Prevent the form from submitting normally
            // Get form data
            var formData = new FormData(this);
            // Make AJAX request
            $.ajax({
                url: '{{ route('settings.website.store') }}',
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
                            window.location.href = '{{ route('settings.website') }}';
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
    </script>
@endsection
