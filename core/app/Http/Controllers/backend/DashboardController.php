<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Product;
use App\Models\SubscriptionOrder;
use App\Models\Blog;
use Illuminate\Support\Facades\DB;


class DashboardController extends Controller
{


    public function dashboard()
    {
        $todayOrders = SubscriptionOrder::whereDate('created_at', Carbon::today())->count();

        $totalOrders = SubscriptionOrder::where('payment_status','paid')->count();
        // $totalOrders = 150000000;
        $todaySales = SubscriptionOrder::whereDate('created_at', Carbon::today())->sum('total');
        $totalSales = SubscriptionOrder::sum('total');

        // $totalSales =1500000;
        $pendingOrders = SubscriptionOrder::where('payment_status', 'pending')->count();
        $completeOrders = SubscriptionOrder::where('payment_status', 'paid')->count();
        $recentProducts = Blog::latest()
            ->limit(10)
            ->get(['id', 'title', 'thumbnail'])
            ->map(function ($blogs) {
                return [
                    'title' => $blogs->title,
                    'thumbnail' => $blogs->thumbnail,
                ];
            });
        // $lowStockProducts = Product::where('stock', '<', 10)
        //     ->take(10)
        //     ->get(['bangla_name', 'current_price', 'stock', 'thumb_image'])
        //     ->map(function ($product) {
        //         return [
        //             'bangla_name' => $product->bangla_name,
        //             'current_price' => $product->current_price,
        //             'stock' => $product->stock,
        //             'thumb_image' => $product->thumb_image,
        //         ];
        //     });
        $currentYear = date('Y');
        // Retrieve monthly sales and orders in one query
        // $monthlyData = SubscriptionOrder::selectRaw('MONTH(created_at) as month COUNT(*) as total_orders')
        //     ->whereYear('created_at', $currentYear)

        //     ->groupBy('month')
        //     ->orderBy('month')
        //     ->get()
        //     ->keyBy('month');

        // $monthlySales = [];
        // $monthlyOrders = [];

        // if ($monthlyData->isNotEmpty()) {
        //     $monthlySales = $monthlyData->mapWithKeys(function ($data) {
        //         return [$data->month => $data->total_sales ?? 0];
        //     })->toArray();

        //     $monthlyOrders = $monthlyData->mapWithKeys(function ($data) {
        //         return [$data->month => $data->total_orders ?? 0];
        //     })->toArray();
        // }

        $monthlyData = DB::table('subscription_orders')
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as total_orders')
            ->whereYear('created_at', 2025)

            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        $monthlySales = [];
        $monthlyOrders = [];

        if ($monthlyData->isNotEmpty()) {
            $monthlySales = $monthlyData->mapWithKeys(function ($data) {
                return [$data->month => $data->total_sales ?? 0];
            })->toArray();

            $monthlyOrders = $monthlyData->mapWithKeys(function ($data) {
                return [$data->month => $data->total_orders ?? 0];
            })->toArray();
        }


        // $monthlySales = $monthlyData->mapWithKeys(fn($data) => [$data->month => $data->total_sales])->toArray();
        // $monthlyOrders = $monthlyData->mapWithKeys(fn($data) => [$data->month => $data->total_orders])->toArray();



        $topSellingPackages = DB::table('subscription_packages as sp')
            ->select('sp.id', 'sp.title', DB::raw('COUNT(so.subscription_package_id) as total_orders'))
            ->leftJoin('subscription_orders as so', 'sp.id', '=', 'so.subscription_package_id')
            ->groupBy('sp.id', 'sp.title')
            ->orderBy('total_orders', 'DESC')
            ->take(5) // Retrieves the top 5 packages
            ->get(); // Fetches multiple rows

        // Output the result


        return response()->json([
            'todayOrders' => $todayOrders,
            'totalOrders' => $totalOrders,
            'todaySales' => $todaySales,
            'totalSales' => $totalSales,
            'recentProducts' => $recentProducts,
            'pendingOrders' => $pendingOrders,
            'completeOrders' => $completeOrders,
            'monthlySales' => $monthlySales,
            'monthlyOrders' => $monthlyOrders,
            'topSellingPackages' => $topSellingPackages,
        ]);
    }



    //    public function dashboard()
    //    {
    //        $todayOrders = Order::whereDate('created_at', Carbon::today())->count();
    //        $totalOrders = Order::count();
    //
    //        $todaySales = Order::whereDate('created_at', Carbon::today())->sum('total');
    //        $totalSales = Order::sum('total');
    //        $pendingOrders = Order::where('order_status_id', 1)->count();
    //        $completeOrders = Order::where('order_status_id', 7)->count();
    //
    //        $recentProducts = Product::latest()
    //            ->limit(10)
    //            ->with('pages')
    //            ->get(['bangla_name', 'current_price', 'thumb_image'])
    //            ->map(function ($product) {
    //                return [
    //                    'bangla_name' => $product->bangla_name,
    //                    'current_price' => $product->current_price,
    //                    'thumb_image' => $product->thumb_image,
    //                ];
    //            });
    //
    //        $lowStockProducts = Product::where('stock', '<', 10)
    //            ->take(10)
    //            ->get(['bangla_name', 'current_price', 'stock', 'thumb_image'])
    //            ->map(function ($product) {
    //                return [
    //                    'bangla_name' => $product->bangla_name,
    //                    'current_price' => $product->current_price,
    //                    'stock' => $product->stock,
    //                    'thumb_image' => $product->thumb_image,
    //                ];
    //            });
    //
    //        // Return JSON response
    //        return response()->json([
    //            'todayOrders' => $todayOrders,
    //            'totalOrders' => $totalOrders,
    //            'todaySales' => $todaySales,
    //            'totalSales' => $totalSales,
    //            'recentProducts' => $recentProducts,
    //            'lowStockProducts' => $lowStockProducts,
    //            'pendingOrders' => $pendingOrders,
    //            'completeOrders' => $completeOrders,
    //        ]);
    //    }
}
