<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;

use App\Models\Courier;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\PurchaseTransaction;
use App\Models\Stock;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{


    public function index()
    {
        $purchaseStatuses=OrderStatus::where('status',1)->get();
        $couriers=Courier::where('status',1)->get();

        return view('backend.modules.purchase.index',[
            'orderStatuses'=>$purchaseStatuses,
            'couriers'=>$couriers
        ]);
    }



    public function ajaxIndex(Request $request)
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

        if ($searchValue == '') {
            $query = Purchase::query();

            // Filter by order status ID if provided
            if ($request->has('order_status_id') && !empty($request->order_status_id)) {
                $query->where('order_status_id', $request->order_status_id);
            }

            $totalCategoriesCount = $query->count();

            $records = $query->orderBy($columnName, $columnSortOrder)
                ->skip($row)
                ->take($rowperpage)
                ->get();

            $totalRecords = $totalDRecords = !empty($totalCategoriesCount) ? $totalCategoriesCount : 0;
        } else {
            // Initialize the query
            $query = Purchase::with(['user']);

// Filter by user name, phone, shipping address, order number, or order ID
            if (!empty($searchValue)) {
                $query->where(function ($q) use ($searchValue) {
                    $q->where('purchase_number', 'like', '%' . $searchValue . '%') // Search by order number
                    ->orWhere('id', $searchValue) ;// Search by order ID


                });
            }

            // Get total records count
            $totalRecords = $query->count();

            // Get the filtered records
            $records = $query->orderBy($columnName, $columnSortOrder)
                ->skip($row)
                ->take($rowperpage)
                ->get();

            $totalDRecords = $totalRecords;
        }


        foreach ($records as $key => $purchase) {
            $data = [];

            $data[] = '<td>
            <label class="checkboxs">
                <input type="checkbox" class="checked-row" data-value="' . $purchase->id . '">
                <span class="checkmarks"></span>
            </label>
        </td>';



            $data[] = $purchase->purchase_number;
         



            $data[] = $purchase->purchase_date;





            $data[] = '<strong>Total:</strong> ' . formatPrice($purchase->total) . '<br>' .
                '<strong>Products:</strong> ' . formatPrice($purchase->subtotal) . '<br>' .
                '<strong>Quantity:</strong> ' . $purchase->quantity;


            $cleanedStatus = trim($purchase->payment_status);
            $data[] = '<div class="card-body d-flex flex-wrap gap-2">
    <span class="badge ' .
                ($cleanedStatus === 'unpaid' ? 'badge-linedanger' :
                    ($cleanedStatus === 'partial' ? 'badge badges-warning' : 'badge-linesuccess')) . '">
        ' .
                ($cleanedStatus === 'unpaid' ? 'Unpaid' :
                    ($cleanedStatus === 'partial' ? 'Partial' : 'Paid')) . '
    </span>
</div>';


            $data[] = '<div class="action-table-data">
            <div class="edit-delete-action">
          <a data-bs-toggle="tooltip" data-bs-placement="top" title="Print Invoice" id="downloadInvoice" class="btn btn-primary p-2 me-2"  data-href="' . route('purchases.invoice', $purchase->id) . '">
            <i class="fas fa-file-pdf text-white"></i>
        </a>
        <!-- Icon for viewing transaction details -->
        <a data-bs-toggle="tooltip" data-bs-placement="top" title="View Transaction Details" class="btn btn-success  p-2" href="' . route('purchases.transactions.show', $purchase->id) . '">
            <i class="fas fa-money-check-alt text-white "></i>
        </a>
        </div>
        </div>';

            $user = User::find($purchase->created_by);
            if ($user) {
                $data[] = '<div class="userimgname">
        <a href="javascript:void(0);" class="product-img">
            <img src="' . image($user->image) . '" alt="user-image">
        </a>
        <a href="javascript:void(0);">' . $user->name . '</a>
    </div>';
            }


            $data[] = '<div class="action-table-data">
        <div class="edit-delete-action">
        <a class="btn btn-info me-2 p-2" " href="' . route('purchases.edit', $purchase->id) . '">
           <i  class="fa fa-edit text-white"></i>
        </a>
        <a class="btn btn-danger delete-btn p-2  me-2"  href="' . route('purchases.destroy', $purchase->id) . '">
            <i  class="fa fa-trash text-white"></i>
        </a>
         <a class=" btn btn-secondary me-2  p-2" href="' . route('purchases.show', $purchase->id) . '">
            <i  class="fa fa-eye text-white"></i>
          </a>

          </div>
        </div>';


            $allData[] = $data;
        }

        $response = [
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalDRecords,
            "aaData" => $allData
        ];

        return response()->json($response);
    }


    public function show($id)

    {

        $purchase = Purchase::findOrFail($id);



        return view('backend.modules.purchase.show_details', ['purchase' => $purchase,]);
    }


    public function create()
    {


        $paymentMethods=PaymentMethod::where('status',1)->get();

        return view('backend.modules.purchase.create',[

            'paymentMethods'=>$paymentMethods,
        ]);
    }



    public function store(Request $request)
    {
        $request->validate([
            'purchase_date' => 'required',
        ]);

        // Extract and process cart data
        $cart = json_decode($request->input('cart_data'), true);
        $itemTotal = $request->input('itemTotal');
        $shipping = floatval(preg_replace('/[^0-9.]/', '', $request->input('shipping')));
        $packingCharge = floatval(preg_replace('/[^0-9.]/', '', $request->input('packing_charge')));
        $discount = floatval(preg_replace('/[^0-9.]/', '', $request->input('discount')));
        $total = floatval(preg_replace('/[^0-9.]/', '', $request->input('total')));
        $totalValue = round($total);
        $decimalPart = $totalValue - $total;
        $adjustAmount = $decimalPart;

        // Calculate total product quantity
        $totalProductQuantity = $this->calculateTotalProductQuantity($cart);

        // Declare $purchase variable outside the closure
        $purchase = null;

        try {
            DB::transaction(function () use ($request, $cart, $itemTotal, $shipping, $packingCharge, $discount, $totalValue, $adjustAmount, $totalProductQuantity, &$purchase) {
                // Create and save the purchase record
                $purchase = new Purchase();
                $purchase->discount_amount = $discount;
                $purchase->subtotal = $itemTotal;
                $purchase->shipping_charge = $shipping;
                $purchase->packing_charge = $packingCharge;
                $purchase->total = $totalValue;
                $purchase->adjust_amount = $adjustAmount;
                $purchase->purchase_date = $request->purchase_date
                    ? Carbon::parse($request->purchase_date)->format('Y-m-d')
                    : Carbon::now()->format('Y-m-d');
                $purchase->quantity = $totalProductQuantity;
                $purchase->tax = 0;
                $purchase->payment_status = 'unpaid';
                $purchase->source = 'pos';

                $purchase->save();

                // Call other methods after saving the purchase
                $this->purchaseTransactions($purchase);
                $this->savePurchaseItems($purchase, $cart);
                $this->saveStockInfo($purchase, $cart);
            });

            // Check if $purchase is set and redirect to the show page
            if ($purchase) {
                return redirect()->route('purchases.show', $purchase->id);
            } else {

                return redirect()->back()->withErrors(['error' => 'An error occurred: Purchase not created.']);
            }
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'An error occurred: ' . $e->getMessage()]);
        }
    }





    private function calculateTotalProductQuantity($cart)
    {
        $totalProductQuantity = 0;
        foreach ($cart as $product) {
            $totalProductQuantity += $product['quantity'];
        }
        return $totalProductQuantity;
    }
    private function purchaseTransactions($purchase)
    {
        // Retrieve the payment details from the session
        $existingPaymentDetails = [];
        if (Session::has('payment_details')) {
            $existingPaymentDetails = Session::get('payment_details');
        }

        // Iterate over payment details and save OrderTransaction instances
        foreach ($existingPaymentDetails as $paymentDetails) {


            $paymentMethodId = $paymentDetails['payment_method_id'];
            $paymentMethodName = $paymentDetails['methodName'];
            $amount = $paymentDetails['amount'];
            $transactionId = $paymentDetails['transaction_id'];
            $note = $paymentDetails['note'];

            // Create a new instance of OrderTransaction
            $payment = new PurchaseTransaction();
            $payment->purchase_id = $purchase->id;
            $payment->purchase_number = $purchase->purchase_number;
//            $payment->user_id = $purchase->user_id;
            $payment->method_id = $paymentMethodId;
            $payment->method_name = $paymentMethodName;
            $payment->transaction_id = $transactionId;
            $payment->amount = $amount;
            $payment->notes = $note;
            $payment->status = 1;
            $payment->save();
        }

        $transactionSum = $purchase->transactions()->sum('amount');
        $paymentStatus =$purchase->total <= $transactionSum  ? 'paid' : ($transactionSum==0 ? 'unpaid' : 'partial');
        $purchase->payment_status = $paymentStatus;
        $purchase->save();


        // Clear the payment_details from the session
        Session::forget('payment_details');
    }

    private function savePurchaseItems($purchase, $cart)
    {
        foreach ($cart as $c) {

            $product = Product::find($c['id']);
            // Check if the order item already exists
            $purchaseItem = PurchaseItem::where('purchase_id',$purchase->id)
                ->where('product_id', $c['id'])
                ->first();


            // If the  item does not exist, create a new one
            if (!$purchaseItem) {
                $purchaseItem = new PurchaseItem();
                $purchaseItem->purchase_number = $purchase->purchase_number;
                $purchaseItem->purchase_id = $purchase->id;
                $purchaseItem->product_id = $c['id'];
            }

            // Update the order item details
            $purchaseItem->qty = $c['quantity'];
            $purchaseItem->price = $c['current_price'];
            $purchaseItem->total = $c['current_price'] * $c['quantity'];
            $purchaseItem->status = $product->status;
            $purchaseItem->publisher_id = $product->publisher_id;

            // Extracting IDs using pluck and setting attributes (json encoded handled by mutators)
            $purchaseItem->author_id = collect($product->authors)->pluck('id')->toArray();
            $purchaseItem->category_id = collect($product->categories)->pluck('id')->toArray();
            $purchaseItem->subcategory_id = collect($product->subcategories)->pluck('id')->toArray();

            $purchaseItem->save();



            $product->save();
        }
    }


    private function saveStockInfo($purchase, $cart)
    {
        foreach ($cart as $c) {
            $product = Product::find($c['id']);
            if (!$product) {
                continue;
            }

            $stock = Stock::where('purchase_id', $purchase->id)
                ->where('product_id', $c['id'])
                ->where('is_bundle_item', 0)
                ->first();

            $previousQty = $stock ? $stock->item_qty : 0;

            if (!$stock) {
                $stock = new Stock();
                $stock->stock_type = 'purchase';
                $stock->stock_entry_date = now();
                $stock->purchase_id = $purchase->id;
                $stock->product_id = $c['id'];

                $stock->item_price = $c['current_price']; // purchase price saving as current price
                $stock->item_qty = $c['quantity'];
                $stock->item_discount = $product->purchase_price > $c['current_price'] ? $product->purchase_price - $c['current_price'] : 0; // Ensure discount is positive or zero
                $stock->item_subtotal = $c['current_price'] * $c['quantity'];
                $stock->item_description = 'purchase as Single';

                $stock->save();

                // Increment product quantity by the new stock quantity
                $product->stock += $c['quantity'];
            } else {
                $stock->item_price = $c['current_price'];
                $stock->item_qty = $c['quantity'];
                $stock->item_discount = $product->purchase_price > $c['current_price'] ? $product->purchase_price - $c['current_price'] : 0; // Ensure discount is positive or zero
                $stock->item_subtotal = $c['current_price'] * $c['quantity'];
                $stock->item_description = 'purchase as Single';

                $stock->save();

                // Adjust product stock quantity
                $product->stock += $c['quantity'] - $previousQty;
            }

            // Update product purchase price and save the updated product stock
            $product->purchase_price = $c['current_price'];
            $product->save();
        }
    }

    public function edit($id)
    {


        $paymentMethods=PaymentMethod::where('status',1)->get();

        $purchase =Purchase::findOrFail($id);

        $purchaseItems = $purchase->purchaseItems;

        $products = [];

        foreach ($purchaseItems as $purchaseItem) {

            $product = Product::find($purchaseItem->product_id);

            if ($product) {

                $productArray = [
                    'id' => $product->id,
                    'english_name' => $product->english_name,
                    'current_price' =>$purchaseItem->price,
                    'mrp_price' => $product->mrp_price,
                    'thumb_image' => image($product->thumb_image),
                    'subtotal'=>$purchaseItem->total,
                    'stock'=> $product->stock?? 0,
                    'quantity' => $purchaseItem->qty,
                    'product_code'=> $product->product_code,

                ];



                // Add the product array to the products array
                $products[] = $productArray;
            }
        }

        $cart = json_encode($products);

        return view('backend.modules.purchase.edit',
            [
                'purchase' => $purchase,
                'cart' => $cart,
                'paymentMethods'=>$paymentMethods,

            ]);


    }

    public function update(Request $request,$id)
    {
        $request->validate([
            'purchase_date' => 'required',
        ]);

        // Extract and process cart data
        $cart = json_decode($request->input('cart_data'), true);
        $itemTotal = $request->input('itemTotal');
        $shipping = floatval(preg_replace('/[^0-9.]/', '', $request->input('shipping')));
        $packingCharge = floatval(preg_replace('/[^0-9.]/', '', $request->input('packing_charge')));
        $discount = floatval(preg_replace('/[^0-9.]/', '', $request->input('discount')));
        $total = floatval(preg_replace('/[^0-9.]/', '', $request->input('total')));
        $totalValue = round($total);
        $decimalPart = $totalValue - $total;
        $adjustAmount = $decimalPart;

        // Calculate total product quantity
        $totalProductQuantity = $this->calculateTotalProductQuantity($cart);

        // Declare $purchase variable outside the closure
        $purchase = null;

        try {
            DB::transaction(function () use ($request,$id, $cart, $itemTotal, $shipping, $packingCharge, $discount, $totalValue, $adjustAmount, $totalProductQuantity, &$purchase) {
                // Create and save the purchase record
                $purchase =Purchase::find($id);
                $purchase->discount_amount = $discount;
                $purchase->subtotal = $itemTotal;
                $purchase->shipping_charge = $shipping;
                $purchase->packing_charge = $packingCharge;
                $purchase->total = $totalValue;
                $purchase->adjust_amount = $adjustAmount;
                $purchase->purchase_date = $request->purchase_date
                    ? Carbon::parse($request->purchase_date)->format('Y-m-d')
                    : Carbon::now()->format('Y-m-d');
                $purchase->quantity = $totalProductQuantity;
                $purchase->tax = 0;
                $purchase->payment_status = 'unpaid';
                $purchase->source = 'pos';

                $purchase->save();

                // Call other methods after saving the purchase
                $this->purchaseTransactions($purchase);
                $this->savePurchaseItems($purchase, $cart);
                $this->saveStockInfo($purchase, $cart);
            });

            // Check if $purchase is set and redirect to the show page
            if ($purchase) {
                return redirect()->route('purchases.show', $purchase->id);
            } else {

                return redirect()->back()->withErrors(['error' => 'An error occurred: Purchase not update.']);
            }
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'An error occurred: ' . $e->getMessage()]);
        }
    }






    public function searchProduct(Request $request)
    {
        $search = $request->search;
        $category_id = $request->category_id;
        $publisher_id = $request->publisher_id;

        $products = Product::query()
            ->when($publisher_id, function ($query) use ($publisher_id) {
                return $query->where('publisher_id', $publisher_id);
            })
            ->when($category_id, function ($query) use ($category_id) {
                return $query->whereHas('categories', function ($query) use ($category_id) {
                    $query->where('categories.id', $category_id);
                });
            })
            ->when($search, function ($query) use ($search) {
                return $query->where(function ($query) use ($search) {
                    $query->where('english_name', 'like', '%' . $search . '%')
                        ->orWhere('bangla_name', 'like', '%' . $search . '%')
                        ->orWhere('slug', 'like', '%' . $search . '%');
                });
            })
            ->where('status', 1)
            ->latest()
            ->take(100)
            ->get();


        if ($products->isEmpty()) {
            return response()->json([
                'error' => 'no',
                'message' => 'No product found',
                'type' => 'success',
                'dataView' => 'No product found',
            ]);
        }

        $view = view('backend.modules.purchase.ajax_pos_product_list', compact('products'))->render();

        return response()->json([
            'error' => 'no',
            'message' => 'Product has been loaded',
            'type' => 'success',
            'dataView' => $view,
        ]);
    }



    public function destroyAll(Request $request)
    {
        $token = base64_decode($request->get("token"));
        $ids = json_decode($token);

        foreach ($ids as $id) {
            $purchase = Purchase::find($id);
            if ($purchase) {
                $purchase->delete();
            }
        }

        return response()->json(['message' => 'Purchase deleted successfully'], 200);
    }



    public function destroy($id)
    {
        $purchase = Purchase::find($id);

        if ($purchase) {
            $purchase->delete();
            return response()->json(['message' => 'Purchase deleted successfully'], 200);
        } else {
            return response()->json(['error' => 'Purchase not found'], 404);
        }
    }


    public function purchaseCourier(Request $request)
    {
        try {
            $token = base64_decode($request->get("purchase_ids"));
            $ids = json_decode($token);

            $courier = Courier::find($request->courier_id);
            if (!$courier) {
                return response()->json(['status' => 'error', 'message' => 'Courier not found'], 404);
            }
            foreach ($ids as $id) {
                $purchase = Purchase::find($id);
                if ($purchase) {
                    // Assign the courier to the order
                    $purchase->courier_id=$request->courier_id;

                    $purchase->save();


                }
            }

            return response()->json(['status' => 'success', 'message' => 'Courier Successfully Updated']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function updatePaymentStatus(Request $request)
    {
        try {
            $token = base64_decode($request->get("purchase_ids"));

            $ids = json_decode($token);

            foreach ($ids as $id) {
                $purchase = Purchase::find($id);
                if ($purchase) {
                    $purchase->payment_status = $request->payment_status;
                    $purchase->save();
//                    $purchase->logAction('Payment Status Updated');
                }
            }
            // If the loop completes without errors, return a success response
            return response()->json(['status' => 'success', 'message' => 'Payment Status Successfully Updated']);
        } catch (\Exception $e) {
            // If an exception occurs, return an error response
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }


    public function transactionsShow($id)
    {

        $purchase =Purchase::findOrFail($id);

        $paymentMethods=PaymentMethod::all();

        return view('backend.modules.purchase.transaction_show', ['purchase' => $purchase,'paymentMethods'=>$paymentMethods]);
    }

    public function transactionsStore(Request $request)
    {
        $purchase = Purchase::find($request->id);


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
            $payment = new PurchaseTransaction();
            $payment->purchase_id = $purchase->id;
            $payment->purchase_number = $purchase->purchase_number;
//            $payment->user_id = $purchase->user_id;
            $payment->method_id = $paymentMethodId;
            $payment->method_name = $paymentMethodName;
            $payment->transaction_id = $transactionId;
            $payment->amount = $amount;
            $payment->notes = $note;
            $payment->status = 1;
            $payment->save();
        }


        $transactionSum = $purchase->transactions()->sum('amount');
        $paymentStatus =$transactionSum >= $purchase->total  ? 'paid' : ($transactionSum==0 ? ' unpaid' : 'partial');
        $purchase->payment_status = $paymentStatus;
        $purchase->save();
//        $purchase->logAction('Order Transaction Updated');
        // Clear the payment_details from the session
        Session::forget('payment_details');

        return response()->json(['message' => 'Payment saved successfully']);
    }


    public function transactionsDestroy($id)
    {
        $id = decrypt($id);

        $payment =PurchaseTransaction::find($id);
        if ($payment) {
            $payment->delete();
            return response()->json(['message' => 'Previous Payment Deleted Successfully.']);

        } else {
            return response()->json(['message' => 'Previous Payment Not Found.']);

        }
    }


    public function purchaseInvoice($id)
    {
        $purchase = Purchase::find($id);

        $htmlContent = view('backend.modules.purchase.invoice', compact('purchase'))->render();

        return response()->json(['htmlContent' => $htmlContent]);
    }


}
