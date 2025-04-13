<?php
namespace App\Http\Controllers\backend;
use App\Http\Controllers\Controller;
use App\Models\Common;
use App\Models\Coupon;
use App\Models\Courier;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\OrderTransaction;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\Stock;
use App\Models\User;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;


class OrderController extends Controller
{

    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index()
    {
        $orderStatuses=OrderStatus::where('status',1)->get();
        $couriers=Courier::where('status',1)->get();
        $paymentMethods=PaymentMethod::where('status',1)->get();


        return view('backend.modules.order.index',[
            'orderStatuses'=>$orderStatuses,
            'paymentMethods'=>$paymentMethods,
            'couriers'=>$couriers
        ]);
    }

    public function ajaxIndex(Request $request)
    {
        $columns = ["id"];
        $draw = intval($request->draw);
        $row = intval($request->start);
        $rowperpage = intval($request->length);

        $columnIndex = intval($request->order[0]['column']);
        $columnName = !empty($columns[$columnIndex]) ? $columns[$columnIndex] : $columns[0];
        $columnSortOrder = $request->order[0]['dir'];
        $searchValue = $request->search['value'];
        $query = Order::query();


        $query = Order::query();

// Apply filter by order status ID if provided
        if ($request->has('order_status_id') && !empty($request->order_status_id)) {
            $query->where('order_status_id', $request->order_status_id);
        }

// Apply filter by method ID if provided
        if ($request->has('method_id') && !empty($request->method_id)) {
            $method_id = $request->method_id;

            $query->where(function ($query) use ($method_id) {
                $query->orWhereHas('transactions', function ($q) use ($method_id) {
                    $q->where('method_id', $method_id);
                });
            });
        }

// Apply date range filter if provided
        if ($request->has('date_range') && !empty($request->date_range)) {
            $range = explode(" - ", $request->date_range);
            if (count($range) === 2) {
                $startDate = date("Y-m-d", strtotime(trim($range[0])));
                $endDate = date("Y-m-d", strtotime(trim($range[1])));
                $query->whereDate('orders.created_at', '>=', $startDate)
                    ->whereDate('orders.created_at', '<=', $endDate);
            }
        }

// Search filter
        if (!empty($searchValue)) {
            $query->where(function ($q) use ($searchValue) {
                $q->where('order_number', 'like', '%' . $searchValue . '%') // Search by order number
                ->orWhere('id', $searchValue) // Search by order ID
                ->orWhereHas('user', function ($q) use ($searchValue) {
                    $q->where('name', 'like', '%' . $searchValue . '%')
                        ->orWhere('phone', 'like', '%' . $searchValue . '%');
                })
                    ->orWhereHas('status', function ($q) use ($searchValue) {
                        $q->where('name', 'like', '%' . $searchValue . '%');
                    })
                    ->orWhereHas('shipping', function ($q) use ($searchValue) {
                        $q->where('address', 'like', '%' . $searchValue . '%')
                            ->orWhere('name', 'like', '%' . $searchValue . '%')
                            ->orWhere('phone', 'like', '%' . $searchValue . '%');
                    });
            });
        }


        // Get total records count
        $totalRecords = $query->count();

        // Get the filtered records
        $records = $query->with(['user', 'shipping', 'status', 'paymentMethods'])
            ->orderBy($columnName, $columnSortOrder)
            ->skip($row)
            ->take($rowperpage)
            ->get();

        $allData = [];
        foreach ($records as $order) {
            $data = [];

            $data[] = '<td>
            <label class="checkboxs">
                <input type="checkbox" class="checked-row" data-value="' . $order->id . '">
                <span class="checkmarks"></span>
            </label>
        </td>';

            $data[] = '<strong>Name:</strong> ' . ($order->shipping->name ?? 'NULL') . '<br>' .
                '<strong>Phone:</strong> ' . ($order->shipping->phone ?? 'NULL') . '<br>' .
                '<strong>Alternate Phone:</strong> ' . ($order->shipping->alternate_phone ?? 'NULL') . '<br>' .
                '<strong>Email:</strong> ' . ($order->shipping->email ?? 'NULL') . '<br>' .
                '<strong>Address:</strong> ' . ($order->shipping->address ?? 'NULL');

          

            // Generate Order Status Badge HTML using the helper function
            $orderStatusBadge = generateOrderStatusBadge($order->order_status_id);
            
     $data[] = '
    <div class="card-body d-flex flex-wrap gap-2">
        <div class="order-info">
            <strong> ' . $order->order_number . '</strong><br>
            '. $order->source . '<br>
         ' . $order->created_at->format('Y-m-d H:i') . '<br>
        </div>
        <div class="order-status">
            <a href="' . route('orders.updateStatus') . '" class="order-status-link orderStatusChange">' . $orderStatusBadge . '</a>
        </div>
    </div>';
    
    
      $data[] = $order->customer_note;
            
        //     $data[] = '<div class="card-body d-flex flex-wrap gap-2 ">
        //       <a href="' . route('orders.updateStatus') . '" class="order-status-link orderStatusChange">' . $orderStatusBadge . '</a>
        //   </div>';



$data[] = '<strong>Total:</strong> '.formatPrice($order->total). '<br>' .
          '<strong>Product:</strong> '.formatPrice($order->subtotal).'<br>' .
          '<strong>Shipping:</strong> '.formatPrice($order->shipping_charge) . '<br>' .
          '<strong>Coupon:</strong> '.formatPrice($order->coupon_discount) . '<br>' .
          '<strong>Discount:</strong>'.formatPrice($order->discount_amount);
      



            $paymentStatus = trim($order->payment_status);
            $paymentBadgeClass = $paymentStatus === 'unpaid' ? 'badge-linedanger' : ($paymentStatus === 'partial' ? 'badge badges-warning' : 'badge-linesuccess');
            $paymentBadgeText = $paymentStatus === 'unpaid' ? 'Unpaid' : ($paymentStatus === 'partial' ? 'Partial' : 'Paid');
            $data[] = '<div class="card-body d-flex flex-wrap gap-2">
                      <span class="badge ' . $paymentBadgeClass . '">' . $paymentBadgeText . '</span>
                   </div>';

            $paymentMethods = $order->paymentMethods->unique('name')->map(function ($paymentMethod) {
                return '<span class="badge bg-info">' . htmlspecialchars($paymentMethod->name, ENT_QUOTES, 'UTF-8') . '</span>';
            })->implode(' ');

            if (empty($paymentMethods)) {
                $paymentMethods = '<span class="badge bg-danger">Unknown</span>';
            }

            $data[] = $paymentMethods;

            $data[] = '<div class="action-table-data">
                      <div class="edit-delete-action">
                          <a data-bs-toggle="tooltip" data-bs-placement="top" title="Print Invoice" id="downloadInvoice" class="btn btn-primary p-2 me-2" data-href="' . route('orders.invoice', $order->id) . '">
                              <i class="fas fa-file-pdf text-white"></i>
                          </a>
                          <a data-bs-toggle="tooltip" data-bs-placement="top" title="View Transaction Details" class="btn btn-success p-2" href="' . route('orders.transactions.show', $order->id) . '">
                              <i class="fas fa-money-check-alt text-white"></i>
                          </a>
                      </div>
                   </div>';
                   
                   
                   
                   
                   $userId = $order->ordered_by;
                $user = User::find($userId);
                
                if ($user) {
                    $data[] = '<div class="userimgname">
                                  <a href="javascript:void(0);" class="product-img">
                                      <img src="' . image($user->image) . '" alt="user-image">
                                  </a>
                                  <a href="javascript:void(0);">' . $user->name . '</a>
                               </div>';
                } else {
                    $data[] = '<div class="userimgname text-danger">
                                  <a href="javascript:void(0);">User Not Found</a>
                               </div>';
                }

                   

            // $userId = $order->ordered_by;
            // $user=User::find($userId);
            // if ($user) {
            //     $data[] = '<div class="userimgname">
            //               <a href="javascript:void(0);" class="product-img">
            //                   <img src="' . image($user->image) . '" alt="user-image">
            //               </a>
            //               <a href="javascript:void(0);">' . $user->name . '</a>
            //           </div>';
            // }

            $data[] = '<div class="action-table-data">
                      <div class="edit-delete-action">
                          <a class="btn btn-info me-2 p-2" href="' . route('orders.edit', $order->id) . '">
                              <i class="fa fa-edit text-white"></i>
                          </a>
                         
                            <a class="btn btn-dark me-2 p-2" href="' . route('orders.shipping.edit', $order->id) . '"   title="Edit Shipping">
                              <i class="fa fa-location text-white"></i>
                          </a>
                          <a class="btn btn-danger delete-btn p-2 me-2" href="' . route('orders.destroy', $order->id) . '">
                              <i class="fa fa-trash text-white"></i>
                          </a>
                          <a class="btn btn-secondary me-2 p-2" href="' . route('orders.show', $order->id) . '">
                              <i class="fa fa-eye text-white"></i>
                          </a>
                      </div>
                   </div>';

            $allData[] = $data;
        }

        $response = [
            "draw" => $draw,
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecords,
            "aaData" => $allData
        ];

        return response()->json($response);
    }


    public function orderInvoice($id)
    {
        $order = Order::find($id);



        // Render the view to a variable
        $htmlContent = view('backend.modules.order.invoice_new', compact('order'))->render();
//        $order->logAction('Invoice View');

        return response()->json(['htmlContent' => $htmlContent]);
    }


    public function show($id)

    {

        $order = Order::findOrFail($id);

//        dd($product);
//        $order->logAction('show order details order ');


        return view('backend.modules.order.show_details', ['order' => $order,]);
    }



//     public function edit($id)
//     {

//         $currentTimestamp = Carbon::now()->format('Y-m-d');
//         $coupons = Coupon::where('end_date', '>', $currentTimestamp)->get();
//         $paymentMethods=PaymentMethod::all();
//         $userTypes =Common::getPossibleEnumValues('users', 'user_type');

//         $order =Order::findOrFail($id);

//         $orderItems = $order->orderItems;
//         $products = [];

//         foreach ($orderItems as $orderItem) {
// //            dd($orderItem);
//             // Retrieve the product details based on the product_id
//             $product = Product::find($orderItem->product_id);

//             // Check if the product exists
//             if ($product) {
//                 // Build the product array structure
//                 $productArray = [
//                     'id' => $product->id,
//                     'english_name' => $product->english_name,
//                     'current_price' =>$orderItem->price,
//                     'mrp_price' => $product->mrp_price,
//                     'thumb_image' => image($product->thumb_image),
// //                    'category' => $product->category->name,
//                     'subtotal'=>$orderItem->total,
//                     'stock' => $product->stock,
//                     'quantity' => $orderItem->qty,
//                     'product_code'=> $product->product_code,
// //                    'min_order_qty' => $product->min_order_qty,
// //                    'variations' => []
//                 ];



//                 // Add the product array to the products array
//                 $products[] = $productArray;
//             }
//         }

//         $cart = json_encode($products);

//         return view('backend.modules.pos.edit_order',
//             [
//                 'order' => $order,
//                 'cart' => $cart,
//                 'paymentMethods'=>$paymentMethods,
//                 'coupons' =>$coupons,
//                 'userTypes'=>$userTypes,

//             ]);


//     }

public function edit($id)
    {
 
        $currentTimestamp = Carbon::now()->format('Y-m-d');
        $coupons = Coupon::where('end_date', '>', $currentTimestamp)->get();
          $paymentMethods=PaymentMethod::all();
        $userTypes =Common::getPossibleEnumValues('users', 'user_type');
 
        $order =Order::findOrFail($id);
 
        $orderItems = $order->orderItems;
        $products = [];
 
        foreach ($orderItems as $orderItem) {
//            dd($orderItem);
            // Retrieve the product details based on the product_id
            $product = Product::find($orderItem->product_id);
 
            // Check if the product exists
            if ($product) {
                // Build the product array structure
                if ($product->isBundle == 1){
                    $bundleWeight = 0;
                     foreach ($product->bundleProducts as $bundleProduct) {
                          $bundleItem = Product::find($bundleProduct->bundle_product_id);
                            if ($bundleItem) {
                              $bundleWeight += ($bundleItem->weight ?? 0) * $bundleProduct->quantity;
                            }
                        }
                        $product->weight=$bundleWeight;
                   }
 
 
                $productArray = [
                    'id' => $product->id,
                    'english_name' => $product->english_name,
                    'current_price' =>$orderItem->price,
                    'mrp_price' => $product->mrp_price,
                    'thumb_image' => image($product->thumb_image),
//                    'category' => $product->category->name,
                    'subtotal'=>$orderItem->total,
                    'stock' => $product->stock,
                    'quantity' => $orderItem->qty,
                    'product_code'=> $product->product_code,
                    'product_weight'=>$product->weight??0,
//                    'min_order_qty' => $product->min_order_qty,
//                    'variations' => []
                ];
 
 
 
                // Add the product array to the products array
                $products[] = $productArray;
            }
        }
 
        $cart = json_encode($products);
 
        return view('backend.modules.pos.edit_order',
            [
                'order' => $order,
                'cart' => $cart,
                'paymentMethods'=>$paymentMethods,
                'coupons' =>$coupons,
                'userTypes'=>$userTypes,
 
            ]);
 
 
    }



public function destroyAll(Request $request)
{
    $token = base64_decode($request->get('token'));
    $ids = json_decode($token);

    foreach ($ids as $id) {
        if ($order = Order::find($id)) {
              //  status 5 cancelled
                    if($order->order_status_id!=5){
        $this->adjustStockForCancelledOrder($order);
                    }
            $order->delete();
        }
    }

    return response()->json(['success' => 'Orders deleted successfully'], 200);
}


public function destroy($id)
{
    try {
        $order = Order::findOrFail($id);
        
        // Adjust stock for the canceled order
      
           //  status 5 cancelled
                    if($order->order_status_id!=5){
        $this->adjustStockForCancelledOrder($order);
                    }

        // Soft delete the order
        if ($order->delete()) {
            $order->logAction('Order soft deleted');
        }

        return response()->json(['success' => 'Order deleted successfully'], 200);
    } catch (\Exception $e) {
        // Handle exceptions
        return response()->json([
            'error' => 'An error occurred while deleting the order',
            'message' => $e->getMessage()
        ], 500);
    }
}



//     public function destroy($id)
//     {
//         $order= Order::findOrFail($id);
        
//          $this->adjustStockForCancelledOrder($order);

//         // Delete the product
//         $order->delete();
// //        $order->forceDelete();
//         $order->logAction('order  soft deleted');

//         return response()->json(['success' => 'Order deleted successfully'], 200);
//     }






    public function updateOrderStatus(Request $request)
    {
        try {
            $token = base64_decode($request->get("order_ids"));
            $ids = json_decode($token);

            foreach ($ids as $id) {
                $order = Order::find($id);
                if ($order) {
                    $newStatusId = $request->status_id;
                    $order->order_status_id = $newStatusId;
                    if($newStatusId==2){
                       $order->confirm_at=now(); 
                    }
                    
                    $order->save();
                    $order->logAction('Order Status Updated');

                    // If the order status is cancelled (status ID 5)
                    if ($newStatusId == 5) {
                        $this->adjustStockForCancelledOrder($order);
                    }
                }
            }

            // If the loop completes without errors, return a success response
            return response()->json(['status' => 'success', 'message' => ' Status Successfully Updated']);
        } catch (\Exception $e) {
            // If an exception occurs, return an error response
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    // Private method to adjust stock when order is cancelled
    private function adjustStockForCancelledOrder(Order $order)
    {
        DB::transaction(function () use ($order) {
            foreach ($order->stocks as $stock) {
                // Retrieve the stock entry for the product and order
                $stockEntry = Stock::find($stock->id);
                if ($stockEntry) {
                    // Find the related product
                    $product = Product::find($stockEntry->product_id);
                    if ($product) {
                        // Increment the product stock quantity
                        $product->stock += $stockEntry->item_qty;
                        $product->save();
                    }

                    // Delete the stock entry
                    $stockEntry->delete();
                }
            }
        });
    }




    public function updatePaymentStatus(Request $request)
    {
        try {
            $token = base64_decode($request->get("order_ids"));

            $ids = json_decode($token);

            foreach ($ids as $id) {
                $order = Order::find($id);
                if ($order) {
                    $order->payment_status = $request->payment_status;
                    $order->save();
                    $order->logAction('Payment Status Updated');
                }
            }
            // If the loop completes without errors, return a success response
            return response()->json(['status' => 'success', 'message' => 'Payment Status Successfully Updated']);
        } catch (\Exception $e) {
            // If an exception occurs, return an error response
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }


    public function orderCourier(Request $request)
    {
        try {
            $token = base64_decode($request->get("order_ids"));
            $ids = json_decode($token);

            $courier = Courier::find($request->courier_id);
            if (!$courier) {
                return response()->json(['status' => 'error', 'message' => 'Courier not found'], 404);
            }

            foreach ($ids as $id) {
                $order = Order::find($id);
                if ($order) {
                    // Update order status to 2 only if current status is 1
                    if ($order->order_status_id === 1) {
                        $order->order_status_id = 2;
                    }

                    // Assign the courier to the order
                    $order->courier()->associate($courier);
                    $order->save();

                    // Log the action
                    $order->logAction('Courier Assigned');
                }
            }

            return response()->json(['status' => 'success', 'message' => 'Courier Successfully Updated']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }


    public function transactionsShow($id)
    {

        $order = Order::findOrFail($id);
        $paymentMethods=PaymentMethod::all();

        return view('backend.modules.order.transaction_show', ['order' => $order,'paymentMethods'=>$paymentMethods]);
    }


    public function transactionsStore(Request $request)
    {
        $order = order::find($request->id);

        // Retrieve the payment details from the session
        $existingPaymentDetails=[];
        if(Session::has('payment_details'))
            $existingPaymentDetails = Session::get('payment_details');

        foreach ($existingPaymentDetails as $paymentDetails) {
            // Access the individual payment details
            $paymentMethodId = $paymentDetails['payment_method_id'];
            $paymentMethodName = $paymentDetails['methodName'];
            $amount = $paymentDetails['amount'];
            $transactionId = $paymentDetails['transaction_id'];
            $note = $paymentDetails['note'];

            // Create a new instance of OrderTransaction
            $payment = new OrderTransaction();
            $payment->order_id = $order->id;
            $payment->order_number = $order->order_number;
            $payment->user_id = $order->user_id;
            $payment->method_id = $paymentMethodId;
            $payment->method_name = $paymentMethodName;
            $payment->transaction_id = $transactionId;
            $payment->amount = $amount;
            $payment->notes = $note;
            $payment->status = 1;
            $payment->save();
        }


        $transactionSum = $order->transactions()->sum('amount');
        $paymentStatus =$transactionSum >= $order->total  ? 'paid' : ($transactionSum==0 ? ' unpaid' : 'partial');
        $order->payment_status = $paymentStatus;
        $order->save();
        $order->logAction('Order Transaction Updated');
        // Clear the payment_details from the session
        Session::forget('payment_details');

        return response()->json(['message' => 'Payment saved successfully']);
    }


    public function transactionsDestroy($id)
    {
        $id = decrypt($id);

        $payment =OrderTransaction::find($id);
        if ($payment) {

            $payment->delete();

            return response()->json(['message' => 'Order Previous Payment Deleted Successfully.']);

        } else {

            return response()->json(['message' => 'Order Previous Payment Not Found.']);

        }
    }





    public function orderShippingEdit($id)
    {
        $order = Order::find($id);


        return  view('backend.modules.order.shipping_edit', compact('order'));



    }



    public function orderShippingUpdate(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'address' => 'required',
        ]);

        // Find the order by its ID
        $order = Order::find($id);

        // Update the shipping information using your order service
        $this->orderService->createOrUpdateOrderShipping($order, $request);

        return redirect()->route('orders.show',$order->id)->with('success', 'Order shipping updated successfully.');
    }

    public function pendingIndex()
    {
        $orderStatuses=OrderStatus::where('status',1)->get();
        $couriers=Courier::where('status',1)->get();

        return view('backend.modules.order.pending_order',[
            'orderStatuses'=>$orderStatuses,
            'couriers'=>$couriers
        ]);
    }

    public function ajaxGetPending(Request $request)
    {


        $columns = array("id");

        $draw = $request->draw;
        $row = $request->start;
        $rowperpage = $request->length;

        $columnIndex = $request->order[0]['column'];
        $columnName = !empty($columns[$columnIndex]) ? $columns[$columnIndex] : $columns[0];
        $columnSortOrder = $request->order[0]['dir'];
        $searchValue = $request->search['value'];

        $totalRecords = $totalDRecords = 0;
        $allData = [];

        $query = Order::where('order_status_id', 1);

// Apply date range filter if provided
        if ($request->has('date_range') && !empty($request->date_range)) {
            $range = explode(" - ", $request->date_range);
            if (count($range) === 2) {
                $startDate = date("Y-m-d", strtotime(trim($range[0])));
                $endDate = date("Y-m-d", strtotime(trim($range[1])));
                $query->whereDate('orders.created_at', '>=', $startDate)
                    ->whereDate('orders.created_at', '<=', $endDate);
            }
        }

// Search filter
        if (!empty($searchValue)) {
            $query->where(function ($q) use ($searchValue) {
                $q->where('order_number', 'like', '%' . $searchValue . '%') // Search by order number
                ->orWhere('id', $searchValue) // Search by order ID
                ->orWhereHas('user', function ($q) use ($searchValue) {
                    $q->where('name', 'like', '%' . $searchValue . '%')
                        ->orWhere('phone', 'like', '%' . $searchValue . '%');
                })
                    ->orWhereHas('shipping', function ($q) use ($searchValue) {
                        $q->where('address', 'like', '%' . $searchValue . '%')
                            ->orWhere('name', 'like', '%' . $searchValue . '%')
                            ->orWhere('phone', 'like', '%' . $searchValue . '%');
                    });
            });
        }


// Get total records count
        $totalRecords = $query->count();

// Get the filtered records
        $records = $query->with(['user', 'shipping', 'status', 'paymentMethods'])
            ->where('order_status_id', 1)
            ->orderBy($columnName, $columnSortOrder)
            ->skip($row)
            ->take($rowperpage)
            ->get();


        foreach ($records as $order) {
            $data = [];

            $data[] = '<td>
            <label class="checkboxs">
                <input type="checkbox" class="checked-row" data-value="' . $order->id . '">
                <span class="checkmarks"></span>
            </label>
        </td>';

            $data[] = '<strong>Name:</strong> ' . ($order->shipping->name ?? 'NULL') . '<br>' .
                '<strong>Phone:</strong> ' . ($order->shipping->phone ?? 'NULL') . '<br>' .
                '<strong>Alternate Phone:</strong> ' . ($order->shipping->alternate_phone ?? 'NULL') . '<br>' .
                '<strong>Email:</strong> ' . ($order->shipping->email ?? 'NULL') . '<br>' .
                '<strong>Address:</strong> ' . ($order->shipping->address ?? 'NULL');



            $orderStatusBadge = generateOrderStatusBadge($order->order_status_id);
            
            // $data[] = '<div class="card-body d-flex flex-wrap gap-2 orderStatusChange">' . $orderStatusBadge . '</div>';
            
            
             $data[] = '
    <div class="card-body d-flex flex-wrap gap-2">
        <div class="order-info">
            <strong> ' . $order->order_number . '</strong><br>
         '. $order->source . '<br>
         ' . $order->created_at->format('Y-m-d H:i') . '<br>
        </div>
        <div class="order-status">
            <a href="' . route('orders.updateStatus') . '" class="order-status-link orderStatusChange">' . $orderStatusBadge . '</a>
        </div>
    </div>';
    
    
      $data[] = $order->customer_note;
            
            
            
       

           $data[] = '<strong>Total:</strong> '.formatPrice($order->total). '<br>' .
                  '<strong>Product:</strong> '.formatPrice($order->subtotal).'<br>' .
                  '<strong>Shipping:</strong> '.formatPrice($order->shipping_charge) . '<br>' .
                  '<strong>Coupon:</strong> '.formatPrice($order->coupon_discount) . '<br>' .
                  '<strong>Discount:</strong>'.formatPrice($order->discount_amount);
          
            $paymentStatus = trim($order->payment_status);
            $paymentBadgeClass = $paymentStatus === 'unpaid' ? 'badge-linedanger' : ($paymentStatus === 'partial' ? 'badge badges-warning' : 'badge-linesuccess');
            $paymentBadgeText = $paymentStatus === 'unpaid' ? 'Unpaid' : ($paymentStatus === 'partial' ? 'Partial' : 'Paid');
            $data[] = '<div class="card-body d-flex flex-wrap gap-2">
                  <span class="badge ' . $paymentBadgeClass . '">' . $paymentBadgeText . '</span>
               </div>';

            $paymentMethods = $order->paymentMethods->unique('name')->map(function ($paymentMethod) {
                return '<span class="badge bg-info">' . htmlspecialchars($paymentMethod->name, ENT_QUOTES, 'UTF-8') . '</span>';
            })->implode(' ');

              if (empty($paymentMethods)) {
                $paymentMethods = '<span class="badge bg-danger">Unknown</span>';
            }

            $data[] = $paymentMethods;

            $data[] = '<div class="action-table-data">
                  <div class="edit-delete-action">
                      <a data-bs-toggle="tooltip" data-bs-placement="top" title="Print Invoice" id="downloadInvoice" class="btn btn-primary p-2 me-2" data-href="' . route('orders.invoice', $order->id) . '">
                          <i class="fas fa-file-pdf text-white"></i>
                      </a>
                      <a data-bs-toggle="tooltip" data-bs-placement="top" title="View Transaction Details" class="btn btn-success p-2" href="' . route('orders.transactions.show', $order->id) . '">
                          <i class="fas fa-money-check-alt text-white"></i>
                      </a>
                  </div>
               </div>';

                 $userId = $order->ordered_by;
                $user = User::find($userId);
                
                if ($user) {
                    $data[] = '<div class="userimgname">
                                  <a href="javascript:void(0);" class="product-img">
                                      <img src="' . image($user->image) . '" alt="user-image">
                                  </a>
                                  <a href="javascript:void(0);">' . $user->name . '</a>
                               </div>';
                } else {
                    $data[] = '<div class="userimgname text-danger">
                                  <a href="javascript:void(0);">User Not Found</a>
                               </div>';
                }



            $data[] = '<div class="action-table-data">
                  <div class="edit-delete-action">
                      <a class="btn btn-info me-2 p-2" href="' . route('orders.edit', $order->id) . '"  title="Edit Order">
                          <i class="fa fa-edit text-white"></i>
                      </a>
                            <a class="btn btn-dark me-2 p-2" href="' . route('orders.shipping.edit', $order->id) . '"   title="Edit Shipping">
                              <i class="fa fa-location text-white"></i>
                          </a>

                      <a class="btn btn-danger delete-btn p-2 me-2" href="' . route('orders.destroy', $order->id) . '" title="Delete Order">
                          <i class="fa fa-trash text-white"></i>
                      </a>
                      <a class="btn btn-secondary me-2 p-2" href="' . route('orders.show', $order->id) . '" title="View Order">
                          <i class="fa fa-eye text-white"></i>
                      </a>
                  </div>
               </div>';

            $allData[] = $data;
        }

        $response = [
            "draw" => $draw,
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecords,
            "aaData" => $allData
        ];

        return response()->json($response);
    }




    public function paidIndex()
    {
        $orderStatuses=OrderStatus::where('status',1)->get();
        $couriers=Courier::where('status',1)->get();

        return view('backend.modules.order.paid_order',[
            'orderStatuses'=>$orderStatuses,
            'couriers'=>$couriers
        ]);
    }

    public function ajaxGetPaid(Request $request)
    {
        // Define the columns for sorting
        $columns = ["id"];

        // Get parameters from the request
        $draw = $request->draw;
        $row = $request->start;
        $rowperpage = $request->length;
        $columnIndex = $request->order[0]['column'];
        $columnName = $columns[$columnIndex] ?? $columns[0];
        $columnSortOrder = $request->order[0]['dir'];
        $searchValue = $request->search['value'];

        // Initialize total records and data array
        $totalRecords = 0;
        $allData = [];

        // Build the query for the orders
        $query = Order::query()->with(['user', 'shipping', 'status', 'paymentMethods'])
            ->where('payment_status', 'paid');

        // Apply date range filter if provided
        if ($request->has('date_range') && !empty($request->date_range)) {
            $range = explode(" - ", $request->date_range);
            if (count($range) === 2) {
                $startDate = date("Y-m-d", strtotime(trim($range[0])));
                $endDate = date("Y-m-d", strtotime(trim($range[1])));

                // Ensure to include the whole day for the end date
                $query->where('orders.created_at', '>=', $startDate)
                    ->where('orders.created_at', '<=', $endDate . ' 23:59:59');
            }
        }

        // Search filter
        if (!empty($searchValue)) {
            $query->where(function ($q) use ($searchValue) {
                $q->where('order_number', 'like', '%' . $searchValue . '%')
                    ->orWhere('id', $searchValue)
                    ->orWhereHas('user', function ($q) use ($searchValue) {
                        $q->where('name', 'like', '%' . $searchValue . '%')
                            ->orWhere('phone', 'like', '%' . $searchValue . '%');
                    })
                    ->orWhereHas('shipping', function ($q) use ($searchValue) {
                        $q->where('address', 'like', '%' . $searchValue . '%')
                            ->orWhere('name', 'like', '%' . $searchValue . '%')
                            ->orWhere('phone', 'like', '%' . $searchValue . '%');
                    });
            });
        }

        // Get total records count
        $totalRecords = $query->count();

        // Get the filtered records
        $records = $query->orderBy($columnName, $columnSortOrder)
            ->skip($row)
            ->take($rowperpage)
            ->get();

        // Prepare data for the response
        foreach ($records as $order) {
            $data = [];

            $data[] = '<td>
            <label class="checkboxs">
                <input type="checkbox" class="checked-row" data-value="' . $order->id . '">
                <span class="checkmarks"></span>
            </label>
        </td>';

            $data[] = '<strong>Name:</strong> ' . ($order->shipping->name ?? 'NULL') . '<br>' .
                '<strong>Phone:</strong> ' . ($order->shipping->phone ?? 'NULL') . '<br>' .
                '<strong>Alternate Phone:</strong> ' . ($order->shipping->alternate_phone ?? 'NULL') . '<br>' .
                '<strong>Email:</strong> ' . ($order->shipping->email ?? 'NULL') . '<br>' .
                '<strong>Address:</strong> ' . ($order->shipping->address ?? 'NULL');

             $data[] ='<strong>'. $order->order_number.'</strong> ';

            $orderStatusBadge = generateOrderStatusBadge($order->order_status_id);
            
         $data[] = '<div class="card-body d-flex flex-wrap gap-2 ">
              <a href="' . route('orders.updateStatus') . '" class="order-status-link orderStatusChange">' . $orderStatusBadge . '</a>
          </div>';

            
            // $data[] = '<div class="card-body d-flex flex-wrap gap-2 orderStatusChange">' . $orderStatusBadge . '</div>';

                   $data[] = '<strong>Total:</strong> '.formatPrice($order->total). '<br>' .
                  '<strong>Product:</strong> '.formatPrice($order->subtotal).'<br>' .
                  '<strong>Shipping:</strong> '.formatPrice($order->shipping_charge) . '<br>' .
                  '<strong>Coupon:</strong> '.formatPrice($order->coupon_discount) . '<br>' .
                  '<strong>Discount:</strong>'.formatPrice($order->discount_amount);

            $paymentStatus = trim($order->payment_status);
            $paymentBadgeClass = $paymentStatus === 'unpaid' ? 'badge-linedanger' :
                ($paymentStatus === 'partial' ? 'badge badges-warning' : 'badge-linesuccess');
            $paymentBadgeText = $paymentStatus === 'unpaid' ? 'Unpaid' :
                ($paymentStatus === 'partial' ? 'Partial' : 'Paid');
            $data[] = '<div class="card-body d-flex flex-wrap gap-2">
                  <span class="badge ' . $paymentBadgeClass . '">' . $paymentBadgeText . '</span>
               </div>';

            $paymentMethods = $order->paymentMethods->unique('name')->map(function ($paymentMethod) {
                return '<span class="badge bg-info">' . htmlspecialchars($paymentMethod->name, ENT_QUOTES, 'UTF-8') . '</span>';
            })->implode(' ');

             if (empty($paymentMethods)) {
                $paymentMethods = '<span class="badge bg-danger">Unknown</span>';
            }

            $data[] = $paymentMethods;

            // Action buttons
            $data[] = '<div class="action-table-data">
                  <div class="edit-delete-action">
                      <a data-bs-toggle="tooltip" data-bs-placement="top" title="Print Invoice" id="downloadInvoice" class="btn btn-primary p-2 me-2" data-href="' . route('orders.invoice', $order->id) . '">
                          <i class="fas fa-file-pdf text-white"></i>
                      </a>
                      <a data-bs-toggle="tooltip" data-bs-placement="top" title="View Transaction Details" class="btn btn-success p-2" href="' . route('orders.transactions.show', $order->id) . '">
                          <i class="fas fa-money-check-alt text-white"></i>
                      </a>
                  </div>
               </div>';

            $userId = $order->ordered_by;
            $user = User::find($userId);
            
            if ($user) {
                $data[] = '<div class="userimgname">
                              <a href="javascript:void(0);" class="product-img">
                                  <img src="' . image($user->image) . '" alt="user-image">
                              </a>
                              <a href="javascript:void(0);">' . $user->name . '</a>
                           </div>';
            } else {
                $data[] = '<div class="userimgname text-danger">
                              <a href="javascript:void(0);">User Not Found</a>
                           </div>';
            }


            // Additional action buttons
            $data[] = '<div class="action-table-data">
                  <div class="edit-delete-action">
                      <a class="btn btn-info me-2 p-2" href="' . route('orders.edit', $order->id) . '" title="Edit Order">
                          <i class="fa fa-edit text-white"></i>
                      </a>
                      
                        <a class="btn btn-dark me-2 p-2" href="' . route('orders.shipping.edit', $order->id) . '"   title="Edit Shipping">
                              <i class="fa fa-location text-white"></i>
                          </a>
                      <a class="btn btn-danger delete-btn p-2 me-2" href="' . route('orders.destroy', $order->id) . '" title="Delete Order">
                          <i class="fa fa-trash text-white"></i>
                      </a>
                      <a class="btn btn-secondary me-2 p-2" href="' . route('orders.show', $order->id) . '" title="View Order">
                          <i class="fa fa-eye text-white"></i>
                      </a>
                  </div>
               </div>';

            $allData[] = $data;
        }

        // Prepare the response
        return response()->json([
            "draw" => $draw,
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecords,
            "aaData" => $allData
        ]);
    }



    public function unPaidIndex()
    {
        $orderStatuses=OrderStatus::where('status',1)->get();
        $couriers=Courier::where('status',1)->get();

        return view('backend.modules.order.unpaid_order',[
            'orderStatuses'=>$orderStatuses,
            'couriers'=>$couriers
        ]);
    }
    public function ajaxGetUnpaid(Request $request)
    {
        $columns = ['id'];

        $draw = $request->draw;
        $row = $request->start;
        $rowperpage = $request->length;

        $columnIndex = $request->order[0]['column'] ?? 0;
        $columnName = $columns[$columnIndex] ?? $columns[0];
        $columnSortOrder = $request->order[0]['dir'];
        $searchValue = $request->search['value'] ?? '';

        // Initialize the query
        $query = Order::query()->where('payment_status', 'unpaid');

        // Apply date range filter if provided
        if ($request->has('date_range') && !empty($request->date_range)) {
            $range = explode(" - ", $request->date_range);
            if (count($range) === 2) {
                $startDate = date("Y-m-d", strtotime(trim($range[0])));
                $endDate = date("Y-m-d", strtotime(trim($range[1])));

                // Ensure to include the whole day for the end date
                $query->where('orders.created_at', '>=', $startDate)
                    ->where('orders.created_at', '<=', $endDate . ' 23:59:59');
            }
        }


        // Search filter
        if ($searchValue) {
            $query->where(function ($q) use ($searchValue) {
                $q->where('order_number', 'like', '%' . $searchValue . '%')
                    ->orWhere('id', $searchValue)
                    ->orWhereHas('user', function ($q) use ($searchValue) {
                        $q->where('name', 'like', '%' . $searchValue . '%')
                            ->orWhere('phone', 'like', '%' . $searchValue . '%');
                    })
                    ->orWhereHas('shipping', function ($q) use ($searchValue) {
                        $q->where('address', 'like', '%' . $searchValue . '%')
                            ->orWhere('name', 'like', '%' . $searchValue . '%')
                            ->orWhere('phone', 'like', '%' . $searchValue . '%');
                    });
            });
        }

        // Get total records count
        $totalRecords = $query->count();

        // Get the filtered records
        $records = $query->with(['user', 'shipping', 'status', 'paymentMethods'])
            ->orderBy($columnName, $columnSortOrder)
            ->skip($row)
            ->take($rowperpage)
            ->get();

        $allData = $records->map(function ($order) {
            $data = [];

            $data[] = '<td>
            <label class="checkboxs">
                <input type="checkbox" class="checked-row" data-value="' . $order->id . '">
                <span class="checkmarks"></span>
            </label>
        </td>';

            $data[] = '<strong>Name:</strong> ' . ($order->shipping->name ?? 'NULL') . '<br>' .
                '<strong>Phone:</strong> ' . ($order->shipping->phone ?? 'NULL') . '<br>' .
                '<strong>Alternate Phone:</strong> ' . ($order->shipping->alternate_phone ?? 'NULL') . '<br>' .
                '<strong>Email:</strong> ' . ($order->shipping->email ?? 'NULL') . '<br>' .
                '<strong>Address:</strong> ' . ($order->shipping->address ?? 'NULL');

            $data[] = $order->order_number;

            $orderStatusBadge = generateOrderStatusBadge($order->order_status_id);
            // $data[] = '<div class="card-body d-flex flex-wrap gap-2 orderStatusChange">' . $orderStatusBadge . '</div>';
            
    $data[] = '<div class="card-body d-flex flex-wrap gap-2 ">
              <a href="' . route('orders.updateStatus') . '" class="order-status-link orderStatusChange">' . $orderStatusBadge . '</a>
          </div>';


            $data[] = '<strong>Total:</strong> '.formatPrice($order->total). '<br>' .
          '<strong>Product:</strong> '.formatPrice($order->subtotal).'<br>' .
          '<strong>Shipping:</strong> '.formatPrice($order->shipping_charge) . '<br>' .
          '<strong>Coupon:</strong> '.formatPrice($order->coupon_discount) . '<br>' .
          '<strong>Discount:</strong>'.formatPrice($order->discount_amount);
          
          
            $paymentStatus = trim($order->payment_status);
            $paymentBadgeClass = $paymentStatus === 'unpaid' ? 'badge-linedanger' : ($paymentStatus === 'partial' ? 'badge badges-warning' : 'badge-linesuccess');
            $paymentBadgeText = ucfirst($paymentStatus);
            $data[] = '<div class="card-body d-flex flex-wrap gap-2">
            <span class="badge ' . $paymentBadgeClass . '">' . $paymentBadgeText . '</span>
        </div>';

            $paymentMethods = $order->paymentMethods->unique('name')->map(function ($paymentMethod) {
                return '<span class="badge bg-info">' . htmlspecialchars($paymentMethod->name, ENT_QUOTES, 'UTF-8') . '</span>';
            })->implode(' ');

            $data[] = $paymentMethods ?: '<span class="badge bg-danger">Unknown</span>';

            $data[] = '<div class="action-table-data">
            <div class="edit-delete-action">
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="Print Invoice" id="downloadInvoice" class="btn btn-primary p-2 me-2" data-href="' . route('orders.invoice', $order->id) . '">
                    <i class="fas fa-file-pdf text-white"></i>
                </a>
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="View Transaction Details" class="btn btn-success p-2" href="' . route('orders.transactions.show', $order->id) . '">
                    <i class="fas fa-money-check-alt text-white"></i>
                </a>
            </div>
        </div>';

            // $user = $order->user;
            // $data[] = '<div class="userimgname">' .
            //     '<a href="javascript:void(0);" class="product-img">' .
            //     '<img src="' . image($user->image ?? '') . '" alt="user-image">' .
            //     '</a>' .
            //     '<a href="javascript:void(0);">' . ($user->name ?? 'Not Available') . '</a>' .
            //     '</div>';
            
            
                 $userId = $order->ordered_by;
                $user = User::find($userId);
                
                if ($user) {
                    $data[] = '<div class="userimgname">
                                  <a href="javascript:void(0);" class="product-img">
                                      <img src="' . image($user->image) . '" alt="user-image">
                                  </a>
                                  <a href="javascript:void(0);">' . $user->name . '</a>
                               </div>';
                } else {
                    $data[] = '<div class="userimgname text-danger">
                                  <a href="javascript:void(0);">User Not Found</a>
                               </div>';
                }

            
            
            
            

            $data[] = '<div class="action-table-data">
            <div class="edit-delete-action">
                <a class="btn btn-info me-2 p-2" href="' . route('orders.edit', $order->id) . '" title="Edit Order">
                    <i class="fa fa-edit text-white"></i>
                </a>
                
                  <a class="btn btn-dark me-2 p-2" href="' . route('orders.shipping.edit', $order->id) . '"   title="Edit Shipping">
                              <i class="fa fa-location text-white"></i>
                          </a>
                <a class="btn btn-danger delete-btn p-2 me-2" href="' . route('orders.destroy', $order->id) . '" title="Delete Order">
                    <i class="fa fa-trash text-white"></i>
                </a>
                <a class="btn btn-secondary me-2 p-2" href="' . route('orders.show', $order->id) . '" title="View Order">
                    <i class="fa fa-eye text-white"></i>
                </a>
            </div>
        </div>';

            return $data;
        });

        $response = [
            "draw" => $draw,
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecords,
            "aaData" => $allData,
        ];

        return response()->json($response);
    }






}
