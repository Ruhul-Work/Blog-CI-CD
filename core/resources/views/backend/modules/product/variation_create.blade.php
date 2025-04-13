@extends('backend.layouts.master')

@section('meta')
    <title>Variation Product Create - {{ get_option('title') }}</title>
@endsection

@section('content')
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4>Product Management</h4>
                <h6>Create variation product</h6>
            </div>
        </div>
        <ul class="table-top-head">
            <li>
                <div class="page-btn">
                    <a href="{{route('products.index')}}" class="btn btn-secondary"><i data-feather="arrow-left"
                                                                                       class="me-2"></i>Back to
                        Products</a>
                </div>
            </li>
            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i
                        data-feather="chevron-up" class="feather-chevron-up"></i></a>
            </li>
        </ul>
    </div>

    <form action="" id="createProduct" method="post">
        @csrf
        <div class="card">
            <div class="card-body add-product pb-0">
                <div class="accordion-card-one accordion" id="accordionExample">
                    <div class="accordion-item">
                        <div class="accordion-header" id="headingOne">
                            <div class="accordion-button" data-bs-toggle="collapse" data-bs-target="#collapseOne"
                                 aria-controls="collapseOne">
                                <div class="addproduct-icon">
                                    <h5><i data-feather="info" class="add-info"></i><span>Variation Information</span></h5>
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
                                            <label class="form-label">Variation Name</label>
                                            <select name="variant_id" id="variant_id" class="select">

                                                @foreach ($variations as $variation)
                                                    <option value="{{$variation->id}}">{{$variation->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-sm-6 col-12">
                                        <div class="mb-3 add-product">
                                            <label class="form-label">Price</label>
                                            <input type="text" name="price" id="price" class="form-control" value="{{old('price')}}" placeholder="Enter price">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-sm-6 col-12">
                                        <div class="mb-3 add-product">
                                            <label class="form-label">Stock</label>
                                            <input type="text" name="stock" id="stock" class="form-control" value="{{old('stock')}}" placeholder="Enter stock quantity">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="mb-3 add-product">
                                            <label class="form-label">Description</label>
                                            <textarea name="description" id="description" class="form-control textarea"  placeholder="Enter description">{{old('description')}}</textarea>
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
                <button type="submit" class="btn btn-submit ">Save All</button>
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

            document.querySelectorAll('.ckEditor').forEach(function (textarea) {
                ClassicEditor
                    .create(textarea, {
                        height: '400px !important' // Set the height here
                    })
                    .catch(error => {
                        console.error(error);
                    });
            });






            $('#createProduct').submit(function (e) {
                e.preventDefault(); // Prevent the form from submitting normally
                var formData = new FormData(this);

                // Make AJAX request
                $.ajax({
                    url: '{{ route('products.variation.store',$product->id) }}',
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
                                window.location.href = '{{ route('products.show',$product->id) }}';
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


