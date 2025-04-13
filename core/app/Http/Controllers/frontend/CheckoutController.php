<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\Order;
use App\Models\SubscriptionOrder;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderPlaced;



class CheckoutController extends Controller

{


    protected $orderService;
    protected $cartService;


    public function __construct(OrderService $orderService, CartService $cartService)
    {
        $this->orderService = $orderService;
        $this->cartService = $cartService;
    }

    public function orderCheckOutForm()
    {

        // Fetch cart data from session
        $cartItems = $this->cartService->getCart();

        if (empty($cartItems)) {
            session()->flash('message', 'আপনার কার্ট খালি। ক্রয় করতে হলে প্রথমে পণ্য যোগ করুন।');
            return redirect()->back();
        }


        // Use the CartService to calculate the subtotal
        $subtotal = $this->cartService->getTotal();

        $discountInfo = $this->cartService->getDiscount();

        $totalDiscountAmount = $discountInfo['total_discount_amount'];
    

        $cashPaymentMethods=PaymentMethod::where('type','Cash')->where('status',1)->get();
        $mfsPaymentMethods=PaymentMethod::where('type','MFS')->where('status',1)->get();




        return view('frontend.modules.checkout.order_form',[
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
            'totalDiscountAmount'=>$totalDiscountAmount,
            'cashPaymentMethods'=>$cashPaymentMethods,
            'mfsPaymentMethods'=>$mfsPaymentMethods,

        ]);
    }






public function storeWebsiteOrder(Request $request)
{
    $result = $this->orderService->storeWebsiteOrder($request);

    if ($result['status'] === 'error') {
        return redirect()->route('home')->with('error', $result['message']);
    }

    if ($result['status'] === 'redirect') {
        return redirect()->route($result['route'], $result['orderId']);
    }

    if ($result['status'] === 'view') {
        return view($result['view'], $result['data'])->with('message', $result['message']);
    }

    // For 'success' status
    return $this->finalizeOrder($request, $result['order']);
}

// protected function finalizeOrder($request, $order)
// {
//     if ($request->has('email') && filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
//     Mail::to($request->email)->send(new OrderPlaced($order));
// }


//     return redirect()->route('checkout.orders.complete', $order->id)
//         ->with('message', 'অর্ডার সফলভাবে সম্পন্ন হয়েছে!');
// }


  protected function finalizeOrder($request, $order)
    {
        if ($request->has('email') && filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            Mail::to($request->email)->send(new OrderPlaced($order));
        }


        return redirect()->route('checkout.orders.complete', encrypt($order->id))
            ->with('message', 'অর্ডার সফলভাবে সম্পন্ন হয়েছে!');
    }
    
    // public function showSuccessOrder(Order $order)

    // {


    //     //dd($order);
    //     return view('frontend.modules.checkout.order_success', compact('order'))->with('message', 'Order placed successfully!');
    // }
    
       public function showSuccessOrder($id)

    {

        $Id = decrypt($id);

        $order = SubscriptionOrder::find($Id);

        return view('frontend.modules.checkout.order_success', compact('order'))->with('message', 'Order placed successfully!');
    }


    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string|max:255',
        ]);

        // Validate the coupon code
        $coupon = $this->validateCoupon($request->coupon_code);

        if ($coupon) {
//            $userId = auth()->user()->id;
//
//            // Validate coupon usage by the user
//            try {
//                $this->validateCouponUsage($userId, $coupon->id);
//            } catch (Exception $e) {
//                return response()->json(['success' => false, 'message' => $e->getMessage()]);
//            }

            // Calculate coupon discount
            $cartSubtotal = $this->cartService->getTotal();
            $cartItems = $this->cartService->getCart();

            $result = calculateCouponDiscount($cartSubtotal, $coupon, $cartItems);

            if (isset($result['error'])) {

                return response()->json(['success' => false, 'message' => $result['error']], $result['status']);
            }

            $couponDiscount = $result['discount_amount'];

            // Store the coupon discount and ID in the session
            Session::put('couponDiscount', $couponDiscount);
            Session::put('couponId', $coupon->id);

            return response()->json(['success' => true, 'couponDiscount' => $couponDiscount]);
        }

        return response()->json(['success' => false, 'message' => 'Invalid coupon code']);
    }


    public function removeCoupon(Request $request)
    {


        if (Session::has('couponId')) {

            Session::forget(['couponId','couponDiscount']);

            return response()->json([
                'success' => true,
                'message' => 'Coupon removed successfully!',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No coupon found to remove.',
        ]);
    }


    private function validateCoupon($couponCode)
    {
        $code = Coupon::where('code', $couponCode)
             ->where('status',1)
            ->where('end_date', '>=', now())
            ->first();

        return $code;
    }


    private function validateCouponUsage($userId, $couponId)
    {

        $coupon = Coupon::findOrFail($couponId);

        $userCouponUsageCount = CouponUsage::where('user_id', $userId)
            ->where('coupon_id', $couponId)
            ->count();

        if ($userCouponUsageCount >= $coupon->individual_max_use) {
            throw new Exception('You have exceeded the maximum usage limit for this coupon.');
        }

        return true;
    }









}
