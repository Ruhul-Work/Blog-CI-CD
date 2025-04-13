@extends('backend.layouts.master')

@section('meta')
    <title>Create New Tag - {{ get_option('title') }}</title>
@endsection

@section('content')
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4>Tag Management</h4>
                <h6>Create New Tag</h6>
            </div>
        </div>
        <ul class="table-top-head">
            <li>
                <div class="page-btn">
                    <a href="{{ route('tags.index') }}" class="btn btn-secondary"><i data-feather="arrow-left"
                                                                                      class="me-2"></i>Back to Tags</a>
                </div>
            </li>
            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i
                        data-feather="chevron-up" class="feather-chevron-up"></i></a>
            </li>
        </ul>
    </div>

    <form action="" id="createTag" method="post">
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
                                    <div class="col-lg-6 col-sm-12 col-12">
                                        <div class="mb-3 add-product">
                                            <label class="form-label">Name <span class="star-sign">*</span></label>
                                            <input type="text" class="form-control" id="name" name="name"
                                                   value="{{ old('name') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-sm-12 col-12">
                                        <div class="mb-3 add-product">
                                            <label class="form-label">Slug</label>
                                            <input type="text" class="form-control" id="slug" name="slug" readonly>
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

    <style>
        .star-sign {
            color: red;
            font-weight: bold;
        }
    </style>

    <script>
        $(document).ready(function () {
            function generateSlug(name) {
                var pattern = /[a-zA-Z0-9\u0980-\u09FF]+/g;
                return name.toLowerCase().match(pattern).join('_');
            }

            // Generate slug when typing in the name field
            $('#name').on('input', function () {
                var name = $(this).val();
                var slug = name ? generateSlug(name) : null;
                $('#slug').val(slug);
            });

            $('#createTag').submit(function (e) {
                e.preventDefault(); // Prevent default form submission

                var formData = new FormData(this);

                // AJAX call for form submission
                $.ajax({
                    url: '{{ route('tags.store') }}',
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
                                window.location.href = '{{ route('tags.index') }}';
                            }
                        });
                    },
                    error: function (xhr, status, error) {
                        try {
                            var responseObj = JSON.parse(xhr.responseText);
                            var errorMessages = responseObj.errors ? Object.values(responseObj.errors).flat() : [responseObj.message];
                            var errorMessageHTML = '<ul>' + errorMessages.map(errorMessage => '<li>' + errorMessage + '</li>').join('') + '</ul>';

                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                html: errorMessageHTML,
                            });
                        } catch (e) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'An unexpected error occurred. Please try again later.',
                            });
                        }
                    }
                });
            });
        });
    </script>
@endsection
