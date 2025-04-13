<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\PackageOrderTransaction;
use App\Models\PaymentMethod;
use App\Models\SubscriptionItem;
use App\Models\SubscriptionOrder;
use App\Models\SubscriptionPackage;
use App\Models\SubscriptionShipping;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class SubscriptionOrderController extends Controller
{

    public function index()
    {
        return view('backend.modules.subscription_order.create');
    }

    public function searchPackage(Request $request)
    {
        $search = $request->search;

        // Fetch subscription packages
        $packages = SubscriptionPackage::query()
            ->when($search, function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            })
            ->where('status', 1)
            ->latest()
            ->take(100)
            ->get();

        if ($packages->isEmpty() && !empty($search)) {
            return response()->json([
                'error' => 'yes',
                'message' => 'No packages found!',
            ]);
        }

        $view = view('backend.modules.subscription_order.ajax_package_list', compact('packages'))->render();

        return response()->json([
            'error' => 'no',
            'message' => 'Packages loaded successfully.',
            'dataView' => $view,
        ]);
    }

    // public function store(Request $request)
    // {
    //     // Validate the incoming request data
    //     $request->validate([
    //         'user_id' => 'required|exists:users,id',
    //         'cart_data' => 'required|json',
    //         'subtotal' => 'required|numeric|min:0',
    //         'discount' => 'nullable|numeric|min:0',
    //         'coupon_id' => 'nullable|integer|exists:coupons,id',
    //         'coupon_discount' => 'nullable|numeric|min:0',
    //         'total' => 'required|numeric|min:0',
    //         'pay_method' => 'nullable|integer',
    //         'pay_amount' => 'nullable|numeric|min:0',
    //         'name' => 'required|string|max:191',
    //         'mobile_number' => 'required|string|max:191',
    //         'subscription_start_date' => 'required|date',
    //         'address' => 'required|string|max:500',

    //     ]);

    //     DB::beginTransaction();
    //     try {
    //         $cartData = json_decode($request->cart_data, true);

    //         if (empty($cartData)) {
    //             return response()->json([
    //                 'error' => true,
    //                 'message' => 'Cart data is empty!',
    //             ], 400);
    //         }

    //         $packageId = $cartData[0]['id'] ?? null;

    //         if (!$packageId) {
    //             return response()->json([
    //                 'error' => true,
    //                 'message' => 'Package ID is missing in cart data!',
    //             ], 400);
    //         }

    //         $package = SubscriptionPackage::find($packageId);

    //         if (!$package) {
    //             return response()->json([
    //                 'error' => true,
    //                 'message' => 'Invalid subscription package!',
    //             ], 404);
    //         }

    //         $startDate = $request->subscription_start_date;
    //         $durationInDays = $package->duration;
    //         $endDate = Carbon::parse($startDate)->addDays($durationInDays);

    //         $orderNumber = SubscriptionOrder::generateOrderNumber();

    //         // Use the user ID from the request
    //         $userId = $request->user_id;

    //         //  Calculate Total Payments from Session
    //         $paymentDetails = Session::get('payment_details', []);
    //         $totalPaid = array_reduce($paymentDetails, function ($sum, $payment) {
    //             return $sum + $payment['amount'];
    //         }, 0);

    //         //Determine Payment Status
    //         $paymentStatus = 'Pending';
    //         if ($totalPaid >= $request->total) {
    //             $paymentStatus = 'Paid';
    //         } elseif ($totalPaid > 0) {
    //             $paymentStatus = 'Partial';
    //         }

    //         //$paymentStatus = $request->pay_amount >= $request->total ? 'Paid' : 'Pending';

    //         $order = new SubscriptionOrder();
    //         $order->order_number = $orderNumber;
    //         $order->user_id = $userId;
    //         $order->subscription_package_id = $packageId;
    //         $order->package_price = $request->subtotal;
    //         $order->discount = $request->discount;
    //         $order->coupon_id = $request->coupon_id;
    //         $order->coupon_discount = $request->coupon_discount;
    //         $order->subtotal = $request->subtotal;
    //         $order->total = $request->total;
    //         $order->pay_method = $request->pay_method;
    //         $order->pay_amount = $request->pay_amount;
    //         $order->payment_status = $paymentStatus;
    //         $order->subscription_start_date = $startDate;
    //         $order->end_date = $endDate;
    //         $order->save();

    //         foreach ($cartData as $item) {
    //             $subscriptionItem = new SubscriptionItem();
    //             $subscriptionItem->order_number = $orderNumber;
    //             $subscriptionItem->subscription_order_id = $order->id;
    //             $subscriptionItem->user_id = $userId;
    //             $subscriptionItem->subscription_package_id = $item['id'];
    //             $subscriptionItem->package_name = $item['title'];
    //             $subscriptionItem->package_price = $item['current_price'];
    //             $subscriptionItem->quantity = $item['quantity'];
    //             $subscriptionItem->total = $item['subtotal'];
    //             $subscriptionItem->start_date = $startDate;
    //             $subscriptionItem->save();
    //         }

    //         $shipping = new SubscriptionShipping();
    //         $shipping->order_number = $orderNumber;
    //         $shipping->subscription_order_id = $order->id;
    //         $shipping->user_id = $userId;
    //         $shipping->name = $request->name;
    //         $shipping->mobile_number = $request->mobile_number;
    //         $shipping->address = $request->address;
    //         $shipping->start_date = $startDate;
    //         $shipping->save();

    //         // Handle payments from session
    //         $paymentDetails = Session::get('payment_details', []);

    //         foreach ($paymentDetails as $payment) {
    //             $transaction = new PackageOrderTransaction();
    //             $transaction->order_number = $orderNumber;
    //             $transaction->subscription_order_id = $order->id;
    //             $transaction->user_id = $userId;
    //             $transaction->method_id = $payment['method_id'];
    //             $transaction->method_name = $payment['methodName'];
    //             $transaction->transaction_id = $payment['transaction_id'];
    //             $transaction->amount = $payment['amount'];
    //             $transaction->notes = $payment['note'];
    //             $transaction->status = 1;
    //             $transaction->save();
    //         }

    //         Session::forget('payment_details');

    //         DB::commit();

    //         return response()->json([
    //             'error' => 'no',
    //             'message' => 'Order placed successfully.',
    //             'redirect_url' => route('subscription-orders.index'),
    //         ]);
    //     } catch (\Exception $e) {
    //         DB::rollBack();

    //         return response()->json([
    //             'error' => 'yes',
    //             'message' => 'Failed to place order. Please try again.',
    //             'exception' => $e->getMessage(),
    //         ], 500);
    //     }
    // }

    //

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'cart_data' => 'required|json',
            'subtotal' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'coupon_id' => 'nullable|integer|exists:coupons,id',
            'coupon_discount' => 'nullable|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'name' => 'required|string|max:191',
            'mobile_number' => 'required|string|max:191',
            'subscription_start_date' => 'required|date',
            'address' => 'required|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $cartData = $this->parseCartData($request->cart_data);

            $packageId = $cartData[0]['id'];
            $package = $this->validatePackage($packageId);

            $startDate = $request->subscription_start_date;
            $endDate = Carbon::parse($startDate)->addDays($package->duration);

            $orderNumber = SubscriptionOrder::generateOrderNumber();
            $userId = $request->user_id;

            $paymentStatus = $this->calculatePaymentStatus($request->total);

            $order = $this->createOrder($request, $orderNumber, $userId, $packageId, $paymentStatus, $startDate, $endDate);

            $this->addSubscriptionItems($cartData, $order, $userId, $startDate);

            $this->addShippingInfo($request, $order, $userId, $startDate);

            $this->addPayments($order, $orderNumber, $userId);

            DB::commit();

            return response()->json([
                'error' => 'no',
                'message' => 'Order placed successfully.',
                'redirect_url' => route('subscription-orders.index'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'error' => 'yes',
                'message' => 'Failed to place order. Please try again.',
                'exception' => $e->getMessage(),
            ], 500);
        }
    }

    // private function start

    private function parseCartData($cartData)
    {
        $cart = json_decode($cartData, true);

        if (empty($cart)) {
            throw new \Exception('Cart data is empty!');
        }

        return $cart;
    }

//

    private function validatePackage($packageId)
    {
        if (!$packageId) {
            throw new \Exception('Package ID is missing in cart data!');
        }

        $package = SubscriptionPackage::find($packageId);

        if (!$package) {
            throw new \Exception('Invalid subscription package!');
        }

        return $package;
    }

//
    private function calculatePaymentStatus($total)
    {
        $paymentDetails = Session::get('payment_details', []);
        $totalPaid = array_reduce($paymentDetails, function ($sum, $payment) {
            return $sum + $payment['amount'];
        }, 0);

        if ($totalPaid >= $total) {
            return 'Paid';
        } elseif ($totalPaid > 0) {
            return 'Partial';
        }

        return 'Pending';
    }
//
    private function createOrder($request, $orderNumber, $userId, $packageId, $paymentStatus, $startDate, $endDate)
    {
        $order = new SubscriptionOrder();
        $order->order_number = $orderNumber;
        $order->user_id = $userId;
        $order->subscription_package_id = $packageId;
        $order->package_price = $request->subtotal;
        $order->discount = $request->discount;
        $order->coupon_id = $request->coupon_id;
        $order->coupon_discount = $request->coupon_discount;
        $order->subtotal = $request->subtotal;
        $order->total = $request->total;
        $order->pay_method = $request->pay_method;
        $order->pay_amount = $request->pay_amount;
        $order->payment_status = $paymentStatus;
        $order->subscription_start_date = $startDate;
        $order->end_date = $endDate;
        $order->save();

        return $order;
    }
//
    private function addSubscriptionItems($cartData, $order, $userId, $startDate)
    {
        foreach ($cartData as $item) {
            $subscriptionItem = new SubscriptionItem();
            $subscriptionItem->order_number = $order->order_number;
            $subscriptionItem->subscription_order_id = $order->id;
            $subscriptionItem->user_id = $userId;
            $subscriptionItem->subscription_package_id = $item['id'];
            $subscriptionItem->package_name = $item['title'];
            $subscriptionItem->package_price = $item['current_price'];
            $subscriptionItem->quantity = $item['quantity'];
            $subscriptionItem->total = $item['subtotal'];
            $subscriptionItem->start_date = $startDate;
            $subscriptionItem->save();
        }
    }

//
    private function addShippingInfo($request, $order, $userId, $startDate)
    {
        $shipping = new SubscriptionShipping();
        $shipping->order_number = $order->order_number;
        $shipping->subscription_order_id = $order->id;
        $shipping->user_id = $userId;
        $shipping->name = $request->name;
        $shipping->mobile_number = $request->mobile_number;
        $shipping->address = $request->address;
        $shipping->start_date = $startDate;
        $shipping->save();
    }

//

    private function addPayments($order, $orderNumber, $userId)
    {
        $paymentDetails = Session::get('payment_details', []);

        foreach ($paymentDetails as $payment) {
            $transaction = new PackageOrderTransaction();
            $transaction->order_number = $orderNumber;
            $transaction->subscription_order_id = $order->id;
            $transaction->user_id = $userId;
            $transaction->method_id = $payment['method_id'];
            $transaction->method_name = $payment['methodName'];
            $transaction->transaction_id = $payment['transaction_id'];
            $transaction->amount = $payment['amount'];
            $transaction->notes = $payment['note'];
            $transaction->status = 1;
            $transaction->save();
        }

        Session::forget('payment_details');
    }
//private  function end

    public function userstore(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email',
            'phone' => 'required|string|max:20|unique:users,phone',
            'address' => 'required|string|max:500',
        ]);

        // Use the entire phone number as the password
        $generatedPassword = $validatedData['phone'];

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'] ?? null,
            'phone' => $validatedData['phone'],
            'address' => $validatedData['address'],
            'user_type' => 'customer',
            'password' => Hash::make($generatedPassword),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User created successfully!',
            'user' => $user,
        ], 201);
    }

    public function searchUser(Request $request)
    {
        $search = $request->q;

        $users = User::where('user_type', 'customer') // Filter by user type
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('phone', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            })
            ->select('id', 'name', 'phone', 'email', 'address') // Select required fields
            ->take(20)
            ->get();

        return response()->json($users);
    }

    public function getUserInfo(Request $request)
    {
        $user = User::findOrFail($request->userId);

        return response()->json([
            'success' => true,
            'data' => $user,
        ]);
    }

    //payment method list show
    public function methodsList()
    {
        $paymentMethods = PaymentMethod::where('status', 1)->get();

        return response()->json([
            'error' => 'no',
            'data' => $paymentMethods,
        ]);
    }

    //save new payment session
    public function savePaymentToSession(Request $request)
    {
        $request->validate([
            'payment_method_id' => 'required|integer|exists:payment_methods,id',
            'amount' => 'required|numeric|min:1',
            'transaction_id' => 'nullable|string|max:191',
            'note' => 'nullable|string|max:500',
        ]);

        $paymentDetails = [
            'method_id' => $request->payment_method_id,
            'methodName' => PaymentMethod::find($request->payment_method_id)->name ?? 'Unknown',
            'amount' => $request->amount,
            'transaction_id' => $request->transaction_id,
            'note' => $request->note,
        ];

        // Add to session
        $paymentDetailsSession = Session::get('payment_details', []);
        $paymentDetailsSession[] = $paymentDetails;
        Session::put('payment_details', $paymentDetailsSession);

        // Generate the payment summary view
        $html = view('backend.modules.subscription_order.payment_summary')->render();

        return response()->json([
            'error' => 'no',
            'message' => 'Payment information saved successfully.',
            'html' => $html,
        ]);
    }

    //remove save sission payment
    public function removePaymentFromSession(Request $request)
    {
        $request->validate([
            'index' => 'required|integer|min:0',
        ]);

        $paymentDetailsSession = Session::get('payment_details', []);
        if (isset($paymentDetailsSession[$request->index])) {
            unset($paymentDetailsSession[$request->index]);
            Session::put('payment_details', array_values($paymentDetailsSession));

            return response()->json([
                'error' => 'no',
                'message' => 'Payment information removed successfully.',
                'payment_details' => $paymentDetailsSession,
            ]);
        }

        return response()->json([
            'error' => 'yes',
            'message' => 'Invalid payment entry.',
        ]);
    }

    //store payment information
    public function storePaymentMethod(Request $request)
    {
        $request->validate([
            'order_number' => 'required|string|exists:subscription_orders,order_number',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'amount' => 'required|numeric|min:0',
            'transaction_id' => 'required|string|max:191',
            'note' => 'nullable|string|max:500',
        ]);

        try {
            // Retrieve the payment method details
            $paymentMethod = PaymentMethod::findOrFail($request->payment_method_id);

            // Store the payment transaction
            $transaction = new PackageOrderTransaction();
            $transaction->order_number = $request->order_number;
            $transaction->subscription_order_id = SubscriptionOrder::where('order_number', $request->order_number)->value('id');
            $transaction->user_id = auth()->id();
            $transaction->method_id = $paymentMethod->id;
            $transaction->method_name = $paymentMethod->name;
            $transaction->transaction_id = $request->transaction_id;
            $transaction->amount = $request->amount;
            $transaction->notes = $request->note;
            $transaction->status = 1; // Assuming 1 means "Completed"
            $transaction->save();

            return response()->json([
                'error' => 'no',
                'message' => 'Payment information saved successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'yes',
                'message' => 'Failed to save payment information. Please try again.',
                'exception' => $e->getMessage(),
            ], 500);
        }
    }

}
