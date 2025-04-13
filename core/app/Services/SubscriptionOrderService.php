<?php

namespace App\Services;

use App\Models\SubscriptionOrder;
use App\Models\SubscriptionItem;
use App\Models\SubscriptionShipping;
use App\Models\PackageOrderTransaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class SubscriptionOrderService
{
    /**
     * Generate a unique order number.
     */
    public static function generateOrderNumber()
    {
        $lastOrderId = SubscriptionOrder::withTrashed()->max('id');
        $newOrderId = $lastOrderId + 1;
        return 'EMBlog' . date('dmy') . $newOrderId;
    }

    /**
     * Calculate the subscription end date based on start date and duration.
     */
    public static function calculateEndDate($startDate, $durationInDays)
    {
        return Carbon::parse($startDate)->addDays($durationInDays);
    }

    /**
     * Determine the payment status based on total and paid amount.
     */
    public static function determinePaymentStatus($total, $totalPaid)
    {
        if ($totalPaid >= $total) {
            return 'Paid';
        } elseif ($totalPaid > 0) {
            return 'Partial';
        }
        return 'Pending';
    }

    /**
     * Create the order record.
     */
    public static function createOrder($data)
    {
        $order = new SubscriptionOrder();
        $order->order_number = $data['order_number'];
        $order->user_id = $data['user_id'];
        $order->subscription_package_id = $data['subscription_package_id'];
        $order->package_price = $data['package_price'];
        $order->discount = $data['discount'];
        $order->coupon_id = $data['coupon_id'];
        $order->coupon_discount = $data['coupon_discount'];
        $order->subtotal = $data['subtotal'];
        $order->total = $data['total'];
        $order->pay_method = $data['pay_method'];
        $order->pay_amount = $data['pay_amount'];
        $order->payment_status = $data['payment_status'];
        $order->subscription_start_date = $data['subscription_start_date'];
        $order->end_date = $data['end_date'];
        $order->save();

        return $order;
    }

    /**
     * Add items to the order.
     */
    public static function addItems($order, $cartData, $userId, $startDate)
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

    /**
     * Save shipping details.
     */
    public static function saveShipping($order, $data)
    {
        $shipping = new SubscriptionShipping();
        $shipping->order_number = $order->order_number;
        $shipping->subscription_order_id = $order->id;
        $shipping->user_id = $data['user_id'];
        $shipping->name = $data['name'];
        $shipping->mobile_number = $data['mobile_number'];
        $shipping->address = $data['address'];
        $shipping->start_date = $data['start_date'];
        $shipping->save();
    }

    /**
     * Save payment details from session.
     */
    public static function savePayments($order, $userId)
    {
        $paymentDetails = Session::get('payment_details', []);

        foreach ($paymentDetails as $payment) {
            $transaction = new PackageOrderTransaction();
            $transaction->order_number = $order->order_number;
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

        // Clear session payment details
        Session::forget('payment_details');
    }
}
