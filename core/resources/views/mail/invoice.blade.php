


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Order Placed Successfully- {{ get_option('title') }}</title>
<style>
    body {
        font-family: 'Arial', sans-serif;
        margin: 0;
        padding: 20px;
        background-color: #f0f4f8;
        color: #333;
    }

    .invoice-container {
        max-width: 800px;
        margin: auto;
        background: #ffffff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        border: 1px solid #e0e0e0;
    }

    .invoice-header {
        text-align: center;
        margin-bottom: 20px;
    }

    .invoice-logo {
        max-width: 160px;
        height: auto;
    }

    .invoice-contact-info {
        margin-top: 10px;
        font-size: 14px;
    }

    .invoice-info {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
        background-color: #f7f9fc;
        padding: 15px;
        border-radius: 8px;
    }

    .customer-info,
    .invoice-details {
        width: 48%;
    }

    .invoice-details {
        text-align: right;
    }

    .items-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
        font-size: 14px;
    }

    .items-table th,
    .items-table td {
        border: 1px solid #ddd;
        padding: 12px;
        text-align: left;
    }

    .items-table th {
        background-color: #007bff;
        color: #ffffff;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .items-table tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    .payment-info,
    .transaction-info {
        width: 48%;
    }

    .payment-info h4,
    .transaction-info h4 {
        margin-bottom: 10px;
        color: #007bff;
    }

    .highlight {
        color: #007bff;
        font-weight: bold;
    }

    .danger {
        color: #dc3545;
        font-weight: bold;
    }

    .total {
        font-size: 18px;
        font-weight: bold;
        color: #007bff;
    }

    .note {
        font-size: 12px;
        color: #666;
        margin-top: 10px;
        text-align: center;
    }

    /* Print Styles */
    @media print {
        body {
            padding: 0;
            background: none;
        }

        .invoice-container {
            border: none;
            box-shadow: none;
        }
    }

    /* Responsive Styles */
    @media (max-width: 768px) {
        .invoice-info {
            flex-direction: column;
        }

        .customer-info,
        .invoice-details {
            width: 100%;
            text-align: left;
            margin-bottom: 10px;
        }

        .invoice-details {
            text-align: left;
        }

        .items-table th,
        .items-table td {
            padding: 10px;
        }

        .items-table {
            font-size: 12px;
        }

        .invoice-contact-info {
            font-size: 12px;
        }

        .total {
            font-size: 16px;
        }

        .payment-info,
        .transaction-info {
            width: 100%;
            margin-bottom: 10px;
        }
    }

    @media (max-width: 600px) {
        .invoice-info {
            flex-direction: column;
        }

        .customer-info,
        .invoice-details {
            width: 100%;
            margin-bottom: 10px;
        }

        .items-table {
            font-size: 12px;
        }

        .invoice-contact-info {
            font-size: 12px;
        }

        .total {
            font-size: 16px;
        }

        .payment-info,
        .transaction-info {
            width: 100%;
            margin-bottom: 10px;
        }

        .items-table th,
        .items-table td {
            font-size: 11px;
            padding: 8px;
        }
    }

    @media (max-width: 480px) {
        .invoice-header,
        .invoice-contact-info {
            font-size: 10px;
        }

        .invoice-container {
            padding: 20px;
        }

        .items-table th,
        .items-table td {
            font-size: 10px;
            padding: 6px;
        }

        .total {
            font-size: 14px;
        }

        .note {
            font-size: 10px;
        }
    }
</style>

</head>

<body>
    <div class="invoice-container">
        <div class="invoice-header">
            <div class="invoice-logo-container">
                <img class="invoice-logo" src="{{ asset(get_option('logo')) }}" alt="Logo">
            </div>
            <div class="invoice-contact-info">
                 <p>{{ get_option('phone_number') }}</p>
                <p>{{ get_option('email') }}</p>
                <p>{{ get_option('address') ?? 'N/A' }}</p>
            </div>
        </div>
        <div class="invoice-info">
            <div class="customer-info">
                <h3>Customer Information</h3>
                <p><strong>Name:</strong> {{ $order->shipping->name }}</p>
                <p><strong>Email:</strong> {{ $order->shipping->email }}</p>
                <p><strong>Phone:</strong> {{ $order->shipping->phone }}</p>
                <p><strong>Address:</strong> {{ $order->shipping->address }}</p>
            </div>
            <div class="invoice-details">
                <h3>Invoice Information</h3>
                <p><strong>Invoice No:</strong> {{ $order->order_number }}</p>
                <p><strong>Date:</strong> {{ $order->created_at->format('Y-m-d') }}</p>
                <p><strong>Total:</strong> {{ $order->total }} {{ get_option('currency_symbol') }}</p>
            </div>
        </div>
        <table class="items-table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->orderItems as $item)
                    <tr>
                        <td>{{ $loop->iteration }}. {{ $item->product->bangla_name }}</td>
                        <td>{{ $item->price }} {{ get_option('currency_symbol') }}</td>
                        <td>{{ $item->qty }}</td>
                        <td>{{ $item->price * $item->qty }} {{ get_option('currency_symbol') }}</td>
                    </tr>
                    @if ($item->product->isBundle == 1)
                        @foreach ($item->product->bundleProducts as $bundleProduct)
                            <tr>
                                <td style="padding-left: 30px;">{{ $loop->parent->iteration }}.{{ $loop->iteration }}. {{ $bundleProduct->name }} (x{{ $item->qty * $bundleProduct->quantity }})</td>
                                <td>{{ $bundleProduct->price }} {{ get_option('currency_symbol') }}</td>
                                <td>{{ $item->qty * $bundleProduct->quantity }}</td>
                                <td>{{ $bundleProduct->price * ($item->qty * $bundleProduct->quantity) }} {{ get_option('currency_symbol') }}</td>
                            </tr>
                        @endforeach
                    @endif
                @endforeach
            </tbody>
        </table>
        <div class="invoice-footer" style="display: flex; justify-content: space-between; align-items: flex-start; margin-top: 20px; border-top: 2px solid #007bff; padding-top: 15px;">
            <div class="transaction-info" style="flex: 1; border-right: 1px solid #e0e0e0; padding-right: 15px;">
                <h4>Transaction Details</h4>
                <table style="width: 100%; border-collapse: collapse;">
                    <tbody>
                        <tr>
                            <td style="padding: 8px 0;">Paid:</td>
                            <td class="highlight" style="text-align: right;">{{ formatPrice($order->transactions->sum('amount')) }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 8px 0;">Due:</td>
                            <td class="danger" style="text-align: right;">{{ formatPrice($order->total - $order->transactions->sum('amount')) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="payment-info" style="flex: 1; text-align: right; padding-left: 15px;">
                <h4>Order Summary</h4>
                <table style="width: 100%; text-align: right; border-collapse: collapse;">
                    <tbody>
                        <tr>
                            <td style="padding: 8px 0;">Subtotal:</td>
                            <td class="highlight" style="text-align: right;">{{ formatPrice($order->subtotal) }}</td>
                        </tr>
                        <tr>
                            <td class="danger" style="padding: 8px 0;">Discount:</td>
                            <td class="danger" style="text-align: right;">{{ formatPrice($order->discount, 2) }}</td>
                        </tr>
                        @if ($order->adjust_amount)
                            <tr>
                                <td style="padding: 8px 0;">Adjust Amount:</td>
                                <td style="text-align: right;">{{ formatPrice($order->adjust_amount) }}</td>
                            </tr>
                        @endif
                        @if ($order->coupon_discount)
                            <tr>
                                <td style="padding: 8px 0;">Coupon Discount:</td>
                                <td style="text-align: right;">{{ formatPrice($order->coupon_discount) }}</td>
                            </tr>
                        @endif
                        @if ($order->packing_charge)
                            <tr>
                                <td style="padding: 8px 0;">Packing Charge:</td>
                                <td style="text-align: right;">{{ formatPrice($order->packing_charge) }}</td>
                            </tr>
                        @endif
                        <tr>
                            <td style="padding: 8px 0;">Shipping:</td>
                            <td style="text-align: right;">{{ formatPrice($order->shipping_charge, 2) }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 8px 0;">Tax:</td>
                            <td style="text-align: right;">{{ formatPrice($order->tax, 2) }}</td>
                        </tr>
                        <tr class="total">
                            <td style="padding: 8px 0; font-weight: bold;">Grand Total:</td>
                            <td style="text-align: right; font-weight: bold;">{{ formatPrice($order->total, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="note">
            <p>Thank you for your purchase!</p>
        </div>
    </div>
</body>

</html>

