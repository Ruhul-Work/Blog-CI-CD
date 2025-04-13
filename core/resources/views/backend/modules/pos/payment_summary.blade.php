@if(Session::has('payment_details') && count(Session::get('payment_details')) > 0)
    <table class="table ml-3">
        <thead>
        <tr>
            <th>Description</th>
            <th>Method</th>
            <th>Amount</th>
            <th>Delete</th>
        </tr>
        </thead>
        <tbody>
        @php
            $totalPaid = 0;
        @endphp

        @foreach(Session::get('payment_details') as $key => $payment)
            <tr>
                <td>Advance/{{ $payment['note'] }}</td>
                <td>{{ $payment['methodName'] }}</td>
                <td>{{ $payment['amount'] }}Tk.</td>
                <td>
                        <span class="badge bg-danger" onclick="removeBTN({{$key}})">
                            <i class="fas fa-times"></i>
                        </span>
                </td>
            </tr>
            @php
                $totalPaid += $payment['amount'];
            @endphp
        @endforeach

        <tr>
            <td colspan="2"></td>
            <td class="text-end"><strong>Total Paid:</strong></td>
            <td>{{ $totalPaid }}</td>
        </tr>
        </tbody>
    </table>


@else
    <p>নতুন পেমেন্ট লেনদেন এখানে দেখাবে</p>
@endif


