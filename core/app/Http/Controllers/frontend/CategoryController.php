<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Category;
use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;


class CategoryController extends Controller
{




    public function allCategories()
    {
        return view('frontend.modules.category.all_category');
    }

    public function getCategories()
    {
        $categories = Category::where('status', 1)->where('type', 'book') ->paginate(60);

        $view = view('frontend.modules.category.category_design', ['categories' => $categories])->render();

        return response()->json(['html' => $view]);
    }


    public function categorySingle($slug)
    {




    $category = Category::findBySlug($slug);

    // If category is not found by slug and the input is numeric, try finding by ID
    if (!$category && is_numeric($slug)) {
        $category = Category::findOrFail((int)$slug); // Automatically throws 404 if not found
    }



        // If category not found, abort with 404
        if (!$category) {
            abort(404);
        }


         if($category->type=='book'){
             $cacheKey = 'category_' . $slug . '_products';
             $data = Cache::remember($cacheKey, 5, function () use ($category) {

                 $products = collect();

                 // Load products with authors and publisher relationships in chunks
                 $category->products()->where('status', 1)->with(['authors:id,name', 'publisher:id,name'])->chunk(1000, function ($chunk) use (&$products) {

                     $products = $products->merge($chunk);
                 });




                 // Extract unique authors from products
                 $authorIds = $products->pluck('authors')->flatten()->pluck('id')->unique();
                 $authors = Author::whereIn('id', $authorIds)->pluck('name', 'id')->sort()->toArray();


                 // Extract unique publishers from products
                 $publisherIds = $products->pluck('publisher.id')->unique();
                 $publishers = Publisher::whereIn('id', $publisherIds)->pluck('name', 'id')->sort()->toArray();

                 // Return the data to be cached
                 return [
                     'products' => $products,
                     'authors' => $authors,
                     'publishers' => $publishers,
                 ];
             });

             // Extract the cached data
             $authors = $data['authors'];
             $publishers = $data['publishers'];


             // Pass category, authors, and publishers to the view
             return view('frontend.modules.category.category_products', [
                 'category' => $category,
                 'authors' => $authors,
                 'publishers' => $publishers,

             ]);

         }else{
             $products = collect();

             // Load products with authors and publisher relationships in chunks
             $category->products()->where('status', 1)->with([ 'publisher:id,name'])->chunk(1000, function ($chunk) use (&$products) {

                 $products = $products->merge($chunk);
             });

             // Extract unique publishers from products
             $publisherIds = $products->pluck('publisher.id')->unique();
             $publishers = Publisher::whereIn('id', $publisherIds)->pluck('name', 'id')->sort()->toArray();


             return view('frontend.modules.stationary.category_products', [

                 'category' => $category,
                 'publishers' => $publishers,

             ]);
         }




    }


    public function getCategoryProducts(Request $request, $slug)
    {
        if (is_numeric($slug)) {
            $id = $slug;
            $category = Category::findOrFail($id);
        } else {
            $category = Category::findBySlug($slug);
        }


        if (!$category) {
            return response()->json(['error' => 'Category not found'], 404);
        }

        // Query products based on category


        $productsQuery = $category->products()->where('products.status', 1); // Specify table name for 'status'


        // Filter by author IDs if provided
        if ($request->has('authorIds')) {
            $authorIds = explode(',', $request->input('authorIds'));
            $productsQuery->whereHas('authors', function ($query) use ($authorIds) {
                $query->whereIn('authors.id', $authorIds); // Specify table name for 'id'
            });
        }

        // Filter by publication IDs if provided
        if ($request->has('publicationIds')) {
            $publicationIds = explode(',', $request->input('publicationIds'));
            $productsQuery->whereIn('products.publisher_id', $publicationIds); // Specify table name for 'publisher_id'
        }

        // Ensure that products satisfy both authorIds and publicationIds conditions if both are provided
        if ($request->has('authorIds') && $request->has('publicationIds')) {

            $authorIds = explode(',', $request->input('authorIds'));
            $publicationIds = explode(',', $request->input('publicationIds'));

            $productsQuery->where(function ($query) use ($authorIds, $publicationIds) {
                $query->whereHas('authors', function ($q) use ($authorIds) {
                    $q->whereIn('authors.id', $authorIds); // Specify table name for 'id'
                })->whereIn('products.publisher_id', $publicationIds); // Specify table name for 'publisher_id'
            });
        }

        // Sort products based on sortBy parameter
        $sortBy = $request->input('sortBy', 'latest');

        $productsQuery->applySorting($sortBy);

        // Get products with authors and publisher eager loaded, and paginate
        $products = $productsQuery->with(['authors:id,name', 'publisher:id,name'])->paginate(40);


        $view = view('frontend.modules.product.all_products', compact('products'))->render();

        // Return JSON response with rendered view
        return response()->json(['html' => $view]);
    }

    public function searchCategories(Request $request)
    {
        $query = $request->input('query');

        if (!$query) {
            return response()->json(['categories' => []]); // Return an empty array if query is empty
        }

        // Perform search logic and select specific columns
        $categories = Category::where('name', 'like', '%' . $query . '%')
            ->where('status', 1)
            ->where('type', 'book')
            ->select('id', 'name', 'slug')
            ->get();

        return response()->json(['categories' => $categories]);
    }



}
