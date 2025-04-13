<?php

namespace App\Http\Controllers\backend\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\OrderStatus;


class OrderStatusController extends Controller
{
    // author view
    public function index()
    {
        return view('backend.modules.order_status.index');
    }
    // create form
    public function create()
    {
        return view('backend.modules.order_status.create');
    }
    // store
    public function store(Request $request)
    {

        $rules = [
            'name' => 'required',
            'status' => 'required',

        ];

        $messages = [
            'name' => 'Status Name is required',
            'status' => 'Status required',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $status = new OrderStatus();
        $status->name = $request->name;
        $status->status = $request->status;
        // Set other attributes here following the same pattern
        $status->save();

        return response()->json([
            'message' => 'status created successfully',
        ], 201);
    }
    // datatable ajax
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
        $totalAuthors = 0;
        $allData = [];

        $query = OrderStatus::query()->orderBy('created_at', 'desc');


        if (!empty($searchValue)) {
            $query->where('name', 'like', '%' . $searchValue . '%');
        }

        $totalDbAuthors = $query->count(); // Count total records without pagination

        $allAuthors = $query->orderBy($columnName, $columnSortOrder)
            ->skip($row)
            ->take($rowPerPage)
            ->get();

        foreach ($allAuthors as $key => $author) {

            $checkMark = '<td><label class="checkboxs"><input type="checkbox" data-value="' . $author->id . '"><span class="checkmarks"></span></label></td>';
            $data = [];
            $data[] = $checkMark;
            $data[] = ++$key + $row;
            // $data[] = $author->name ?? '';
            $data[] = '<div class="productimgname">

              <a href="javascript:void(0);">' . $author->name ?? '' . '</a>
          </div>';

            $data[] = '<span class="badge changeStatus ' . ($author->status == 1 ? 'badge-linesuccess' : 'badge-linedanger') . '" data-author-id="' . $author->id . '" style="cursor: pointer;">' . ($author->status == 1 ? 'Active' : 'Inactive') . '</span>';

            $data[] = $author->user->name ?? '';
            // $data[] = '
            //   <div class="action-table-data">

            //   <div class="edit-delete-action">

            //       <a class="btn btn-info me-2 p-2 edit_status" data-bs-toggle="modal"
            //       data-bs-target="#statusUpdateModal" data-id="' . $author->id . '">
            //       <i  class="fa fa-edit text-white"></i>
            //       </a>

            //       <a class="btn btn-danger delete-btn p-2 " href="' . route("orderstatuses.destroy", ['id' => encrypt($author->id)]) . '">
            //           <i  class="fa fa-trash text-white"></i>
            //       </a>
            //   </div>

            //   </div>';

            $data[] = '
              <div class="action-table-data">

              <div class="edit-delete-action">

                  <a class="btn btn-info me-2 p-2 edit_status" data-bs-toggle="modal"
                  data-bs-target="#statusUpdateModal" data-id="' . $author->id . '">
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
    // edit blade
    public function edit(Request $request)
    {
        $Id = $request->id;

        $statuses = OrderStatus::find($Id);

        $statData = view('backend.modules.order_status.edit_modal', compact('statuses'))->render();

        return response()->json([
            'statData' => $statData,
            'success' => true
        ]);
    }

    //   //store updated data
    public function editStore(Request $request)
    {

        $rules = [
            'name' => 'required',
            'status' => 'required',

        ];

        $messages = [
            'name' => 'Status Name is required',
            'status' => 'Status required',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $status = OrderStatus::find($request->id);
        $status->name = $request->name;
        $status->status = $request->status;
        // Set other attributes here following the same pattern
        $status->save();

        return response()->json([
            'message' => 'Status Info Updated successfully',
        ], 201);
    }

    //   // change status
    public function updateStatus(Request $request)
    {
        $id = $request->id;
        $status = OrderStatus::findOrFail($id);
        // Toggle the status
        $status->status = $status->status == 1 ? 0 : 1;
        // Save the category
        $status->save();
        // Return a response
        return response()->json(['message' => 'Status updated successfully'], 200);
    }


    public function destroyAll(Request $request)
    {
        $allIds = base64_decode($request->token);

        foreach (json_decode($allIds) as $authorId) {
            $status = OrderStatus::find($authorId);
            if ($status) {
                $status->delete();
            }
        }

        return response()->json(['message' => 'Selected Status deleted successfully']);
    }
    //   // single destroy
    public function destroy(Request $request, $id)
    {
        $Id = decrypt($id);

        $status = OrderStatus::find($Id);

        if ($status) {
            $status->delete();
        }

        return response()->json(['message' => 'Deleted successfully']);
    }
}
