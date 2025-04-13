@extends('backend.layouts.master')

@section('meta')
    <title>Edit Tag - {{ get_option('title') }}</title>
@endsection

@section('content')
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4>Tag Management</h4>
                <h6>Edit Tag</h6>
            </div>
        </div>
        <ul class="table-top-head">
            <li>
                <div class="page-btn">
                    <a href="{{ route('tags.index') }}" class="btn btn-secondary"><i data-feather="arrow-left"
                                                                                      class="me-2"></i>Back to Tags</a>
                </div>
            </li>
        </ul>
    </div>

    <form action="" id="updateTag" method="post">
        @csrf
        @method('post')
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
                                                   value="{{ $tag->name }}" required>
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
                <button type="submit" class="btn btn-submit">Update Tag</button>
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
            // Generate slug when typing in the name field
            $('#name').on('input', function () {
                var name = $(this).val();
                var slug = name ? name.toLowerCase().replace(/ /g, '_').replace(/[^\w-]+/g, '') : '';
                $('#slug').val(slug);
            });

            // Handle form submission with AJAX
            $('#updateTag').submit(function (e) {
                e.preventDefault(); 

                var formData = new FormData(this);

                // Make AJAX request
                $.ajax({
                    url: '{{ route('tags.update', $tag->id) }}',
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
        });
    </script>
@endsection
