<?php
namespace App\Http\Controllers;

use App\Mail\OrderPlaced;

use App\Models\OrderTransaction;
use App\Models\SubscriptionOrder;
use App\Models\PackageOrderTransaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;

class BkashController extends Controller
{
    private $base_url;
    private $username;
    private $password;
    private $app_key;
    private $app_secret;

    public function __construct()
    {

        $this->base_url = 'https://tokenized.pay.bka.sh/v1.2.0-beta';
        $this->username = "01732325310";
        $this->password = ",b[C4K8C-pf";
        $this->app_key = "aq5HAqhaXaQr8heBEKJTDPXItc";
        $this->app_secret ="Zna7vEghmrnMmg4wVZDvOA9UXWKHMOCvg3eSlysSVNfQHJudI0BH";

        // $this->base_url   = 'https://tokenized.sandbox.bka.sh/v1.2.0-beta';
        // $this->username   = '01770618567';
        // $this->password   = 'D7DaC<*E*eG';
        // $this->app_key    = '0vWQuCRGiUX7EPVjQDr0EUAYtc';
        // $this->app_secret = 'jcUNPBgbcqEDedNKdvE4G1cAK7D3hCjmJccNPZZBq96QIxxwAMEx';

        // $this->base_url = 'https://checkout.pay.bka.sh/v1.2.0-beta';
        // $this->username = "ENGLISHMOJARM50575_CHKOUT";
        // $this->password = "!10-]JH]5sC";
        // $this->app_key = "jfcQs5ucvcpTPM43d7JwYhGTch";
        // $this->app_secret = "TNhPjNYnpk7eYrqdqfBFfYkxCtcrMNSplgTx1avQJUozNkJtn0AF";

    }

    public function authHeaders()
    {
        return [
            'Content-Type:application/json',
            'Authorization:' . $this->grant(),
            'X-APP-Key:' . $this->app_key,
        ];
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
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($curl);
        curl_errno($curl);
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
        $envUsername = $this->username;

        $cachedTokenData = Cache::get('token_data');

        if (! is_null($cachedTokenData) && $cachedTokenData['username'] === $envUsername) {
            return $cachedTokenData['token'];
        }

        $header = [
            'Content-Type: application/json',
            'username: ' . $this->username,
            'password: ' . $this->password,
        ];

        $body_data = ['app_key' => $this->app_key, 'app_secret' => $this->app_secret];

        $response = $this->curlWithBody('/tokenized/checkout/token/grant', $header, 'POST', json_encode($body_data));

        $decodedResponse = json_decode($response);

        $token = $decodedResponse->id_token;

        Cache::put('token_data', ['token' => $token, 'username' => $envUsername], 3600);

        return $token;
    }

    public function payment(Request $request)
    {

        return view('bkash.pay');
    }

    public function createPayment(Request $request)
    {
        $orderId = $request->order_id;

        if (! empty($orderId)) {
            $order = SubscriptionOrder::find($orderId);

            if ($order) {
                // Bkash Part
                $header = $this->authHeaders();

                $website_url = URL::to("/");

                $body_data = [
                    'mode'                  => '0011',
                    'payerReference'        => $order->order_number, // Pass orderId or anything
                    'callbackURL'           => $website_url . '/bkash-callback/' . encrypt($order->id),
                    'amount'                => $order->total,
                    'currency'              => 'BDT',
                    'intent'                => 'sale',
                    'merchantInvoiceNumber' => $order->order_number,
                ];

                $response = $this->curlWithBody('/tokenized/checkout/create', $header, 'POST', json_encode($body_data));

                $decodedResponse = json_decode($response);

                if (isset($decodedResponse->bkashURL)) {
                    return redirect($decodedResponse->bkashURL);
                } else {
                    return redirect()->back()->with('error', 'Payment initialization failed.');
                }
            } else {
                abort(404, "Invalid Order Number");
            }
        } else {
            abort(404, "Invalid Order Number");
        }
    }

    public function executePayment($paymentID)
    {

        $header = $this->authHeaders();

        $body_data = [
            'paymentID' => $paymentID,
        ];

        $response = $this->curlWithBody('/tokenized/checkout/execute', $header, 'POST', json_encode($body_data));

        return $response;
    }
    public function queryPayment($paymentID)
    {
        $header = $this->authHeaders();

        $body_data = [
            'paymentID' => $paymentID,
        ];

        $response = $this->curlWithBody('/tokenized/checkout/payment/status', $header, 'POST', json_encode($body_data));

        return $response;
    }

public function callback(Request $request)
{
    $allRequest = $request->all();

    if (isset($allRequest['status']) && $allRequest['status'] == 'success') {
        // Execute payment
        $response = $this->executePayment($allRequest['paymentID']);
        if (is_null($response)) {
            sleep(1);
            $response = $this->queryPayment($allRequest['paymentID']);
        }

        $res_array = json_decode($response, true);

        if (
            isset($res_array['statusCode'], $res_array['transactionStatus']) &&
            $res_array['statusCode'] == '0000' &&
            $res_array['transactionStatus'] == 'Completed'
        ) {
            // Payment success
            $orderId = decrypt($request->order_id);
            $order = SubscriptionOrder::with('package')->find($orderId);
           

            if (!$order) {
                return redirect()->route('payment.failed', ['error' => 'Order not found']);
            }

            // Update payment status to "paid"
            $order->payment_status = 'paid';
            $order->save();

            // Save payment transaction
            $payment = new PackageOrderTransaction();
            $payment->order_number = $order->order_number;
            $payment->subscription_order_id = $order->id;
            $payment->user_id = $order->user_id;
            $payment->method_id = 2; 
            $payment->amount = $order->total;
            $payment->method_name = 'bkash';
            $payment->transaction_id = $res_array['trxID'];
            $payment->payment_id = $res_array['paymentID'];
            $payment->customerMsisdn = $res_array['customerMsisdn'] ?? 'Unknown';
            $payment->notes = 'Mobile Number: ' . ($res_array['customerMsisdn'] ?? 'N/A') . ', Payment ID: ' . $res_array['paymentID'];
            $payment->status = 1; 
            $payment->save();

            // Notify user if necessary
            // $user = User::find($order->user_id);
            // if ($user && $order->shipping->email) {
            //     Mail::to($order->shipping->email)->send(new OrderPlaced($order));
            // }

            // Clear session data
            session()->forget(['couponId', 'couponDiscount', 'cart.items']);

            // Redirect to success page
            return redirect()->route('checkout.orders.complete', encrypt($order->id))
                ->with('message', 'Order placed successfully!');
        }

        // Payment failed or invalid status
        return redirect()->route('payment.failed', ['error' => 'Invalid payment status']);
    } else {
        // Payment cancelled
        return redirect()->route('payment.failed', ['error' => 'Payment cancelled by user']);
    }
}



    public function getRefund(Request $request)
    {
        return view('bkash.refund');
    }

    public function refundPayment(Request $request)
    {
        $header = $this->authHeaders();

        $body_data = [
            'paymentID' => $request->paymentID,
            'trxID'     => $request->trxID,
        ];

        $response = $this->curlWithBody('/tokenized/checkout/payment/refund', $header, 'POST', json_encode($body_data));

        $res_array = json_decode($response, true);

        $message = "Refund Failed !!";

        if (! isset($res_array['refundTrxID'])) {

            $body_data = [
                'paymentID' => $request->paymentID,
                'amount'    => $request->amount,
                'trxID'     => $request->trxID,
                'sku'       => 'sku',
                'reason'    => 'Quality issue',
            ];

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

        $header    = $this->authHeaders();
        $body_data = [
            'trxID' => $request->trxID,
        ];

        $response = $this->curlWithBody('/tokenized/checkout/general/searchTransaction', $header, 'POST', json_encode($body_data));

        return view('bkash.search')->with([
            'response' => $response,
        ]);
    }

}
