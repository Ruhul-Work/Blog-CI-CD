@extends('backend.layouts.master')

@section('meta')
    <title>Edit Subcategory - {{ get_option('title') }}</title>
@endsection

@section('content')
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4>Subcategory Management</h4>
                <h6>Edit Subcategory</h6>
            </div>
        </div>
        <ul class="table-top-head">
            <li>
                <div class="page-btn">
                    <a href="{{route('subcategories.index')}}" class="btn btn-secondary"><i data-feather="arrow-left"
                                                                                            class="me-2"></i>Back to
                        Subcategories</a>
                </div>
            </li>
            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i
                        data-feather="chevron-up" class="feather-chevron-up"></i></a>
            </li>
        </ul>
    </div>

    <form action="" id="updateSubcategory" method="post">
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
                                    <div class="col-lg-6 col-sm-6 col-12">
                                        <div class="mb-3 add-product">
                                            <label class="form-label">Name <span
                                                    class="star-sign">*</span></label>
                                            <input type="text" class="form-control" id="name" name="name"
                                                   value="{{$subcategory->name}}">

                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-sm-4 col-12">
                                        <label class="form-label">Status</label>
                                        <select class="form-select" name="status">
                                            <option value="1" {{ $subcategory->status == 1 ? 'selected' : '' }}>Active
                                            </option>
                                            <option value="0" {{ $subcategory->status == 0 ? 'selected' : '' }}>
                                                Inactive
                                            </option>
                                        </select>
                                    </div>


                                </div>
                                <div class="row">
                                    <div class="col-lg-3 col-sm-6 col-12">
                                        <div class="mb-3 add-product">
                                            <label class="form-label">Icon Image<span
                                                    class="star-sign">*</span></label>

                                            <div class="form-group">
                                                <div class="row" id="icon">

                                                    @if ($subcategory->icon != null)
                                                        <div class="col-md-6">
                                                            <div class="img-upload-preview">
                                                                <img src="{{ image($subcategory->icon) }}" alt="icon"
                                                                     style="height: 180px;"
                                                                     class="img-responsive">
                                                                <button type="button"
                                                                        class="btn btn-danger close-btn remove-files"><i
                                                                        class="fa fa-times"></i></button>
                                                            </div>
                                                        </div>
                                                    @endif

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-6 col-12">
                                        <div class="mb-3 add-product">
                                            <label class="form-label">Cover Image</label>

                                            <div class="form-group">
                                                <div class="row" id="cover_image">
                                                    @if ($subcategory->cover_image != null)
                                                        <div class="col-md-6">
                                                            <div class="img-upload-preview">
                                                                <img src="{{ image($subcategory->cover_image) }}"
                                                                     alt="icon" style="height: 180px;"
                                                                     class="img-responsive">

                                                                <button type="button"
                                                                        class="btn btn-danger close-btn remove-files"><i
                                                                        class="fa fa-times"></i></button>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-sm-6 col-12">
                                        <div class="mb-3 add-product">
                                            <label class="form-label"> Description</label>

                                            <textarea class="form-control h-100" rows="8"
                                                      name="description">{{$subcategory->description}}</textarea>
                                            {{--                                            <p class="mt-1">Maximum 60 Characters</p>--}}


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
                                        <h5><i data-feather="life-buoy"
                                               class="add-info"></i><span>Meta Section</span></h5>
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
                                                           value="{{$subcategory->meta_title}}">
                                                </div>
                                            </div>

                                            <div class="col-lg-3 col-sm-6 col-12">
                                                <label class="form-label"> Meta Image</label>
                                                <div class="form-group">
                                                    <div class="row" id="meta_image">
                                                        @if ($subcategory->meta_image != null)
                                                            <div class="col-md-6">
                                                                <div class="img-upload-preview">
                                                                    <img src="{{ image($subcategory->meta_image) }}"
                                                                         alt="icon" style="height: 180px;"
                                                                         class="img-responsive">
                                                                    <button type="button"
                                                                            class="btn btn-danger close-btn remove-files">
                                                                        <i
                                                                            class="fa fa-times"></i></button>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>


                                            </div>
                                            <div class="col-lg-6 col-sm-6 col-12">
                                                <div class="add-product list">
                                                    <label class="form-label"> Meta Description</label>
                                                    <textarea rows="8" cols="5" class="form-control h-100"
                                                              name="meta_description"
                                                              placeholder="Enter text here">{{$subcategory->meta_description}}</textarea>

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
                <button type="submit" class="btn btn-submit">Update All</button>
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
    <script src="{{asset('theme/admin/assets/plugins/fileupload/spartan-multi-image-picker-min.js')}}"
            type="text/javascript"></script>
    <script>

        $(document).ready(function () {


            $('.remove-files').on('click', function () {
                $(this).closest(".col-md-6").remove();
            });


            function generateSlug(name) {

                var pattern = /[a-zA-Z0-9\u0980-\u09FF]+/g;

                return name.toLowerCase().match(pattern).join('_');
            }

            // Event listener for name field
            $('#name').on('input', function () {
                var name = $(this).val();
                var slug = name ? generateSlug(name) : null; // Generate slug only if name is not empty
                $('#slug').val(slug);
            });

            $('#updateSubcategory').submit(function (e) {

                e.preventDefault(); // Prevent the form from submitting normally


                var formData = new FormData(this);

                // Make AJAX request
                $.ajax({
                    url: '{{ route('subcategories.update',$subcategory->id) }}',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {


                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = '{{ route('subcategories.index') }}';
                            }
                        });
                    },
                    error: function (xhr, status, error) {
                        // Parse the JSON response from the server
                        try {
                            var responseObj = JSON.parse(xhr.responseText);
                            var errorMessages = responseObj.errors ? Object.values(responseObj.errors).flat() : [responseObj.message];
                            var errorMessageHTML = '<ul>' + errorMessages.map(errorMessage => '<li>' + errorMessage + '</li>').join('') + '</ul>';

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
                onExtensionErr: function (index, file) {
                    console.log(index, file, 'extension err');
                    alert('Please only input png or jpg type file')
                },
                onSizeErr: function (index, file) {
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
                onExtensionErr: function (index, file) {
                    console.log(index, file, 'extension err');
                    alert('Please only input png or jpg type file')
                },
                onSizeErr: function (index, file) {
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
                onExtensionErr: function (index, file) {
                    console.log(index, file, 'extension err');
                    alert('Please only input png or jpg type file')
                },
                onSizeErr: function (index, file) {
                    console.log(index, file, 'file size too big');
                    alert('File size too big max:250KB');
                }
            });
        });
    </script>


@endsection



