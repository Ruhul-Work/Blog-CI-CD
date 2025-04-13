<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Coupon;
use App\Models\Point;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\User;


class CouponGenerateController extends Controller
{
    //
    public function couponUser(Request $request)
    {
        //return view('backend.modules.coupon.generate');
        // $points = Point::with('user')
        //     ->where('balance', '>=', 100)->get();

        $userpoints = User::where('id', Auth::id())->first();

        // $userpoints = Point::where('user_id', Auth::id())->first();

        return view('frontend.modules.blogs.coupon', ['isDashboard' => true, 'userpoints' => $userpoints]);
    }

    public function couponUserView(Request $request)
    {

        //return view('backend.modules.coupon.generate');
        $coupons = Coupon::with('user')
            ->where('user_id', Auth::id())->get();

        $userpoints = Point::where('user_id', Auth::id())->first();

        return view('frontend.modules.blogs.user_coupon', ['isDashboard' => true,  'coupons' => $coupons, 'userpoints' => $userpoints]);
    }

    public function eligibleCouponUser(Request $request)
    {
        $columns = [
            'id',        // ID column
            'user.name', // User name column
            'points',    // Points column
        ];
        $draw = $request->draw;
        $row = $request->start;
        $rowPerPage = $request->length;
        $columnIndex = $request->order[0]['column'];
        $columnName = $columns[$columnIndex] ?? $columns[0];
        $columnSortOrder = $request->order[0]['dir'];
        $searchValue = $request->search['value'];
        // Base query for eligible users (points >= 100)
        $query = Point::with('user')
            ->where('balance', '>=', 100);

        // Apply search filter if searchValue is provided
        if (!empty($searchValue)) {
            $query->whereHas('user', function ($q) use ($searchValue) {
                $q->where('name', 'like', '%' . $searchValue . '%');
            });
        }
        // Get total records count (before pagination)
        $totalDbCoupons = $query->count();

        // Apply ordering, pagination, and fetch data
        $allCoupons = $query->orderBy($columnName, $columnSortOrder)
            ->skip($row)
            ->take($rowPerPage)
            ->get();

        // Prepare the data for DataTables
        $allData = [];
        foreach ($allCoupons as $key => $coupon) {

            $checkMark = '<label class="checkboxs">
                        <input type="checkbox" data-value="' . $coupon->id . '">
                        <span class="checkmarks"></span>
                      </label>';

            $data = [];
            $data[] = $checkMark; // Checkbox
            $data[] = ++$key + $row; // Serial number
            $data[] = $coupon->user->name ?? 'N/A'; // User name
            $data[] = $coupon->balance; // User points
            $data[] = '<div class="action-table-data">
                        <div class="edit-delete-action">
                        
                            <a class="btn btn-info me-2 p-2" href="' . route("coupons.generate", ['id' => ($coupon->id)]) . '">
                               <i class="fa-solid fa-plus"></i>
                            </a>
                            
                            <a class="btn btn-danger delete-btn p-2" href="' . route("coupons.destroy", ['id' => encrypt($coupon->id)]) . '">
                                <i class="fa fa-trash text-white"></i>
                            </a>
                        </div>
                   </div>';

            $allData[] = $data;
        }

        // Prepare the response for DataTables
        $response = [
            "draw" => intval($draw),
            "iTotalRecords" => $totalDbCoupons,
            "iTotalDisplayRecords" => $totalDbCoupons,
            "aaData" => $allData,
        ];

        return response()->json($response);
    }

    public function generateCouponForPoints(Request $request, $id)
    {
        $users = User::find($id);


        if (!$users || $users->points < config('app.coupon_minimum_points')) {
            return response()->json(['success' => false, 'message' => 'Insufficient points to generate a coupon'], 400);
        }
        // Generate a unique coupon code
        $couponCode = 'EM' . strtoupper(Str::random(5)); // Generates an 8-character code

        try {
            if ($request->platform === 'subscriptions') {

                // Create a coupon for subscriptions
                Coupon::create([
                    'code' => $couponCode,
                    'discount' => 100,
                    'discount_type' => 'amount',
                    'user_id' => $users->id,
                    'c_type' => 'cart_base',
                    'title' => 'Em Blog',
                    'min_buy'=>1,
                    'max_discount'=>100,
                    'discount_type' => 'amount',
                    'individual_max_use' => 1,
                    'notes' => 'User got this coupon by earned points',
                    'user_type' => 'Customer',
                    'status' => '1',
                    'stock' => 1,
                    'start_date' => now(),
                    'end_date' => now()->addDays(30),
                ]);
            } elseif ($request->platform === 'ecommerce') {
                // Create a coupon for e-commerce
                DB::connection('ecommerce')->table('coupons')->insert([
                    'code' => $couponCode,
                    'discount' => 100,
                    'discount_type' => 'amount',
                    //coupon type
                    'c_type' => 'cart_base',
                    'title' => 'Em Blog',
                    'min_buy'=>1,
                    'max_discount'=>100,
                    'individual_max_use' => 1,
                    'notes' => 'User got this coupon by earned points from English Moja Blog website',
                    'user_type' => 'Customer',
                    'status' => '1',
                    'stock' => 1,
                    'start_date' => now(),
                    'end_date' => now()->addDays(30),
                ]);
                Coupon::create([
                    'code' => $couponCode,
                    'discount' => 100,
                    'discount_type' => 'amount',
                    'user_id' => $users->id,
                    'c_type' => 'cart_base',
                    'title' => 'Em Blog',
                    'min_buy'=>1,
                    'max_discount'=>100,
                    'discount_type' => 'amount',
                    'individual_max_use' => 1,
                    'notes' => 'User got this coupon by earned points',
                    'user_type' => 'Customer',
                    'status' => '0',
                    'stock' => 0,
                    'notes' => "This Coupon Only Use for  English Moja Ecommerce Website",
                    'start_date' => now(),
                    'end_date' => now()->addDays(30),
                ]);
                
            }

            // Coupon generation was successful
            $couponGeneratedSuccessfully = true;

            // Update points table
            if ($couponGeneratedSuccessfully) {
                User::where('id', $id)->update([
                    'points' => DB::raw('points - 100'),
                ]);
            }

            return response()->json(['success' => true, 'message' => 'Coupon generated successfully', 'coupon_code' => $couponCode]);
        } catch (\Exception $e) {
            // Handle errors
            return response()->json(['success' => false, 'message' => 'Failed to generate coupon: ' . $e->getMessage()], 500);
        }
    }

    public function redeemCoupon(Request $request)
    {
        $userId = $request->input('user_id');
        $couponCode = $request->input('coupon_code');

        // Check if the coupon exists and belongs to the user
        $coupon = Coupon::where('code', $couponCode)->where('user_id', $userId)->where('is_used', false)->first();

        if (!$coupon) {
            return response()->json(['message' => 'Invalid or expired coupon'], 404);
        }

        // Mark the coupon as used
        $coupon->update(['is_used' => true]);

        return response()->json(['message' => 'Coupon redeemed successfully', 'discount' => $coupon->discount]);
    }
}
