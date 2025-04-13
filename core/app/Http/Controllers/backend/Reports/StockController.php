<?php

namespace App\Http\Controllers\backend\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Stock;
use App\Models\Product;
use App\Jobs\ProcessStockUpdatesJob;

class StockController extends Controller
{
    public function index()
    {

        return view('backend.modules.reports.stock');
    }


    public function triggerStockUpdate()
    {
       
       
       
DB::table('products')
    ->join('stocks', 'products.id', '=', 'stocks.product_id')

    ->update([
        'products.stock' => DB::raw("
            (
                SELECT 
                    COALESCE(SUM(CASE WHEN stocks.stock_type = 'purchase' THEN stocks.item_qty ELSE 0 END), 0) +
                    COALESCE(SUM(CASE WHEN stocks.stock_type = 'return' THEN stocks.item_qty ELSE 0 END), 0) -
                    COALESCE(SUM(CASE WHEN stocks.stock_type = 'sale' THEN stocks.item_qty ELSE 0 END), 0)
                FROM stocks
                WHERE stocks.product_id = products.id
                AND stocks.deleted_at IS NULL
            )
        ")
    ]);
    return response()->json(['message' => 'Stock update  successfully.']);



        // // Dispatch the job to update stock
        // ProcessStockUpdatesJob::dispatch();

        // return response()->json(['message' => 'Stock update, job dispatched successfully.']);
    }
 
    public function stockAjaxOld(Request $request)
    {

        $columns = ['product_id', 'total_stock'];
        $draw = $request->draw;
        $row = $request->start;
        $rowPerPage = $request->length;
        $columnIndex = $request->order[0]['column'];
        $columnName = $columns[$columnIndex] ?? $columns[0];
        $columnSortOrder = $request->order[0]['dir'];
        $searchValue = $request->search['value'];
   
        
    //     $query = DB::table('products')
    // ->select(
    //     'stocks.product_id',
    //     DB::raw('MIN(products.bangla_name) AS bangla_name'),  // Using MIN to get a value from non-aggregated fields
    //     DB::raw('MIN(products.thumb_image) AS thumb_image'),  // You can also use MAX depending on your requirement
    //     DB::raw('MIN(products.stock) AS stock'),
    //     DB::raw("SUM(CASE WHEN stocks.stock_type = 'return' THEN stocks.item_subtotal ELSE 0 END) AS total_return_amount"),
    //     DB::raw("SUM(CASE WHEN stocks.stock_type = 'return' THEN stocks.item_qty ELSE 0 END) AS total_return"),
    //     DB::raw("SUM(CASE WHEN stocks.stock_type = 'purchase' THEN stocks.item_qty ELSE 0 END) AS total_purchase"),
    //     DB::raw("SUM(CASE WHEN stocks.stock_type = 'purchase' THEN stocks.item_subtotal ELSE 0 END) AS total_purchase_amount"),
    //     DB::raw("SUM(CASE WHEN stocks.stock_type = 'sale' THEN stocks.item_qty ELSE 0 END) AS total_order"),
    //     DB::raw("SUM(CASE WHEN stocks.stock_type = 'sale' THEN stocks.item_subtotal ELSE 0 END) AS total_order_amount"),
    //     DB::raw("
    //         SUM(CASE WHEN stocks.stock_type = 'return' THEN stocks.item_qty ELSE 0 END) +
    //         SUM(CASE WHEN stocks.stock_type = 'purchase' THEN stocks.item_qty ELSE 0 END) -
    //         SUM(CASE WHEN stocks.stock_type = 'sale' THEN stocks.item_qty ELSE 0 END) AS total_stock
    //     ")
    // )
    // ->leftJoin('stocks', 'products.id', '=', 'stocks.product_id')
    // ->groupBy('stocks.product_id')
    // ->orderBy('stocks.product_id', 'desc')
    // ->limit(10)
    // ->offset(0);
    
    
    
    $query = DB::table('products')
    ->select(
        'stocks.product_id',
        DB::raw('MIN(products.bangla_name) AS bangla_name'),  // Using MIN to get a value from non-aggregated fields
        DB::raw('MIN(products.thumb_image) AS thumb_image'),  // You can also use MAX depending on your requirement
        DB::raw('MIN(products.stock) AS stock'),
        DB::raw("SUM(CASE WHEN stocks.stock_type = 'return' THEN stocks.item_subtotal ELSE 0 END) AS total_return_amount"),
        DB::raw("SUM(CASE WHEN stocks.stock_type = 'return' THEN stocks.item_qty ELSE 0 END) AS total_return"),
        DB::raw("SUM(CASE WHEN stocks.stock_type = 'purchase' THEN stocks.item_qty ELSE 0 END) AS total_purchase"),
        DB::raw("SUM(CASE WHEN stocks.stock_type = 'purchase' THEN stocks.item_subtotal ELSE 0 END) AS total_purchase_amount"),
        DB::raw("SUM(CASE WHEN stocks.stock_type = 'sale' THEN stocks.item_qty ELSE 0 END) AS total_order"),
        DB::raw("SUM(CASE WHEN stocks.stock_type = 'sale' THEN stocks.item_subtotal ELSE 0 END) AS total_order_amount"),
        DB::raw("
            SUM(CASE WHEN stocks.stock_type = 'return' THEN stocks.item_qty ELSE 0 END) +
            SUM(CASE WHEN stocks.stock_type = 'purchase' THEN stocks.item_qty ELSE 0 END) -
            SUM(CASE WHEN stocks.stock_type = 'sale' THEN stocks.item_qty ELSE 0 END) AS total_stock
        ")
    )
    ->leftJoin('stocks', 'products.id', '=', 'stocks.product_id')
    // ->whereNull('products.deleted_at') // Exclude soft-deleted products
     ->whereNull('stocks.deleted_at')   // Exclude soft-deleted stocks
    ->groupBy('stocks.product_id')
    ->orderBy('stocks.product_id', 'desc')
    ->limit(10)
    ->offset(0);

    
    
    
    


        if (!empty($searchValue)) {
            $query->where('products.bangla_name', 'like', '%' . $searchValue . '%');
        }


        if ($request->has('datetimes') && !empty($request->datetimes)) {
            $range = explode(" - ", $request->datetimes);

            if (count($range) === 2) {
                $startDate = trim($range[0]);
                $endDate = trim($range[1]);

                $startDate = date("Y-m-d", strtotime($startDate));
                $endDate = date("Y-m-d", strtotime($endDate));

                $query->whereDate('stocks.created_at', '>=', $startDate)
                    ->whereDate('stocks.created_at', '<=', $endDate);
            }
        }


        $totalDbProducts = $query->count();


        $allStocks = $query
            ->orderBy($columnName, $columnSortOrder)
            ->skip($row)
            ->take($rowPerPage)
            ->get();

        $allData = [];
        foreach ($allStocks as $key => $stock) {

            $checkMark = '<td><label class="checkboxs"><input type="checkbox" data-value="' . $stock->product_id . '"><span class="checkmarks"></span></label></td>';

            $data = [];
            $data[] = $checkMark;
            $data[] = ++$key + $row;

            $data[] = '<div class="productimgname">
                <a href="javascript:void(0);" class="product-img stock-img">
                    <img src="' . image($stock->thumb_image ?? 'default_image_path') . '" alt="' . ($stock->bangla_name ?? '') . '">
                </a>
                <a href="javascript:void(0);">' . ($stock->bangla_name ?? '') . '</a>
            </div>';

            $data[] = '<span class="badge badge-linesuccess mb-2">' . 'Qty: ' . ($stock->total_return ?? 0) . '</span>' . '</br>' . '.<span class="badge badge-linedanger">' . 'amount: ' . ($stock->total_return_amount ?? 0) . '</span>.';



            $data[] = '<span class="badge badge-linesuccess mb-2">' . 'Qty: ' . ($stock->total_purchase ?? 0) . '</span>' . '</br>' . '.<span class="badge badge-linedanger">' . 'amount: ' . ($stock->total_purchase_amount ?? 0) . '</span>.';



            $data[] = '<span class="badge badge-linesuccess mb-2">' . 'Qty: ' . ($stock->total_order ?? 0) . '</span>' . '</br>' . '.<span class="badge badge-linedanger">' . 'amount: ' . ($stock->total_order_amount ?? 0) . '</span>.';

            $data[] = '<span class="badge badge-linedanger">' . ($stock->stock ?? 0) . '</span>';

            $allData[] = $data;
        }

        $response = [
            "draw" => intval($draw),
            "iTotalRecords" => $totalDbProducts,
            "iTotalDisplayRecords" => $totalDbProducts,
            "aaData" => $allData,
        ];

        return response()->json($response);
    }
    
    public function stockAjax(Request $request)
{
    $columns = ['product_id', 'total_stock'];
    $draw = $request->draw;
    $row = $request->start;
    $rowPerPage = $request->length;
    $columnIndex = $request->order[0]['column'];
    $columnName = $columns[$columnIndex] ?? $columns[0];
    $columnSortOrder = $request->order[0]['dir'];
    $searchValue = $request->search['value'];

    $query = DB::table('products')
        ->leftJoin('stocks', 'products.id', '=', 'stocks.product_id')
        ->select(
            'stocks.product_id',
            DB::raw('MIN(products.bangla_name) AS bangla_name'),
            DB::raw('MIN(products.thumb_image) AS thumb_image'),
            DB::raw('MIN(products.stock) AS stock'),
            DB::raw("SUM(CASE WHEN stocks.stock_type = 'return' THEN stocks.item_subtotal ELSE 0 END) AS total_return_amount"),
            DB::raw("SUM(CASE WHEN stocks.stock_type = 'return' THEN stocks.item_qty ELSE 0 END) AS total_return"),
            DB::raw("SUM(CASE WHEN stocks.stock_type = 'purchase' THEN stocks.item_qty ELSE 0 END) AS total_purchase"),
            DB::raw("SUM(CASE WHEN stocks.stock_type = 'purchase' THEN stocks.item_subtotal ELSE 0 END) AS total_purchase_amount"),
            DB::raw("SUM(CASE WHEN stocks.stock_type = 'sale' THEN stocks.item_qty ELSE 0 END) AS total_order"),
            DB::raw("SUM(CASE WHEN stocks.stock_type = 'sale' THEN stocks.item_subtotal ELSE 0 END) AS total_order_amount"),
            DB::raw("
                SUM(CASE WHEN stocks.stock_type = 'return' THEN stocks.item_qty ELSE 0 END) +
                SUM(CASE WHEN stocks.stock_type = 'purchase' THEN stocks.item_qty ELSE 0 END) -
                SUM(CASE WHEN stocks.stock_type = 'sale' THEN stocks.item_qty ELSE 0 END) AS total_stock
            "),
            DB::raw('COUNT(*) OVER() AS total_count') // Total records for pagination
        )
        ->whereNull('stocks.deleted_at')
        ->groupBy('stocks.product_id')
        ->orderBy($columnName, $columnSortOrder);

    if (!empty($searchValue)) {
        $query->where('products.bangla_name', 'like', '%' . $searchValue . '%');
    }

    if ($request->has('datetimes') && !empty($request->datetimes)) {
        $range = explode(" - ", $request->datetimes);

        if (count($range) === 2) {
            $startDate = trim($range[0]);
            $endDate = trim($range[1]);

            $startDate = date("Y-m-d", strtotime($startDate));
            $endDate = date("Y-m-d", strtotime($endDate));

            $query->whereDate('stocks.created_at', '>=', $startDate)
                ->whereDate('stocks.created_at', '<=', $endDate);
        }
    }

    // Apply pagination
    $paginatedQuery = $query->orderBy($columnName, $columnSortOrder)
        ->skip($row)
        ->take($rowPerPage)
        ->get();

    $totalDbProducts = $paginatedQuery->isEmpty() ? 0 : $paginatedQuery->first()->total_count;

    $allData = [];
    foreach ($paginatedQuery as $key => $stock) {
        $checkMark = '<td><label class="checkboxs"><input type="checkbox" data-value="' . $stock->product_id . '"><span class="checkmarks"></span></label></td>';

        $data = [];
        $data[] = $checkMark;
        $data[] = ++$key + $row;

        $data[] = '<div class="productimgname">
            <a href="javascript:void(0);" class="product-img stock-img">
                <img src="' . image($stock->thumb_image ?? 'default_image_path') . '" alt="' . ($stock->bangla_name ?? '') . '">
            </a>
            <a href="javascript:void(0);">' . ($stock->bangla_name ?? '') . '</a>
        </div>';

        $data[] = '<span class="badge badge-linesuccess mb-2">Qty: ' . ($stock->total_return ?? 0) . '</span></br>
            <span class=" badge badge-linedanger">Amount: ' . ($stock->total_return_amount ?? 0) . '</span>.';

        $data[] = '<span class="badge badge-linesuccess mb-2">Qty: ' . ($stock->total_purchase ?? 0) . '</span></br>
            <span class="badge badge-linedanger">Amount: ' . ($stock->total_purchase_amount ?? 0) . '</span>.';

        $data[] = '<span class="badge badge-linesuccess mb-2">Qty: ' . ($stock->total_order ?? 0) . '</span></br>
            <span class="badge badge-linedanger">Amount: ' . ($stock->total_order_amount ?? 0) . '</span>.';

        // $data[] = '<span class="badge badge-linedanger">' . ($stock->stock ?? 0) . '</span>';
        
        // Calculate stock dynamically
$calculatedStock = ($stock->total_purchase ?? 0) + ($stock->total_return ?? 0) - ($stock->total_order ?? 0);

$data[] = '<span class="badge rounded-pill bg-secondary">' . $calculatedStock . '</span>';

        $allData[] = $data;
    }

    $response = [
        "draw" => intval($draw),
        "iTotalRecords" => $totalDbProducts,
        "iTotalDisplayRecords" => $totalDbProducts,
        "aaData" => $allData,
    ];

    return response()->json($response);
}

}
