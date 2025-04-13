<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Category;
use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AuthorController extends Controller


{



    public function allAuthors()
    {


        return view('frontend.modules.author.all_author');
    }



    public function getAuthors()
    {

        $authors = Author::where('status', 1)->paginate(60);

        $view= view('frontend.modules.author.author_design',['authors'=>$authors])->render();

        return response()->json(['html'=>$view]);
    }




    public function authorSingle($slug)
    {


      
        $author = Author::findBySlug($slug);

        if (!$author && is_numeric($slug)) {
            $id = (int)$slug;
            $author = Author::findOrFail($id);
        }


        // If  not found, abort with 404
        if (!$author) {
            abort(404);
        }

        // Define cache key
        $cacheKey = 'author_' . $slug . '_products';

        // Check if the data is already cached
        $data = Cache::remember($cacheKey, 5, function () use ($author) {

            $products = collect();

            // Load products with authors and publisher relationships in chunks
           $author->products()->where('status', 1)->with(['categories:id,name', 'publisher:id,name'])->chunk(1000, function ($chunk) use (&$products) {

                $products = $products->merge($chunk);
            });




            // Extract unique authors from products
            $categoryIds = $products->pluck('categories')->flatten()->pluck('id')->unique();
            $categories= Category::whereIn('id', $categoryIds)->pluck('name', 'id')->sort()->toArray();


            // Extract unique publishers from products
            $publisherIds = $products->pluck('publisher.id')->unique();

            $publishers = Publisher::whereIn('id', $publisherIds)->pluck('name', 'id')->sort()->toArray();

            // Return the data to be cached
            return [
                'products' => $products,
                'categories' => $categories,
                'publishers' => $publishers,
            ];
        });

        // Extract the cached data
        $categories = $data['categories'];
        $publishers = $data['publishers'];

        // Pass category, authors, and publishers to the view
        return view('frontend.modules.author.author_products', [
            'author' => $author,
            'categories' => $categories,
            'publishers' => $publishers,

        ]);
    }

    public function getAuthorProducts(Request $request, $slug)
    {



        if(is_numeric($slug)) {
            $id = $slug;
            $author = Author::findOrFail($id);
        } else {
            $author = Author::findBySlug($slug);
        }


        if (!$author) {
            return response()->json(['error' => 'author not found'], 404);
        }

        // Query products based on category
        $productsQuery = $author->products()->where('products.status', 1); // Specify table name for 'status'



        // Filter by author IDs if provided
        if ($request->has('categoryIds')) {
            $categoryIds = explode(',', $request->input('categoryIds'));
            $productsQuery->whereHas('categories', function ($query) use ($categoryIds) {
                $query->whereIn('categories.id', $categoryIds); // Specify table name for 'id'
            });
        }

        // Filter by publication IDs if provided
        if ($request->has('publicationIds')) {
            $publicationIds = explode(',', $request->input('publicationIds'));
            $productsQuery->whereIn('products.publisher_id', $publicationIds); // Specify table name for 'publisher_id'
        }

        // Ensure that products satisfy both authorIds and publicationIds conditions if both are provided
        if ($request->has('categoryIds') && $request->has('publicationIds')) {

            $categoryIds = explode(',', $request->input('categoryIds'));
            $publicationIds = explode(',', $request->input('publicationIds'));

            $productsQuery->where(function ($query) use ($categoryIds, $publicationIds) {
                $query->whereHas('categories', function ($q) use ($categoryIds) {
                    $q->whereIn('categories.id', $categoryIds); // Specify table name for 'id'
                })->whereIn('products.publisher_id', $publicationIds);
            });
        }

        // Sort products based on sortBy parameter
        $sortBy = $request->input('sortBy', 'latest');

        $productsQuery->applySorting($sortBy);

        // Get products with authors and publisher eager loaded, and paginate
        $products = $productsQuery->with(['categories:id,name', 'publisher:id,name'])->paginate(40);


        $view = view('frontend.modules.product.all_products', compact('products'))->render();

        // Return JSON response with rendered view
        return response()->json(['html' => $view]);
    }




    public function searchAuthors(Request $request)
    {
        $query = $request->input('query');

        if (!$query) {
            return response()->json(['authors' => []]); // Return an empty array if query is empty
        }

        // Perform your search logic here and select only specific columns
        $authors = Author::where('name', 'like', '%' . $query . '%')
            ->where('status',1)
            ->select('id', 'name', 'slug')
            ->get();



        return response()->json(['authors' => $authors]);
    }


}
