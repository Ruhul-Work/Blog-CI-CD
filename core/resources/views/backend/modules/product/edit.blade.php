@extends('backend.layouts.master')
@section('meta')
    <title>Edit Product - {{ get_option('title') }}</title>
@endsection

@section('content')
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4>Product Management</h4>
                <h6>Edit Product</h6>
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

    <form action="" id="updateProduct" method="post">
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
                                        <div class="mb-3 add-product">
                                            <label class="form-label">English Name <span class="star-sign">*</span></label>
                                            <input type="text" class="form-control" id="productName" name="english_name"
                                                value="{{ $product->english_name }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-sm-6 col-12">
                                        <div class="mb-3 add-product">
                                            <label class="form-label">Bangla Name <span class="star-sign">*</span></label>
                                            <input type="text" class="form-control" name="bangla_name"
                                                value="{{ $product->bangla_name }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-sm-6 col-12">
                                        <div class="mb-3 add-product">
                                            <label class="form-label">Slug<span class="star-sign">*</span></label>
                                            <input type="text" class="form-control" id="productSlug" name="slug"
                                                value="{{ $product->slug }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4 col-sm-4 col-12">
                                        <div class="mb-3 add-product">
                                            <div class="add-newplus">
                                                <label class="form-label">Status</label>
                                            </div>
                                            <select class="select" name="status" id="status">
                                                <option value="1" {{ $product->status == 1 ? 'selected' : '' }}>Publish
                                                </option>
                                                <option value="0" {{ $product->status == 0 ? 'selected' : '' }}>
                                                    Unpublish</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-sm-6 col-12">
                                        <div class="mb-3 add-product">
                                            <label class="form-label">Product Type</label>
                                            <select class="select" name="product_type" id="product_type">
                                                <option value="book"
                                                    {{ $product->product_type == 'book' ? 'selected' : '' }}>Book</option>
                                                <option value="stationary"
                                                    {{ $product->product_type == 'stationary' ? 'selected' : '' }}>
                                                    Stationary</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-sm-6 col-12">
                                        <div class="mb-3 add-product">
                                            <div class="add-newplus">
                                                <label class="form-label">Authors</label>
                                            </div>
                                            <select class=" select2AjaxAuthor" name="author_id[]" id="author_id"
                                                multiple="multiple">

                                                @foreach ($product->authors as $author)
                                                    <option
                                                        value="{{ $author->id }}"{{ in_array($author->id, $productAuthorIds) ? 'selected' : '' }}>
                                                        {{ $author->name }}</option>
                                                @endforeach
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
                                            <select class="select2AjaxCategory" name="category_id[]" id="category_id"
                                                multiple>

                                                @foreach ($product->categories as $category)
                                                    <option value="{{ $category->id }}"
                                                        {{ in_array($category->id, $productCategoryIds) ? 'selected' : '' }}>
                                                        {{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                  <!--<div class="col-lg-4 col-sm-6 col-12">-->
                                  <!--      <div class="mb-3 add-product">-->
                                  <!--          <div class="add-newplus">-->
                                  <!--              <label class="form-label">Sub Category</label>-->
                                  <!--          </div>-->
                                  <!--          <select class="select" name="subcategory_id[]" id="subcategory_id" multiple>-->
                                  <!--              @foreach ($product->subcategories as $subcategory)-->
                                  <!--                  <option value="{{ $subcategory->id }}" {{ in_array($subcategory->id, $productSubcategoryIds) ? 'selected' : '' }} >{{ $subcategory->name }}</option>-->
                                  <!--              @endforeach-->
                                  <!--          </select>-->
                                  <!--      </div>-->
                                  <!--  </div> -->

                                    <div class="col-lg-4 col-sm-6 col-12">
                                        <div class="mb-3 add-product">
                                            <label class="form-label">Publisher</label>
                                            <select class=" select2AjaxPublisher" name="publisher_id" id="publisher_id">

                                                @if ($product->publisher_id)
                                                    <option value="{{ $product->publisher_id }}" selected>
                                                        {{ $product->publisher->name }}</option>
                                                @endif

                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-4 col-sm-6 col-12">
                                        <div class="mb-3 add-product">
                                            <label class="form-label">Review URL</label>
                                            <input type="text" class="form-control" name="review_url"
                                                   value="{{$product->review_url}}" placeholder="youtube link">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4 col-sm-6 col-12">
                                        <div class="mb-3 add-product">
                                            <label class="form-label">Published Year</label>
                                            <input type="date" class="form-control" name="published_year"
                                                value="{{ $product->published_year }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-sm-6 col-12">
                                        <div class="mb-3 add-product">
                                            <label class="form-label">Edition</label>
                                            <input type="text" class="form-control" name="edition"
                                                value="{{ $product->edition }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-sm-6 col-12">
                                        <div class="mb-3 add-product">
                                            <label class="form-label">Number of Pages</label>
                                            <input type="text" class="form-control" name="pages_no"
                                                value="{{ $product->pages_no }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4 col-sm-6 col-12">
                                        <div class="mb-3 add-product">
                                            <label class="form-label">Weight</label>
                                            <input type="text" class="form-control" name="weight"
                                                value="{{ $product->weight }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-sm-6 col-12">
                                        <div class="mb-3 add-product">
                                            <label class="form-label">ISBN</label>
                                            <input type="text" class="form-control" name="isbn"
                                                value="{{ $product->isbn }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-sm-6 col-12">
                                        <div class="mb-3 add-product">
                                            <div class="add-newplus">
                                                <label class="form-label">Language</label>
                                            </div>
                                            <select class="select" name="language[]" multiple>
                                                @php
                                                    $languages = $product->language
                                                        ? explode(',', $product->language)
                                                        : [];
                                                @endphp
                                                @foreach (['Bangla', 'English', 'Arabic'] as $lang)
                                                    <option value="{{ $lang }}"
                                                        {{ in_array($lang, $languages) ? 'selected' : '' }}>
                                                        {{ $lang }}</option>
                                                @endforeach
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
                                                <option value="hardcover"
                                                    {{ $product->cover_type == 'hardcover' ? 'selected' : '' }}>Hardcover
                                                </option>
                                                <option value="paperback"
                                                    {{ $product->cover_type == 'paperback' ? 'selected' : '' }}>Paperback
                                                </option>
                                                <option value="ebook"
                                                    {{ $product->cover_type == 'ebook' ? 'selected' : '' }}>eBook</option>
                                                <option value="audiobook"
                                                    {{ $product->cover_type == 'audiobook' ? 'selected' : '' }}>Audiobook
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
                                                @foreach ($product->subjects as $subject)
                                                    <option value="{{ $subject->id }}"
                                                        {{ in_array($subject->id, $productSubjectIds) ? 'selected' : '' }}>
                                                        {{ $subject->name }}</option>
                                                @endforeach


                                            </select>
                                        </div>
                                    </div>



                                </div>
                                <div class="row">
                                    <div class="col-lg-4 col-sm-6 col-12">
                                        <div class="mb-3 add-product">
                                            <label class="form-label">Short Description</label>
                                            <textarea class="form-control editorBasic2" rows="8" name="short_description">{{ $product->short_description }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-lg-8 col-sm-6 col-12">
                                        <div class="mb-3 add-product">
                                            <label class="form-label">Description</label>
                                            <textarea class="form-control editorBasic" rows="8" name="description">{{ $product->description }}</textarea>
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
                                            <h5><i data-feather="life-buoy" class="add-info"></i><span>Pricing &
                                                    Stocks</span></h5>
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
                                                            id="purchase_price" value="{{ $product->purchase_price }}">
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6 col-12">
                                                    <div class="input-blocks add-product">
                                                        <label>MRP Price</label>
                                                        <input type="text" class="form-control" name="mrp_price"
                                                            id="mrp_price" value="{{ $product->mrp_price }}">
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6 col-12">
                                                    <div class="input-blocks add-product">
                                                        <label>Current Price</label>
                                                        <input type="text" class="form-control" id="current_price"
                                                            name="current_price" value="{{ $product->current_price }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-4 col-sm-6 col-12">
                                                    <div class="input-blocks add-product">
                                                        <label>Discount Type</label>
                                                        <select class="select" name="discount_type" id="discount_type">
                                                            <option value=""
                                                                {{ $product->discount_type == null ? 'selected' : '' }}>
                                                                Choose</option>
                                                            <option value="percentage"
                                                                {{ $product->discount_type == 'percentage' ? 'selected' : '' }}>
                                                                Percentage</option>
                                                            <option value="amount"
                                                                {{ $product->discount_type == 'amount' ? 'selected' : '' }}>
                                                                Amount</option>
                                                        </select>

                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6 col-12">
                                                    <div class="input-blocks add-product">
                                                        <label>Discount Amount</label>
                                                        <input type="text" name="discount_amount" id="discount_amount"
                                                            value="{{ $product->discount_amount }}">
                                                    </div>
                                                </div>


                                                <div class="col-lg-4 col-sm-4 col-12">
                                                    <div class="mb-3 add-product">
                                                        <div class="add-newplus">
                                                            <label class="form-label">Show Discount</label>
                                                        </div>
                                                        <select class="select" name="show_discount" id="show_discount">
                                                            <option value="1"
                                                                {{ $product->show_discount == 1 ? 'selected' : '' }}>Yes
                                                            </option>
                                                            <option value="0"
                                                                {{ $product->show_discount == 0 ? 'selected' : '' }}>No
                                                            </option>
                                                        </select>

                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6 col-12">
                                                    <div class="input-blocks add-product">
                                                        <label>Stock Quantity</label>
                                                        <input type="text" class="form-control" name="stock"
                                                            id="stock" value="{{ $product->stock }}">
                                                    </div>
                                                </div>

                                                <div class="col-lg-4 col-sm-4 col-12">
                                                    <div class="mb-3 add-product">
                                                        <div class="add-newplus">
                                                            <label class="form-label">Stock status</label>
                                                        </div>
                                                        <select id="stock_status" class="select" name="stock_status">

                                                            @foreach ($enumStatusValues as $value)
                                                                <option value="{{ $value }}"
                                                                    {{ $product->stock_status == $value ? 'selected' : '' }}>
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
                            <div id="collapseThree" class="accordion-collapse collapse show"
                                aria-labelledby="headingThree" data-bs-parent="#accordionExample3">
                                <div class="accordion-body">
                                    <div class="text-editor add-list add">


                                        <div class="row">
                                            <div class="col-lg-6 col-sm-6 col-12">
                                                <div class="mb-3 add-product">
                                                    <label class="form-label">Thumb Image<span
                                                            class="star-sign">*</span></label>

                                                    <div class="form-group">
                                                        <div class="row" id="thumbnail">
                                                            
                                                            

                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                            
                                            <div class="col-lg-6 col-sm-6 col-12">
                                                <div class="mb-3 add-product">
                                                    <label class="form-label">Old Thumb Image</label>

                                                    <div class="form-group">
                                                        
                                                        <img src="{{image($product->thumb_image)}}" alt="Avatar"   style="height:300px;width:300px">
                                                        
                                                    </div>

                                                </div>
                                            </div>
                                            <!--<div class="col-lg-6 col-sm-6 col-12">-->
                                            <!--    <div class="mb-3 add-product">-->
                                            <!--        <label class="form-label">Pages Photos<span-->
                                            <!--                class="star-sign">*</span></label>-->

                                            <!--        <div class="form-group">-->
                                            <!--                   <div class="avatar-list-stacked avatar-group-lg mb-4">-->
                                            <!--                        @foreach ($product->pages as $page)-->
                                            <!--                            <span class="avatar" style="height:100px;width:80px">-->
                                            <!--                                <img src="{{ asset($page->pages_photos) }}" alt="Avatar">-->
                                            <!--                            </span>-->
                                            <!--                        @endforeach-->
                                            <!--                        <a class="avatar bg-primary text-fixed-white" href="javascript:void(0);" style="height:100px;width:80px">-->
                                            <!--                            +{{ $product->pages->count() - 3 }} <!-- Assuming you want to show the count of additional avatars -->
                                            <!--                        </a>-->
                                            <!--                    </div>-->


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
                                    <div class="addproduct-icon">
                                        <h5><i data-feather="settings" class="add-info"></i><span>Meta Information</span>
                                        </h5>
                                        <a href="javascript:void(0);"><i data-feather="chevron-down"
                                                class="chevron-down-add"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div id="collapseFour" class="accordion-collapse collapse show" aria-labelledby="headingFour"
                                data-bs-parent="#accordionExample4">
                                <div class="accordion-body">
                                    <div class="tab-content" id="pills-tabContent">
                                        <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                                            aria-labelledby="pills-home-tab">
                                            <div class="row mb-2">
                                                <div class="col-lg-3 col-sm-6 col-12">
                                                    <div class="add-product">
                                                        <label class="form-label">Meta Title</label>
                                                        <input type="text" class="form-control" name="meta_title"
                                                            value="{{ $product->meta_title }}">
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6 col-12">
                                                    <div class="add-product list mb-3">
                                                        <label class="form-label">Meta Description</label>
                                                        <textarea rows="8" cols="5" class="form-control  h-100" name="meta_description"
                                                            placeholder="Enter text here">{{ $product->meta_description }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-sm-6 col-12">
                                                    <label class="form-label">Meta Image</label>
                                                    <div class="form-group">
                                                        <div class="row" id="meta_image">
                                                            <!-- You can include input fields or upload button for the meta image here -->
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-lg-2 col-sm-6 col-12">
                                                <div class="mb-3 add-product">
                                                    <label class="form-label">Old Meta Image</label>
                                                    <div class="form-group">
                                                        
                                                        <img src="{{image($product->meta_image)}}" alt="Avatar"   style="height:150px;width:150px">
                                                        
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
    
    
    
    


    <script>
    

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
      






            function generateSlug(name) {

                var pattern = /[a-zA-Z0-9\u0980-\u09FF]+/g;

                return name.toLowerCase().match(pattern).join('_');
            }


            // Event listener for name field
            $('#productName').on('input', function() {
                var name = $(this).val();
                var slug = name ? generateSlug(name) : null; // Generate slug only if name is not empty
                $('#productSlug').val(slug);
            });

            // Event listener for name field
            $('#name').on('input', function() {
                var name = $(this).val();
                var slug = name ? generateSlug(name) : null; // Generate slug only if name is not empty
                $('#slug').val(slug);
            });

            $('#updateProduct').submit(function(e) {
                e.preventDefault(); // Prevent the form from submitting normally
                var formData = new FormData(this);
                $(this).find('input, button, select, textarea').prop('disabled', true);

                // Make AJAX request
                $.ajax({
                    url: '{{ route('products.update', $product->id) }}',
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
            
            
            
            
            


            // $('#pages').imageUploader({
            //     uploadInputIcon: 'fas fa-upload',
            //     deleteImageIcon: 'fas fa-times',
            //
            //     label: 'Drag & Drop files here or click to browse',
            //     imagesInputName: 'pages_photos[]',
            //     preloadedInputName: 'preloaded'
            // });
            
            
            
              $(document).on('input', '#add-units-category #name', function () {
        var name = $(this).val();
        var slug = name ? generateSlug(name) : ''; // Generate slug only if name is not empty
        $('#add-units-category #slug').val(slug);
    });


            $('#pages').imageUploader({
                uploadInputIcon: 'fas fa-upload',
                deleteImageIcon: 'fas fa-times',
                label: 'Drag & Drop files here or click to browse',
                imagesInputName: 'pages_photos',
                preloadedInputName: 'preloaded',
                // Add validation logic
                onBeforeUpload: function(files) {
                    var isValid = true;
                    $.each(files, function(index, file) {
                        var fileName = file.name;
                        var ext = fileName.split('.').pop().toLowerCase();
                        if (!(ext === 'jpeg' || ext === 'png' || ext === 'jpg' || ext ===
                            'gif')) {
                            isValid = false;
                            return false; // Exit the loop early if any file is invalid
                        }
                    });
                    if (!isValid) {
                        alert('Please upload only JPEG, PNG, JPG, or GIF files.');
                    }
                    return isValid; // Return true if all files are valid, false otherwise
                }
            });




            $("#thumbnail").spartanMultiImagePicker({
                fieldName: 'thumb_image',
                maxCount: 1,
                rowHeight: '300px',
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
                var selectSimple = $('.select2AjaxCategory ');

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


        });
    </script>
@endsection
