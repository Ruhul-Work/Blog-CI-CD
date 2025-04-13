@extends('backend.layouts.master')

@section('meta')
    <title>Create new Author - {{ get_option('title') }}</title>
@endsection

@section('content')
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4>New Author</h4>
                <h6>Create new Authors</h6>
            </div>
        </div>
        <ul class="table-top-head">
            <li>
                <div class="page-btn">
                    <a href="{{ route('authors.index') }}" class="btn btn-secondary"><i data-feather="arrow-left"
                            class="me-2"></i>Back to Authors</a>
                </div>
            </li>
            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i
                        data-feather="chevron-up" class="feather-chevron-up"></i></a>
            </li>
        </ul>
    </div>

    <form action="{{ route('authors.store') }}" id="createCategory" method="post" enctype="multipart/form-data">
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
                                    <div class="col-lg-4 col-sm-4 col-12">

                                        <div class="mb-3 add-product">
                                            <label class="form-label">Name</label>
                                            <input type="text" class="form-control" name="name" id="name">
                                        </div>

                                    </div>
                                    <div class="col-lg-4 col-sm-4 col-12">
                                        <div class="mb-3 add-product">
                                            <label class="form-label">Slug</label>
                                            <input type="text" class="form-control" name="slug" id="slug">
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-sm-4 col-12">
                                        <label class="form-label">Status</label>
                                        <select class="form-select" name="status">
                                            <option>-- Select --</option>
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>

                                    </div>

                                </div>
                                <div class="row">

                                    <div class="col-lg-6 col-sm-6 col-12">
                                        <div class="mb-3 add-product">
                                            <label class="form-label">Icon Image</label>
                                            <input type="file" class="form-control" name="icon">

                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-sm-6 col-12">
                                        <div class="mb-3 add-product">
                                            <label class="form-label">Cover Image</label>
                                            <input type="file" class="form-control" name="cover_image">
                                        </div>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-sm-6 col-12">
                                        <div class="mb-3 add-product">
                                            <label class="form-label">Icon Image<span class="star-sign">*</span></label>

                                            <div class="form-group">
                                                <div class="row" id="icon">

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-6 col-12">
                                        <div class="mb-3 add-product">
                                            <label class="form-label">Cover Image</label>

                                            <div class="form-group">
                                                <div class="row" id="cover_image">

                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-sm-6 col-12">
                                        <div class="mb-3 add-product">
                                            <label class="form-label"> Description</label>
                                            <textarea class="form-control h-100" rows="8" name="description"></textarea>
                                        </div>
                                    </div>

                                </div>

                                <div class="col-lg-12">
                                    <div class="input-blocks summer-description-box transfer mb-3">
                                        <label class="form-label">Description</label>
                                        <textarea class="form-control h-100" rows="5" name="description"></textarea>
                                        <p class="mt-1">Maximum 60 Characters</p>
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
                                            <div class="col-lg-4 col-sm-6 col-12">
                                                <div class="input-blocks add-product">
                                                    <label class="form-label">Meta Title</label>
                                                    <input type="text" class="form-control" name="meta_title">
                                                </div>
                                            </div>

                                            <div class="col-lg-4 col-sm-6 col-12">
                                                <label class="form-label">Meta Image</label>
                                                <div class="add-choosen">
                                                    <div class="input-blocks">
                                                        <div class="image-upload">
                                                            <input type="file" name="meta_image">
                                                            <div class="image-uploads">
                                                                <i data-feather="plus-circle"
                                                                    class="plus-down-add me-0"></i>
                                                                <h4>Add Images</h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="phone-img">
                                                        <img src="assets/img/products/phone-add-2.png" alt="image">
                                                        <a href="javascript:void(0);"><i data-feather="x"
                                                                class="x-square-add remove-product"></i></a>
                                                    </div>
                                                    <div class="phone-img">
                                                        <img src="assets/img/products/phone-add-1.png" alt="image">
                                                        <a href="javascript:void(0);"><i data-feather="x"
                                                                class="x-square-add remove-product"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-12 col-sm-6 col-12">
                                            <div class="input-blocks add-product list">
                                                <label class="form-label"> Meta Description</label>
                                                <textarea rows="5" cols="5" class="form-control" name="meta_description" placeholder="Enter text here"></textarea>

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
                <button type="button" class="btn btn-cancel me-2">Cancel</button>
                <button type="submit" class="btn btn-submit">Save Product</button>
            </div>
        </div>
    </form>
@endsection
@section('script')
    <script src="{{ asset('theme/admin/assets/plugins/fileupload/spartan-multi-image-picker-min.js') }}"
        type="text/javascript"></script>

    {{-- <script>
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


        });
    </script> --}}

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
        });

        // image preview
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
    </script>
@endsection
