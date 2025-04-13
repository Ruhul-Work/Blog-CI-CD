@extends('backend.layouts.master')

@section('meta')
    <title>Edit Blog - {{ get_option('title') }}</title>
@endsection

@section('content')
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4>Blog Management</h4>
                <h6>Edit Blog</h6>
            </div>
        </div>
        <ul class="table-top-head">
            <li>
                <div class="page-btn">
                    <a href="{{route('blogs.index')}}" class="btn btn-secondary"><i data-feather="arrow-left"
                            class="me-2"></i>Back to
                        Blog</a>
                </div>
            </li>
            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i
                        data-feather="chevron-up" class="feather-chevron-up"></i></a>
            </li>
        </ul>
    </div>

    <form action="" id="updateBlog" method="post" enctype="multipart/form-data">
        @csrf
        @method('POST ')
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
                                            <label class="form-label">Title <span class="star-sign">*</span></label>
                                            <input type="text" class="form-control" id="title" name="title"
                                                value="{{ old('title', $blog->title) }}">

                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-6 col-12">
                                        <div class="mb-3 add-product">
                                            <label class="form-label">Slug<span class="star-sign">*</span></label>
                                            <input type="text" class="form-control" id="slug" name="slug"
                                                value="{{ old('slug', $blog->slug) }}">
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-sm-6 col-12">
                                        <label class="form-label">Status</label>
                                        <select class="select" name="status">
                                            <option value="1"
                                                {{ old('status', $blog->status) == 1 ? 'selected' : '' }}>Active</option>
                                            <option value="0"
                                                {{ old('status', $blog->status) == 0 ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-3 col-sm-6 col-12">
                                        <label class="form-label">Publish Status</label>
                                        <select id="publish_status" class="select" name="publish_status">
                                            @foreach ($enumOptions as $value)
                                                <option value="{{ $value }}"
                                                    {{ old('publish_status', $blog->publish_status) == $value ? 'selected' : '' }}>
                                                    {{ ucfirst($value) }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>
                                <div class="row">

                                    <div class="col-lg-3 col-sm-6 col-12">
                                        <div class="mb-3">
                                            <label class="form-label">Category</label>
                                            <select class="form-control multi-tags" name="category_ids[]"
                                                multiple="multiple" style="width: 375px!important">
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}"
                                                        {{ in_array($category->id, $blog->categories->pluck('id')->toArray()) ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-sm-6 col-12">
                                        <div class="mb-3">
                                            <label class="form-label">Tags<span class="star-sign"></span></label>
                                            <select class="form-control multi-tags" name="tags[]" multiple="multiple"
                                                style="width: 375px!important">
                                                @foreach ($tags as $tag)
                                                    <option value="{{ $tag->id }}"
                                                        {{ in_array($tag->id, $blog->tags->pluck('id')->toArray()) ? 'selected' : '' }}>
                                                        {{ $tag->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-sm-6 col-12">
                                        <div class="form-check form-check-lg form-switch">
                                            <label class="form-label">Blog Type</label><br>
                                            <input class="form-check-input mx-2" type="checkbox" role="switch"
                                                id="blogTypeSwitch" name="blog_type" value="1"
                                                {{ old('blog_type', $blog->blog_type) == 1 ? 'checked' : '' }}
                                                onchange="toggleBlogType(this)">
                                            <label class="form-check-label" for="blogTypeSwitch"
                                                id="switchLabel">{{ old('blog_type', $blog->blog_type) == 1 ? 'Paid' : 'Free' }}</label>
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-sm-6 col-12">
                                        <label class="form-label">Authors</label>
                                        <select class="form-select select-show" name="author_id"
                                            style="width: 375px!important">
                                            <option value="">Select Author</option>
                                            @foreach ($authors as $author)
                                                <option value="{{ $author->id }}"
                                                    {{ old('author_id', $blog->author_id) == $author->id ? 'selected' : '' }}>
                                                    {{ $author->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>
                                <div class="row">

                                    <div class="col-lg-12 col-sm-12 col-12">
                                        <div class="mb-3 add-product">
                                            <label class="form-label">Content<span class="star-sign">*</span></label>
                                            <textarea name="content" class="editor" id="editor" rows="60" cols="120">
                                                {{ old('content', $blog->content) }}
                                            </textarea>
                                        </div>

                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-sm-6 col-12">
                                        <div class="mb-3 add-product">
                                            <label class="form-label">Thumbnail Image<span
                                                    class="star-sign">*</span></label>

                                            <div class="form-group">
                                                <div class="row" id="thumbnail" name="thumbnail">
                                                    @if ($blog->thumbnail)
                                                        <div class="col-md-6">
                                                            <div class="img-upload-preview">
                                                                <img src="{{ image($blog->thumbnail) }}" alt="icon"
                                                                    style="height: 180px;" class="img-responsive">
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
                                                        value="{{ old('meta_title', $blog->meta_title) }}">
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-sm-6 col-12">
                                                <div class=" add-product">
                                                    <label class="form-label">Meta Keywords</label>
                                                    <input type="text" class="form-control" name="meta_keywords"
                                                        value="{{ old('meta_keywords', $blog->meta_keywords) }}">
                                                </div>
                                            </div>

                                            <div class="col-lg-3 col-sm-6 col-12">
                                                <label class="form-label">Meta Image</label>
                                                <div class="form-group">
                                                    <div class="row" id="meta_image" name="meta_image">
                                                        @if ($blog->meta_image)
                                                            <div class="col-md-6">
                                                                <div class="img-upload-preview">
                                                                    <img src="{{ image($blog->meta_image) }}"
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

                                            <div class="col-lg-3 col-sm-6 col-12">
                                                <div class="add-product list">
                                                    <label class="form-label">Meta Description</label>
                                                    <textarea rows="8" cols="5" class="form-control h-100" name="meta_description"
                                                        placeholder="Enter text here">{{ old('meta_description', $blog->meta_description) }}</textarea>
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
                <button type="submit" class="btn btn-submit">Update Blog</button>
            </div>
        </div>
    </form>
@endsection

@section('script')
    <script>
        $(document).ready(function() {

            $('.remove-files').on('click', function() {
                $(this).closest(".col-md-6").remove();
            });

            function generateSlug(title) {

                var pattern = /[a-zA-Z0-9\u0980-\u09FF]+/g;

                return title.toLowerCase().match(pattern).join('_');
            }

            // Event listener for name field
            $('#title').on('input', function() {
                var title = $(this).val();
                var slug = title ? generateSlug(title) : null;
                $('#slug').val(slug);

            });

            // AJAX Submission
            $('#updateBlog').submit(function(e) {
                e.preventDefault();

                var formData = new FormData(this);

                $.ajax({
                    url: '{{ route('blogs.update', $blog->id) }}',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Updated!',
                            text: response.message,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = '{{ route('blogs.index') }}';
                            }
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Something went wrong. Please try again.',
                        });
                    },
                });
            });

            $(".select-show").select2();
     
        });

        $("#thumbnail").spartanMultiImagePicker({
            fieldName: 'thumbnail',
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

        function toggleBlogType(checkbox) {
            checkbox.value = checkbox.checked ? '1' : '0';
            $('#switchLabel').text(checkbox.checked ? 'Paid' : 'Free');
        }


        $(".multi-tags").select2({
            tags: true,
            tokenSeparators: [','],
            createTag: function(params) {
                var term = $.trim(params.term);
                if (term === '') {
                    return null;
                }
                return {
                    id: term,
                    text: term
                };
            },
            maximumInputLength: 50,
        });
     
     
    </script>
@endsection
