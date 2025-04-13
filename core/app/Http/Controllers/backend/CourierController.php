<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Courier;

class CourierController extends Controller
{

    // view all sliders
    public function index()
    {
        return view('backend.modules.courier.index');
    }
    // create form
    public function create()
    {

        return view('backend.modules.courier.create');
    }
    // store slider data
    public function store(Request $request)
    {

        $rules = [
            'name' => 'required',
            'in_dhaka' => 'nullable',
            'outside' => 'nullable',
            'logo' => 'required',
            'status' => 'required',
            'status' => 'required|in:0,1',
        ];

        $messages = [

            'status.required' => 'Status is required',
            'status.in' => 'Invalid status selected',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $couriers = new Courier();
        $couriers->name = $request->name;
        $couriers->in_dhaka = $request->in_dhaka;
        $couriers->outside = $request->outside;
        $couriers->description = $request->description;
        $couriers->status = $request->status;
        // Set other attributes here following the same pattern
        if ($request->hasFile('logo')) {
            $path = 'uploads/couriers/logo/' . date('Y/m/d') . '/';
            $imageName = uniqid() . '.webp';
            $request->file('logo')->move($path, $imageName);
            $couriers->logo = $path . $imageName;
        }

        $couriers->save();

        return response()->json([
            'message' => 'Courier created successfully',

        ], 201);
    }
    // ajax view
    public function ajaxIndex(Request $request)
    {

        $columns = [
            'id',
            // Add other column names here as needed
        ];

        $draw = $request->draw;
        $row = $request->start;
        $rowPerPage = $request->length;
        $columnIndex = $request->order[0]['column'];
        $columnName = $columns[$columnIndex] ?? $columns[0];
        $columnSortOrder = $request->order[0]['dir'];
        $searchValue = $request->search['value'];
        $totalCouriers = 0;
        $allData = [];
        $query = Courier::query()->orderBy('created_at', 'desc');
        if (!empty($searchValue)) {
            $query->where('name', 'like', '%' . $searchValue . '%');
        }
        $totalDbCouriers = $query->count(); // Count total records without pagination
        $allCouriers = $query->orderBy($columnName, $columnSortOrder)
            ->skip($row)
            ->take($rowPerPage)
            ->get();
        foreach ($allCouriers as $key => $courier) {
            $checkMark = '<td><label class="checkboxs"><input type="checkbox" data-value="' . $courier->id . '"><span class="checkmarks"></span></label></td>';
            $data = [];
            $data[] = $checkMark;
            $data[] = ++$key + $row;
            $data[] = $courier->name ?? '';
            // $data[] = $courier->in_dhaka ?? '';
            // $data[] = $courier->outside ?? '';
            $data[] = '<div class="productimgname">
   <button type="button" class="btn btn-sm btn-outline-info view-image-btn" data-bs-toggle="modal" data-bs-target="#imageViewModal" data-image-url="' . image($courier->logo) . '">View</button>
 </div>';
            $data[] = $courier->description ?? '';
            $data[] = '<span style="cursor: pointer;" class="badge changeStatus ' . ($courier->status == 1 ? 'badge-linesuccess' : 'badge-linedanger') . '" data-sliders-id="' . $courier->id . '">' . ($courier->status == 1 ? 'Active' : 'Inactive') . '</span>';
            $data[] = $courier->user->name ?? '';
            $data[] = '
            <div class="action-table-data">
            <div class="edit-delete-action">
                <a class="btn btn-info me-2 p-2" href="' . route("couriers.edit", ['id' => encrypt($courier->id)]) . '">
                <i  class="fa fa-edit text-white"></i>
                </a>
                <a class="btn btn-danger delete-btn p-2 " href="' . route("couriers.destroy", ['id' => encrypt($courier->id)]) . '"  style="cursor: pointer;">
                    <i  class="fa fa-trash text-white"></i>
                </a>
            </div>
            </div>';
            $allData[] = $data;
        }

        $response = [
            "draw" => intval($draw),
            "iTotalRecords" => $totalCouriers, // Total records in your database table
            "iTotalDisplayRecords" => $totalDbCouriers, // Total records after filtering
            "aaData" => $allData,
        ];

        return response()->json($response);
    }


    // edit blade
    public function edit($id)
    {
        $Id = decrypt($id);
        $couriers = Courier::find($Id);
        return view('backend.modules.courier.edit', compact('couriers'));
    }

    //store updated data
    public function editStore(Request $request)
    {

        $rules = [
            'in_dhaka' => 'nullable',
            'outside' => 'nullable',
            'status' => 'required',
            'status' => 'required|in:0,1',
        ];

        $messages = [

            'status.required' => 'Status is required',
            'status.in' => 'Invalid status selected',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $couriers = Courier::find($request->id);
        $couriers->name = $request->name;
        $couriers->in_dhaka = $request->in_dhaka;
        $couriers->outside = $request->outside;
        $couriers->description = $request->description;
        $couriers->status = $request->status;
        // Set other attributes here following the same pattern
        if ($request->hasFile('logo')) {

            if (isset($couriers->logo) && file_exists($couriers->logo)) {
                unlink($couriers->logo);
            }

            $path = 'uploads/couriers/logo/' . date('Y/m/d') . '/';
            $imageName = uniqid() . '.webp';
            $request->file('logo')->move($path, $imageName);
            $couriers->logo = $path . $imageName;
        }

        $couriers->save();

        return response()->json([
            'message' => 'Courier Updated successfully',

        ], 201);
    }
    // delete all
    // single delete

    // change status
    public function updateStatus(Request $request)
    {
        $id = $request->id;
        $couriers = Courier::findOrFail($id);

        // Toggle the status
        $couriers->status = $couriers->status == 1 ? 0 : 1;

        // Save the category
        $couriers->save();

        // Return a response
        return response()->json(['message' => 'Courier Status updated successfully'], 200);
    }

    //destroy

    public function destroyAll(Request $request)
    {


        $allIds = base64_decode($request->token);


        foreach (json_decode($allIds) as $couriersId) {

            $couriers = Courier::find($couriersId);

            if ($couriers) {
                if (isset($couriers->logo) && file_exists($couriers->logo)) {
                    unlink($couriers->logo);
                }
                $couriers->delete(); // Trigger the deleting event for each model
            }
        }

        return response()->json(['message' => 'Deleted successfully', 'success' => true], 200);
    }
    // single destroy
    public function destroy(Request $request, $id)
    {

        $Id = decrypt($id);

        $couriers = Courier::find($Id);

        if ($couriers) {

            if (isset($couriers->logo) && file_exists($couriers->logo)) {
                unlink($couriers->logo);
            }

            $couriers->delete();
        }

        return response()->json(['message' => 'Courier Deleted successfully', 'success' => true], 200);
    }
}
