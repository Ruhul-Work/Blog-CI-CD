<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\backend\Setting\HomeBannerController;
use App\Http\Controllers\backend\SliderController;
use App\Http\Controllers\backend\HomeCategoryController;
use App\Http\Controllers\backend\ReviewController;
use App\Http\Controllers\backend\Setting\CountryCityController;
use App\Http\Controllers\backend\DashboardController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/store', [HomeBannerController::class, 'store']);
Route::get('/home-cart', [HomeBannerController::class, 'viewHomeCart']);
Route::get('/home-cart-edit/{id}', [HomeBannerController::class, 'edit']);
Route::post('/home-cart-update/{id}', [HomeBannerController::class, 'editStore']);
Route::get('/home-cart-destroy/{id}', [HomeBannerController::class, 'destroy']);

Route::post('/home-banner-store', [HomeBannerController::class, 'homeStore']);

Route::get('/home-banner', [HomeBannerController::class, 'viewHomeBanner']);
Route::get('/home-banner-edit/{id}', [HomeBannerController::class, 'editHomeBanner']);
Route::post('/home-banner-update/{id}', [HomeBannerController::class, 'editStoreHomeBanner']);
Route::get('/home-banner-destroy/{id}', [HomeBannerController::class, 'homeBannerDestroy']);
Route::post('/sub-slider', [SliderController::class, 'subSliderStore']);
Route::get('/view-sub-slider', [SliderController::class, 'viewSubslider']);
Route::get('/sub-slider-edit/{id}', [SliderController::class, 'editSubSlider']);
Route::post('/sub-slider-update/{id}', [SliderController::class, 'editSubStore']);
Route::get('/sub-slider-destroy/{id}', [SliderController::class, 'subDestroy']);
//home category section routes
Route::get('/category-all', [HomeCategoryController::class, 'viewHomeCategory']);
Route::post('/home-category-store', [HomeCategoryController::class, 'store']);
Route::get('/category-destroy/{id}', [HomeCategoryController::class, 'categoryDestroy']);
// review section routes
Route::post('/store-review', [ReviewController::class, 'storeReview']);
Route::get('/all-review', [ReviewController::class, 'allReviews']);
Route::get('/single-review-edit/{id}', [ReviewController::class, 'singleReview']);
Route::post('/review-update/{id}', [ReviewController::class, 'reviewUpdate']);
Route::get('/review-destroy/{id}', [ReviewController::class, 'reviewDestroy']);
// geolocation api
Route::get('/country-list', [CountryCityController::class, 'countryList']);
Route::get('/single-country-edit/{id}', [CountryCityController::class, 'singleCountry']);
Route::post('/store-country', [CountryCityController::class, 'storeCountry']);
Route::post('/country-update/{id}', [CountryCityController::class, 'countryUpdate']);
Route::get('/country-destroy/{id}', [CountryCityController::class, 'countryDestroy']);
// city
Route::get('/city-list', [CountryCityController::class, 'cityList']);
Route::post('/store-city', [CountryCityController::class, 'createCity']);
Route::get('/single-city-edit/{id}', [CountryCityController::class, 'singleCity']);
Route::post('/city-update/{id}', [CountryCityController::class, 'cityUpdate']);
Route::get('/city-destroy/{id}', [CountryCityController::class, 'cityDestroy']);
// upazila list
Route::get('/upazila-list', [CountryCityController::class, 'upazilaList']);
Route::post('/store-upazila', [CountryCityController::class, 'storeUpazila']);
Route::get('/single-upazila-edit/{id}', [CountryCityController::class, 'singleUpazila']);
Route::post('/upazila-update/{id}', [CountryCityController::class, 'updateUpazila']);
Route::get('/upazila-destroy/{id}', [CountryCityController::class, 'upazilaDestroy']);
// dashboard api
Route::get('/dashboard', [DashboardController::class, 'dashboard']);
