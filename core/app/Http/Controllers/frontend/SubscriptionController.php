<?php
namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\SubscriptionOrder;
use App\Models\SubscriptionPackage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SubscriptionController extends Controller
{
    public function index()
    {
        $packages = SubscriptionPackage::with('features')->where('status', true)->get();
        return view('frontend.modules.blogs.subscription', compact('packages'));

    }

    public function checkout($id)
    {
        $packages        = SubscriptionPackage::with('features')->where('status', true)->get();
        $selectedPackage = SubscriptionPackage::findOrFail($id);

        return view('frontend.modules.blogs.checkout', compact('packages', 'selectedPackage'));
    }

    

public function storeOrder(Request $request)
{
    $request->validate([
        'package_id'     => 'required|exists:subscription_packages,id',
        'payment_method' => 'required|string',
        'coupon_code'    => 'nullable|string',
    ]);

    DB::beginTransaction();

    try {
        $user = Auth::user();
        $package = SubscriptionPackage::findOrFail($request->package_id);

        // Initialize variables
        $discount = 0;
        $couponId = null;

        // Process the coupon code, if provided
        if ($request->coupon_code) {
            $coupon = Coupon::where('code', $request->coupon_code)->first();

            if ($coupon) {
                // Validate the coupon and usage
                $alreadyUsed = CouponUsage::where('user_id', $user->id)
                    ->where('coupon_id', $coupon->id)
                    ->exists();

                if ($alreadyUsed) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You have already used this coupon.',
                    ], 400);
                }

                if ($coupon->isValid($user->id)) {
                    // Calculate the discount
                    if ($coupon->discount_type === 'amount') {
                        $discount = min($coupon->discount, $package->current_price);
                    } elseif ($coupon->discount_type === 'percentage') {
                        $discount = ($coupon->discount / 100) * $package->current_price;
                    }

                    // Update coupon stock
                    if ($coupon->stock !== null && $coupon->stock > 0) {
                        $coupon->decrement('stock');
                    }

                    $couponId = $coupon->id;
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid or expired coupon code.',
                    ], 400);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid coupon code.',
                ], 404);
            }
        }

        // Calculate start and end dates for the subscription
        $startDate = Carbon::now()->startOfDay();
        $endDate = $startDate->copy()->addDays($package->duration);

        // Generate a unique order number
        $orderNumber = SubscriptionOrder::generateOrderNumber();

        // Save Subscription Order
        $order = SubscriptionOrder::create([
            'order_number'            => $orderNumber,
            'user_id'                 => $user->id,
            'subscription_package_id' => $package->id,
            'package_price'           => $package->current_price,
            'subtotal'                => $package->current_price - $discount,
            'total'                   => $package->current_price - $discount,
            'pay_method'              => $request->payment_method,
            'pay_amount'              => $package->current_price - $discount,
            'discount'                => $discount,
            'coupon_id'               => $couponId,
            'payment_status'          => 'Pending',
            'subscription_start_date' => $startDate,
            'end_date'                => $endDate,
        ]);

        // Record coupon usage
        if ($couponId) {
            CouponUsage::create([
                'user_id' => $user->id,
                'coupon_id' => $couponId,
                'code' => $request->coupon_code,
                'amount' => $discount,
                'total_used' => 1,
            ]);
        }

        DB::commit();

        return response()->json([
            'success'      => true,
            'message'      => 'Subscription order placed successfully!',
            'redirect_url' => route('create.bkash.payment', ['order_id' => $order->id]),
        ]);
    } catch (\Exception $e) {
        DB::rollBack();

        // Log the error for debugging
        \Log::error('Order Creation Error', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Failed to place the subscription order. Please try again.',
        ], 500);
    }
}

public function validateCoupon(Request $request)
{
    $couponCode = $request->input('coupon_code');
    $user = Auth::user();

    $coupon = Coupon::where(function ($query) use ($couponCode) {
        $query->where('code', $couponCode)
              ->orWhere('status', 1)
              ->orWhere('stock', 1);
    })->first();

    if (!$coupon) {
        return response()->json([
            'success' => false,
            'message' => 'কুপন কোডটি সঠিক নয় ।',
        ]);
    }

    // Check if the user has already used the coupon
    $alreadyUsed = CouponUsage::where('user_id', $user->id)
        ->where('coupon_id', $coupon->id)
        ->exists();

    if ($alreadyUsed) {
        return response()->json([
            'success' => false,
            'message' => 'আপনি ইতিমধ্যেই এই কুপনটি ব্যবহার করেছেন।',
        ]);
    }

    if (!$coupon->isValid($user->id)) {
        return response()->json([
            'success' => false,
            'message' => 'This coupon is not valid or expired.',
        ]);
    }

    // Calculate the discount and total
    $packagePrice = $request->input('package_price');
    $discount = 0;

    if ($coupon->discount_type === 'amount') {
        $discount = min($coupon->discount, $packagePrice);
    } elseif ($coupon->discount_type === 'percentage') {
        $discount = ($coupon->discount / 100) * $packagePrice;
    }

    $total = $packagePrice - $discount;

    return response()->json([
        'success' => true,
        'discount' => number_format($discount, 2),
        'total' => number_format($total, 2),
    ]);
}



}
