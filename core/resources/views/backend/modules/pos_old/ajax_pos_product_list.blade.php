@forelse($products as $product)
    <div class="col-lg-6 col-xl-4 col-md-6 col-sm-6 col-12 p-0 product  "
        data-product='{
                "id": {{ $product->id }},
                "bangla_name": "{{ replaceQuotes($product->bangla_name) }}",
                   "english_name": "{{ replaceQuotes($product->english_name) }}",
                "current_price": {{priceAfterDiscount($product)}},
                "mrp_price": {{ $product->mrp_price }},
                "thumb_image": "{{image($product->thumb_image) }}",
               
                "quantity":1,
                "product_code": "{{ $product->product_code }}"
             }'>
        <div class="product-card position-relative shadow-sm">
            <span class="badge badge-danger product-stock-badge">{{ $product->stock ? $product->stock : '00' }}</span>
            <img src="{{ image($product->thumb_image) }}" alt="{{ $product->english_name }}"
                class="img-fluid product-image">
            <div class="product-detail">
                <div class="product-name">{{ $product->english_name }}</div>
                <div class="publication-name">{{ $product->publisher->name ?? '' }}</div>
                <div class="product-price">৳{{ $product->current_price }}</div>
            </div>
        </div>
    </div>

@empty
    <div class="col-12 justify-content-center">
        <div class="text-center mx-md-auto align-items-md-center mt-5 mb-5 col-12">
            <div class="mb-5">
                <h4>No products found</h4>
            </div>
        </div>
    </div>
@endforelse

<style>
    .product-stock-badge {
        position: absolute;
        top: 5px;
        right: 6px;
        padding: 3px 3px;
    }


    .product-card {
        padding-bottom: 10px;
        padding-top: 10px;
        margin: 10px;
        text-align: center;
        position: relative;
        background-color: white;
        border-radius: 10px;
        border: 1px solid #ffe7e7;
    }

    .product-image {
        height: 120px;
    }

    .product-detail {
        padding: 2px;
    }

    .product-name {
        font-size: 12px;
        margin-bottom: 2px;
        font-weight: bold;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
    }

    .publication-name {
        font-size: 10px;
    }

    .product-price {
        color: #4CAF50;
        font-size: 15px;
        font-weight: 600;

    }
</style>
