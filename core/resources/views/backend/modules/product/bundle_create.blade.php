@extends('backend.layouts.master')

@section('meta')
    <title>Bundle Product Create - {{ get_option('title') }}</title>
@endsection

@section('content')
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4>Product Management</h4>
                <h6>Create bundle product</h6>
            </div>
        </div>
        <ul class="table-top-head">
            <li>
                <div class="page-btn">
                    <a href="{{ route('products.index') }}" class="btn btn-secondary"><i data-feather="arrow-left"
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

        <input type="hidden" name="isBundle" value="1">
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
                                        <div class="mb-3 add-product">
                                            <label class="form-label">English Name <span class="star-sign">*</span></label>
                                            <input type="text" class="form-control " id="productName" name="english_name"
                                                value="{{ old('english_name') }}">

                                        </div>

                                    </div>
                                    <div class="col-lg-4 col-sm-6 col-12">
                                        <div class="mb-3 add-product">
                                            <label class="form-label">Bangla Name <span class="star-sign">*</span></label>
                                            <input type="text" class="form-control" name="bangla_name"
                                                value="{{ old('bangla_name') }}">

                                        </div>

                                    </div>
                                    <div class="col-lg-4 col-sm-6 col-12">
                                        <div class="mb-3 add-product">
                                            <label class="form-label">Slug<span class="star-sign">*</span></label>
                                            <input type="text" class="form-control" id="productSlug" name="slug">
                                        </div>
                                    </div>


                                    <div class="col-lg-4 col-sm-4 col-12">
                                        <div class="mb-3 add-product">
                                            <div class="add-newplus">
                                                <label class="form-label">Status</label>
                                            </div>
                                            <select class="select" name="status" id="status">
                                                <option value="1" {{ old('status') == 1 ? 'selected' : '' }}>Publish
                                                </option>
                                                <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>Unpublish
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-sm-6 col-12">
                                        <div class="mb-3 add-product">
                                            <label class="form-label">Product Type</label>
                                            <select class="select" name="product_type" id="product_type">
                                                <option disabled>Choose</option>
                                                <option value="book"
                                                    {{ old('product_type') == 'book' ? 'selected' : '' }}>
                                                    Book
                                                </option>
                                                <option value="stationary"
                                                    {{ old('product_type') == 'stationary' ? 'selected' : '' }}>
                                                    Stationary
                                                </option>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-sm-6 col-12">
                                        <div class="mb-3 add-product">
                                            <div class="add-newplus">
                                                <label class="form-label">Authors</label>
                                            </div>


                                            <select class="select2AjaxAuthor" name="author_id[]" id="author_id"
                                                multiple="multiple">


                                            </select>
                                        </div>
                                    </div>


                                </div>

                                <div class="row">
                                    <div class="col-lg-4 col-sm-6 col-12">
                                        <div class="mb-3 add-product">
                                            <div class="add-newplus">
                                                <label class="form-label">Category</label>
                                                <a href="javascript:void(0);" data-bs-toggle="modal"
                                                    data-bs-target="#add-units-category"><i data-feather="plus-circle"
                                                        class="plus-down-add"></i><span>Add New</span></a>
                                            </div>
                                            <select class="select2AjaxCategory " name="category_id[]" id="category_id"  multiple="multiple">


                                            </select>
                                        </div>
                                    </div>

                                    {{-- <div class="col-lg-4 col-sm-6 col-12">
                                        <div class="mb-3 add-product">
                                            <div class="add-newplus">
                                                <label class="form-label">Sub Category</label>
                                            </div>


                                            <select class="select" name="subcategory_id[]" id="subcategory_id"
                                                    multiple="multiple">


                                            </select>
                                        </div>
                                    </div> --}}
                                    <!--<div class="col-lg-4 col-sm-6 col-12">-->
                                    <!--    <div class="mb-3 add-product">-->
                                    <!--        <label class="form-label">Publisher</label>-->
                                    <!--        <select class="select select2AjaxPublisher " name="publisher_id"-->
                                    <!--            id="publisher_id">-->


                                    <!--        </select>-->
                                    <!--    </div>-->
                                    <!--</div>-->
                                </div>


                                <div class="row">
                                    <div class="col-lg-4 col-sm-6 col-12">
                                        <div class="mb-3 add-product">
                                            <label class="form-label">Published Year</label>
                                            <input type="date" class="form-control" name="published_year"
                                                value="{{ old('published_year') }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-sm-6 col-12">
                                        <div class="mb-3 add-product">
                                            <label class="form-label">Edition</label>
                                            <input type="text" class="form-control" name="edition"
                                                value="{{ old('edition') }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-sm-6 col-12">
                                        <div class="mb-3 add-product">
                                            <label class="form-label">Number of Pages</label>
                                            <input type="text" class="form-control" name="pages_no"
                                                value="{{ old('pages_no') }}">
                                        </div>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-lg-4 col-sm-6 col-12">
                                        <div class="mb-3 add-product">
                                            <label class="form-label">Weight (gram)</label>
                                            <input type="text" class="form-control" name="weight"
                                                value="{{ old('weight') }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-sm-6 col-12">
                                        <div class="mb-3 add-product">
                                            <label class="form-label">ISBN</label>
                                            <input type="text" class="form-control" name="isbn"
                                                value="{{ old('isbn') }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-sm-6 col-12">
                                        <div class="mb-3 add-product">
                                            <div class="add-newplus">
                                                <label class="form-label">Language</label>
                                            </div>
                                            <select class="select" name="language[]" multiple>
                                                <option disabled>Choose</option>
                                                <option {{ in_array('Bangla', old('language', [])) ? 'selected' : '' }}>
                                                    Bangla
                                                </option>
                                                <option {{ in_array('English', old('language', [])) ? 'selected' : '' }}>
                                                    English
                                                </option>
                                                <option {{ in_array('Arabic', old('language', [])) ? 'selected' : '' }}>
                                                    Arabic
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-4 col-sm-6 col-12">
                                        <div class="mb-3 add-product">
                                            <div class="add-newplus">
                                                <label class="form-label">Cover Type</label>
                                            </div>
                                            <select name="cover_type" id="cover_type" class="select">
                                                <option value="">Select Cover Type</option>
                                                <option value="hardcover"
                                                    {{ old('cover_type') == 'hardcover' ? 'selected' : '' }}>Hardcover
                                                </option>
                                                <option value="paperback"
                                                    {{ old('cover_type') == 'paperback' ? 'selected' : '' }}>Paperback
                                                </option>
                                                <option value="ebook"
                                                    {{ old('cover_type') == 'ebook' ? 'selected' : '' }}>eBook</option>
                                                <option value="audiobook"
                                                    {{ old('cover_type') == 'audiobook' ? 'selected' : '' }}>Audiobook
                                                </option>
                                            </select>
                                        </div>
                                    </div>


                                    <div class="col-lg-4 col-sm-6 col-12">
                                        <div class="mb-3 add-product">
                                            <div class="add-newplus">
                                                <label class="form-label">Subjects</label>
                                            </div>
                                            <select class="select2AjaxSubject " name="subject_id[]" id="subject_id"
                                                multiple>


                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4 col-sm-6 col-12">
                                        <div class="mb-3 add-product">
                                            <label class="form-label"> Short Description</label>
                                            <textarea class="form-control
                                           editorBasic2" rows="8"
                                                name="short_description">{{ old('short_description') }}</textarea>
                                            {{--                                            <p class="mt-1">Maximum 60 Characters</p> --}}


                                        </div>
                                    </div>
                                    <div class="col-lg-8 col-sm-6 col-12">
                                        <div class="mb-3 add-product">
                                            <label class="form-label"> Description</label>


                                            <textarea class="form-control editorBasic " rows="8" name="description">{{ old('description') }}</textarea>
                                            {{--                                                                                       <p class="mt-1">Maximum 60 Characters</p> --}}


                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
                <div class="accordion-card-one accordion" id="accordionExample5">
                    <div class="accordion-item">
                        <div class="accordion-header" id="headingFive">
                            <div class="accordion-button" data-bs-toggle="collapse" data-bs-target="#collapseFive"
                                aria-controls="collapseFive">
                                <div class="text-editor add-list">
                                    <div class="addproduct-icon list icon">
                                        <h5><i data-feather="book" class="add-info"></i><span>Add Bundle Product</span>
                                        </h5>
                                        <a href="javascript:void(0);"><i data-feather="chevron-down"
                                                class="chevron-down-add"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="collapseFive" class="accordion-collapse collapse show" aria-labelledby="headingFour"
                            data-bs-parent="#accordionExample5">
                            <div class="accordion-body">

                                <div class="tab-content" id="pills-tabContent">
                                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                                        aria-labelledby="pills-home-tab">


                                        <div class="row">
                                            <div class="col">
                                                <div class="mb-3">
                                                    <label for="name" class="form-label">Select Product</label>
                                                    <select name="name" id="name" class="select2Ajax"></select>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="row mt-3">
                                            <div class="col">
                                                <div class="form-group">
                                                    <div class="row bg-light fw-bold fs-6 py-3">
                                                        <div class="col-md-5">Name</div>
                                                        <div class="col-md-2">Current Price</div>
                                                        <div class="col-md-2">Qty</div>
                                                        <div class="col-md-2">Total</div>
                                                        <div class="col-md-1">Delete</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-3" id="productContainer">
                                            <!-- Content for product list will be dynamically added here -->
                                        </div>

                                        <div class="row">
                                            <div class="col">
                                                <div class="form-group mb-5">
                                                    <label class="fw-bold fs-3 text-dark" for="subtotal">Subtotal</label>
                                                    <input type="text" class="form-control subtotal" id="subtotal">
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
                                        <h5><i data-feather="life-buoy" class="add-info"></i><span>Pricing & Stocks</span>
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
                                                    <label>Purchase Price</label>
                                                    <input type="text" class="form-control" name="purchase_price"
                                                        id="purchase_price" value="{{ old('purchase_price') }}">
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-sm-6 col-12">
                                                <div class="input-blocks add-product">
                                                    <label>Mrp Price</label>
                                                    <input type="text" class="form-control subtotal" name="mrp_price"
                                                        id="mrp_price" value="{{ old('mrp_price') }}">
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-sm-6 col-12">
                                                <div class="input-blocks add-product">
                                                    <label>Current Price</label>
                                                    <input type="text" class="form-control subtotal"
                                                        id="current_price" name="current_price"
                                                        value="{{ old('current_price') }}">
                                                </div>
                                            </div>


                                        </div>
                                        <div class="row">
                                            <div class="col-lg-4 col-sm-6 col-12">
                                                <div class="input-blocks add-product">
                                                    <label>Discount Type</label>
                                                    <select class="select" name="discount_type" id="discount_type">
                                                        <option value="percentage"
                                                            {{ old('discount_type') == 'percentage' ? 'selected' : '' }}>
                                                            Percentage
                                                        </option>
                                                        <option value="amount"
                                                            {{ old('discount_type') == 'amount' ? 'selected' : '' }}>
                                                            Amount
                                                        </option>
                                                    </select>

                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-sm-6 col-12">
                                                <div class="input-blocks add-product">
                                                    <label>Discount Amount</label>
                                                    <input type="text" placeholder="Choose" name="discount_amount"
                                                        id="discount_amount">
                                                </div>
                                            </div>

                                            <div class="col-lg-4 col-sm-4 col-12">
                                                <div class="mb-3 add-product">
                                                    <div class="add-newplus">
                                                        <label class="form-label">Show Discount</label>
                                                    </div>
                                                    <select class="select" name="show_discount" id="show_discount">
                                                        <option value="1"
                                                            {{ old('show_discount') == 1 ? 'selected' : '' }}>Yes
                                                        </option>
                                                        <option value="0"
                                                            {{ old('show_discount') == 0 ? 'selected' : '' }}>NO
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <!--<div class="col-lg-4 col-sm-6 col-12">-->
                                            <!--    <div class="input-blocks add-product">-->
                                            <!--        <label>Stock Quantity</label>-->
                                            <!--        <input type="text" class="form-control" name="stock"-->
                                            <!--            id="stock" value="{{ old('stock') }}">-->
                                            <!--    </div>-->
                                            <!--</div>-->


                                            <div class="col-lg-4 col-sm-4 col-12">
                                                <div class="mb-3 add-product">
                                                    <div class="add-newplus">
                                                        <label class="form-label">Stock status</label>
                                                    </div>
                                                    <select id="stock_status" class="select" name="stock_status">
                                                        @foreach ($enumStatusValues as $value)
                                                            <option value="{{ $value }}"
                                                                {{ old('stock_status') == $value ? 'selected' : '' }}>
                                                                {{ ucfirst($value) }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="accordion-card-one accordion" id="accordionExample3">
                    <div class="accordion-item">
                        <div class="accordion-header" id="headingThree">
                            <div class="accordion-button" data-bs-toggle="collapse" data-bs-target="#collapseThree"
                                aria-controls="collapseThree">
                                <div class="addproduct-icon list">
                                    <h5><i data-feather="image" class="add-info"></i><span>Images</span></h5>
                                    <a href="javascript:void(0);"><i data-feather="chevron-down"
                                            class="chevron-down-add"></i></a>
                                </div>
                            </div>
                        </div>
                        <div id="collapseThree" class="accordion-collapse collapse show" aria-labelledby="headingThree"
                            data-bs-parent="#accordionExample3">
                            <div class="accordion-body">
                                <div class="text-editor add-list add">
                                    <div class="row">
                                        <div class="col-lg-6 col-sm-6 col-12">
                                            <div class="mb-3 add-product pe-2">
                                                <label class="form-label">Thumb Image<span
                                                        class="star-sign">*</span></label>

                                                <div class="form-group">
                                                    <div class="row" id="thumbnail">

                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        <!--<div class="col-lg-6 col-sm-6 col-12">-->
                                        <!--    <div class="mb-3 add-product">-->
                                        <!--        <label class="form-label">Pages Photos<span-->
                                        <!--                class="star-sign">*</span></label>-->

                                        <!--        <div class="form-group">-->
                                        <!--            <div class="row" id="pages">-->

                                        <!--            </div>-->
                                        <!--        </div>-->
                                        <!--    </div>-->
                                        <!--</div>-->


                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="accordion-card-one accordion" id="accordionExample4">
                    <div class="accordion-item">
                        <div class="accordion-header" id="headingFour">
                            <div class="accordion-button" data-bs-toggle="collapse" data-bs-target="#collapseFour"
                                aria-controls="collapseFour">
                                <div class="text-editor add-list">
                                    <div class="addproduct-icon list icon">
                                        <h5><i data-feather="settings" class="add-info"></i><span>Meta Section</span>
                                            <a href="javascript:void(0);" class="m-lg-3"
                                                title="The meta tag enhances website performance, SEO, and user experience by defining character encoding, viewport settings, and essential metadata for search engines"><i
                                                    data-feather="eye" class="eye-icon"></i></a>
                                        </h5>
                                        <a href="javascript:void(0);"><i data-feather="chevron-down"
                                                class="chevron-down-add"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="collapseFour" class="accordion-collapse collapse show" aria-labelledby="headingFour"
                            data-bs-parent="#accordionExample4">
                            <div class="accordion-body">

                                <div class="tab-content" id="pills-tabContent">
                                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                                        aria-labelledby="pills-home-tab">
                                        <div class="row">


                                            <div class="col-lg-3 col-sm-6 col-12">
                                                <div class=" add-product">
                                                    <label class="form-label">Meta Title</label>
                                                    <input type="text" class="form-control" name="meta_title"
                                                        value="{{ old('meta_title') }}">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-sm-6 col-12">
                                                <div class="add-product list mb-3">
                                                    <label class="form-label"> Meta Description</label>
                                                    <textarea rows="8" cols="5" class="form-control  h-100" name="meta_description"
                                                        placeholder="Enter text here">{{ old('meta_description') }}</textarea>

                                                </div>
                                            </div>

                                            <div class="col-lg-3 col-sm-6 col-12">
                                                <label class="form-label"> Meta Image</label>
                                                <div class="form-group">
                                                    <div class="row" id="meta_image">

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
        </div>

        <div class="col-lg-12">
            <div class="btn-addproduct mb-4">
                <button type="submit" class="btn btn-submit ">Save All</button>
            </div>
        </div>
    </form>










    <div class="modal fade" id="add-units-category">
        <div class="modal-dialog modal-dialog-centered custom-modal-two">
            <div class="modal-content">
                <div class="page-wrapper-new p-0">
                    <div class="content">
                        <div class="modal-header border-0 custom-modal-header">
                            <div class="page-title">
                                <h4>Add New Category</h4>
                            </div>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body custom-modal-body">
                            <form action="{{ route('categories.store') }}" id="createCategory" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                
                                  <div class="mb-3">
                                      
                                        <label class="form-label">Category Type</label>
                                        <select class="form-select" name="type">
                                            <option value="book" {{ old('type') == 'book' ? 'selected' : '' }}>Book</option>
                                            <option value="stationary" {{ old('type') == 'stationary' ? 'selected' : '' }}>Stationary</option>
                                        </select>
                                        
                                    </div>
                                
                                
                                <div class="mb-3">
                                    <label class="form-label">Name</label>
                                    <input type="text" class="form-control" id="name" name="name">
                                </div>
                                
                                <div class="mb-3 add-product">
                                    <label class="form-label">Slug<span class="star-sign">*</span></label>
                                    <input type="text" class="form-control" id="slug" name="slug">
                                </div>
                                <div class="mb-3 add-product">
                                    <label class="form-label">Icon Image<span class="star-sign">*</span></label>
                                    <input type="file" class="form-control" name="icon">
                                </div>


                                <div class="mb-3 add-product">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" name="status">
                                        <option value="1" {{ old('status') == 1 ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>Inactive
                                        </option>
                                    </select>
                                </div>

                                <div class="modal-footer-btn">
                                    <a href="javascript:void(0);" class="btn btn-cancel me-2"
                                        data-bs-dismiss="modal">Cancel</a>
                                    <button class="btn btn-submit">Submit</button>
                                </div>
                            </form>

                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <style>
        .star-sign {
            color: red;
            font-weight: bold;
        }
    </style>

    <script>
        $(document).ready(function() {
            // Remove product row
            $(document).on('click', '.remove-button', function() {
                $(this).closest('.form-group').remove();
                updateSubtotal();
            });

            // Calculate subtotal and update total field
            $(document).on('input', '.quantity, .selling_price', function() {
                calculateRowTotal($(this).closest('.row'));
                updateSubtotal();
            });

            function calculateRowTotal(row) {
                var quantity = row.find('.quantity').val();
                var cost = row.find('.selling_price').val();

                var subtotal = parseFloat(quantity) * parseFloat(cost);
                row.find('.total').val(subtotal.toFixed(2));
            }

            function updateSubtotal() {
                var subtotalSum = 0;
                $('.total').each(function() {
                    var value = parseFloat($(this).val());
                    if (!isNaN(value)) {
                        subtotalSum += value;
                    }
                });
                $('#subtotal').val(subtotalSum.toFixed(2));
            }
        });


        $(document).ready(function() {
             // Function to calculate the current price based on discount type and amount
        function calculateCurrentPrice() {
            var mrpPrice = parseFloat($('#mrp_price').val());
            var discountType = $('#discount_type').val();
            var discountAmount = parseFloat($('#discount_amount').val());
            var currentPrice;

            if (!isNaN(mrpPrice) && !isNaN(discountAmount)) {
                if (discountType === 'percentage') {
                    currentPrice = mrpPrice - (mrpPrice * discountAmount / 100);
                } else {
                    currentPrice = mrpPrice - discountAmount;
                }

                // Update the current price field
                $('#current_price').val(currentPrice.toFixed(2));
            }
        }

        // Function to calculate the discount amount based on current price
        function calculateDiscountAmount() {
            var mrpPrice = parseFloat($('#mrp_price').val());
            var currentPrice = parseFloat($('#current_price').val());
            var discountType = $('#discount_type').val();
            var discountAmount;

            if (!isNaN(mrpPrice) && !isNaN(currentPrice)) {
                if (discountType === 'percentage') {
                    discountAmount = ((mrpPrice - currentPrice) / mrpPrice) * 100;
                } else {
                    discountAmount = mrpPrice - currentPrice;
                }

                // Update the discount amount field
                $('#discount_amount').val(discountAmount.toFixed(2));
            }
        }

        // Event listeners to calculate current price when discount type or amount changes
        $('#discount_type').change(calculateCurrentPrice);
        $('#discount_amount').on('input', calculateCurrentPrice);

        // Event listener to calculate discount amount when current price is manually changed
        $('#current_price').on('input', calculateDiscountAmount);
        });


        $(document).ready(function() {

          
            function generateSlug(name) {

                var pattern = /[a-zA-Z0-9\u0980-\u09FF]+/g;

                return name.toLowerCase().match(pattern).join('-');
            }


            // Event listener for  product name field
            $('#productName').on('input', function() {
                var name = $(this).val();
                var slug = name ? generateSlug(name) : null; // Generate slug only if name is not empty
                $('#productSlug').val(slug);
            });

            // Event listener for  category name field
            // $('#name').on('input', function() {
            //     var name = $(this).val();
            //     var slug = name ? generateSlug(name) : null; // Generate slug only if name is not empty
            //     $('#slug').val(slug);
            // });
           $(document).on('input', '#add-units-category #name', function () {
        var name = $(this).val();
        var slug = name ? generateSlug(name) : ''; // Generate slug only if name is not empty
        $('#add-units-category #slug').val(slug);
    });

            $('#createProduct').submit(function(e) {
                e.preventDefault(); // Prevent the form from submitting normally
                var formData = new FormData(this);

                // Make AJAX request
                $.ajax({
                    url: '{{ route('products.bundle.store') }}',
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
                                window.location.href = '{{ route('products.index') }}';
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

            // using modal

            $(document).on('submit', '#createCategory', function(e) {
                e.preventDefault(); // Prevent the form from submitting normally
                var formData = new FormData(this);

                // Make AJAX request
                $.ajax({
                    url: $(this).attr('action'), // Use the form's action attribute
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
                                // Hide the modal
                                $('#add-units-category').modal('hide');

                                // Reset the form
                                $('#createCategory')[0].reset();
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


            $('#pages').imageUploader({
                uploadInputIcon: 'fas fa-upload',
                deleteImageIcon: 'fas fa-times',
                label: 'Drag & Drop files here or click to browse',
                imagesInputName: 'pages_photos',
                preloadedInputName: 'preloaded',
                // Add validation logic
                // onBeforeUpload: function(files) {
                //     var isValid = true;
                //     $.each(files, function(index, file) {
                //         var fileName = file.name;
                //         var ext = fileName.split('.').pop().toLowerCase();
                //         if (!(ext === 'jpeg' || ext === 'png' || ext === 'jpg' || ext === 'gif')) {
                //             isValid = false;
                //             return false; // Exit the loop early if any file is invalid
                //         }
                //     });
                //     if (!isValid) {
                //         alert('Please upload only JPEG, PNG, JPG, or GIF files.');
                //     }
                //     return isValid; // Return true if all files are valid, false otherwise
                // }
            });


            $("#thumbnail").spartanMultiImagePicker({
                fieldName: 'thumb_image',
                maxCount: 1,
                rowHeight: '160px',
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
                rowHeight: '150px',
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

            try {
                var selectSimple = $('.select2AjaxCategory');

                selectSimple.select2({
                    placeholder: 'Search for Category',
                    minimumInputLength: 1,
                    ajax: {
                        url: '{{ route('categories.search') }}',
                        dataType: 'json',
                        type: 'GET',
                        quietMillis: 50,
                        data: function(params) {
                            return {
                                q: params.term
                            };
                        },
                        processResults: function(data) {
                            return {
                                results: data.map(function(category) {
                                    return {
                                        text: category.text,
                                        id: category.id
                                    };
                                })
                            };
                        }
                    }
                });
            } catch (err) {
                console.log(err);
            }


            try {
                var selectSimple = $('.select2AjaxPublisher');

                selectSimple.select2({
                    placeholder: 'Search for Publisher',
                    minimumInputLength: 1,
                    ajax: {
                        url: '{{ route('publishers.search') }}',
                        dataType: 'json',
                        type: 'GET',
                        quietMillis: 50,
                        data: function(params) {
                            return {
                                q: params.term
                            };
                        },
                        processResults: function(data) {
                            return {
                                results: data.map(function(publisher) {
                                    return {
                                        text: publisher.text,
                                        id: publisher.id
                                    };
                                })
                            };
                        }
                    }
                });
            } catch (err) {
                console.log(err);
            }


            try {
                var selectSimple = $('.select2AjaxAuthor');

                selectSimple.select2({
                    placeholder: 'Search for Author',
                    minimumInputLength: 1,
                    ajax: {
                        url: '{{ route('authors.search') }}',
                        dataType: 'json',
                        type: 'GET',
                        quietMillis: 50,
                        data: function(params) {
                            return {
                                q: params.term
                            };
                        },
                        processResults: function(data) {
                            return {
                                results: data.map(function(author) {
                                    return {
                                        text: author.text,
                                        id: author.id
                                    };
                                })
                            };
                        }
                    }
                });
            } catch (err) {
                console.log(err);
            }


            try {
                var selectSimple = $('.select2AjaxSubject');

                selectSimple.select2({
                    placeholder: 'Search for Subject',
                    minimumInputLength: 1,
                    ajax: {
                        url: '{{ route('subjects.search') }}',
                        dataType: 'json',
                        type: 'GET',
                        quietMillis: 50,
                        data: function(params) {
                            return {
                                q: params.term
                            };
                        },
                        processResults: function(data) {
                            return {
                                results: data.map(function(category) {
                                    return {
                                        text: category.text,
                                        id: category.id
                                    };
                                })
                            };
                        }
                    }
                });
            } catch (err) {
                console.log(err);
            }

            $('#category_id').on('change', function() {
                var categoryId = $(this).val();

                // Send an AJAX request to fetch subcategories based on the selected category
                $.ajax({

                    url: '{{ route('subcategories.fetch') }}',

                    method: 'POST',
                    data: {
                        category_id: categoryId
                    },
                    success: function(response) {
                        var optionsHtml = '';

                        // Check if there are no subcategories available
                        if (response.length === 0 || (response.length === 1 && !response[0]
                            .id)) {
                            optionsHtml +=
                                '<option value="">No subcategories available</option>';
                        } else {
                            // Append the default "Select Subcategory" option
                            // optionsHtml += '<option value="">Select Subcategory</option>';

                            // Iterate over each subcategory and build the options HTML
                            $.each(response, function(index, subcategory) {
                                optionsHtml += '<option value="' + subcategory.id +
                                    '">' + subcategory.name + '</option>';
                            });
                        }

                        // Update the HTML content of the subcategory dropdown
                        $('#subcategory_id').html(optionsHtml);
                    },


                    error: function(xhr, status, error) {
                        // Handle errors
                        console.error(error);
                    }
                });
            });


            try {
                var selectSimple = $('.select2Ajax');

                selectSimple.select2({

                    minimumInputLength: 1,
                    ajax: {
                        url: '{{ route('products.search') }}',
                        dataType: 'json',
                        type: "GET",
                        quietMillis: 50,
                        data: function(term) {
                            return {
                                q: term.term
                            }
                        },
                        processResults: function(data) {
                            return {
                                results: data
                            };
                        }
                    }

                });

                selectSimple.on('change', function() {

                    const productId = $(this).val();
                    $.ajax({
                        url: '{{ route('products.get.details') }}',
                        method: 'GET',
                        data: {
                            productId: productId
                        },
                        dataType: 'json',
                        success: function(response) {
                        
                        let bundleProductExists = false;
                        const bundleProductId = response.id;


                            $('.bundle_product_id').each(function() {
                                if ($(this).val() === bundleProductId.toString()) {
                                    bundleProductExists = true;
                                    return false; // Exit the loop if a matching bundle product is found
                                }
                            });

                            if (bundleProductExists) {
                                // Alert the user that the bundle product already exists using a simple JavaScript alert
                                console.log('Bundle product with ID ' + bundleProductId +
                                    ' already exists.');
                                alert('A bundle product with this ID already exists.');
                                return;
                            }

                            var sum = parseFloat(response.current_price) * 1;

                            var html =
                                `<div class="form-group">
                            <div class="row mb-3">

                                    <input type="hidden" class="form-control bundle_product_id" name="bundle_product_id[]" value="` +
                                response.id + `">

                                <div class="col-md-5">
                                    <input type="text" class="form-control name" name="name[]" value="` + response.name.replace(/"/g, '&quot;') +
                                `">
                                </div>
                                <div class="col-md-2">
                                    <input type="number" class="form-control selling_price" name="bundle_current_price[]" value="` +
                                response.current_price +
                                `" >
                                </div>
                                <div class="col-md-2">
                                    <input type="number" class="form-control quantity" name="quantity[]"  value="1">
                                </div>
                                <div class="col-md-2">
                                    <input type="number" class="form-control total" name="total[]" placeholder="Total"  value="` + sum + `" readonly>
                                </div>
                                <div class="col-md-1 align-center">
                                    <i class="fas fa-trash remove-button text-danger fa-lg"></i>
                                </div>
                            </div>
                        </div>`;
                            $('#productContainer').append(html);

                            let subtotal = 0;
                            $('#productContainer .total').each(function() {
                                const totalValue = parseFloat($(this).val());
                                subtotal += !isNaN(totalValue) ? totalValue : 0;
                            });

                            $('#subtotal').val(subtotal.toFixed(2));
                            updateSubtotal();

                        },
                        error: function() {
                            // Handle any errors that occur during the Ajax request
                            console.log('Error fetching patient information.');
                        }
                    });
                });
            } catch (err) {
                console.log(err);
            }


            // Remove product row
            $(document).on('click', '.remove-button', function() {
                $(this).closest('.form-group').remove();
                updateSubtotal();
            });

            // Calculate subtotal and update total field
            $(document).on('input', '.quantity, .selling_price', function() {
                calculateRowTotal($(this).closest('.row'));
                updateSubtotal();
            });

            function calculateRowTotal(row) {
                var quantity = row.find('.quantity').val();
                var cost = row.find('.selling_price').val();

                var subtotal = parseFloat(quantity) * parseFloat(cost);
                row.find('.total').val(subtotal.toFixed(2));
            }

            function updateSubtotal() {
                var subtotalSum = 0;
                $('.total').each(function() {
                    var value = parseFloat($(this).val());
                    if (!isNaN(value)) {
                        subtotalSum += value;
                    }
                });
                $('.subtotal').val(subtotalSum.toFixed(2));
            }


        });
    </script>
@endsection
