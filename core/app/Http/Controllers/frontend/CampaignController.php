<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Campaign;
use App\Models\Product;
use App\Models\Publisher;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class CampaignController extends Controller
{
    public function allCampaigns()
    {
        return view('frontend.modules.campaign.all_campaign');
    }

    public function getCampaigns()
    {
        $now = Carbon::now();

        $campaigns = Campaign::where('status', 1)
            ->where('end_date', '>=', $now)
            ->paginate(15);

        $view = view('frontend.modules.campaign.campaign_design', ['campaigns' => $campaigns])->render();

        return response()->json(['html' => $view]);
    }

    public function campaignSingle($slug)
    {
        // Attempt to find the campaign by slug first
    $campaign = Campaign::findBySlug($slug);

    // If campaign is not found by slug and the input is numeric, try finding by ID
    if (!$campaign && is_numeric($slug)) {
        $campaign = Campaign::findOrFail((int)$slug); // Automatically throws 404 if not found
    }
        // If campaign not found, abort with 404
        if (!$campaign) {
            abort(404);
        }

        // Define cache key
        $cacheKey = 'campaign_' . $slug . '_products';

        // Check if the data is already cached
        $data = Cache::remember($cacheKey, 5, function () use ($campaign) {

            $products = collect();

            // Load products with authors and publisher relationships in chunks
            $campaign->products()->where('status', 1)->with(['authors:id,name', 'publisher:id,name'])->chunk(1000, function ($chunk) use (&$products) {
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
        $products = $data['products'];
        $authors = $data['authors'];
        $publishers = $data['publishers'];

        // Pass campaign, products, authors, and publishers to the view
        return view('frontend.modules.campaign.campaign_products', [
            'campaign' => $campaign,
            'products' => $products,
            'authors' => $authors,
            'publishers' => $publishers,
        ]);
    }


    public function getCampaignProducts(Request $request, $slug)
    {
        if (is_numeric($slug)) {
            $id = $slug;
            $campaign = Campaign::findOrFail($id);
        } else {
            $campaign = Campaign::findBySlug($slug);
        }


        if (!$campaign) {
            return response()->json(['error' => 'Campaign not found'], 404);
        }

        // Query products based on campaign
        $productsQuery = Product::whereHas('campaigns', function ($query) use ($campaign) {
            $query->where('campaigns.id', $campaign->id);
        })->where('products.status', 1);

        // Filter by author IDs if provided
        if ($request->has('authorIds')) {
            $authorIds = explode(',', $request->input('authorIds'));
            $productsQuery->whereHas('authors', function ($query) use ($authorIds) {
                $query->whereIn('authors.id', $authorIds);
            });
        }

        // Filter by publication IDs if provided
        if ($request->has('publicationIds')) {
            $publicationIds = explode(',', $request->input('publicationIds'));
            $productsQuery->whereIn('products.publisher_id', $publicationIds);
        }

        // Ensure that products satisfy both authorIds and publicationIds conditions if both are provided
        if ($request->has('authorIds') && $request->has('publicationIds')) {
            $authorIds = explode(',', $request->input('authorIds'));
            $publicationIds = explode(',', $request->input('publicationIds'));

            $productsQuery->where(function ($query) use ($authorIds, $publicationIds) {
                $query->whereHas('authors', function ($q) use ($authorIds) {
                    $q->whereIn('authors.id', $authorIds);
                })->whereIn('products.publisher_id', $publicationIds);
            });
        }

        // Sort products based on sortBy parameter
        $sortBy = $request->input('sortBy', 'latest');
        $productsQuery->applySorting($sortBy); // Assuming applySorting is a custom method to apply sorting

        // Get products with authors and publisher eager loaded, and paginate
        $products = $productsQuery->with(['authors:id,name', 'publisher:id,name'])->paginate(40);

        // Render the view with products
        $view = view('frontend.modules.product.all_products', compact('products'))->render();

        // Return JSON response with rendered view
        return response()->json(['html' => $view]);
    }

}
