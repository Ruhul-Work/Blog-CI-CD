<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Category;
use App\Models\Product;
use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

// Your class and method definitions go here

class SearchController extends Controller
{



    public function searchingAjax(Request $request)
    {
        if ($request->filled('search')) {

            $searchTerm = $request->input('search');

            $productType = $request->input('product_type');


            $query = $this->buildProductQuery($searchTerm, $productType);

            $products = $query->take(15)->get();

            $totalProducts = $query->count();


            $html = view('frontend.modules.search.search_results', [
                'products' => $products,
                'totalProducts' => $totalProducts,
                'searchTerm' => $searchTerm,
                'productType'=>$productType

            ])->render();

            return response()->json(['html' => $html]);
        }
        abort(404);
        //return response()->json([], 400);
    }

    public function searchSingleQuery(Request $request)
    {
        $searchTerm = $request->search;
        $productType = $request->productType ?? null;




        $query = $this->buildProductQuery($searchTerm, $productType);

        $products = $query->get();

        // Extract unique authors from products
        $authorIds = $products->pluck('authors')->flatten()->pluck('id')->unique();
        $authors = Author::whereIn('id', $authorIds)->pluck('name', 'id')->sort()->toArray();


        // Extract unique categories from products
        $categoryIds = $products->pluck('categories')->flatten()->pluck('id')->unique();
        $categories= Category::whereIn('id', $categoryIds)->pluck('name', 'id')->sort()->toArray();


        // Extract unique publishers from products
        $publisherIds = $products->pluck('publisher.id')->unique();
        $publishers = Publisher::whereIn('id', $publisherIds)->pluck('name', 'id')->sort()->toArray();

        return view('frontend.modules.search.search_products', [
            'search' => $searchTerm,
            'productType'=>$productType,
            'authors' => $authors,
            'categories'=>$categories,
            'publishers' => $publishers,
        ]);
    }
 public function getSearchProducts(Request $request, $search, $productType = null)
    {
        $searchTerm = $request->search;
        $productType = $request->productType ?? null;

        $query = $this->buildProductQuery($searchTerm, $productType);

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

    // public function getSearchProducts(Request $request, $search, $productType = null)
    // {
    //     $searchTerm = $request->search;
    //     $productType = $request->productType ?? null;

    //     $query = $this->buildProductQuery($searchTerm, $productType);

    //     // Filter by category IDs if provided
    //     if ($request->has('categoryIds')) {
    //         $categoryIds = explode(',', $request->input('categoryIds'));
    //         $query->whereHas('categories', function ($query) use ($categoryIds) {
    //             $query->whereIn('categories.id', $categoryIds);
    //         });
    //     }

    //     // Filter by author IDs if provided
    //     if ($request->has('authorIds')) {
    //         $authorIds = explode(',', $request->input('authorIds'));
    //         $query->whereHas('authors', function ($query) use ($authorIds) {
    //             $query->whereIn('authors.id', $authorIds);
    //         });
    //     }

    //     // Filter by publication IDs if provided
    //     if ($request->has('publicationIds')) {
    //         $publicationIds = explode(',', $request->input('publicationIds'));
    //         $query->whereIn('products.publisher_id', $publicationIds);
    //     }

    //     // Combined filters logic

    //     // If categoryIds and authorIds are provided
    //     if ($request->has('categoryIds') && $request->has('authorIds') && !$request->has('publicationIds')) {
    //         $categoryIds = explode(',', $request->input('categoryIds'));
    //         $authorIds = explode(',', $request->input('authorIds'));

    //         $query->whereHas('categories', function ($q) use ($categoryIds) {
    //             $q->whereIn('categories.id', $categoryIds);
    //         })->whereHas('authors', function ($q) use ($authorIds) {
    //             $q->whereIn('authors.id', $authorIds);
    //         });
    //     }

    //     // If categoryIds and publicationIds are provided
    //     if ($request->has('categoryIds') && !$request->has('authorIds') && $request->has('publicationIds')) {
    //         $categoryIds = explode(',', $request->input('categoryIds'));
    //         $publicationIds = explode(',', $request->input('publicationIds'));

    //         $query->whereHas('categories', function ($q) use ($categoryIds) {
    //             $q->whereIn('categories.id', $categoryIds);
    //         })->whereIn('products.publisher_id', $publicationIds);
    //     }

    //     // If authorIds and publicationIds are provided
    //     if (!$request->has('categoryIds') && $request->has('authorIds') && $request->has('publicationIds')) {
    //         $authorIds = explode(',', $request->input('authorIds'));
    //         $publicationIds = explode(',', $request->input('publicationIds'));

    //         $query->whereHas('authors', function ($q) use ($authorIds) {
    //             $q->whereIn('authors.id', $authorIds);
    //         })->whereIn('products.publisher_id', $publicationIds);
    //     }

    //     // If all three filters are provided
    //     if ($request->has('categoryIds') && $request->has('authorIds') && $request->has('publicationIds')) {
    //         $categoryIds = explode(',', $request->input('categoryIds'));
    //         $authorIds = explode(',', $request->input('authorIds'));
    //         $publicationIds = explode(',', $request->input('publicationIds'));

    //         $query->where(function ($query) use ($categoryIds, $authorIds, $publicationIds) {
    //             $query->whereHas('categories', function ($q) use ($categoryIds) {
    //                 $q->whereIn('categories.id', $categoryIds);
    //             })->whereHas('authors', function ($q) use ($authorIds) {
    //                 $q->whereIn('authors.id', $authorIds);
    //             })->whereIn('products.publisher_id', $publicationIds);
    //         });
    //     }

    //     // Sort products based on sortBy parameter
    //     $sortBy = $request->input('sortBy', 'latest');
    //     $query->applySorting($sortBy);

    //     // Get products with authors and publisher eager loaded, and paginate
    //     $products = $query->with(['authors:id,name', 'publisher:id,name'])->paginate(40);

    //     $view = view('frontend.modules.product.all_products', compact('products'))->render();

    //     // Return JSON response with rendered view
    //     return response()->json(['html' => $view]);
    // }




    // Private method to build product query
    private function buildProductQuery($searchTerm, $productType = null)
    {
        $query = Product::query();

        if ($productType) {
            $query->where('product_type', $productType);
        }

        $query->where('status', 1)
            ->where(function ($q) use ($searchTerm) {
                $q->where('bangla_name', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('english_name', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('searchable_data', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('description', 'LIKE', "%{$searchTerm}%");
            });

        $query->select('products.*')
            ->selectRaw("
                (
                    (CASE WHEN bangla_name LIKE '%{$searchTerm}%' THEN 1 ELSE 0 END) +
                    (CASE WHEN english_name LIKE '%{$searchTerm}%' THEN 1 ELSE 0 END) +
                    (CASE WHEN searchable_data LIKE '%{$searchTerm}%' THEN 1 ELSE 0 END) +
                    (CASE WHEN description LIKE '%{$searchTerm}%' THEN 1 ELSE 0 END)
                ) AS relevance_score
            ")
            ->orderByDesc('relevance_score');

        return $query;
    }
    
    




}
