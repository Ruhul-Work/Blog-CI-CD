<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Category;
use App\Models\CategorySection;
use App\Models\HomeBanner1;
use App\Models\HomeBanner2;
use App\Models\Product;
use App\Models\ProductSection;
use App\Models\Review;
use App\Models\ReviewSection;
use App\Models\Slider;
use App\Models\SubSlider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;


class HomeController extends Controller
{

    public  function index()
    {

        $tabSections=ProductSection::where('status',1)
            ->orderBy('sort_order', 'asc')
            ->take(5)
            ->get();
        $banners = HomeBanner1::where('status', 1)
            ->latest()
            ->take(4)
            ->get();
            
        $popUpBanner = HomeBanner2::where('status', 1)
    ->latest()
    ->first();



        $featuredCampaign = Campaign::where('status', 1)
            ->where('is_featured', 1)
            ->where('end_date', '>', now()) // Ensure the campaign's end date is in the future
            ->first();


        return view('frontend.modules.home.index',[
            'tabSections'=>$tabSections,
            'banners'=>$banners,
            'featuredCampaign'=>$featuredCampaign,
            'popUpBanner'=>$popUpBanner,
        ]);

    }
    public function sectionProduct(Request $request)
    {

        $id = $request->query('id');

        $section = ProductSection::find($id);


        if (!$section) {
            return response()->json(['error' => 'Product  not found'], 404);
        }

        if ($section->section_type=='recent') {
          // function in helper.php
            $products=productSection($section->section_type);

        }else{

            $products = $section->sectionProducts()->take(10)->get();
        }



        $content = view('frontend.modules.home.tab_products', ['products' => $products])->render();

        return response()->json(['content' => $content]);
    }

    public function getSectionContent(Request $request)
    {

        $section = $request->input('section');

        $content = '';

        switch ($section) {

            case 'slider-banner-section':

                $sliders = Slider::where('status', 1)
                    ->select(['id', 'name','image','url'])
                    ->get();

                $subSliders = SubSlider::latest()
                    ->take(2)
                    ->get();

                $content = view('frontend.modules.home.slider_banner',['sliders' =>$sliders,'subSliders'=>$subSliders])->render();
                break;


            case 'category-section':
                $cacheKey = 'categories';

                // Check if the categories are cached
               // if (!Cache::has($cacheKey)) {
                    // Get the sorted category IDs
                    $categoryIds = CategorySection::orderBy('sort_order')
                        ->pluck('category_id')
                        ->toArray();

                    $categoryIds = array_unique($categoryIds);

                    //Cache::forget($cacheKey);

                    // Fetch categories based on the sorted IDs
                    $categories = Category::whereIn('id', $categoryIds)
                        ->where('status', 1)
                        ->where('type', 'book')
//                        ->orderByRaw('FIELD(id, ' . implode(',', $categoryIds) . ')')
                        ->select(['id', 'name', 'icon', 'slug'])
                        ->get();

                //     Cache::put($cacheKey, $categories, now()->addMinutes(5));
                // } else {

                //     $categories = Cache::get($cacheKey);
                // }


                // Render the view with the categories
                $content = view('frontend.modules.home.category', ['categories' => $categories])->render();

                break;


            case 'stationary-section':

                $StationaryProducts=Product::where('product_type','stationary')
                    ->where('status', 1)
                    ->latest()
                    ->take(10)
                    ->get();

                $content = view('frontend.modules.home.stationary',['StationaryProducts' =>$StationaryProducts,])->render();
                break;

            case 'best-seller-section':
            //  public function in product model
                $bestSellers = Product::bestSeller(10);

                $content = view('frontend.modules.home.best_seller',['bestSellers' =>$bestSellers,])->render();
                break;


            case 'review-section':

                $reviewIds = ReviewSection::orderBy('sort_order')->pluck('review_id')->toArray();
               
                if($reviewIds){
                $reviews = Review::whereIn('id', $reviewIds)
                    ->orderByRaw('FIELD(id, ' . implode(',', $reviewIds) . ')')
                    ->get();

                $content = view('frontend.modules.home.review',['reviews' =>$reviews,])->render();
                }
                break;

            default:
                $content = '';
                break;
        }

        return response()->json(['content' => $content]);
    }






}
