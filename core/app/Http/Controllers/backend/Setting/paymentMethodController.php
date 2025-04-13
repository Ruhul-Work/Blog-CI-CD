<?php

namespace App\Http\Controllers\backend\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;

class paymentMethodController extends Controller
{
    // author view
    public function index()
    {
        return view('backend.modules.payment_method.index');
    }
    // create form
    public function create()
    {
        return view('backend.modules.payment_method.create');
    }
    // store
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:50',
            'status' => 'required',

        ];

        $messages = [
            'name.required' => 'Status Name is required',
            'status.required' => 'Status is required',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);


        $validator->sometimes('bank_name', 'required|string|max:255', function ($input) {
            return $input->type === 'Bank';
        });

        $validator->sometimes('bank_branch', 'required|string|max:255', function ($input) {
            return $input->type === 'Bank';
        });


        $validator->sometimes('account_number', 'required|max:50', function ($input) {
            return $input->type === 'MFS';
        });


        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $payments = new PaymentMethod();
        $payments->name = $request->name;
        $payments->type = $request->type;
        $payments->account_name = $request->account_name;
        $payments->account_number = $request->account_number;
        $payments->bank_name = $request->bank_name;
        $payments->bank_branch = $request->bank_branch;
        $payments->status = $request->status;
        $payments->payment_process = $request->payment_process;

        if ($request->hasFile('icon')) {
            $path = 'uploads/payments/icon/' . date('Y/m/d') . '/';
            $imageName = uniqid() . '.webp';
            $request->file('icon')->move($path, $imageName);
            $payments->icon = $path . $imageName;
        }

        $payments->save();

        return response()->json([
            'message' => 'New Payment Method created successfully',
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
        $totalAuthors = 0;
        $allData = [];

        $query = PaymentMethod::query()->orderBy('created_at', 'desc');


        if (!empty($searchValue)) {
            $query->where('name', 'like', '%' . $searchValue . '%');
        }

        $totalDbAuthors = $query->count();

        $allMethods = $query->orderBy($columnName, $columnSortOrder)
            ->skip($row)
            ->take($rowPerPage)
            ->get();

        foreach ($allMethods as $key => $methods) {

            if ($methods->type == "Bank") {
                $branch_name = '<span class="badge badge-info">' . $methods->bank_branch . '</span>';
                $bank_name = '<span class="badge badge-info">' . $methods->bank_name . '</span>';
            } else {
                $branch_name = '';
                $bank_name = '';
            }

            $data = [];
            $data[] = ++$key + $row;
            $data[] = '<div class="productimgname">
                <a href="javascript:void(0);" class="product-img stock-img">
                    <img src="' . image($methods->icon) . '" alt="Icon" style="border-radius: 30px;">
                </a>
                <a href="javascript:void(0);">' . $methods->name ?? '' . '</a>
            </div>';

            $data[] = '<span class="badge badge-info p-2"  style="font-size:12px;" >' . ($methods->type ?? '') . '</span>';

            $data[] = '<div class="productimgname">
                             <p>' . ($methods->account_name ?? '') . '</p>
                             <p>' . $bank_name . '</p>
                           </div>';

            $data[] = '<div class="productimgname">
                             <p>' . ($methods->account_number ?? '') . '</p>
                             <p>' . $branch_name . '</p>
                           </div>';

            $data[] = '<span class="badge changeStatus ' . ($methods->status == 1 ? 'badge-linesuccess' : 'badge-linedanger') . '" data-author-id="' . $methods->id . '" style="cursor: pointer;">' . ($methods->status == 1 ? 'Active' : 'Inactive') . '</span>';

            $data[] = $methods->payment_process ?? '';
            $data[] = $methods->user->name ?? '';


            $data[] = '<div class="action-table-data">
                             <div class="edit-delete-action">
                                 <a class="btn btn-info me-2 p-2" href="'  . route("paymentmethod.edit", ['id' => encrypt($methods->id)]) . '">
                                 <i  class="fa fa-edit text-white"></i>
                                 </a>
                             </div>
                           </div>';

            $allData[] = $data;
        }


        $response = [
            "draw" => intval($draw),
            "iTotalRecords" => $totalAuthors,
            "iTotalDisplayRecords" => $totalDbAuthors,
            "aaData" => $allData,
        ];

        return response()->json($response);
    }

    public function edit($id)
    {
        $Id = decrypt($id);
        $payMethods = PaymentMethod::find($Id);
        return view('backend.modules.payment_method.edit', compact('payMethods'));
    }



    public function editStore(Request $request)
    {

        $rules = [
            'name' => 'required|string|max:255',
            'status' => 'required',
        ];
        $messages = [
            'name.required' => 'Status Name is required',
            'status.required' => 'Status is required',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $payments = PaymentMethod::find($request->id);
        $payments->name = $request->name;
        $payments->account_name = $request->account_name;
        $payments->account_number = $request->account_number;
        $payments->bank_name = $request->bank_name;
        $payments->bank_branch = $request->bank_branch;
        $payments->status = $request->status;
        $payments->payment_process = $request->payment_process;

        if ($request->hasFile('icon')) {
            if (!empty($payments->icon)) {
                $oldImagePath = $payments->icon;
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            $path = 'uploads/payments/icon/' . date('Y/m/d') . '/';
            $imageName = uniqid() . '.webp';
            $request->file('icon')->move($path, $imageName);

            $payments->icon = $path . $imageName;
        }


        $payments->save();

        return response()->json([
            'message' => 'Updated successfully',
        ], 201);
    }


    public function updateStatus(Request $request)
    {
        $id = $request->id;
        $payMethods = PaymentMethod::findOrFail($id);


        $activeMethodsCount = PaymentMethod::where('status', 1)->count();


        if ($payMethods->status == 1) {
            if ($activeMethodsCount == 1) {
                return response()->json(['message' => 'At least one payment method must be active.'], 400);
            }
            $payMethods->status = 0;
        } else {

            $payMethods->status = 1;
        }


        $payMethods->save();


        return response()->json(['message' => 'Payment Method Status updated successfully.'], 200);
    }



    public function destroyAll(Request $request)
    {
        $allIds = base64_decode($request->token);

        foreach (json_decode($allIds) as $payId) {

            $payMethods = PaymentMethod::find($payId);

            if ($payMethods) {

                if (isset($payMethods->icon) && file_exists($payMethods->icon)) {
                    unlink($payMethods->icon);
                }
                $payMethods->delete();
            }
        }

        return response()->json(['message' => 'Selected Methods deleted successfully']);
    }



    public function destroy(Request $request, $id)
    {

        $Id = decrypt($id);

        $payMethods = PaymentMethod::find($Id);

        if ($payMethods) {

            if (isset($payMethods->icon) && file_exists($payMethods->icon)) {
                unlink($payMethods->icon);
            }

            $payMethods->delete();
        }

        return response()->json(['message' => 'Deleted successfully']);
    }
}
