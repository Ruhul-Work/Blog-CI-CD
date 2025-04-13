<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Common;
use App\Models\Coupon;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\User;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class PosController extends Controller
{

    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index()
    {
        $currentTimestamp = Carbon::now()->format('Y-m-d');
        $coupons = Coupon::where('end_date', '>', $currentTimestamp)->get();
        //$paymentMethods=PaymentMethod::where('status',1)->get();
        $paymentMethods=PaymentMethod::get();
        $userTypes =Common::getPossibleEnumValues('users', 'user_type');

        return view('backend.modules.pos.create_order',[
            'coupons'=>$coupons,
            'userTypes'=>$userTypes,
            'paymentMethods'=>$paymentMethods,
        ]);
    }


    public function searchProduct(Request $request)
    {
        $search = $request->search;
        $category_id = $request->category_id;
        $publisher_id = $request->publisher_id;

        $products = Product::query()
            ->when($publisher_id, function ($query) use ($publisher_id) {
                return $query->where('publisher_id', $publisher_id);
            })
            ->when($category_id, function ($query) use ($category_id) {
                return $query->whereHas('categories', function ($query) use ($category_id) {
                    $query->where('categories.id', $category_id);
                });
            })
            ->when($search, function ($query) use ($search) {
                return $query->where(function ($query) use ($search) {
                    $query->where('english_name', 'like', '%' . $search . '%')
                        ->orWhere('bangla_name', 'like', '%' . $search . '%')
                        ->orWhere('slug', 'like', '%' . $search . '%');
                });
            })
            ->where('status', 1)
            ->latest()
            ->take(100)
            ->get();


        if ($products->isEmpty()) {
            return response()->json([
                'error' => 'no',
                'message' => 'No product found',
                'type' => 'success',
                'dataView' => 'No product found',
            ]);
        }

        $view = view('backend.modules.pos.ajax_pos_product_list', compact('products'))->render();

        return response()->json([
            'error' => 'no',
            'message' => 'Product has been loaded',
            'type' => 'success',
            'dataView' => $view,
        ]);
    }


    //new
    public  function searchCustomer(Request $request){
        $search = $request->has('q') ? $request->q : "";
        $select2Json = [];

     $sellers = User::whereNotIn('user_type', ['admin'])
    ->where('name', 'like', '%' . $search . '%')
    ->orWhere('phone', 'like', '%' . $search . '%') // Add phone search
    ->orWhere('email', 'like', '%' . $search . '%') // Add email search
    ->orderBy('name', 'ASC') // First order by name
    ->orderBy('phone', 'ASC') // Then order by phone
    ->orderBy('email', 'ASC') // Finally order by email
    ->get();



        foreach ($sellers as $single) {
            $select2Json[] = [
                'id' => $single->id,
                'text' => $single->name,
            ];
        }

        return response()->json($select2Json);
    }

    public function getCustomerInfo(Request $request)
    {
        $customerId = $request->input('customerId');

        $customer = User::find($customerId);

        if ($customer) {
            // Retrieve the latest shipping address
            $latestShipping = $customer->shipping;
            // Return the customer information along with the latest shipping address as JSON response
            return response()->json([
                'name' => $customer->name,
                'phone' => $customer->phone,
                'phone_alt' => $customer->alternate_phone,
                'email' => $customer->email,
                'country_id' => $latestShipping ? $latestShipping->country_id : null,
                'city_id' => $latestShipping ? $latestShipping->city_id : null,
                'upazila_id' => $latestShipping ? $latestShipping->upazila_id : null,
                'zip' => $latestShipping ? $latestShipping->zip : null,
                'address' => $latestShipping ? $latestShipping->address : null,
            ]);
        } else {
            // If customer not found, return an empty response with a 404 status code
            return response()->json([], 404);
        }
    }

    public function storeCustomer(Request $request)
    {


        $request->validate([
            'name' => ['required'],
            'user_type' => ['required', function ($attribute, $value, $fail) {
                if ($value === 'admin') {
                    $fail('The ' . $attribute . ' cannot be admin.Try to add different user');
                }
            }],
            'phone' => ['required', 'unique:users,phone', 'regex:/01[2-9]\d{8}$/'],
        ]);


        $user = new User();
        $user->user_type = $request->user_type;
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->email = $request->email ?? null;
        $user->user_role = 2;
        $user->alternate_phone = $request->phone_alt;
        $user->status = 1;
        $user->password = Hash::make($request->phone);
        $user->save();


        // Return JSON response
        return response()->json([
            'message' => 'User has been created successfully',
        ]);
    }


    public function paymentMethodStore(Request $request)
    {


        // Retrieve the input values from the request
        $paymentMethodId = $request->input('payment_method_id');
//        $methodName = $request->input('method_name');
        $amount = $request->input('amount');
        $transactionId = $request->input('transaction_id');
        $note = $request->input('note');
        $method=PaymentMethod::where('id', $paymentMethodId)->first();
        $methodName=  $method->name;

        $paymentDetails = [
            'payment_method_id' => $paymentMethodId,
            'methodName'=> $methodName,
            'amount' => $amount,
            'transaction_id' => $transactionId,
            'note' => $note,
        ];

        // Retrieve the existing payment details from the session (if any)
        $existingPaymentDetails = Session::get('payment_details', []);

        // Append the new payment details to the existing ones
        $existingPaymentDetails[] = $paymentDetails;

        // Save the updated payment details in the session
        Session::put('payment_details', $existingPaymentDetails);

         $html=view('backend.modules.pos.payment_summary')->render();

        return response()->json([
            'existingPaymentDetails' => $existingPaymentDetails,
            'success' => 'Payment  method saved successfully',
            'html' =>$html,
        ]);
    }
    public function removePaymentMethod(Request $request)
    {

        $index = $request->input('index');


        // Retrieve the existing payment details from the session
        $existingPaymentDetails = Session::get('payment_details', []);

        if ($index >= 0 || $index < count($existingPaymentDetails)) {

            // Remove the payment at the specified index
            array_splice($existingPaymentDetails, $index, 1);

            // Update the payment details in the session
            Session::put('payment_details', $existingPaymentDetails);

            // Return a success response
            return response()->json([
                'success' => 'Payment removed successfully',
                'existingPaymentDetails' => $existingPaymentDetails,
                'html' =>view('backend.modules.pos.payment_summary')->render(),
            ]);
        }

        // Return an error response if the index is out of range
        return response()->json([
            'error' => 'Invalid index provided',
        ], 400);
    }


    public function orderStore(Request $request)
    {
        return $this->orderService->posOrderStore($request);
    }


    public function orderUpdate(Request $request,$id)
    {

        return $this->orderService->orderUpdate($request, $id);


    }



    public function applyCoupon(Request $request)
    {
        // Validate the request data
 
        $request->validate([
            'coupon_code' => 'required|string|max:255',
            'cartData' => 'required|json'
        ]);

        $cartItems = json_decode($request->input('cartData'), true);
        $couponCode = $request->input('coupon_code');


        $coupon = Coupon::where('code', $couponCode)
            ->where('end_date', '>=', now())
            ->first();

        // Calculate the total cart subtotal
        $cartSubtotal = array_sum(array_column($cartItems, 'subtotal'));


        if ($coupon) {

            $result = calculateCouponDiscount($cartSubtotal, $coupon, $cartItems);

            // Check for error in the result
            if (isset($result['error'])) {
                return response()->json(['error' => $result['error']], $result['status']);
            }


            $couponDiscount = $result['discount_amount'];

            Session::put('couponDiscountAmount', $couponDiscount);
            Session::put('coupon_id', $coupon->id);

            // Return the updated cart data with the discount applied
            return response()->json([
                'cart' => $cartItems,
                'discount_amount' => $result['discount_amount'],
            ]);
        } else {
            // Coupon not found or invalid
            return response()->json(['error' => 'Coupon not found or invalid'], 404);
        }
    }

}
