<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{

    public function productDetails($slug_or_id)
    {



       // Try to find the product by slug first
    $product = Product::findBySlug($slug_or_id);

    // If product is not found by slug and the input is numeric, try finding by ID
    if (!$product && is_numeric($slug_or_id)) {
        $product = Product::findOrFail((int)$slug_or_id); // Automatically throws 404 if not found
    }

    // If product is still not found, abort with 404
    if (!$product) {
        abort(404, 'Product not found');
    }

if($product->product_type =='book')
{
    return view('frontend.modules.product.single_book_detail', ['product' => $product]);
}else{
    return view('frontend.modules.product.stationary_detail', ['product' => $product]);
}


    }


    public function fetchRelatedProducts(Product $product)
    {

        $categoryIds = $product->categories->pluck('id')->toArray();


        $relatedProducts = Product::whereHas('categories', function ($query) use ($categoryIds) {
            $query->whereIn('categories.id', $categoryIds);
        })
            ->where('products.id', '!=', $product->id)
            ->take(8)
            ->get();


         $html= view('frontend.modules.product.related_products', compact('relatedProducts'))->render();

         return response($html);
    }




    public function productAuthorBooks(Product $product)
    {

        $relatedAuthorProducts = collect();

        foreach ($product->authors as $author) {

            $relatedAuthorProducts = $relatedAuthorProducts->merge($author->products()->where('products.id', '!=', $product->id)->take(10)->get());
        }

        $html = view('frontend.modules.product.product_author_books', compact('relatedAuthorProducts'))->render();

        return response($html);
    }


    public function latestProduct()
    {
        $latestProducts = Product::latest()
            ->take(10)
            ->get();

        $html = view('frontend.modules.product.latest_product', compact('latestProducts'))->render();

        return response($html);
    }
    



public function getProductImages($id)
{
    // Fetch the product by ID, or fail if not found
    $product = Product::findOrFail($id);

    // Assuming your Product model has a 'pages' relation that contains image URLs
    // $images = $product->pages;
    
    $images = $product->pages()->orderBy('id', 'asc')->get();

    // Map the images to return the URL and optional alt text or any other data
    $imagesData = $images->map(function ($page) {
        return [
            'url' => asset($page->pages_photos), // Assuming 'pages_photos' contains the image path
            'alt_text' => 'Page image', // You can customize this if you have an alt text in your database
        ];
    });

    // Return the images data as a JSON response
    return response()->json($imagesData);
}





}
