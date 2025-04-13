@extends('backend.layouts.master')
@section('meta')
    <title>Invoice - {{ get_option('title') }}</title>
@endsection
@section('content')
    <div id="invoiceContent"
        style="font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
        <div style="text-align: center; margin-bottom: 20px;">
            <a href="javascript:void(0);">
                <img src="{{ asset('theme/admin/assets/logo/logo.png') }}" alt="Receipt Logo"
                    style="width: 100px; height: 30px;">
            </a>
        </div>
        <div style="text-align: center; margin-bottom: 20px;">
            <h4 style="margin: 0;">{{ get_option('company_name') }},</h4>
            <p style="margin: 0;">Phone Number: {{ get_option('phone_number') }}</p>
            <p style="margin: 0;">Email: <a href="{{ get_option('email') }}">{{ get_option('email') }}</a></p>
        </div>

        <div class="tax-invoice" style="margin-bottom: 20px;">
            <h5 style="text-align: center; margin-bottom: 20px;">----------Returns Invoice----------</h5>

            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="width: 30%; vertical-align: top; padding: 10px;">
                        <div class="invoice-user-name" style="margin-bottom: 10px;"><strong>Return Number:
                            </strong><span>{{ $returns->return_number }}</span></div>
                        <div class="invoice-user-name" style="margin-bottom: 10px;"><strong>Date:
                            </strong><span>{{ $returns->return_date }}</span></div>
                        {{-- <div class="invoice-user-name" style="margin-bottom: 10px;"><strong>Courier:
                            </strong><span>{{ $returns->courier->name ?? 'Null' }}</span></div> --}}
                    </td>
                </tr>
            </table>
        </div>

        <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px; border-bottom: 1px dashed #ddd;">
            <thead>
                <tr style="border-top: 1px dashed #ddd; border-bottom: 1px dashed #ddd;">
                    <th style="padding: 5px; text-align: left;">#Item</th>
                    <th style="padding: 5px; text-align: left;">Price(৳) </th>
                    <th style="padding: 5px; text-align: left;">Qty</th>
                    <th style="padding: 5px; text-align: right;">Total(৳) </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($returns->returnItems as $item)
                    <tr>
                        <td style="padding: 5px;">{{ $loop->iteration }}. {{ $item->product->bangla_name }}</td>
                        <td style="padding: 5px;">{{ $item->price }}</td>
                        <td style="padding: 5px;">{{ $item->qty }}</td>
                        <td style="padding: 5px; text-align: right;">{{ $item->price * $item->qty }}</td>
                    </tr>
                @endforeach
                <tr style="border-top: 1px dashed #ddd;">
                    {{-- <td> </td> --}}
                    <td colspan="4">
                        <div style="text-align:right;">
                            <table style="width: 100%; border-collapse: collapse; margin-bottom: 10px;">
                                <tr>
                                    <td style="padding: 5px;">Item Total:</td>
                                    <td style="padding: 5px; text-align: right;">{{ formatPrice($returns->subtotal) }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 5px;">Discount:</td>
                                    <td style="padding: 5px; text-align: right;">-{{ formatPrice($returns->discount, 2) }}
                                    </td>
                                </tr>
                                @if ($returns->adjust_amount)
                                    <tr>
                                        <td style="padding: 5px;">Adjust Amount:</td>
                                        <td style="padding: 5px; text-align: right;">
                                            {{ formatPrice($returns->adjust_amount) }}</td>
                                    </tr>
                                @endif
                                @if ($returns->packing_charge)
                                    <tr>
                                        <td style="padding: 5px;">Wrapping:</td>
                                        <td style="padding: 5px; text-align: right;">
                                            {{ formatPrice($returns->packing_charge) }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <td style="padding: 5px;">Shipping :</td>
                                    <td style="padding: 5px; text-align: right;">
                                        {{ formatPrice($returns->shipping_charge, 2) }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 5px;">Tax:</td>
                                    <td style="padding: 5px; text-align: right;">{{ formatPrice($returns->tax, 2) }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 5px;"><strong>Total Bill :</strong></td>
                                    <td style="padding: 5px; text-align: right;">
                                        <strong>{{ formatPrice($returns->total, 2) }}</strong></td>
                                </tr>


                                @if ($returns->transactions->sum('amount') > 0)
                                    <tr>
                                        <td style="padding: 5px;">Paid :</td>
                                        <td style="padding: 5px; text-align: right;">
                                            {{ formatPrice($returns->transactions->sum('amount')) }}</td>
                                    </tr>

                                    <tr>
                                        <td style="padding: 5px;"><strong>Due :</strong></td>
                                        <td style="padding: 5px; text-align: right;">
                                            <strong>{{ formatPrice($returns->total - $returns->transactions->sum('amount')) }}</strong>
                                        </td>
                                    </tr>
                                @endif

                            </table>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="4" style="padding: 5px;"><strong>Total in Words:</strong>
                        {{ numberToWords($returns->total) }}</td>
                </tr>
            </tbody>
        </table>

        <div style="text-align: center; margin-top: 20px;">
            {{--            <p>Visit us at :www.englishmoja.com</p> --}}
            <p>Thank You For Shopping With Us.</p>
        </div>

    </div>
    <div style="text-align: center;">
        {{--        <a href="javascript:void(0);" onclick="printDiv('invoiceContent')" style="display: inline-block; margin-top: 20px; padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 4px;">Print Receipt</a> --}}
        <a href="javascript:void(0);" onclick="printDiv()"
            style="display: inline-block; margin-top: 20px; padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 4px;">Print
            Receipt</a>
    </div>
@endsection
@section('script')
    <style>
        @media print {

            body,
            html {
                height: 100%;
                width: 100%;
                margin: 0;
                padding: 0;

            }

            #invoiceContent {
                width: 100%;
                height: 100%;
            }
        }
    </style>

    <script>
        function printDiv() {
            var printContents = document.getElementById("invoiceContent").innerHTML;
            var originalContents = document.body.innerHTML;

            // Set the content of the current window and print
            document.body.innerHTML = printContents;
            window.print();

            // Restore the original content
            document.body.innerHTML = originalContents;
        }
    </script>
@endsection
