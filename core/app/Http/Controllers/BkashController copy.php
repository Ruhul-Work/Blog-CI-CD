<?php

namespace App\Http\Controllers;

use App\Mail\OrderPlaced;
use App\Models\Order;
use Illuminate\Support\Facades\Session;
use App\Models\OrderTransaction;
use App\Models\User;
use App\Models\SubscriptionOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
class BkashController extends Controller
{
    private $base_url;
    private $username;
    private $password;
    private $app_key;
    private $app_secret;


    public function __construct()
    {
        
        
        // $this->base_url = 'https://tokenized.pay.bka.sh/v1.2.0-beta';
        // $this->username = "01732325310";
        // $this->password = ",b[C4K8C-pf";
        // $this->app_key = "aq5HAqhaXaQr8heBEKJTDPXItc";
        // $this->app_secret ="Zna7vEghmrnMmg4wVZDvOA9UXWKHMOCvg3eSlysSVNfQHJudI0BH";

        $this->base_url = 'https://tokenized.sandbox.bka.sh/v1.2.0-beta';
        $this->username = '01770618567';
        $this->password = 'D7DaC<*E*eG';
        $this->app_key = '0vWQuCRGiUX7EPVjQDr0EUAYtc';
        $this->app_secret ='jcUNPBgbcqEDedNKdvE4G1cAK7D3hCjmJccNPZZBq96QIxxwAMEx';
        
        // $this->base_url = 'https://checkout.pay.bka.sh/v1.2.0-beta';
        // $this->username = "ENGLISHMOJARM50575_CHKOUT";
        // $this->password = "!10-]JH]5sC";
        // $this->app_key = "jfcQs5ucvcpTPM43d7JwYhGTch";
        // $this->app_secret = "TNhPjNYnpk7eYrqdqfBFfYkxCtcrMNSplgTx1avQJUozNkJtn0AF";



    }

    public function authHeaders()
    {
        return array(
            'Content-Type:application/json',
            'Authorization:' . $this->grant(),
            'X-APP-Key:' . $this->app_key
        );
    }

    public function curlWithBody($url, $header, $method, $body_data)
    {
        $curl = curl_init($this->base_url . $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $body_data);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

   /* public function grant()
    {
        $header = array(
            'Content-Type:application/json',
            'username:' . $this->username,
            'password:' . $this->password
        );

        $body_data = array('app_key' => $this->app_key, 'app_secret' => $this->app_secret);

        $response = $this->curlWithBody('/tokenized/checkout/token/grant', $header, 'POST', json_encode($body_data));
        
       

        $token = json_decode($response)->id_token;

        return $token;
    }*/
    
    
    
    public function grant()
    {
        // Fetch the current username from the .env file
        $envUsername = $this->username;//env('BKASH_USERNAME');

        // Get cached token and username
        $cachedTokenData = Cache::get('token_data');

        // If there is a cached token and the username matches the current one in the .env file
        if (!is_null($cachedTokenData) && $cachedTokenData['username'] === $envUsername) {
            return $cachedTokenData['token'];
        }

        $header = array(
            'Content-Type: application/json',
            'username: ' . $this->username,
            'password: ' . $this->password,
        );

        $body_data = array('app_key' => $this->app_key, 'app_secret' => $this->app_secret);

        $response = $this->curlWithBody('/tokenized/checkout/token/grant', $header, 'POST', json_encode($body_data));

        $token = json_decode($response)->id_token;

        Cache::put('token_data', ['token' => $token, 'username' => $envUsername], 3600); 

        return $token;
    }
    

    public function payment(Request $request)
    {


        return view('bkash.pay');
    }





    public function createPayment(Request $request)
    {
        //$orderId = decrypt($request->order_id);
        $orderId = $request->order_id;

        if (!empty($orderId)) {
            $order = Order::find($orderId);

            if ($order) {

                // Bkash Part
                $header = $this->authHeaders();

                $website_url = URL::to("/");

                $body_data = array(
                    'mode' => '0011',
                    'payerReference' => $order->order_number, // pass orderId or anything
                    'callbackURL' => $website_url . '/bkash-callback/' . encrypt($order->id),
                    'amount' => $order->total,
                    'currency' => 'BDT',
                    'intent' => 'sale',
                    'merchantInvoiceNumber' => $order->order_number
                );

                $response = $this->curlWithBody('/tokenized/checkout/create', $header, 'POST', json_encode($body_data));

                return redirect((json_decode($response)->bkashURL));
            } else {
                abort(404, "Invalid Order Number");
            }
        } else {
            abort(404, "Invalid Order Number");
        }
    }
//     public function createPayment($order_id)
// {
//     $order = SubscriptionOrder::findOrFail($order_id);

//     // Mock bKash API request for demonstration
//     $paymentURL = 'https://sandbox.pay.bka.sh/v1.2.0-beta/checkout';
//     $callbackURL = route('url-callback', ['order_id' => $order_id]);

//     // Generate bKash payment URL (you need to implement actual API call here)
//     $paymentResponse = [
//         'paymentID' => '1234567890',
//         'bkashURL' => 'https://sandbox.pay.bka.sh/v1.2.0-beta/checkout?paymentID=1234567890',
//     ];

//     // Save paymentID in the order for future reference
//     $order->bkash_payment_id = $paymentResponse['paymentID'];
//     $order->save();

//     return redirect()->away($paymentResponse['bkashURL']);
// }





    public function executePayment($paymentID)
    {

        $header = $this->authHeaders();

        $body_data = array(
            'paymentID' => $paymentID
        );


        $response = $this->curlWithBody('/tokenized/checkout/execute', $header, 'POST', json_encode($body_data));

        return $response;
    }
    public function queryPayment($paymentID)
    {
        $header = $this->authHeaders();

        $body_data = array(
            'paymentID' => $paymentID,
        );

        $response = $this->curlWithBody('/tokenized/checkout/payment/status', $header, 'POST', json_encode($body_data));

        return $response;
    }

    public function callback(Request $request)
    {
        $allRequest = $request->all();
        //dd($allRequest);
        
        if (isset($allRequest['status']) && $allRequest['status'] == 'success') {
            $response = $this->executePayment($allRequest['paymentID']);
            if (is_null($response)) {
                sleep(1);
                $response = $this->queryPayment($allRequest['paymentID']);
            }

            $res_array = json_decode($response, true);
           

            if (array_key_exists("statusCode", $res_array) && $res_array['statusCode'] == '0000' && array_key_exists("transactionStatus", $res_array) && $res_array['transactionStatus'] == 'Completed') {
                // Payment success case
                $orderId = decrypt($request->order_id);
                $order = Order::find($orderId);
                $order->order_status_id=1;//pending
                
            
                $payment = new OrderTransaction();
                $payment->order_id = $order->id;
                $payment->order_number = $order->order_number;
                $payment->user_id = $order->user_id;
                $payment->method_id = 2;
                $payment->amount = $order->total;
                $payment->method_name = 'bkash';
                $payment->transaction_id = $res_array['trxID'];
                $payment->payment_id = $res_array['paymentID'];
                //$payment->payment_id = $res_array['paymentID'];
                $payment->customerMsisdn = $res_array['customerMsisdn'];
                $payment->notes = 'mobile_number: ' . $res_array['customerMsisdn'] . ', paymentID: ' . $res_array['paymentID'];
                $payment->status = 1;
                $payment->save();

                $transactionSum = $order->transactions()->sum('amount');
                $paymentStatus = $order->total <= $transactionSum ? 'paid' : ($transactionSum == 0 ? 'unpaid' : 'partial');
                $order->payment_status = $paymentStatus;
                $order->save();


                $user = User::find($order->user_id);

                if ($user && !Auth::check()) {
                    //Auth::login($user);
                }


                if ($order->shipping->email) {
                    Mail::to($order->shipping->email)->send(new OrderPlaced($order));
                }
                
                // Clear session data
                session()->forget(['couponId', 'couponDiscount']);
                session()->forget('cart.items');

                  return redirect()->route('checkout.orders.complete', encrypt($order->id))->with('message', 'Order placed successfully!');

                  //return view('bkash.success')->with(['response' => $res_array['trxID']]);
            }

               
            
                //$orderId = decrypt($request->order_id);
                //$order = Order::find($orderId)->delete();
            
                return redirect()->route('payment.failed',['error' =>"Invalid Payment"]);
            
        } else {
                //$orderId = decrypt($request->order_id);
                //$order = Order::find($orderId)->delete();
                return redirect()->route('payment.failed',['error' => 'Payment Cancelled']);
           
        }
    }







    public function getRefund(Request $request)
    {
        return view('bkash.refund');
    }

    public function refundPayment(Request $request)
    {
        $header = $this->authHeaders();

        $body_data = array(
            'paymentID' => $request->paymentID,
            'trxID' => $request->trxID
        );

        $response = $this->curlWithBody('/tokenized/checkout/payment/refund', $header, 'POST', json_encode($body_data));

        $res_array = json_decode($response, true);

        $message = "Refund Failed !!";

        if (!isset($res_array['refundTrxID'])) {

            $body_data = array(
                'paymentID' => $request->paymentID,
                'amount' => $request->amount,
                'trxID' => $request->trxID,
                'sku' => 'sku',
                'reason' => 'Quality issue'
            );

            $response = $this->curlWithBody('/tokenized/checkout/payment/refund', $header, 'POST', json_encode($body_data));

            $res_array = json_decode($response, true);

            if (isset($res_array['refundTrxID'])) {
                // your database insert operation
                $message = "Refund successful !!.Your Refund TrxID : " . $res_array['refundTrxID'];
            }

        } else {
            $message = "Already Refunded !!.Your Refund TrxID : " . $res_array['refundTrxID'];
        }

        return view('bkash.refund')->with([
            'response' => $message,
        ]);
    }

    public function getSearchTransaction(Request $request)
    {
        return view('bkash.search');
    }

    public function searchTransaction(Request $request)
    {

        $header = $this->authHeaders();
        $body_data = array(
            'trxID' => $request->trxID,
        );

        $response = $this->curlWithBody('/tokenized/checkout/general/searchTransaction', $header, 'POST', json_encode($body_data));


        return view('bkash.search')->with([
            'response' => $response,
        ]);
    }

}
