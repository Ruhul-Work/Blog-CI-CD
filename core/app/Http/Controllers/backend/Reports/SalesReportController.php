<?php

namespace App\Http\Controllers\backend\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\PaymentMethod;
use App\Models\OrderStatus;
use App\Models\Order;
use App\Models\Courier;
use Carbon\Carbon;

class SalesReportController extends Controller
{

    public function salesReport()
    {
        return view('backend.modules.reports.sales-report');
    }
    public function DailySales()
    {
        return view('backend.modules.reports.sales');
    }
    

  public function dailySalesAjax(Request $request)
{
    $columns = ['date'];
    $draw = $request->draw;
    $row = $request->start;
    $rowPerPage = $request->length; // Rows per page
    $columnIndex = $request->order[0]['column']; // Column index
    $columnName = $columns[$columnIndex] ?? $columns[0]; // Column name
    $columnSortOrder = $request->order[0]['dir']; // asc or desc

    // Subquery to calculate daily aggregates
    $subQuery = DB::table('orders as o')
        ->whereNull('o.deleted_at') // Exclude deleted orders
        ->whereNotIn('o.order_status_id', [5, 6, 8]) // Exclude specific order statuses, cancel 5,return 6, hold 8
        ->selectRaw('DATE(o.created_at) as date') // Extract date
        ->selectRaw('COUNT(*) as total_orders') // Total orders
        ->selectRaw('SUM(o.payment_status = "paid") as paid_orders') // Total paid orders
        ->selectRaw('SUM(o.payment_status = "unpaid") as unpaid_orders') // Total unpaid orders
        ->selectRaw('SUM(o.total) as total_order_amount') // Total order amount
        ->selectRaw('SUM(CASE WHEN o.payment_status = "paid" THEN o.total ELSE 0 END) as paid_order_total_amount') // Paid order amount
        ->selectRaw('SUM(CASE WHEN o.payment_status = "unpaid" THEN o.total ELSE 0 END) as unpaid_order_total_amount') // Unpaid order amount
        ->when($request->has('year') && !empty($request->input('year')), function ($query) use ($request) {
            return $query->whereYear('o.created_at', $request->input('year'));
        })
        ->when($request->has('month') && !empty($request->input('month')), function ($query) use ($request) {
            return $query->whereMonth('o.created_at', $request->input('month'));
        })
        ->when($request->has('datetimes') && !empty($request->input('datetimes')), function ($query) use ($request) {
            $dateRange = explode(' - ', $request->input('datetimes'));
            if (count($dateRange) === 2) {
                $startDate = Carbon::parse($dateRange[0])->startOfDay();
                $endDate = Carbon::parse($dateRange[1])->endOfDay();
                $query->whereBetween('o.created_at', [$startDate, $endDate]);
            }
        })
        ->groupBy('date');

    // Wrap the subquery to apply pagination, ordering, and sorting
    $query = DB::table(DB::raw("({$subQuery->toSql()}) as daily_totals"))
        ->mergeBindings($subQuery) // Bind parameters from the subquery
        ->orderBy($columnName, $columnSortOrder);

    // Get total records after filtering
    $totalFiltered = $query->count();

    // Apply pagination
    $data = $query
        ->offset($row)
        ->limit($rowPerPage)
        ->get();

    // Prepare data for DataTables
    $allData = [];
    foreach ($data as $key => $item) {
        $rowData = [];
        $rowData[] = ++$key + $row; // Row number
   $rowData[] = \Carbon\Carbon::parse($item->date)->format('d-m-Y'); // Format date as day-month-year

      
      $rowData[] = 
    '<strong>Quantity:</strong> ' . $item->total_orders . '<br>' .
    '<strong>Amount:</strong> ৳' . ($item->total_order_amount ?? 0); // Total orders with bold labels

$rowData[] = 
    '<strong>Quantity:</strong> ' . $item->paid_orders . '<br>' .
    '<strong>Amount:</strong> ৳' . ($item->paid_order_total_amount ?? 0); // Paid orders with bold labels

$rowData[] = 
    '<strong>Quantity:</strong> ' . $item->unpaid_orders . '<br>' .
    '<strong>Amount:</strong> ৳' . ($item->unpaid_order_total_amount ?? 0); // Unpaid orders with bold labels


        $allData[] = $rowData;
    }

    // Response structure for DataTables
    $response = [
        "draw" => intval($draw),
        "recordsTotal" => $totalFiltered,
        "recordsFiltered" => $totalFiltered,
        "data" => $allData,
    ];

    return response()->json($response);
}

  
    public function dailySalesAjaxOld(Request $request)
    {
        $columns = ['product_id', 'product_name', 'order_date', 'total_order', 'total_price'];
        $draw = $request->draw;
        $row = $request->start;
        $rowPerPage = $request->length;
        $columnIndex = $request->order[0]['column'];
        $columnName = $columns[$columnIndex] ?? $columns[0];
        $columnSortOrder = $request->order[0]['dir'];
        $searchValue = $request->search['value'];



        $query = DB::table('products as p')
            ->leftJoin('orders_items as items', 'p.id', '=', 'items.product_id')
            ->select(
                'p.id as product_id',
                'p.english_name as product_name',
                DB::raw('DATE(items.created_at) as order_date'),
                DB::raw('COUNT(DISTINCT items.order_id) as total_order'), // Count distinct order IDs
                DB::raw('SUM(items.total) as total_price')
            )
            ->whereNull('items.deleted_at') // Exclude soft-deleted rows in orders_items

            ->groupBy('p.id', DB::raw('DATE(items.created_at)'));




        if (!empty($searchValue)) {
            $query->where('p.english_name', 'like', '%' . $searchValue . '%');
        }

        // Apply date range filter if provided
        if ($request->has('datetimes') && !empty($request->datetimes)) {
            $range = explode(" - ", $request->datetimes);

            if (count($range) === 2) {
                $startDate = trim($range[0]);
                $endDate = trim($range[1]);

                $startDate = date("Y-m-d", strtotime($startDate));
                $endDate = date("Y-m-d", strtotime($endDate));

                $query->whereDate('items.created_at', '>=', $startDate)
                    ->whereDate('items.created_at', '<=', $endDate);
            }
        }

        // Get total records count after filters
        $totalFiltered = $query->get()->count();

        // Get paginated results ordered by total_quantity in descending order
        $allSales = $query
            ->orderBy('total_order', 'desc') // Order by total quantity in descending order
            ->offset($row)
            ->limit($rowPerPage)
            ->get();

        // Prepare data for DataTables
        $allData = [];
        foreach ($allSales as $key => $sales) {
            $data = [];
            $data[] = ++$key + $row;
            $data[] = $sales->order_date;
            $data[] = $sales->product_name;
            $data[] = '<span class="badge badge-linesuccess mb-2">' . 'Qty: ' . ($sales->total_order ?? 0);
            $data[] = '<span class="badge badge-linesuccess mb-2">' . 'Tk: ' . ($sales->total_price ?? 0);

            $allData[] = $data;
        }

        $response = [
            "draw" => intval($draw),
            "iTotalRecords" => $totalFiltered,
            "iTotalDisplayRecords" => $totalFiltered,
            "aaData" => $allData,
        ];

        return response()->json($response);
    }
    public function salesMonthly()
    {
        return view('backend.modules.reports.monthly-sales');
    }

   /* public function monthlySalesAjax(Request $request)
    {
        $columns = ['months', 'total_order', 'total_price'];
        $draw = $request->draw;
        $row = $request->start;
        $rowPerPage = $request->length;
        $columnIndex = $request->order[0]['column'];
        $columnName = $columns[$columnIndex] ?? $columns[0];
        $columnSortOrder = $request->order[0]['dir'];
        $searchValue = $request->search['value'];


        $query = DB::table('orders as order')->select(
            DB::raw("DATE_FORMAT(order.created_at, '%M %Y') as months"),
            DB::raw('COUNT(order.id) as total_order'),
            DB::raw('SUM(order.total) as total_price')
        )
             ->whereNull('order.deleted_at')
            ->where('order.created_at', '>=', DB::raw('DATE_SUB(CURDATE(), INTERVAL 12 MONTH)'))
            ->where('deleted_at',NULL)
            ->groupBy(DB::raw("months"));



        if ($request->has('year') && !empty($request->year)) {
            $query->whereYear('order.created_at', $request->year);
        }

        if ($request->has('month') && !empty($request->month)) {
            $query->whereMonth('order.created_at', $request->month);
        }

        $totalFiltered = $query->get()->count();

        $monthlySales = $query
            ->orderBy($columnName, $columnSortOrder)
            ->offset($row)
            ->limit($rowPerPage)
            ->get();

        $allData = [];
        foreach ($monthlySales as $key => $sales) {
            $data = [];
            $data[] = ++$key + $row; // Incrementing key for row number
            $data[] = $sales->months; // Display the formatted month name and year
            $data[] = 'Qty: ' . ($sales->total_order ?? 0);
            $data[] = 'Tk: ' . ($sales->total_price ?? 0);

            $allData[] = $data;
        }

        $response = [
            "draw" => intval($draw),
            "iTotalRecords" => $totalFiltered,
            "iTotalDisplayRecords" => $totalFiltered,
            "aaData" => $allData,
        ];

        return response()->json($response);
    }*/
    
    public function monthlySalesAjax(Request $request)
{
    $columns = ['months', 'total_order', 'total_price'];
    $draw = $request->draw;
    $row = $request->start;
    $rowPerPage = $request->length;
    $columnIndex = $request->order[0]['column'];
    $columnName = $columns[$columnIndex] ?? $columns[0];
    $columnSortOrder = $request->order[0]['dir'];
    $searchValue = $request->search['value'];

    $query = DB::table('orders as order')->select(
        DB::raw("DATE_FORMAT(order.created_at, '%M %Y') as months"),
        DB::raw('COUNT(order.id) as total_order'),
        DB::raw('SUM(order.total) as total_price'),
        DB::raw('SUM(CASE WHEN order.payment_status = "paid" THEN 1 ELSE 0 END) as total_paid'),
        DB::raw('SUM(CASE WHEN order.payment_status = "unpaid" THEN 1 ELSE 0 END) as total_unpaid'),
        DB::raw('SUM(CASE WHEN order.payment_status = "paid" THEN order.total ELSE 0 END) as paid_value'),
        DB::raw('SUM(CASE WHEN order.payment_status = "unpaid" THEN order.total ELSE 0 END) as unpaid_value')
    )
        ->where('order.created_at', '>=', DB::raw('DATE_SUB(CURDATE(), INTERVAL 12 MONTH)'))
        ->where('deleted_at', null)
        ->groupBy(DB::raw("months"));

    if ($request->has('year') && !empty($request->year)) {
        $query->whereYear('order.created_at', $request->year);
    }

    if ($request->has('month') && !empty($request->month)) {
        $query->whereMonth('order.created_at', $request->month);
    }

    $totalFiltered = $query->get()->count();

    $monthlySales = $query
        ->orderBy($columnName, $columnSortOrder)
        ->offset($row)
        ->limit($rowPerPage)
        ->get();

    $allData = [];
    foreach ($monthlySales as $key => $sales) {
        $data = [];
        $data[] = ++$key + $row; // Incrementing key for row number
        $data[] = $sales->months; // Display the formatted month name and year
        // $data[] = 'Paid: ' . ($sales->total_paid ?? 0) . " Unpaid: " . ($sales->total_unpaid ?? 0) . " & Total: " . ($sales->total_order ?? 0);
        // $data[] = 'Paid Value: ' . ($sales->paid_value ?? 0) . " Due Value: " . ($sales->unpaid_value ?? 0) . " & Total: " . ($sales->total_price ?? 0);


$data[] = 'Paid: ' . ($sales->total_paid ?? 0) . '<br>' .
          'Unpaid: ' . ($sales->total_unpaid ?? 0) . '<br>' .
          'Total: ' . ($sales->total_order ?? 0);

$data[] = 'Paid Amount: ' . ($sales->paid_value ?? 0) . '<br>' .
          'Due Amount: ' . ($sales->unpaid_value ?? 0) . '<br>' .
          'Total: ' . ($sales->total_price ?? 0);

        $allData[] = $data;
    }

    $response = [
        "draw" => intval($draw),
        "iTotalRecords" => $totalFiltered,
        "iTotalDisplayRecords" => $totalFiltered,
        "aaData" => $allData,
    ];

    return response()->json($response);
}


    public function salesYearly()
    {
        return view('backend.modules.reports.yearly-sales');
    }

    public function yearlySalesAjax(Request $request)
    {
        $columns = ['year', 'total_quantity', 'total_price'];
        $draw = $request->draw;
        $row = $request->start;
        $rowPerPage = $request->length;
        $columnIndex = $request->order[0]['column'];
        $columnName = $columns[$columnIndex] ?? $columns[0];
        $columnSortOrder = $request->order[0]['dir'];
        $searchValue = $request->search['value'];



        $query = DB::table('orders as items')->select(
            DB::raw("YEAR(items.created_at) as year"),
            DB::raw('COUNT(items.id) as total_order'),
            DB::raw('SUM(items.total) as total_price')
        )
             ->whereNull('items.deleted_at')
            ->where('items.created_at', '>=', DB::raw('DATE_SUB(CURDATE(), INTERVAL 3 YEAR)'))
            ->groupBy(DB::raw("YEAR(items.created_at)"));

        // Apply search filter by product name


        // Apply date range filter if provided
        if ($request->has('datetimes') && !empty($request->datetimes)) {
            $range = explode(" - ", $request->datetimes);

            if (count($range) === 2) {
                $startDate = trim($range[0]);
                $endDate = trim($range[1]);

                $startDate = date("Y-m-d", strtotime($startDate));
                $endDate = date("Y-m-d", strtotime($endDate));

                $query->whereDate('items.created_at', '>=', $startDate)
                    ->whereDate('items.created_at', '<=', $endDate);
            }
        }

        // Get total records count after filters
        $totalFiltered = $query->get()->count();

        // Get paginated results ordered by year in descending order
        $yearlySales = $query
            ->orderBy($columnName, $columnSortOrder)
            ->offset($row)
            ->limit($rowPerPage)
            ->get();

        // Prepare data for DataTables
        $allData = [];
        foreach ($yearlySales as $key => $sales) {
            $data = [];
            $data[] = ++$key + $row; // Incrementing key for row number
            $data[] = $sales->year; // Display the year
            $data[] = 'Qty: ' . ($sales->total_order ?? 0);
            $data[] = 'Tk: ' . ($sales->total_price ?? 0);

            $allData[] = $data;
        }

        $response = [
            "draw" => intval($draw),
            "iTotalRecords" => $totalFiltered,
            "iTotalDisplayRecords" => $totalFiltered,
            "aaData" => $allData,
        ];

        return response()->json($response);
    }

    // payments sales
    public function salesByPayments()
    {

        $payment_status = ['paid', 'unpaid', 'partial'];

        $order_types = ['retail', 'wholesale', 'guest'];

        $paymentmethods = PaymentMethod::OrderBy('id', 'desc')->get();
        $orderstatus = OrderStatus::OrderBy('id', 'desc')->get();

        return view('backend.modules.reports.sales-payments', compact('payment_status', 'paymentmethods', 'orderstatus', 'order_types'));
    }
    public function salesByPaymentsAjax(Request $request)
    {
        $columns = ['order_number', 'order_type', 'payment_status', 'sale_date', 'name'];

        $draw = $request->draw;
        $row = $request->start;
        $rowPerPage = $request->length;
        $columnIndex = $request->order[0]['column'];
        $columnName = $columns[$columnIndex] ?? $columns[0];
        $columnSortOrder = $request->order[0]['dir'];
        $searchValue = $request->search['value'];

        // Initialize the query
        $query = Order::with(['paymentMethods', 'status'])
            ->orderBy($columnName, $columnSortOrder);

        // Check payment methods filter
        if ($request->has('payment_methods') && !empty($request->payment_methods)) {
            // Check if 'All' is selected
            if (!in_array('#', $request->payment_methods)) {
                $query->whereHas('paymentMethods', function ($q) use ($request) {
                    $q->whereIn('payment_methods.id', $request->payment_methods);
                });
            }
        }

        // Check order status filter
        if ($request->has('order_status') && !empty($request->order_status)) {
            // Check if 'All' is selected
            if (!in_array('#', $request->order_status)) {
                $query->whereHas('status', function ($q) use ($request) {
                    $q->whereIn('id', $request->order_status);
                });
            }
        }

        // Check order types filter
        if ($request->has('order_types') && !empty($request->order_types)) {
            // Check if 'All' is selected
            if (!in_array('#', $request->order_types)) {
                $query->whereIn('order_type', $request->order_types);
            }
        }

        // Check payment status filter
        if ($request->has('payment_status') && !empty($request->payment_status)) {
            // Check if 'All' is selected
            if (!in_array('#', $request->payment_status)) {
                $query->whereIn('payment_status', $request->payment_status);
            }
        }

        // Date range filter
        if ($request->datetimes) {
            $range = explode(" - ", $request->datetimes);
            if (count($range) === 2) {
                $startDate = date("Y-m-d", strtotime(trim($range[0])));
                $endDate = date("Y-m-d", strtotime(trim($range[1])));
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }
        }

        // Search functionality
        if (!empty($searchValue)) {
            $query->where('order_number', 'LIKE', "%$searchValue%");
        }

        // Fetch total filtered records
        $totalFiltered = $query->count();
        // Fetch paginated records
        $orders = $query->offset($row)->limit($rowPerPage)->get();

        // Prepare data for DataTables
        $allData = [];
        foreach ($orders as $key => $order) {
            $pStatus = '';
            if ($order->payment_status === 'paid') {
                $pStatus = '<span class="badge badge-linesuccess">' . ucfirst($order->payment_status) . '</span>';
            } elseif ($order->payment_status === 'partial') {
                $pStatus = '<span class="badge badge-linedanger">' . ucfirst($order->payment_status) . '</span>';
            } else {
                $pStatus = '<span class="badge badge-linedanger">' . ucfirst($order->payment_status) . '</span>';
            }

            $oStatus = '';
            if ($order->status->name === 'Completed') {
                $oStatus = '<span class="badge badge-linesuccess">' . ucfirst($order->status->name) . '</span>';
            } elseif ($order->status->name === 'Confirmed') {
                $oStatus = '<span class="badge badge-linesuccess">' . ucfirst($order->status->name) . '</span>';
            } else {
                $oStatus = '<span class="badge badge-linedanger">' . ucfirst($order->status->name) . '</span>';
            }

            $data = [];
            $data[] = ++$key + $row;
            $data[] = '<b>' . $order->order_number . '</b>';
            $data[] = '<span class="badge badge-linesuccess">' . ucfirst($order->order_type) . '</span>';
            $data[] = $pStatus;
            $data[] = $oStatus;
            $data[] = '<span class="badge badge-linesuccess">' . ucfirst(implode(', ', $order->paymentMethods->pluck('name')->toArray())) . '</span>';
            $data[] = $order->sale_date;
            $data[] = $order->total;

            $allData[] = $data;
        }

        return response()->json([
            "draw" => intval($draw),
            "iTotalRecords" => $totalFiltered,
            "iTotalDisplayRecords" => $totalFiltered,
            "aaData" => $allData,
        ]);
    }
    // courier report 
    public function courierReport()
    {

        $couriers = Courier::OrderBy('id', 'desc')->get();

        $payment_status = ['paid', 'unpaid', 'partial'];

        $orderstatus = OrderStatus::whereIn('name', ['Confirmed','Pending','Packaging'])
            ->orderBy('id', 'desc')
            ->get();
//whereIn('name', ['Confirmed','Pending','Packaging'])

        return view('backend.modules.reports.courier-reports', compact('orderstatus', 'couriers', 'payment_status'));
    }

   

     public function courierReportAjax(Request $request)
{
    $columns = ['id']; // Add more columns as needed
    $draw = intval($request->draw);
    $row = intval($request->start);
    $rowPerPage = intval($request->length); // Number of records per page
    $columnIndex = $request->order[0]['column']; // Column index for sorting
    $columnName = $columns[$columnIndex] ?? $columns[0]; // Get the column name to sort by
    $columnSortOrder = $request->order[0]['dir']; // Sort order (asc or desc)
    $searchValue = $request->search['value'] ?? ''; // Search value

    // Initialize the query with eager loading
    // $query = Order::with(['courier', 'status', 'shipping.city', 'shipping.upazila'])
    //     ->orderBy($columnName, $columnSortOrder);
    
    
     $query = Order::with(['courier', 'status', 'shipping.city', 'shipping.upazila'])
    ->whereHas('status', function($q) {
        $q->whereIn('name', ['confirmed', 'pending','packaging']); // Assuming 'name' is the field for status in the 'status' table
    })
    ->orderBy($columnName, $columnSortOrder);

    // Apply payment methods filter
    if ($request->has('payment_methods') && !empty($request->payment_methods) && !in_array('#', $request->payment_methods)) {
        $query->whereHas('paymentMethods', function ($q) use ($request) {
            $q->whereIn('payment_methods.id', $request->payment_methods);
        });
    }

    // Apply order status filter
    if ($request->has('order_status') && !empty($request->order_status) && !in_array('#', $request->order_status)) {
        $query->whereHas('status', function ($q) use ($request) {
            $q->whereIn('id', $request->order_status);
        });
    }

    // Apply courier filter
    if ($request->has('courier') && !empty($request->courier) && !in_array('#', $request->courier)) {
        $query->whereIn('courier_id', $request->courier);
    }



    if ($request->has('payment_status') && !empty($request->payment_status)) {
        // Check if 'All' is selected
        if (!in_array('#', $request->payment_status)) {
            $query->whereIn('payment_status', $request->payment_status);
        }
    }

    // Apply date range filter
    if (!empty($request->datetimes)) {
        $range = explode(" - ", $request->datetimes);
        if (count($range) === 2) {
            // Use correct date format for your database
           // $startDate = \Carbon\Carbon::createFromFormat('m/d/Y', trim($range[0]))->startOfDay();
            //$endDate = \Carbon\Carbon::createFromFormat('m/d/Y', trim($range[1]))->endOfDay();
            $startDate = $range[0] . ' 00:00:00'; // Start of the day
            $endDate = trim($range[1]) . ' 23:59:59'; // End of the day
            //dd($startDate,$endDate);
            $query->where('confirm_at', '>=', $startDate)
                     ->where('confirm_at', '<=', $endDate);
        }
    }

    // Apply search functionality
    if (!empty($searchValue)) {
        $query->where('order_number', 'LIKE', "%$searchValue%");
    }

    // Count total filtered records
    $totalFiltered = $query->count();

    // Fetch paginated records
    $orders = $query->offset($row)->limit($rowPerPage)->get();

    // Prepare data for DataTables
    $allData = [];
    
    foreach ($orders as $order) {
        $data = [];

        // Format order number with ash text color and icon
        $data[] = '<b style="color: #5b6670;"><i class="fas fa-hashtag" style="color: #5b6670;"></i> ' . $order->order_number . '</b>';

        // Format user name with ash text and icon
        $data[] = '<i class="fas fa-user" style="color: #5b6670;"></i> <span style="color: #5b6670;">' . ($order->shipping->name ?? 'N/A') . '</span>';

        // Format shipping address with ash text and icon
        $data[] = '<i class="fas fa-map-marker-alt" style="color: #5b6670;"></i> 
        <span style="color: #5b6670;">' .
            ($order->shipping->address ?? 'N/A') . ', ' .
            ($order->shipping->city->name ?? '') . ', ' .
            ($order->shipping->upazila->name ?? '') .
            '</span>';

        // Format shipping phone with ash text and icon
        $data[] = '<i class="fas fa-phone" style="color: #5b6670;"></i> <span style="color: #5b6670;">' . ($order->shipping->phone ?? 'N/A') . '</span>';

        // Format order total
        $data[] = number_format($order->total, 2);

        // Customer note formatted with italic and ash color
        $data[] = '<em style="color: #5b6670;">' . ($order->customer_note ?? 'No notes') . '</em>';

        // Display company name with ash text and icon
        $data[] = '<i class="fas fa-building" style="color: #5b6670;"></i> <span style="color: #5b6670;">' . (get_option('company_name') ?? 'N/A') . '</span>';

        // Display phone number with ash text and icon
        $data[] = '<i class="fas fa-envelope" style="color: #5b6670;"></i> <span style="color: #5b6670;">' . (get_option('phone_number') ?? 'N/A') . '</span>';

        $allData[] = $data;
    }

    return response()->json([
        "draw" => $draw,
        "iTotalRecords" => $totalFiltered,
        "iTotalDisplayRecords" => $totalFiltered,
        "aaData" => $allData,
    ]);
}

    
}
