<?php


namespace App\Services;

use App\Mail\OrderPlaced;
use App\Models\City;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\Order;
use App\Models\OrderShipping;
use App\Models\OrderItem;
use App\Models\OrderTransaction;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\Stock;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use function Ramsey\Collection\Map\put;


class OrderService
{
    protected $cartService;




    public function __construct(CartService $cartService ) {
        $this->cartService = $cartService;

    }


    public function posOrderStore($request)
    {
         $request->validate([
            'name' => 'required',
            'phone' => ['required', 'regex:/01[2-9]\d{8}$/'],
            'address' => 'required',
        ]);

        return DB::transaction(function () use ($request) {

            $user = User::where('phone', $request->phone)->firstOrFail();

            $cartItems = json_decode($request->input('cart_data'), true);

            $itemTotal = $this->sanitizeInput($request->input('itemTotal'));

            $shipping = $this->sanitizeInput($request->input('shipping'));
            $packingCharge = $this->sanitizeInput($request->input('packing_charge'));
            $couponDiscount = $this->sanitizeInput($request->input('coupon_discount'));
            $discount = $this->sanitizeInput($request->input('discount'));
            $total = $this->sanitizeInput($request->input('total'));

            $totalValue = round($total);
            $adjustAmount = $totalValue - $total;

            $totalProductQuantity = $this->calculateTotalProductQuantity($cartItems);

            $couponId = Session::get('coupon_id', null);

            $couponDiscountAmount = Session::get('couponDiscountAmount', 0);

            $order = new Order();
            $order->user_id = $user->id;
            $order->discount_amount = $discount;


            $order->coupon_discount = $couponDiscount;

            if ($couponId !== null && is_numeric($couponDiscountAmount)) {
                $order->coupon_id = $couponId;
                $order->coupon_discount = $couponDiscountAmount;

                $this->updateCouponUsageAndStock($user->id, $couponId,  $couponDiscountAmount);
            }



            $order->subtotal = $itemTotal;
            $order->shipping_charge = $shipping;
            $order->packing_charge = $packingCharge;
            $order->total = $totalValue;
            $order->adjust_amount = $adjustAmount;

            $order->sale_date = $request->sale_date ? Carbon::parse($request->sale_date)->format('Y-m-d') : Carbon::now()->format('Y-m-d');
            $order->quantity = $totalProductQuantity;
            $order->tax = 0;
            $order->order_type = $user->user_type == 'shop' ? 'wholesale' : 'retail';
            $order->payment_status = 'unpaid';
            $order->order_status_id = 1;
            $order->source = 'pos';
            $order->save();

            $this->processOrderTransactions($order);
            $this->saveOrderItems($order, $cartItems);
            $this->createOrUpdateOrderShipping($order, $request);
            $this->saveStockInfo($order, $cartItems);

            return redirect()->route('orders.show', $order->id);
        });
    }


    private function sanitizeInput($input)
    {
        return floatval(preg_replace('/[^0-9.]/', '', $input));
    }


    private function calculateTotalProductQuantity($cart)
    {
        $totalProductQuantity = 0;
        foreach ($cart as $product) {
            $totalProductQuantity += $product['quantity'];
        }
        return $totalProductQuantity;
    }






public function storeWebsiteOrder($request)
{
   $request->validate([
        'location_type' => 'required',
        'paymentMethod' => 'required',
        'shippingCharge' => 'required|numeric',
        'name' => 'required|string|max:255',
        'phone' => [
            'required',
            'string',
            'regex:/^(?:\+88|88)?(01[3-9]\d{8})$/'
        ],
        'alternate_phone' => [
            'nullable',
            'string',
            'regex:/^(?:\+88|88)?(01[3-9]\d{8})$/'
        ],
        'email' => 'nullable|string|email|max:255',
        'division_id' => 'required|integer|exists:divisions,id',
        'city_id' => 'required|integer|exists:cities,id',
        'upazila_id' => 'required|integer|exists:upazilas,id',
        'union_id' => 'nullable|integer|exists:unions,id',
        'address' => 'required|string|max:255',
        'customer_note' => 'nullable|string|max:255',
    ]);

  
   return DB::transaction(function () use ($request) {

        // Determine the user
        $user = Auth::check() ? Auth::user() : $this->storeCustomer($request);

      // Check if the user is an admin and not logged in
        // if (!Auth::check() && $user->is_admin == 1) {
        //     return [
        //         'status' => 'error',
        //         'message' => 'You are not allowed to make an order. Please Login'
        //     ];
        // }

        // Check if user is valid; handle errors if user creation failed
        if (is_array($user) && $user['status'] === 'error') {
            return [
                'status' => 'error',
                'message' => $user['message']
            ];
        }

        // Fetch cart items
        $cartItems = $this->cartService->getCart();
        if (empty($cartItems)) {
            return [
                'status' => 'error',
                'message' => 'Your cart is empty. Please add items to your cart before placing an order.'
            ];
        }

        // Calculate totals and discounts
        $itemTotal = $this->cartService->getTotal();
        $discountInfo = $this->cartService->getDiscount();
        $itemDiscountAmount = $discountInfo['total_discount_amount'];

        $couponId = Session::get('couponId', null);
        $couponDiscountAmount = Session::get('couponDiscount', 0);

        $paymentMethodId = $request->paymentMethod;
        
    
        
       switch ($paymentMethodId) {
    case 1: // Cash on delivery
        $shippingCharge =getCartWeightBasedShipping();
        $order_status_id=1;
        break;
    case 2: // bKash
        $shippingCharge = $request->shippingCharge;
        $order_status_id=8;
        break;
    default: // Other payment methods
        $shippingCharge =getCartWeightBasedShipping();
        $order_status_id=1;
        break;
}

        
        

        $total = $this->calculateTotalAmount($couponDiscountAmount, $shippingCharge);
        $totalValue = round($total);
        $adjustAmount = $totalValue - $total;

      // Create a new Order instance
        $order = new Order();
        
        // Set each property individually
        $order->user_id = $user->id;
        $order->subtotal = $itemTotal;
        $order->discount_amount = $itemDiscountAmount;
        $order->coupon_id = $couponId;
        $order->coupon_discount = $couponDiscountAmount;
        
        // $order->shipping_charge = $request->shippingCharge;
    
         $order->shipping_charge = $shippingCharge;
          
        $order->total = $totalValue;
        $order->adjust_amount = $adjustAmount;
        $order->sale_date = Carbon::now()->format('Y-m-d');
        $order->quantity = $this->cartService->countItems();
        $order->tax = 0;
        $order->order_type = 'retail';
        $order->payment_status = 'unpaid';
        $order->order_status_id =$order_status_id; // pending
        
        $order->source = 'website';
        $order->customer_note = $request->customer_note;
        
       
        // Save the Order instance
        $order->save();


        // Save shipping details, order items, and update stock
        $this->createOrUpdateOrderShipping($order, $request);
        $this->saveOrderItems($order, $cartItems);
        $this->saveStockInfo($order, $cartItems);

        

        // Handle payment methods
        $paymentMethod = PaymentMethod::findOrFail($paymentMethodId);
        switch ($paymentMethod->id) {
            case 1:
                $this->createOrderTransaction($order, 0, 1, 'Cash on Delivery', 'Customer Will Pay on Cash');
                    // Clear session data
                    session()->forget(['couponId', 'couponDiscount']);
                    $this->cartService->clearCart();
                return [
                    'status' => 'success',
                    'order' => $order
                ];
            case 2:
                return [
                    'status' => 'redirect',
                    'route' => 'create.bkash.payment',
                    'orderId' => encrypt($order->id)
                ];
            case 3:
                return [
                    'status' => 'view',
                    'view' => 'frontend.modules.paymentMethod.nagad',
                    'data' => ['order' => $order],
                    'message' => 'অর্ডার সফলভাবে সম্পন্ন হয়েছে!'
                ];
            default:
                return [
                    'status' => 'success',
                    'order' => $order
                ];
        }
    });
}




// protected function finalizeOrder($request, $order)
// {
//     if ($request->has('email')) {
//         Mail::to($request->email)->send(new OrderPlaced($order));
//     }

//     return redirect()->route('checkout.orders.complete', $order->id)
//         ->with('message', 'অর্ডার সফলভাবে সম্পন্ন হয়েছে!');
// }




    // frontend order transaction
    protected function createOrderTransaction($order, $amount,$methodId,$methodName,  $notes)
    {
        $orderTransaction = new OrderTransaction();

        $orderTransaction->fill([
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'user_id' => $order->user_id,
            'method_id' => $methodId,
            'method_name' =>$methodName ,
            'transaction_id' => $methodId,
            'amount' => $amount,
            'notes' => $notes,
            'status' => 1
        ]);
        $orderTransaction->save();

        $transactionSum = $order->transactions()->sum('amount');
        $paymentStatus = $order->total <= $transactionSum ? 'paid' : ($transactionSum == 0 ? 'unpaid' : 'partial');
        $order->payment_status = $paymentStatus;
        $order->save();
    }




    public function orderUpdate($request, $id)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'address' => 'required',
        ]);

        return DB::transaction(function () use ($request, $id) {

            $cartItems = json_decode($request->input('cart_data'), true);

            if (empty($cartItems)) {
                session()->flash('message', 'আপনার কার্ট খালি.');
                session()->flash('type', 'warning');
            }

            $itemTotal = $this->sanitizeInput($request->input('itemTotal'));
            $shipping = $this->sanitizeInput($request->input('shipping'));
            $packingCharge = $this->sanitizeInput($request->input('packing_charge'));
            $couponDiscount = $this->sanitizeInput($request->input('coupon_discount'));
            $discount = $this->sanitizeInput($request->input('discount'));
            $total = $this->sanitizeInput($request->input('total'));
            $totalValue = round($total);
            $adjustAmount = $totalValue - $total;

            $user = User::where('phone', $request->phone)->first();

            $totalProductQuantity = $this->calculateTotalProductQuantity($cartItems);

            $order = Order::find($id);
            $order->user_id = $user->id;
            $order->discount_amount = $discount;
            $order->coupon_discount = $couponDiscount;
            $order->subtotal = $itemTotal;
            $order->shipping_charge = $shipping;
            $order->packing_charge = $packingCharge;
            $order->total = $totalValue;
            $order->adjust_amount = $adjustAmount;
            $order->quantity = $totalProductQuantity;
            $order->tax = 0;
            $order->order_type = $user->user_type == 'shop' ? 'wholesale' : 'retail';
//            $order->payment_status = 'unpaid';
//            $order->order_status_id = 1;
//            $order->source = 'pos';
            $order->save();
            
            

            $this->processOrderTransactions($order);
            $this->saveOrderItems($order, $cartItems);
            $this->createOrUpdateOrderShipping($order, $request);
            $this->saveStockInfo($order, $cartItems);

            $order->logAction('Order updated');

            return redirect()->route('orders.show', $order->id);
        });
    }


    private function updateCouponUsageAndStock($userId, $couponId, $couponDiscountAmount)
    {
        // Find the coupon by ID or fail
        $coupon = Coupon::findOrFail($couponId);

        // Find or create a new CouponUsage record
        $couponUsed = CouponUsage::firstOrNew([
            'user_id' => $userId,
            'coupon_id' => $couponId,
        ]);

        // Set the code and amount, and increment total_used
        $couponUsed->code = $coupon->code;
        $couponUsed->amount = $couponDiscountAmount;
        $couponUsed->total_used = $couponUsed->exists ? $couponUsed->total_used + 1 : 1;
        $couponUsed->save();

        // Update coupon stock
        $coupon->stock -= 1;
        $coupon->total_used += 1;
        $coupon->save();
    }


private function storeCustomer($request)
{
    // Check if an existing user is an admin
    $user = User::where('phone', $request->phone)->first();

    // If the user exists, return it
    if ($user) {
        return $user;
    }

    // If the user doesn't exist, create a new one
    $user = new User();
    $user->password = Hash::make($request->phone);
    $user->user_role = 3;//default for customer
    $user->user_type = 'customer';
    $user->name = $request->name;
    $user->phone = $request->phone;
    $user->alternate_phone = $request->alternate_phone;
    $user->email = $request->email ?? null;
    $user->save();

    return $user;
}


/*private function storeCustomer($request)
{
    // Check if the request is valid
    $request->validate([
        'name' => 'required|string|max:255',
        'phone' => 'required|string|max:15',
        // Add other validation rules as necessary
    ]);
    
    // If the user is authenticated, return the user
    if (Auth::user()) {
        return Auth::user();
    } 
    
    // Find the user by phone number
    $user = User::where('phone', $request->phone)->first();
    
    if ($user) {
        // If the user is an admin, throw an exception
        if ($user->is_admin) {
           abort(403,"Error: Please login first");
        } else {
            // If the user is not an admin, return the user
            return $user;
        }
    } 
    
    // If the user doesn't exist, create a new one
    $user = new User();
    $user->password = Hash::make($request->phone);
    $user->user_role = 3; //default role for customer
    $user->user_type = 'customer';
    $user->name = $request->name;
    $user->phone = $request->phone;
    $user->alternate_phone = $request->alternate_phone;
    $user->email = $request->email ?? null;
    $user->save();
    return $user;
    
}*/




    private function calculateTotalAmount($couponAmount, $shippingCharge)
    {
        $cartTotal = $this->cartService->getTotal();
        return $cartTotal - $couponAmount + $shippingCharge;
    }

    public function createOrUpdateOrderShipping($order, $request)
    {
        $orderShipping = OrderShipping::where('order_id', $order->id)
            ->first();

        if (!$orderShipping) {
            $orderShipping = new OrderShipping();
            $orderShipping->order_id = $order->id;
            $orderShipping->order_number = $order->order_number;
            $orderShipping->user_id = $order->user_id;
        }

        if ($request->has('location_type')) {
            $orderShipping->location_type = $request->location_type;
        }

        $orderShipping->name = $request->name;
        $orderShipping->email = $request->email;
        $orderShipping->phone = $request->phone;
        $orderShipping->alternate_phone = $request->alternate_phone;

        if ($request->has('country_id')) {
            $orderShipping->country_id = $request->country_id;
        } else {
            $city = City::find($request->city_id);
            $orderShipping->country_id = $city ? $city->country_id : null;
        }

        if ($request->has('division_id')) {
            $orderShipping->division_id = $request->division_id;
        }

        $orderShipping->city_id = $request->city_id;

        $orderShipping->upazila_id = $request->upazila_id;

        if ($request->has('union_id')) {
            $orderShipping->union_id = $request->union_id;
        }
        $orderShipping->address = $request->address;
        if ($request->has('zip_code')) {
            $orderShipping->zip_code = $request->zip;
        }

        $orderShipping->save();
    }

    private function saveOrderItems($order, $cartItems)
    {
        OrderItem::where('order_id',$order->id)->delete();
        //delete previous item if exsist;
        
        foreach ($cartItems as $item) {
            $orderItem = new OrderItem();

            $product = Product::find($item['id']);

            $orderItem->order_id = $order->id;
            $orderItem->order_number = $order->order_number;
            $orderItem->product_id = $item['id'];
            $orderItem->qty = $item['quantity'];

            $orderItem->price = $item['current_price'];

            $orderItem->total = $item['current_price'] * $item['quantity'];

            $orderItem->status = $product->status;

            $orderItem->publisher_id = $product->publisher_id;

            $orderItem->author_id = collect($product->authors)->pluck('id')->toArray();
            $orderItem->category_id = collect($product->categories)->pluck('id')->toArray();
            $orderItem->subcategory_id = collect($product->subcategories)->pluck('id')->toArray();

            $orderItem->save();
        }
    }
    
    





    private function saveStockInfo($order, $cartItems)
    {

        foreach ($cartItems as $item) {

            $product = Product::find($item['id']);

            if ($product->isBundle != 1) {

                $stock = Stock::where('order_id', $order->id)
                    ->where('product_id',$item['id'])
                    ->where('is_bundle_item', 0)
                    ->first();

                $previousQty = 0;
                if (!$stock) {
                    $stock = new Stock();
                } else {
                    $previousQty = $stock->item_qty;
                }


                $stock->stock_type = 'sale';
                $stock->stock_entry_date = now();
                $stock->order_id = $order->id;
                $stock->product_id =$item['id'];
                $stock->item_price =$item['current_price'];
                $stock->item_qty =$item['quantity'];
                $stock->item_discount =$product->current_price - $item['current_price'];
                $stock->item_subtotal =$item['current_price'] *$item['quantity'];
                $stock->item_description = 'Sale as  Single';
                $stock->save();

                // Decrement product quantity
                $product->stock += $previousQty; // Revert the previous quantity deduction
                $product->stock -=$item['quantity']; // Apply the new quantity deduction
                $product->save();
            } else {

                if ($item['current_price'] < $product->current_price) {

                    $totalDiscount = ($product->current_price *$item['quantity']) - ($item['current_price'] *$item['quantity']);


                    $totalBundleQty = $product->bundleProducts->sum('quantity');
                    // Ensure we don't divide by zero
                    if ($totalBundleQty > 0) {
                        $averageDiscount = $totalDiscount / ($item['quantity'] * $totalBundleQty);
                    } else {
                        $averageDiscount = 0;
                    }
                }


                // Save stock information for bundle products
                foreach ($product->bundleProducts as $bundleItem) {

                    $stock = Stock::where('order_id', $order->id)
                        ->where('product_id', $bundleItem->bundle_product_id)
                        ->where('is_bundle_item', 1)
                        ->first();

                    $previousQty = 0;
                    if (!$stock) {
                        $stock = new Stock();
                    } else {
                        $previousQty = $stock->item_qty;
                    }


                    $stock->stock_type = 'sale';
                    $stock->stock_entry_date = now();
                    $stock->order_id = $order->id;
                    $stock->product_id = $bundleItem->bundle_product_id;

                    $stock->item_price = $bundleItem->current_price - ($averageDiscount ?? 0);

                    $stock->item_qty =$item['quantity'] * $bundleItem->quantity;

                    $stock->item_discount = $averageDiscount ?? 0; // Set the item discount

                    $stock->item_subtotal = $stock->item_price * $stock->item_qty;

                    $stock->item_description = 'Bundle Sale,bundle parent product id' . $bundleItem->product_id;
                    $stock->is_bundle_item = 1;

                    $stock->save();

                    // Decrement product quantity
                    $bundleItemProduct = Product::find($bundleItem->bundle_product_id);
                    $bundleItemProduct->stock += $previousQty; // Revert the previous quantity deduction
                    $bundleItemProduct->stock -=$item['quantity'] * $bundleItem->quantity; // Apply the new quantity deduction
                    $bundleItemProduct->save();
                }
            }
        }
    }


//    backend order transaction
    public function processOrderTransactions($order)
    {
        // Retrieve the payment details from the session
        $existingPaymentDetails = [];
        if (Session::has('payment_details')) {
            $existingPaymentDetails = Session::get('payment_details');
        }

        // Iterate over payment details and save OrderTransaction instances
        foreach ($existingPaymentDetails as $paymentDetails) {


            $paymentMethodId = $paymentDetails['payment_method_id'];
            $paymentMethodName = $paymentDetails['methodName'];
            $amount = $paymentDetails['amount'];
            $transactionId = $paymentDetails['transaction_id'];
            $note = $paymentDetails['note'];

            // Create a new instance of OrderTransaction
            $payment = new OrderTransaction();
            $payment->order_id = $order->id;
            $payment->order_number = $order->order_number;
            $payment->user_id = $order->user_id;
            $payment->method_id = $paymentMethodId;
            $payment->method_name = $paymentMethodName;
            $payment->transaction_id = $transactionId;
            $payment->amount = $amount;
            $payment->notes = $note;
            $payment->status = 1;
            $payment->save();
        }

        $transactionSum = $order->transactions()->sum('amount');
        $paymentStatus =$order->total <= $transactionSum  ? 'paid' : ($transactionSum==0 ? 'unpaid' : 'partial');
        $order->payment_status = $paymentStatus;
        $order->save();


        // Clear the payment_details from the session
        Session::forget('payment_details');
    }
}

