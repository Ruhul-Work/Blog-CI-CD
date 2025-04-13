<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Author;
use App\Models\Publisher;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\ReturnItem;
use App\Models\ReturnProduct;
use App\Models\Stock;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\ReturnTransaction;
use App\Models\PaymentMethod;
use App\Models\Wallet;
use Auth;
use DB;


class ReturnController extends Controller
{
    //
    public function index()
    {
        $categories = Category::orderBy('created_at', 'desc')->get();
        $subcategory = Subcategory::orderBy('created_at', 'desc')->get();
        $authors = Author::orderBy('created_at', 'desc')->get();
        $publishers = Publisher::orderBy('created_at', 'desc')->get();

        return view('backend.modules.return.index', compact('categories', 'subcategory', 'authors', 'publishers'));
    }
    //
    public function create(Request $request)
    {
        $paymentMethods = PaymentMethod::where('status', 1)->get();
        return view('backend.modules.return.create', [

            'paymentMethods' => $paymentMethods,
        ]);
    }
    //
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
            $query = ReturnProduct::query();
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
            $query = ReturnProduct::with(['user']);

            if (!empty($searchValue)) {
                $query->where(function ($q) use ($searchValue) {
                    $q->where('return_number', 'like', '%' . $searchValue . '%')
                        ->orWhere('id', $searchValue);
                });
            }
            $totalRecords = $query->count();
            $records = $query->orderBy($columnName, $columnSortOrder)
                ->skip($row)
                ->take($rowperpage)
                ->get();
            $totalDRecords = $totalRecords;
        }
        foreach ($records as $key => $return) {
            $data = [];
            $data[] = '<td>
            <label class="checkboxs">
                <input type="checkbox" class="checked-row" data-value="' . $return->id . '">
                <span class="checkmarks"></span>
            </label>
        </td>';
            $data[] = $return->return_number;
            $data[] = $return->return_date;
            $data[] = '<strong>Total:</strong> ' . formatPrice($return->total) . '<br>' .
                '<strong>Products:</strong> ' . formatPrice($return->subtotal);
            $cleanedStatus = trim($return->payment_status);

            $data[] = '<div class="card-body d-flex flex-wrap gap-2">
    <span class="badge ' .
                ($cleanedStatus === 'unpaid' ? 'badge-linedanger' : ($cleanedStatus === 'partial' ? 'badge badges-warning' : 'badge-linesuccess')) . '">
        ' .
                ($cleanedStatus === 'unpaid' ? 'Unpaid' : ($cleanedStatus === 'partial' ? 'Partial' : 'Paid')) . '
    </span>
</div>';
            $data[] = '<div class="action-table-data">
            <div class="edit-delete-action">
          <a data-bs-toggle="tooltip" data-bs-placement="top" title="Print Invoice" id="downloadInvoice" class="btn btn-primary p-2 me-2"  data-href="' . route('returns.invoice', $return->id) . '">
            <i class="fas fa-file-pdf text-white"></i>
        </a>
        <!-- Icon for viewing transaction details -->
        <a data-bs-toggle="tooltip" data-bs-placement="top" title="View Transaction Details" class="btn btn-success  p-2" href="' . route('returns.transactions.show', $return->id) . '">
            <i class="fas fa-money-check-alt text-white "></i>
        </a>
        </div>
        </div>';
            if ($return->customer_id) {
                $data[] = '<strong>Name:</strong> ' . ($return->customer->name ?? '') . '<br>' .
                    '<strong>Phone:</strong> ' . ($return->customer->phone ?? '') . '<br>' .
                    '<strong>Email:</strong> ' . ($return->customer->email ?? '');
            } else {

                $data[] = '';
            }
            $user = User::find($return->created_by);
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
        <a class="btn btn-info me-2 p-2" " href="' . route('returns.edit', $return->id) . '">
           <i  class="fa fa-edit text-white"></i>
        </a>
        <a class="btn btn-danger delete-btn p-2  me-2"  href="' . route('returns.destroy', $return->id) . '">
            <i  class="fa fa-trash text-white"></i>
        </a>
         <a class=" btn btn-secondary me-2  p-2" href="' . route('returns.show', $return->id) . '">
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
        $singleReturn = ReturnProduct::findOrFail($id);
        return view('backend.modules.return.show_details', ['singleReturn' => $singleReturn]);
    }
    public function store(Request $request)
    {
        // dd($request->discount);
        $request->validate([
            'return_date' => 'required',
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
        $return = null;
        try {
            DB::transaction(function () use ($request, $cart, $itemTotal, $shipping, $packingCharge, $discount, $totalValue, $adjustAmount, $totalProductQuantity, &$return) {
                // Create and save the purchase record
                $return = new ReturnProduct();
                $return->customer_id = $request->customer_id;
                $return->admin_note = $request->admin_note;
                $return->discount_amount = $discount;
                $return->subtotal = $itemTotal;
                $return->shipping_charge = $shipping;
                $return->packing_charge = $packingCharge;
                $return->total = $totalValue;
                $return->adjust_amount = $adjustAmount;
                $return->return_date = $request->return_date
                    ? Carbon::parse($request->purchase_date)->format('Y-m-d')
                    : Carbon::now()->format('Y-m-d');
                $return->quantity = $totalProductQuantity;
                $return->tax = 0;
                $return->payment_status = 'unpaid';
                $return->source = 'pos';
                $return->save();

                $this->returnItems($return, $cart);
                $this->returnTransactions($return);
                $this->returnsRefund($return);
                $this->saveStockInfo($return, $cart);
            });

            if ($return) {
                // return redirect()->route('returns.index');
                return redirect()->route('returns.show', $return->id);
                // return back();
            } else {
                return redirect()->back()->withErrors(['error' => 'An error occurred: Returns not created.']);
            }
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'An error occurred: ' . $e->getMessage()]);
        }
    }
    //
    public function edit($id)
    {

        $paymentMethods = PaymentMethod::where('status', 1)->get();
        // $paymentMethods=PaymentMethod::where('status',1)->get();
        $return = ReturnProduct::findOrFail($id);
        $returnItems = $return->returnItems;
        $products = [];
        foreach ($returnItems as $returnItem) {
            $product = Product::find($returnItem->product_id);
            if ($product) {
                $productArray = [
                    'id' => $product->id,
                    'english_name' => $product->english_name,
                    'current_price' => $returnItem->price,
                    'mrp_price' => $product->mrp_price,
                    'thumb_image' => image($product->thumb_image),
                    'subtotal' => $returnItem->total,
                    'stock' => $product->stock,
                    'quantity' => $returnItem->qty,
                    'product_code' => $product->product_code,
                ];
                // Add the product array to the products array
                $products[] = $productArray;
            }
        }

        $cart = json_encode($products);


        return view(
            'backend.modules.return.edit',
            [
                'returns' => $return,
                'cart' => $cart,
                'paymentMethods' => $paymentMethods,
            ]
        );
    }

    public function editStore(Request $request, $id)
    {
        // dd($request->all(), $id);
        // dd($request->discount);
        $request->validate([
            'return_date' => 'required',
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
        $return = null;
        try {

            DB::transaction(function () use ($request, $id, $cart, $itemTotal, $shipping, $packingCharge, $discount, $totalValue, $adjustAmount, $totalProductQuantity, &$return) {
                // Create and save the purchase record
                $return = ReturnProduct::find($id);
                $return->admin_note = $request->admin_note;
                $return->discount_amount = $discount;
                $return->subtotal = $itemTotal;
                $return->shipping_charge = $shipping;
                $return->packing_charge = $packingCharge;
                $return->total = $totalValue;
                $return->adjust_amount = $adjustAmount;
                $return->return_date = $request->return_date
                    ? Carbon::parse($request->return_date)->format('Y-m-d')
                    : Carbon::now()->format('Y-m-d');
                $return->quantity = $totalProductQuantity;
                $return->tax = 0;
                $return->payment_status = 'unpaid';
                $return->source = 'pos';
                $return->save();

                $this->returnItems($return, $cart);
                $this->returnTransactions($return);
                $this->returnsRefund($return);
                $this->saveStockInfo($return, $cart);
            });

            if ($return) {

                return redirect()->route('returns.index');
            } else {

                return redirect()->back()->withErrors(['error' => 'An error occurred: Returns not created.']);
            }
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'An error occurred: ' . $e->getMessage()]);
        }
    }

    public function destroyAll(Request $request)
    {
        $token = base64_decode($request->get("token"));
        $ids = json_decode($token);

        foreach ($ids as $id) {
            $returnProduct = ReturnProduct::find($id);
            if ($returnProduct) {
                $returnProduct->delete();
            }
        }

        return response()->json(['message' => 'Return deleted successfully'], 200);
    }


    public function destroy($id)
    {
        $return_product = ReturnProduct::find($id);

        if ($return_product) {

            $return_product->delete();

            return response()->json(['message' => 'Returns deleted successfully'], 200);
        } else {

            return response()->json(['error' => 'Returns not found'], 404);
        }
    }


    private function returnItems($return, $cart)
    {

        foreach ($cart as $c) {
            $product = Product::find($c['id']);


            // Check if the order item already exists
            $returnItem = ReturnItem::where('return_id', $return->id)
                ->where('product_id', $c['id'])
                ->first();

            // If the  item does not exist, create a new one
            if (!$returnItem) {
                $returnItem = new ReturnItem();
                $returnItem->return_number = $return->return_number;
                $returnItem->return_id = $return->id;
                $returnItem->product_id = $c['id'];
            }
            // Update the order item details
            $returnItem->qty = $c['quantity'];
            $returnItem->price = $c['current_price'];
            $returnItem->total = $c['current_price'] * $c['quantity'];
            $returnItem->status = $product->status;
            $returnItem->publisher_id = $product->publisher_id;
            // Extracting IDs using pluck and setting attributes (json encoded handled by mutators)
            $returnItem->author_id = collect($product->authors)->pluck('id')->toArray();
            $returnItem->category_id = collect($product->categories)->pluck('id')->toArray();
            $returnItem->subcategory_id = collect($product->subcategories)->pluck('id')->toArray();
            $returnItem->save();
            $product->save();
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

    private function saveStockInfo($return, $cart)
    {
        foreach ($cart as $c) {
            $product = Product::find($c['id']);
            if (!$product) {
                continue;
            }

            $stock = Stock::where('return_id', $return->id)
                ->where('product_id', $c['id'])
                ->where('is_bundle_item', 0)
                ->first();

            $previousQty = $stock ? $stock->item_qty : 0;

            if (!$stock) {
                $stock = new Stock();
                $stock->stock_type = 'return';
                $stock->stock_entry_date = now();
                $stock->return_id = $return->id;
                $stock->product_id = $c['id'];

                $stock->item_price = $c['current_price'];
                $stock->item_qty = $c['quantity'];

                $stock->item_subtotal = $c['current_price'] * $c['quantity'];
                $stock->item_description = 'returns';

                $stock->save();


                $product->stock += $c['quantity'];
            } else {
                $stock->item_price = $c['current_price'];
                $stock->item_qty = $c['quantity'];

                $stock->item_subtotal = $c['current_price'] * $c['quantity'];
                $stock->item_description = 'returns';
                $stock->save();
                $product->stock += $c['quantity'] + $previousQty;
            }
            $product->save();
        }
    }

    private function returnTransactions($return)
    {

        $existingPaymentDetails = [];
        if (Session::has('payment_details')) {
            $existingPaymentDetails = Session::get('payment_details');
        }
        foreach ($existingPaymentDetails as $paymentDetails) {
            $paymentMethodId = $paymentDetails['payment_method_id'];
            $paymentMethodName = $paymentDetails['methodName'];
            $amount = $paymentDetails['amount'];
            $transactionId = $paymentDetails['transaction_id'];
            $note = $paymentDetails['note'];
            $payment = new ReturnTransaction();
            $payment->return_id = $return->id;
            $payment->return_number = $return->return_number;
            $payment->method_id = $paymentMethodId;
            $payment->method_name = $paymentMethodName;
            $payment->transaction_id = $transactionId;
            $payment->amount = $amount;
            $payment->notes = $note;
            $payment->status = 1;
            $payment->save();
        }


        $transactionSum = $return->transactions()->sum('amount');
        $paymentStatus = $return->total <= $transactionSum  ? 'paid' : ($transactionSum == 0 ? 'unpaid' : 'partial');
        $return->payment_status = $paymentStatus;
        $return->save();


        
        Session::forget('payment_details');
    }

    public function transactionsShow($id)
    {

        $return = ReturnProduct::findOrFail($id);

        $paymentMethods = PaymentMethod::all();

        return view('backend.modules.return.transaction_show', ['return' => $return, 'paymentMethods' => $paymentMethods]);
    }

    public function transactionsStore(Request $request)
    {
        // $purchase = Purchase::find($request->id);
        $return = ReturnProduct::find($request->id);
        // Retrieve the payment details from the session
        $existingPaymentDetails = [];
        if (Session::has('payment_details'))
            $existingPaymentDetails = Session::get('payment_details');

        foreach ($existingPaymentDetails as $paymentDetails) {
            // Access the individual payment details
            $paymentMethodId = $paymentDetails['payment_method_id'];
            $paymentMethodName = $paymentDetails['methodName'];
            $amount = $paymentDetails['amount'];
            $transactionId = $paymentDetails['transaction_id'];
            $note = $paymentDetails['note'];
            // Create a new instance of OrderTransaction
            $payment = new ReturnTransaction();
            $payment->return_id = $return->id;
            $payment->return_number = $return->return_number;
            //            $payment->user_id = $purchase->user_id;
            $payment->method_id = $paymentMethodId;
            $payment->method_name = $paymentMethodName;
            $payment->transaction_id = $transactionId;
            $payment->amount = $amount;
            $payment->notes = $note;
            $payment->status = 1;
            $payment->save();
        }
        $transactionSum = $return->transactions()->sum('amount');
        $paymentStatus = $transactionSum >= $return->total  ? 'paid' : ($transactionSum == 0 ? 'unpaid' : 'partial');
        $return->payment_status = $paymentStatus;
        $return->save();

        Session::forget('payment_details');

        $existingWalletDetails = Session::get('wallet_details', []);
        foreach ($existingWalletDetails as $paymentDetails) {
            $wallet = new Wallet();
            // Populate the wallet transaction fields
            $wallet->customer_id = $return->customer_id;
            $wallet->transaction_type = 'debit';
            $wallet->amount = $paymentDetails['amount'];
            $wallet->w_type = 'refund';
            $wallet->return_id = $return->id;
            $wallet->payment_method = 'wallet';
            $wallet->note = $paymentDetails['note'];
            $wallet->status = 1;
            $wallet->created_by = Auth::id();
            $wallet->save();
        }
        $transactionSum = $return->wallets()->sum('amount');
        $paymentStatus = $transactionSum >= $return->total  ? 'paid' : ($transactionSum == 0 ? 'unpaid' : 'partial');
        $return->payment_status = $paymentStatus;
        $return->save();
        // Forget the session
        Session::forget('wallet_details');
        // Check if the session key has been forgotten
        // if (Session::has('wallet_details')) {
        //     return response()->json([
        //         'message' => 'Failed to clear wallet details from session',
        //     ], 500);
        // }
        return response()->json(['message' => 'Payment saved successfully']);
    }

    public function returnsInvoice($id)
    {
        $returns = ReturnProduct::find($id);

        $htmlContent = view('backend.modules.return.invoice', compact('returns'))->render();

        return response()->json(['htmlContent' => $htmlContent]);
    }

    public function updatePaymentStatus(Request $request)
    {
        try {
            $token = base64_decode($request->get("purchase_ids"));

            $ids = json_decode($token);

            foreach ($ids as $id) {
                $returns = ReturnProduct::find($id);
                if ($returns) {
                    $returns->payment_status = $request->payment_status;
                    $returns->save();
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

    public function paymentMethodStore(Request $request)
    {
        // Retrieve the input values from the request
        $paymentMethodId = $request->input('payment_method_id');
        //        $methodName = $request->input('method_name');
        $amount = $request->input('amount');
        $transactionId = $request->input('transaction_id');
        $note = $request->input('note');
        $method = PaymentMethod::where('id', $paymentMethodId)->first();
        $methodName =  $method->name;
        $paymentDetails = [
            'payment_method_id' => $paymentMethodId,
            'methodName' => $methodName,
            'amount' => $amount,
            'transaction_id' => $transactionId,
            'note' => $note,
        ];
        // Retrieve the existing payment details from the session (if any)
        $existingPaymentDetails = Session::get('payment_details', []);
        // Append the new payment details to the existing ones
        $existingPaymentDetails[] = $paymentDetails;
        // Save the updated payment details in the session
        Session::put('payment_details', $existingPaymentDetails);

        $html = view('backend.modules.return.payment_summary')->render();

        return response()->json([
            'existingPaymentDetails' => $existingPaymentDetails,
            'success' => 'Payment  method saved successfully',
            'html' => $html,
        ]);
    }

    public function wallettMethodStore(Request $request)
    {
        // dd($request->all());
        $amount = $request->input('amount');
        $note = $request->input('note');
        $methodName = 'wallet';
        $walletDetails = [
            'payment_method' => $methodName,
            'amount' => $amount,
            'note' => $note,
        ];

        $existingWalletDetails = Session::get('wallet_details', []);
        $existingWalletDetails[] = $walletDetails;

        Session::put('wallet_details', $existingWalletDetails);

        $html = view('backend.modules.return.payment_summary')->render();

        return response()->json([
            'existingWalletDetails' => $existingWalletDetails,
            'success' => 'Saved successfully',
            'html' => $html,
        ]);
    }


    public function removePaymentMethod(Request $request)
    {

        $index = $request->input('index');
        // Retrieve the existing payment details from the session
        $existingPaymentDetails = Session::get('payment_details', []);

        if ($index >= 0 || $index < count($existingPaymentDetails)) {

            // Remove the payment at the specified index
            array_splice($existingPaymentDetails, $index, 1);

            // Update the payment details in the session
            Session::put('payment_details', $existingPaymentDetails);

            // Return a success response
            return response()->json([
                'success' => 'Payment removed successfully',
                'existingPaymentDetails' => $existingPaymentDetails,
                'html' =>view('backend.modules.return.payment_summary')->render(),
            ]);
        }

        // Return an error response if the index is out of range
        return response()->json([
            'error' => 'Invalid index provided',
        ], 400);
    }

    public function removeBTNWallet(Request $request)
    {

        $index = $request->input('index');


        // Retrieve the existing payment details from the session
        $existingPaymentDetails = Session::get('wallet_details', []);

        if ($index >= 0 || $index < count($existingPaymentDetails)) {

            // Remove the payment at the specified index
            array_splice($existingPaymentDetails, $index, 1);

            // Update the payment details in the session
            Session::put('wallet_details', $existingPaymentDetails);

            // Return a success response
            return response()->json([
                'success' => 'Payment removed successfully',
                'existingPaymentDetails' => $existingPaymentDetails,
                'html' =>view('backend.modules.return.payment_summary')->render(),
            ]);
        }

        // Return an error response if the index is out of range
        return response()->json([
            'error' => 'Invalid index provided',
        ], 400);
    }



    public function returnMethodStore(Request $request)
    {

        // dd($request->all());
        // Retrieve the input values from the request
        $paymentMethodId = $request->input('payment_method_id');
//        $methodName = $request->input('method_name');
        $amount = $request->input('amount');
        $transactionId = $request->input('transaction_id');
        $note = $request->input('note');
        $method=PaymentMethod::where('id', $paymentMethodId)->first();
        $methodName=  $method->name;

        $paymentDetails = [
            'payment_method_id' => $paymentMethodId,
            'methodName'=> $methodName,
            'amount' => $amount,
            'transaction_id' => $transactionId,
            'note' => $note,
        ];

        // Retrieve the existing payment details from the session (if any)
        $existingPaymentDetails = Session::get('payment_details', []);

        // Append the new payment details to the existing ones
        $existingPaymentDetails[] = $paymentDetails;

        // Save the updated payment details in the session
        Session::put('payment_details', $existingPaymentDetails);

         $html=view('backend.modules.return.payment_summary')->render();

        return response()->json([
            'existingPaymentDetails' => $existingPaymentDetails,
            'success' => 'Payment  method saved successfully',
            'html' =>$html,
        ]);
    }

    public function returnsRefund($return)
    {

        $existingWalletDetails = Session::get('wallet_details', []);
        foreach ($existingWalletDetails as $paymentDetails) {
            $wallet = new Wallet();
            // Populate the wallet transaction fields
            $wallet->customer_id = $return->customer_id;
            $wallet->transaction_type = 'debit';
            $wallet->amount = $paymentDetails['amount'];
            $wallet->w_type = 'refund';
            $wallet->return_id = $return->id;
            $wallet->payment_method = 'wallet';
            $wallet->note = $paymentDetails['note'];
            $wallet->status = 1;
            $wallet->created_by = Auth::id();
            $wallet->save();
        }
        $transactionSum = $return->wallets()->sum('amount');
        $paymentStatus = $transactionSum >= $return->total  ? 'paid' : ($transactionSum == 0 ? 'unpaid' : 'partial');
        $return->payment_status = $paymentStatus;
        $return->save();
        // Forget the session
        Session::forget('wallet_details');
        // Check if the session key has been forgotten
        if (Session::has('wallet_details')) {
            return response()->json([
                'message' => 'Failed to clear wallet details from session',
            ], 500);
        }
        // Return a JSON response
        return response()->json([
            'message' => 'Wallet Saved Successfully'
        ]);
    }



    public function searchCustomer(Request $request)
    {
        $search = $request->input('q');
        $select2Json = [];

        // Fetch users
        $users = User::select('id', 'name', 'email', 'phone')
            ->where('name', 'like', '%' . $search . '%')
            ->orWhere('phone', 'like', '%' . $search . '%')
            ->orWhere('email', 'like', '%' . $search . '%')
            ->get();

        foreach ($users as $single) {
            $select2Json[] = [
                'value' => $single->id,
                'text' => $single->name,
            ];
        }

        return response()->json($select2Json);
    }
}
