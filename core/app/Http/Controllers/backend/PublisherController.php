<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Publisher;

class PublisherController extends Controller
{
    // publisher view
    public function index()
    {
        return view('backend.modules.publisher.index');
    }
    // create form
    public function create()
    {

        return view('backend.modules.publisher.create');
    }
    // store
    public function store(Request $request)
    {

        $rules = [
            'name' => 'required',
            'icon' => 'required',
            'cover_image' => 'nullable',
            'meta_title' => 'nullable',
            'meta_description' => 'nullable',
            'meta_image' => 'nullable',
            'status' => 'required',
            'slug' => 'required',
        ];

        $messages = [
            'name' => 'Publisher Name is required',
            'status' => 'Status required',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $publisher = new Publisher();
        $publisher->name = $request->name;
        $publisher->description = $request->description;
        $publisher->meta_title = $request->meta_title;
        $publisher->meta_description = $request->meta_description;
        $publisher->status = $request->status;
        $publisher->slug = $request->slug;



        if ($request->hasFile('icon')) {

            $path = 'uploads/Publishers/icon/' . date('Y/m/d') . '/';
            $imageName = uniqid() . '.webp';
            $request->file('icon')->move($path, $imageName);
            $publisher->icon = $path . $imageName;
        }

        if ($request->hasFile('cover_image')) {

            $path = 'uploads/publishers/cover_image/' . date('Y/m/d') . '/';
            $imageName = uniqid() . '.webp';
            $request->file('cover_image')->move($path, $imageName);
            $publisher->cover_image = $path . $imageName;

        }

        if ($request->hasFile('meta_image')) {
            $path = 'uploads/publishers/meta_image/' . date('Y/m/d') . '/';
            $imageName = uniqid() . '.webp';
            $request->file('meta_image')->move($path, $imageName);
            $publisher->meta_image = $path . $imageName;
        }

        $publisher->save();

        return response()->json([
            'message' => 'Publisher created successfully',

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

        $query = Publisher::query()->orderBy('created_at', 'desc');


        if (!empty($searchValue)) {
            $query->where('name', 'like', '%' . $searchValue . '%');
        }

        $totalDbAuthors = $query->count(); // Count total records without pagination

        $allPublishers = $query->orderBy($columnName, $columnSortOrder)
            ->skip($row)
            ->take($rowPerPage)
            ->get();

        foreach ($allPublishers as $key => $publisher) {

            $checkMark = '<td><label class="checkboxs"><input type="checkbox" data-value="' . $publisher->id . '"><span class="checkmarks"></span></label></td>';
            $data = [];
            $data[] = $checkMark;
            $data[] = $publisher->id;
            // $data[] = $author->name ?? '';

            $data[] = '<div class="productimgname">
            <a href="javascript:void(0);" class="product-img stock-img">
                <img src="' . image($publisher->icon) . '" alt="Icon" style="border-radius: 30px;">
            </a>
            <a href="javascript:void(0);">' . $publisher->name ?? '' . '</a>
        </div>';

            $data[] = '<div class="productimgname">
        <button type="button" class="btn btn-sm btn-outline-info view-image-btn" data-bs-toggle="modal" data-bs-target="#imageViewModal" data-cover-url="' . image($publisher->cover_image) . '">View</button>
      </div>';

            $data[] = $publisher->description ?? '';

            $data[] = '<span style="cursor: pointer;" class="badge changeStatus  ' . ($publisher->status == 1 ? 'badge-linesuccess' : 'badge-linedanger') . '" data-publishers-id="' . $publisher->id . '" >' . ($publisher->status == 1 ? 'Active' : 'Inactive') . '</span>';

            $data[] = $publisher->user->name ?? '';

            $data[] = '
            <div class="action-table-data">
            <div class="edit-delete-action">

                <a class="btn btn-info me-2 p-2" href="' . route("publishers.edit", ['id' => encrypt($publisher->id)]) . '">
                <i  class="fa fa-edit text-white"></i>
                </a>
                <a class="btn btn-danger delete-btn p-2 " href="' . route("publishers.destroy", ['id' => encrypt($publisher->id)]) . '">
                    <i  class="fa fa-trash text-white"></i>
                </a>
            </div>
            </div>';

            $allData[] = $data;
        }

        $response = [
            "draw" => intval($draw),
            "iTotalRecords" => $totalAuthors, // Total records in your database table
            "iTotalDisplayRecords" => $totalDbAuthors, // Total records after filtering
            "aaData" => $allData,
        ];

        return response()->json($response);
    }
    // edit blade
    public function edit($id)
    {

        $Id = decrypt($id);
        $publisher = Publisher::find($Id);
        return view('backend.modules.publisher.edit', compact('publisher'));
    }
    //store updated data
    public function editStore(Request $request)
    {

        $rules = [
            'name' => 'required',
            'icon' => 'nullable',
            'cover_image' => 'nullable',
            'meta_title' => 'nullable',
            'meta_description' => 'nullable',
            'meta_image' => 'nullable',
            'status' => 'required',
        ];

        $messages = [

            'name' => 'Publisher Name is required',
            'status' => 'Status required',

        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $publishers = Publisher::find($request->id);
        $publishers->name = $request->name;
        $publishers->description = $request->description;
        $publishers->meta_title = $request->meta_title;
        $publishers->meta_description = $request->meta_description;
        $publishers->status = $request->status;

        // Set other attributes here following the same pattern

        if ($request->hasFile('icon')) {


            if (isset($publishers->icon) && file_exists($publishers->icon)) {
                unlink($publishers->icon);
            }

            $path = 'uploads/Publishers/icon/' . date('Y/m/d') . '/';
            $imageName = uniqid() . '.webp';
            $request->file('icon')->move($path, $imageName);
            $publishers->icon = $path . $imageName;
        }


        if ($request->hasFile('cover_image')) {

            if (isset($publishers->cover_image) && file_exists($publishers->cover_image)) {
                unlink($publishers->cover_image);
            }

            $path = 'uploads/publishers/cover_image/' . date('Y/m/d') . '/';
            $imageName = uniqid() . '.webp';
            $request->file('cover_image')->move($path, $imageName);
            $publishers->cover_image = $path . $imageName;
        }

        if ($request->hasFile('meta_image')) {

            if (isset($publishers->meta_image) && file_exists($publishers->meta_image)) {
                unlink($publishers->meta_image);
            }
            $path = 'uploads/publishers/meta_image/' . date('Y/m/d') . '/';
            $imageName = uniqid() . '.webp';
            $request->file('meta_image')->move($path, $imageName);
            $publishers->meta_image = $path . $imageName;
        }

        $publishers->save();

        return response()->json([
            'message' => 'Publishers Updated successfully',
        ], 201);
    }

    // change status
    public function updateStatus(Request $request)
    {
        $id = $request->id;
        $Author = Publisher::findOrFail($id);

        // Toggle the status
        $Author->status = $Author->status == 1 ? 0 : 1;

        // Save the category
        $Author->save();

        // Return a response
        return response()->json(['message' => 'Publishers Status updated successfully'], 200);
    }
    //destroy

    public function destroyAll(Request $request)
    {
        $allIds = base64_decode($request->token);


        foreach (json_decode($allIds) as $publisherId) {
            $publisher = Publisher::find($publisherId);

            if ($publisher) {

                if (isset($publisher->icon) && file_exists($publisher->icon)) {
                    unlink($publisher->icon);
                }

                if (isset($publisher->cover_image) && file_exists($publisher->cover_image)) {
                    unlink($publisher->cover_image);
                }

                if (isset($publisher->meta_image) && file_exists($publisher->meta_image)) {
                    unlink($publisher->meta_image);
                }

                $publisher->delete(); // Trigger the deleting event for each model
            }
        }

        return response()->json(['message' => 'Selected publishers deleted successfully', 'success' => true], 200);
    }
    // single destroy
    public function destroy(Request $request, $id)
    {

        $Id = decrypt($id);

        $publisher = Publisher::find($Id);

        if ($publisher) {

            if (isset($publisher->icon) && file_exists($publisher->icon)) {
                unlink($publisher->icon);
            }

            if (isset($publisher->cover_image) && file_exists($publisher->cover_image)) {
                unlink($publisher->cover_image);
            }

            if (isset($publisher->meta_image) && file_exists($publisher->meta_image)) {
                unlink($publisher->meta_image);
            }

            $publisher->delete();
        }

        return response()->json(['message' => 'Publisher deleted successfully','success' => true], 200);
    }
    public function publisherSearch(Request $request)
    {
        $search = $request->input('q');
        $select2Json = [];

        $publishers = Publisher::where('name', 'like', '%' . $search . '%')
            -> where('status', 1)
            ->get();

        foreach ($publishers as $publisher) {
            $select2Json[] = array(
                'id' =>  $publisher->id,
                'text' => $publisher->name,
            );
        }

        return response()->json($select2Json);
    }
}
