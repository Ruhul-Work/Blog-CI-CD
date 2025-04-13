<!DOCTYPE html>
<html>
<head>
    <title>Order Placed</title>
</head>
<body>
<h1>Thank you for your order, {{ $order->shipping->name }}!</h1>
<p>Your order number is: {{ $order->order_number }}</p>
<p>Total Amount: {{ $order->total }}</p>
<p>Discount: {{ $order->discount_amount }}</p>

@if($order->coupon_discount)
    <p>Coupon Discount: {{ $order->coupon_discount }}</p>
@endif

@php
     $transactionSum = $order->transactions->sum('amount');
@endphp

@if($transactionSum)
    <p>Total paid Amount: {{ $transactionSum }}</p>
@endif
</body>
</html>

