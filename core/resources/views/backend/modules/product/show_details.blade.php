@extends('backend.layouts.master')
@section('meta')
    <title>
        Product Details - {{ get_option('title') }}</title>
@endsection
@section('content')
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4>Product Management</h4>
                <h6>Full details of a product</h6>
            </div>
        </div>
        <ul class="table-top-head">
            <li>
                <div class="page-btn d-flex">
                    <a href="{{ route('products.index') }}" class="btn btn-secondary me-2"><i data-feather="arrow-left"
                            class="me-2"></i>Back to
                        Products</a>

                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-info"><i data-feather="edit"
                            class="me-2 text-white"></i>
                        Edit</a>
                </div>
            </li>
            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i
                        data-feather="chevron-up" class="feather-chevron-up"></i></a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-lg-8 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="productdetails">
                        <ul class="product-bar">
                            <li>
                                <h4>Product</h4>
                                <h6>{{ $product->english_name }}</h6>
                            </li>
                            <li>
                                <h4>Product type</h4>
                                <h6>{{ $product->product_type }}</h6>
                            </li>

                            @if ($product->categories()->count() > 0)
                                <li>
                                    <h4>Category</h4>
                                    <h6>
                                        @foreach ($product->categories as $category)
                                            {{ $category->name }}{{ !$loop->last ? ', ' : '' }}
                                        @endforeach
                                    </h6>
                                </li>
                            @endif
                            @if ($product->subcategories()->count() > 0)
                                <li>
                                    <h4>Sub Category</h4>
                                    <h6>
                                        @foreach ($product->subcategories as $subCategory)
                                            {{ $subCategory->name }}{{ !$loop->last ? ', ' : '' }}
                                        @endforeach
                                    </h6>
                                </li>
                            @endif
                            @if ($product->publisher)
                                <li>
                                    <h4>Publisher</h4>
                                    <h6>{{ $product->publisher->name }}</h6>
                                </li>
                            @endif

                            @if ($product->authors()->count() > 0)
                                <li>
                                    <h4>Author</h4>
                                    <h6>
                                        @foreach ($product->authors as $author)
                                            {{ $author->name }}{{ !$loop->last ? ', ' : '' }}
                                        @endforeach
                                    </h6>
                                </li>
                            @endif
                            @if ($product->subjects()->count() > 0)
                                <li>
                                    <h4>Subjects</h4>
                                    <h6>
                                        @foreach ($product->subjects as $subject)
                                            {{ $subject->name }}{{ !$loop->last ? ', ' : '' }}
                                        @endforeach
                                    </h6>
                                </li>
                            @endif
                            <li>
                                <h4>Edition</h4>
                                <h6>{{ $product->edition }}</h6>
                            </li>
                            <li>
                                <h4>Published Year</h4>
                                <h6>{{ $product->published_year }}</h6>
                            </li>
                            <li>
                                <h4>ISBN</h4>
                                <h6>{{ $product->isbn }}</h6>
                            </li>
                            <li>
                                <h4>Code</h4>
                                <h6>{{ $product->product_code }}</h6>
                            </li>
                            <li>
                                <h4>isBundle</h4>
                                <h6>{{ $product->isBundle == 1 ? 'Yes' : 'No' }}</h6>
                            </li>
                            @if ($product->discount_amount)
                                <li>
                                    <h4>Discount</h4>
                                    <h6>{{ $product->discount_amount }}{{ $product->discount_type == 'percentage' ? '%' : 'Tk' }}
                                    </h6>
                                </li>
                            @endif
                            <li>
                                <h4>Stock</h4>
                                <h6>{{ $product->stock }}</h6>
                            </li>
                            <li>
                                <h4>Pages No</h4>
                                <h6>{{ $product->pages_no }}</h6>
                            </li>
                            <li>
                                <h4>Language</h4>
                                <h6>{{ $product->language }}</h6>
                            </li>
                            <li>
                                <h4>Weight</h4>
                                <h6>{{ $product->weight }}</h6>
                            </li>
                            <li>
                                <h4>Meta Title </h4>
                                <h6>{!! $product->meta_title !!} </h6>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        @if (count($product->pages) > 0)
            <div class="col-lg-4 col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="slider-product-details">
                            <div class="owl-carousel owl-theme product-slide">
                                @foreach ($product->pages as $page)
                                    <div class="slider-product">
                                        <img src="{{ image($page->pages_photos) }}" alt="page photo">
                                        <h4>{{ $page->product->english_name }}</h4>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
    @if (count($product->bundleProducts) > 0)
        <div class="card mb-4">
            <div class="card-header">
                <h3>Bundle Products</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Sl.</th>
                            <th>Name</th>
                            <th>Current Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($product->bundleProducts as $bundleProduct)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $bundleProduct->name }}</td>
                                <td>{{ $bundleProduct->current_price }}</td>
                                <td>{{ $bundleProduct->quantity }}</td>
                                <td>{{ $bundleProduct->current_price * $bundleProduct->quantity }}</td>

                                <td>
                                    <button class="btn btn-danger btn-sm delete-bundle-item">Remove</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif


    @if ($product->variants()->count() > 0)
        <div class="card mb-4">
            <div class="card-header">
                <h3>Product Variants</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Sl.</th>
                            <th>Variant Name</th>
                            <th> Variant Type</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($product->variants as $variant)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $variant->name }}</td>
                                <td>{{ $variant->type }}</td>
                                <td>{{ $variant->pivot->price }}</td>
                                <td>{{ $variant->pivot->stock }}</td>
                                <td>{{ $variant->pivot->description }}</td>
                                <td>

                                    <a href="{{ route('products.variation.edit', ['productId' => $product->id, 'variationId' => $variant->id]) }}"
                                        class="btn btn-info btn-sm me-2" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button class="btn btn-danger btn-sm delete-variant"
                                        data-variant-id="{{ $variant->id }}"> <i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

@endsection
@section('script')
    <link rel="stylesheet" href="{{ asset('theme/admin/assets/plugins/owlcarousel/owl.carousel.min.css') }}">

    <script src="{{ asset('theme/admin/assets/plugins/owlcarousel/owl.carousel.min.js') }}"></script>




    <script>
        $('.delete-bundle-item').on('click', function() {
            var bundleProductId = $(this).val();
            var productId = {{ $product->id }};
            $.ajax({
                url: '{{ route('products.bundle.destroy') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    bundleProductId: bundleProductId,
                    productId: productId,
                },
                success: function(response) {
                    if (response.message) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message
                        }).then(function() {
                            window.location.reload();
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });




        $(document).ready(function() {
            $('.delete-variant').on('click', function() {
                var variantId = $(this).data('variant-id');
                var productId = {{ $product->id }};
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route('products.variation.destroy') }}',
                            type: 'POSt',
                            data: {
                                _token: '{{ csrf_token() }}',
                                variantId: variantId,
                                productId: productId,
                            },
                            success: function(response) {
                                if (response.message) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Success',
                                        text: response.message
                                    }).then(function() {
                                        window.location.reload();
                                    });
                                }
                            },
                            error: function(xhr, status, error) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: xhr.responseText
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
