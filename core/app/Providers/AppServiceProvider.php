<?php
namespace App\Providers;

use App\Models\City;
use App\Models\Country;
use App\Models\Point;
use App\Models\Product;
use App\Models\Upazila;
use App\Observers\PointObserver;
use App\Services\MenuService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */

    public function boot(Request $request)
    {
        Schema::defaultStringLength(191);

        // Detect device type
        $userAgent = $request->header('User-Agent');
        $device    = preg_match('/(android|iphone|ipad|ipod|blackberry|mobile|opera mini|windows phone)/i', $userAgent)
        ? 'Mobile'
        : 'PC';

        View::share('device', $device);

        $menuItems = app(MenuService::class)->getMenuItems();
        View::share('menuItems', $menuItems);

        // Share data if not in API routes
        if (! $request->is('api/*')) {
            $countries    = Country::all();
            $cities       = City::all();
            $upazilas     = Upazila::all();
            $productTypes = Product::distinct()->pluck('product_type');
            View::share('productTypes', $productTypes);

            View::share('countries', $countries);
            View::share('cities', $cities);
            View::share('upazilas', $upazilas);

            // In a Service Provider or dedicated ViewComposer class

            // Register the Point observer
            Point::observe(PointObserver::class);

        }
    }

}
