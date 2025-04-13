<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Author;
use App\Models\Publisher;
use Illuminate\Http\Request;

class ShopController extends Controller
{
   public  function shopIndex()
   {

       return view('frontend.modules.shop.shop_products');
   }

    public function getShopFilter(Request $request)
    {
        // Fetch categories, authors, and publishers where status is 1
        $categories = Category::where('status', 1)->where('type','book')->pluck('name', 'id')->sort()->toArray();
        $authors = Author::where('status', 1)->pluck('name', 'id')->sort()->toArray();
        $publishers = Publisher::where('status', 1)->pluck('name', 'id')->sort()->toArray();

        // Render the view with the data
        $html = view('frontend.modules.shop.get_filter_option', compact('categories', 'authors', 'publishers'))->render();

        // Return the HTML as JSON response
        return response()->json(['html' => $html]);
    }


    public function getShopProducts(Request $request)
    {


        $query = Product::where('status', 1)
            ->where('product_type', 'book');
        

        // Extract and filter conditions
        $categoryIds = $request->has('categoryIds') ? explode(',', $request->input('categoryIds')) : [];
        $authorIds = $request->has('authorIds') ? explode(',', $request->input('authorIds')) : [];
        $publicationIds = $request->has('publicationIds') ? explode(',', $request->input('publicationIds')) : [];

        if (!empty($categoryIds)) {
            $query->whereHas('categories', function ($q) use ($categoryIds) {
                $q->whereIn('categories.id', $categoryIds);
            });
        }

        if (!empty($authorIds)) {
            $query->whereHas('authors', function ($q) use ($authorIds) {
                $q->whereIn('authors.id', $authorIds);
            });
        }

        if (!empty($publicationIds)) {
            $query->whereIn('products.publisher_id', $publicationIds);
        }

        // Sort products based on sortBy parameter
        $sortBy = $request->input('sortBy', 'latest');
         $query->applySorting($sortBy);

        // Get products with authors and publisher eager loaded, and paginate
        $products = $query->with([ 'categories:id,name','authors:id,name', 'publisher:id,name'])->paginate(40);

        $view = view('frontend.modules.product.all_products', compact('products'))->render();

        // Return JSON response with rendered view
        return response()->json(['html' => $view]);
    }

}
