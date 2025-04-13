<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Product;
use App\Models\Coupon;
use App\Models\Author;
use App\Models\Publisher;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\CouponProduct;


class CouponController extends Controller
{
    // publisher view
    public function index()
    {
        return view('backend.modules.coupon.index');
    }
    // create form
    public function create()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        $products = Product::where('status', 1)
            ->orderBy('created_at', 'desc')
            ->get(['id', 'english_name']);


        return view('backend.modules.coupon.create', compact('users', 'products'));
    }
    // store
    public function store(Request $request)
    {
        $rules = [
            'c_type' => 'required|string',
            'title' => 'nullable|string',
            'status' => 'required',
            'status' => 'required|in:0,1',
            'code' => [
                'required',
                'string',
            ],
            'discount' => 'required|numeric',
            'discount_type' => 'required|string',
            'status' => 'required|string',
           
            'stock' => 'required|integer',
            'notes' => 'nullable|string',
            'individual_max_use' => 'required|integer'

        ];
        $messages = [
            'c_type' => 'The Coupon type is required',
            'code' => 'The Coupon Code is required',
            'status.required' => 'Status is required',
            'status.in' => 'Invalid status selected',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }
        $jsonData = json_encode($request->type_details);
        // $allProductId = implode(',', $request->type_details);
        $range = explode(" - ", $request->daterange);
        $startDate = trim($range[0]);
        $endDate = trim($range[1]);

        $coupon = new Coupon();
        $coupon->c_type = $request->c_type;
        $coupon->title = $request->title;
        $coupon->status = $request->status;
        $coupon->code = $request->code;
        $coupon->discount = $request->discount;
        $coupon->discount_type = $request->discount_type;
        $coupon->min_buy = $request->min_buy;
        $coupon->max_discount = $request->max_discount;
        $coupon->start_date = $startDate;
        $coupon->end_date = $endDate;
        $coupon->stock = $request->stock;
        $coupon->individual_max_use = $request->individual_max_use;
        $coupon->notes = $request->notes;
        $coupon->save();

        return response()->json([
            'message' => 'Coupon Created Successfully',
        ], 201);
    }
    // datatable ajax
    public function ajaxIndex(Request $request)
    {
        $columns = [
            'id',
        ];

        $draw = $request->draw;
        $row = $request->start;
        $rowPerPage = $request->length;
        $columnIndex = $request->order[0]['column'];
        $columnName = $columns[$columnIndex] ?? $columns[0];
        $columnSortOrder = $request->order[0]['dir'];
        $searchValue = $request->search['value'];
        $totalCoupons = 0;
        $allData = [];

        $query = Coupon::query()->orderBy('created_at', 'desc');

        if (!empty($searchValue)) {
            $query->where('c_type', 'like', '%' . $searchValue . '%');
        }

        $totalDbCoupons = $query->count();

        $allCoupons = $query->orderBy($columnName, $columnSortOrder)
            ->skip($row)
            ->take($rowPerPage)
            ->get();

        foreach ($allCoupons as $key => $coupon) {
            $hasProduct = CouponProduct::where('coupon_id', $coupon->id)->exists();
            $couponTypeP = '';
            if ($coupon->c_type === 'product_base') {
                if ($hasProduct) {
                    $couponTypeP = '<p style="margin-bottom: 0;" class="fw-bold">Product Base
                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="View Product" class="text-info ms-1" href="' . route("coupons.product.view", ['id' => encrypt($coupon->id)]) . '">
                            <i class="fa fa-eye fa-lg"></i>
                        </a>
                    </p>';
                } else {
                    $couponTypeP = '<p style="margin-bottom: 0;" class="fw-bold">Product Base
                     <a data-bs-toggle="tooltip" data-bs-placement="top" title="Add Product" class="text-primary ms-1" href="' . route("coupons.product.add", ['id' => encrypt($coupon->id)]) . '">
                            <i class="fa fa-plus fa-lg"></i>
                        </a>

                    </p>';
                }
            } elseif ($coupon->c_type === 'cart_base') {
                $couponTypeP = '<p style="margin-bottom: 0;" class="fw-bold">Cart Base</p>';
            }

            $checkMark = '<td><label class="checkboxs"><input type="checkbox" data-value="' . $coupon->id . '"><span class="checkmarks"></span></label></td>';
            $data = [];
            $data[] = $checkMark;
            $data[] = ++$key + $row;
            $data[] = $couponTypeP;
            $data[] = $coupon->title ?? '';

            $data[]  = '<span class="badge-linesuccess">' . $coupon->code . '</span>';

            $data[] = '<div class="productimgname">
                            <a href="javascript:void(0);" class="product-img stock-img">' . $coupon->discount . ' ' . $coupon->discount_type ?? '' . '</a>
                        </div>';

            $data[] = '<input type="checkbox" id="user' . $coupon->id . '" class="check" ' . ($coupon->status == 1 ? 'checked' : '') . '>
            <label style="width: 42px !important;" for="user' . $coupon->id . '" class="checktoggle changeStatus" data-coupon-id="' . $coupon->id . '"></label>';

            $data[] = '<div class="m-0">
                            <p>' . $coupon->start_date . '</p>
                            <p>' . $coupon->end_date . '</p>
                        </div>';
            $data[] = $coupon->stock ?? '';

            // $data[] = $coupon->individual_max_use ?? '';

            // $data[] = $coupon->min_buy ?? '';

            // $data[] = $coupon->max_discount ?? '';

            // $data[] = '<span style="cursor: pointer;" class="badge changeValidStatus ' . ($coupon->is_valid_first_order == 1 ? 'badge-linesuccess' : 'badge-linedanger') . '" data-coupon-id="' . $coupon->id . '">' . ($coupon->is_valid_first_order == 1 ? 'Yes' : 'No') . '</span>';

            // $data[] = '<span class="badge-linesuccess">' . $coupon->user_type . '</span>';

            $data[] = '
                        <div class="action-table-data">
                        <div class="edit-delete-action">

                            <a class="btn btn-info me-2 p-2" href="' . route("coupons.edit", ['id' => encrypt($coupon->id)]) . '">
                            <i  class="fa fa-edit text-white"></i>
                            </a>
                            <a class="btn btn-danger delete-btn p-2 " href="' . route("coupons.destroy", ['id' => encrypt($coupon->id)]) . '">
                                <i  class="fa fa-trash text-white"></i>
                            </a>
                        </div>
                        </div>';
            $allData[] = $data;
        }

        $response = [
            "draw" => intval($draw),
            "iTotalRecords" => $totalCoupons,
            "iTotalDisplayRecords" => $totalDbCoupons,
            "aaData" => $allData,
        ];

        return response()->json($response);
    }
    // edit blade
    public function edit($id)
    {

        $Id = decrypt($id);

        $coupons = Coupon::find($Id);

        $users = User::orderBy('created_at', 'desc')->get();

        $allProducts = Product::where('status', 1)
            ->orderBy('created_at', 'desc')
            ->get(['id', 'english_name']);

        return view('backend.modules.coupon.edit', compact('coupons', 'users', 'allProducts'));
    }
    //store updated data
    public function editStore(Request $request)
    {
        $rules = [
            // 'type_details' => 'nullable',
            'title' => 'nullable|string',
            'status' => 'required',
            'status' => 'required|in:0,1',
            'code' => [
                'required',
                'string',
            ],
            'discount' => 'required|numeric',
            'discount_type' => 'required|string',
            'status' => 'required|string',
            // 'start_date' => 'required|date',
            // 'end_date' => 'required|date|after:start_date',
            // 'user_type' => 'required|string',
            'stock' => 'required|integer',
            'notes' => 'nullable|string',
            'individual_max_use' => 'required|integer'
        ];
        $messages = [
            'c_type' => 'The Coupon type is required',
            'code' => 'The Coupon Code is required',
            'status.required' => 'Status is required',
            'status.in' => 'Invalid status selected',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }
        $jsonData = json_encode($request->type_details);
        // $allProductId = implode(',', $request->type_details);
        $range = explode(" - ", $request->daterange);
        $startDate = trim($range[0]);
        $endDate = trim($range[1]);
        $coupon = Coupon::find($request->id);
        // $coupon->type_details = $jsonData;
        $coupon->title = $request->title;
        $coupon->status = $request->status;
        $coupon->code = $request->code;
        $coupon->discount = $request->discount;
        $coupon->discount_type = $request->discount_type;
        $coupon->min_buy = $request->min_buy;
        $coupon->max_discount = $request->max_discount;
        // $coupon->is_valid_first_order = $request->is_valid_first_order;
        $coupon->start_date = $startDate;
        $coupon->end_date = $endDate;
        // $coupon->user_type = $request->user_type;
        $coupon->stock = $request->stock;
        $coupon->individual_max_use = $request->individual_max_use;
        $coupon->notes = $request->notes;
        $coupon->save();

        return response()->json([

            'message' => 'Coupon Updated successfully',

        ], 201);
    }
    // change status
    public function updateStatus(Request $request)
    {
        $id = $request->id;
        $coupon = Coupon::findOrFail($id);
        // Toggle the status
        $coupon->status = $coupon->status == 1 ? 0 : 1;
        // Save the category
        $coupon->save();
        // Return a response
        return response()->json(['message' => 'Coupon Status updated successfully'], 200);
    }

    public function isValidFirtsOrder(Request $request)
    {
        $id = $request->id;

        $coupon = Coupon::findOrFail($id);
        // Toggle the status
        $coupon->is_valid_first_order = $coupon->is_valid_first_order == 1 ? 0 : 1;
        // Save the category
        $coupon->save();
        // Return a response
        return response()->json(['message' => 'Updated successfully'], 200);
    }
    //destroy
    public function destroyAll(Request $request)
    {


        $allIds = base64_decode($request->token);

        foreach (json_decode($allIds) as $couponId) {

            $coupons = Coupon::find($couponId);
            $coupons->delete();
        }

        return response()->json(['message' => 'Selected coupon deleted successfully', 'success' => true], 200);
    }

    public function destroy(Request $request, $id)
    {
        $Id = decrypt($id);
        $coupon = Coupon::find($Id);

        if ($coupon) {
            $coupon->delete();
            return response()->json(['message' => 'Coupon deleted successfully', 'success' => true], 200);
        } else {
            return response()->json(['message' => 'Coupon not found'], 404);
        }
    }

    //add coupon product
    public function addCouponProduct($id)
    {

        $Id = decrypt($id);
        $categories = Category::orderBy('created_at', 'desc')->get();
        $subcategory = Subcategory::orderBy('created_at', 'desc')->get();
        $authors = Author::orderBy('created_at', 'desc')->get();
        $publishers = Publisher::orderBy('created_at', 'desc')->get();
        $coupon = Coupon::where('id', $Id)->first();
        return view('backend.modules.coupon.add-product', compact('categories', 'subcategory', 'authors', 'publishers', 'coupon'));
    }

    public function CouponProductStore(Request $request)
    {
        $ids = $request->ids;


        $coupon = Coupon::find($request->coupon_id);
        if (!$coupon) {
            return response()->json(['message' => 'No coupon found.'], 404);
        }

        foreach ($ids as $id) {
            // Check if the product is already associated with the coupon
            $existingProduct = CouponProduct::where('coupon_id', $coupon->id)
                ->where('product_id', $id)
                ->first();

            if (!$existingProduct) {
                $product = new CouponProduct();
                $product->coupon_id = $coupon->id;
                $product->product_id = $id;
                $product->save();
            }
        }

        return response()->json(['message' => 'products added successfully'], 200);
    }

    // coupons products
    public function singleCouponProductsAjax(Request $request)
    {
        // Initialize variables
        $columns = [
            'id'
        ];

        $draw = $request->draw;
        $row = $request->start;
        $rowPerPage = $request->length;
        $columnIndex = $request->order[0]['column'] ?? 0;
        $columnName = $columns[$columnIndex] ?? 'id';
        $columnSortOrder = $request->order[0]['dir'] ?? 'asc';
        $searchValue = $request->search['value'] ?? '';


        $query = CouponProduct::with('product')
            ->where('coupon_id', $request->id)
            ->orderBy('created_at', 'desc');

        if (!empty($searchValue)) {
            $query->whereHas('product', function ($q) use ($searchValue) {
                $q->where('english_name', 'like', '%' . $searchValue . '%');
            });
        }


        $totalDbProducts = $query->count();
        $allProducts = $query->orderBy($columnName, $columnSortOrder)
            ->skip($row)
            ->take($rowPerPage)
            ->get();
        $allData = [];
        foreach ($allProducts as $key => $sectionProduct) {

            $product = $sectionProduct->product;
            $productId = $product->id;

            if ($productId) {
                $data = [];
                $data[] = ++$key + $row;
                $data[] = '<div class="productimgname"><a href="javascript:void(0);" class="product-img stock-img"><img src="' . image($product->thumb_image) . '" alt="Icon" style="border-radius: 30px;"></a><a href="javascript:void(0);">' . ($product->english_name ?? '') . '</a></div>';

                $data[] = '<ul>' . $product->categories->pluck('name')->unique()->map(function ($name) {
                    return "<li>$name</li>";
                })->implode('') . '</ul>';

                $data[] = '<ul>' . $product->authors->pluck('name')->unique()->map(function ($name) {
                    return "<li>$name</li>";
                })->implode('') . '</ul>';

                $data[] = $product->publisher->name ?? '';

                $data[] = $product->current_price ?? '';

                $data[] = '
            <div class="action-table-data">
                <div class="edit-delete-action">
                    <a class="btn btn-danger delete-btn p-2" href="' . route("coupons.product-destroy", ['id' => $sectionProduct->id]) . '">
                        <i class="fa fa-trash text-white"></i>
                    </a>
                </div>
            </div>';
                $allData[] = $data;
            }
        }

        $response = [
            "draw" => intval($draw),
            "iTotalRecords" => $totalDbProducts,
            "iTotalDisplayRecords" => $totalDbProducts,
            "aaData" => $allData,
        ];
        return response()->json($response);
    }
    // coupon product view
    public function CouponProductView($id)
    {

        $Id = decrypt($id);
        $coupon = Coupon::find($Id);
        return view('backend.modules.coupon.coupon-product', compact('coupon'));
    }
    // single coupon product edit
    public function singleCouponProductsEdit($id)
    {
        $coupon = Coupon::find($id);
        $categories = Category::orderBy('created_at', 'desc')->get();
        $subcategory = Subcategory::orderBy('created_at', 'desc')->get();
        $authors = Author::orderBy('created_at', 'desc')->get();
        $publishers = Publisher::orderBy('created_at', 'desc')->get();

        return view('backend.modules.coupon.coupon-product-edit', compact('coupon', 'categories', 'subcategory', 'authors', 'publishers'));
    }

    public function singleDesteroyProduct(Request $request, $id)
    {

        $couponProduct = CouponProduct::find($id);

        if ($couponProduct) {
            $couponProduct->delete();
        }


        return response()->json(['message' => 'Coupon Product deleted successfully', 'success' => true], 200);
    }
}
