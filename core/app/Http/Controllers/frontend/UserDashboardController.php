<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    public function index()
    {
       
       
       if (!Auth::check()) {
        return redirect()->back()->with('error', 'প্রথমে লগইন করুন ড্যাশবোর্ড দেখার জন্য।');
    }
        $user = Auth::user();

        $orders = $user->orders()->get();


        $totalOrders = $user->orders()->count();
        $totalSpend = number_format($user->orders()->sum('total'), 0, '', ',');

        $onTheWayOrders = $user->orders()
            ->whereHas('status', function ($query) {
                $query->where('name', 'On the Way');
            })
            ->count();

        $deliveredOrders = $user->orders()
            ->whereHas('status', function ($query) {
                $query->where('name', 'Delivered');
            })
            ->count();

      
            return view('frontend.modules.dashboard.index', compact('orders', 'totalOrders', 'totalSpend', 'onTheWayOrders', 'deliveredOrders'));
        
    }

    public function track($id)
    {
        $order = Order::findOrFail($id);
        // You can add logic to fetch tracking information or view
        return view('frontend.modules.dashboard.track_order', compact('order'));
    }

    public function showOrderItems($id)
    {
        $order = Order::findOrFail($id);
        $items=$order->orderItems;

        return view('frontend.modules.dashboard.show_order_items', compact('order','items'));
    }
}
