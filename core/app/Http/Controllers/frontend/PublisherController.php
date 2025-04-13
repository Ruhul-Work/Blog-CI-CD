<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Category;
use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PublisherController extends Controller
{
    public function allPublishers()
    {
        return view('frontend.modules.publisher.all_publisher');
    }

    public function getPublishers()
    {
        $publishers = Publisher::where('status', 1)->paginate(60);

        $view = view('frontend.modules.publisher.publisher_design', ['publishers' => $publishers])->render();

        return response()->json(['html' => $view]);
    }


    public function publisherSingle($slug)
    {
        // Attempt to find the publisher by slug first
        $publisher = Publisher::findBySlug($slug);

        // If publisher is not found by slug and the input is numeric, try finding by ID
        if (!$publisher && is_numeric($slug)) {

            $publisher = Publisher::findOrFail((int)$slug); // Automatically throws 404 if not found

        }

        // If publisher not found, abort with 404
        if (!$publisher) {
            abort(404);
        }

        // Define cache key
        $cacheKey = 'publisher_' . $slug . '_products';

        // Check if the data is already cached
        $data = Cache::remember($cacheKey, 5, function () use ($publisher) {
            $products = collect();

            // Load products with relationships in chunks
            $publisher->products()->where('status', 1)->with(['categories:id,name', 'authors:id,name'])->chunk(1000, function ($chunk) use (&$products) {
                $products = $products->merge($chunk);
            });

            // Extract unique categories from products
            $categoryIds = $products->pluck('categories')->flatten()->pluck('id')->unique();
            $categories = Category::whereIn('id', $categoryIds)->pluck('name', 'id')->sort()->toArray();

            // Extract unique authors from products
            $authorIds = $products->pluck('authors')->flatten()->pluck('id')->unique();
            $authors = Author::whereIn('id', $authorIds)->pluck('name', 'id')->sort()->toArray();

            // Return the data to be cached
            return [
                'products' => $products,
                'categories' => $categories,
                'authors' => $authors,
            ];
        });

        // Extract the cached data
        $categories = $data['categories'];
        $authors = $data['authors'];

        // Pass category, authors, and products to the view
        return view('frontend.modules.publisher.publisher_products', [
            'publisher' => $publisher,
            'categories' => $categories,
            'authors' => $authors,
        ]);
    }


    public function getPublisherProducts(Request $request, $slug)
    {
        if (is_numeric($slug)) {
            $id = $slug;
            $publisher = Publisher::findOrFail($id);
        } else {
            $publisher = Publisher::findBySlug($slug);
        }


        if (!$publisher) {
            return response()->json(['error' => 'Publisher not found'], 404);
        }

        // Query products based on publisher
        $productsQuery = $publisher->products()->where('products.status', 1); // Adjust table name for 'status' as needed

        // Filter by category IDs if provided
        if ($request->has('categoryIds')) {
            $categoryIds = explode(',', $request->input('categoryIds'));
            $productsQuery->whereHas('categories', function ($query) use ($categoryIds) {
                $query->whereIn('categories.id', $categoryIds); // Adjust table name for 'id' as needed
            });
        }

        // Filter by author IDs if provided
        if ($request->has('authorIds')) {
            $authorIds = explode(',', $request->input('authorIds'));
            $productsQuery->whereHas('authors', function ($query) use ($authorIds) {
                $query->whereIn('authors.id', $authorIds); // Adjust table name for 'id' as needed
            });
        }

        // Ensure that products satisfy both categoryIds and authorIds conditions if both are provided
        if ($request->has('categoryIds') && $request->has('authorIds')) {
            $categoryIds = explode(',', $request->input('categoryIds'));
            $authorIds = explode(',', $request->input('authorIds'));

            $productsQuery->where(function ($query) use ($categoryIds, $authorIds) {
                $query->whereHas('categories', function ($q) use ($categoryIds) {
                    $q->whereIn('categories.id', $categoryIds); // Adjust table name for 'id' as needed
                })->whereHas('authors', function ($q) use ($authorIds) {
                    $q->whereIn('authors.id', $authorIds); // Adjust table name for 'id' as needed
                });
            });
        }

        // Sort products based on sortBy parameter
        $sortBy = $request->input('sortBy', 'latest');
        $productsQuery->applySorting($sortBy);

        // Get products with categories and authors eager loaded, and paginate
        $products = $productsQuery->with(['categories:id,name', 'authors:id,name'])->paginate(40);

        // Render the products view
        $view = view('frontend.modules.product.all_products', compact('products'))->render();

        // Return JSON response with rendered view
        return response()->json(['html' => $view]);
    }


    public function searchPublishers(Request $request)
    {
        $query = $request->input('query');

        if (!$query) {
            return response()->json(['publishers' => []]); // Return an empty array if query is empty
        }

        // Perform search logic and select specific columns
        $publishers = Publisher::where('name', 'like', '%' . $query . '%')
            ->where('status', 1)
            ->select('id', 'name', 'slug')
            ->get();

        return response()->json(['publishers' => $publishers]);
    }
}
