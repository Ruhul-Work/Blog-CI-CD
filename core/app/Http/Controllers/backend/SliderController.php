<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Slider;
use App\Models\SubSlider;

class SliderController extends Controller
{
    // view all sliders
    public function index()
    {
        return view('backend.modules.slider.index');
    }

    // view all sub sliders
    public function Subindex()
    {
        return view('backend.modules.slider.sub-slider');
    }

    public function viewSubslider()
    {

        $data = SubSlider::orderBy('created_at', 'desc')->get();

        return response()->json([

            'data' => $data

        ]);
    }

    public function editSubSlider($id)
    {

        $data = SubSlider::find($id);

        return response()->json([
            'data' => $data
        ]);
    }

    // sub slider
    public function editSubStore(Request $request, $id)
    {

        $sliders = SubSlider::find($id);
        
          $sliders->url = $request->url;
          

        if ($request->hasFile('image')) {

            if (isset($sliders->image) && file_exists($sliders->image)) {
                unlink($sliders->image);
            }

            // $path = 'uploads/sliders/image/' . date('Y/m/d') . '/';
            // $imageName = uniqid() . '.webp';
            // $request->file('image')->move($path, $imageName);
            // $sliders->image = $path . $imageName;
            $sliders->image = uploadImage($request->file('image'), 'sliders/image', '0', 60);
        }


        $sliders->save();

        return response()->json([
            'message' => 'Sub Slider created successfully',
        ], 201);
    }

    // public function subSliderStore(Request $request)
    // {

    //     $rules = [
    //         'image' => 'required',
    //     ];

    //     $messages = [

    //         'image' => 'Image is Required',

    //     ];

    //     $validator = Validator::make($request->all(), $rules, $messages);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'errors' => $validator->errors(),
    //         ], 422);
    //     }

    //     $sliders = new SubSlider();
        
        
    //       $sliders->url = $request->url;
    //       dd($request->url);



    //     // Set other attributes here following the same pattern

    //     if ($request->hasFile('image')) {
    //         // $path = 'uploads/sliders/image/' . date('Y/m/d') . '/';
    //         // $imageName = uniqid() . '.webp';
    //         // $request->file('image')->move($path, $imageName);
    //         // $sliders->image = $path . $imageName;

    //         $sliders->image = uploadImage($request->file('image'), 'sliders/image', '0', 60);
    //     }

    //     $sliders->save();

    //     return response()->json([
    //         'message' => 'Sub Slider created successfully',
    //     ], 201);
    // }
    
     public function subSliderStore(Request $request)
    {
        $rules = [
            'image' => 'required|image',

        ];

        $messages = [
            'image.required' => 'Image is required',

        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $sliders = new SubSlider();
        $sliders->url = $request->url;

        if ($request->hasFile('image')) {
            $sliders->image = uploadImage($request->file('image'), 'sliders/image', '0', 60);
        }

        $sliders->save();

        return response()->json([
            'message' => 'Sub Slider created successfully',
        ], 201);
    }


    // sub slider delete

    public function subDestroy(Request $request, $id)
    {

        $slider = SubSlider::find($id);

        if ($slider) {

            if (isset($slider->image) && file_exists($slider->image)) {
                unlink($slider->image);
            }

            $slider->delete();
        }

        return response()->json(['message' => 'Sub Sliders deleted successfully', 'success' => true], 200);
    }
    // create form
    public function create()
    {

        return view('backend.modules.slider.create');
    }
    // store slider data
    public function store(Request $request)
    {
        $rules = [
            'image' => 'required',
            'status' => 'required|in:0,1',

        ];

        $messages = [
            'image' => 'Image is Required',
            'status.required' => 'Status is required',
            'status.in' => 'Invalid status selected',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $sliders = new Slider();
        $sliders->name = $request->name;
        $sliders->description = $request->description;
        $sliders->status = $request->status;
        $sliders->url = $request->url;
        // Set other attributes here following the same pattern
        if ($request->hasFile('image')) {
            // $path = 'uploads/sliders/image/' . date('Y/m/d') . '/';
            // $imageName = uniqid() . '.webp';
            // $request->file('image')->move($path, $imageName);
            // $sliders->image = $path . $imageName;
            $sliders->image = uploadImage($request->file('image'), 'sliders/image', '0', 60);
        }

        $sliders->save();

        return response()->json([
            'message' => 'Slider created successfully',

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
        $totalSliders = 0;
        $allData = [];

        $query = Slider::query()->orderBy('created_at', 'desc');


        if (!empty($searchValue)) {
            $query->where('name', 'like', '%' . $searchValue . '%');
        }

        $totalDbSliders = $query->count(); // Count total records without pagination

        $allSliders = $query->orderBy($columnName, $columnSortOrder)
            ->skip($row)
            ->take($rowPerPage)
            ->get();

        foreach ($allSliders as $key => $sliders) {

            $checkMark = '<td><label class="checkboxs"><input type="checkbox" data-value="' . $sliders->id . '"><span class="checkmarks"></span></label></td>';
            $data = [];
            $data[] = $checkMark;
            $data[] = ++$key + $row;
            $data[] = $sliders->name ?? '';
            $data[] = '<div class="productimgname">
      <button type="button" class="btn btn-sm btn-outline-info view-image-btn" data-bs-toggle="modal" data-bs-target="#imageViewModal" data-image-url="' . image($sliders->image) . '">View</button>
    </div>';

            $data[] = $sliders->description ?? '';

            $data[] = '<span style="cursor: pointer;" class="badge changeStatus ' . ($sliders->status == 1 ? 'badge-linesuccess' : 'badge-linedanger') . '" data-sliders-id="' . $sliders->id . '">' . ($sliders->status == 1 ? 'Active' : 'Inactive') . '</span>';
            $data[] = $sliders->user->name ?? '';
            $data[] = '
            <div class="action-table-data">
            <div class="edit-delete-action">

                <a class="btn btn-info me-2 p-2" href="' . route("sliders.edit", ['id' => encrypt($sliders->id)]) . '">
                <i  class="fa fa-edit text-white"></i>
                </a>
                <a class="btn btn-danger delete-btn p-2 " href="' .  route("sliders.destroy", ['id' => encrypt($sliders->id)]) . '">
                    <i  class="fa fa-trash text-white"></i>
                </a>
            </div>
            </div>';

            $allData[] = $data;
        }

        $response = [
            "draw" => intval($draw),
            "iTotalRecords" => $totalSliders, // Total records in your database table
            "iTotalDisplayRecords" => $totalDbSliders, // Total records after filtering
            "aaData" => $allData,
        ];

        return response()->json($response);
    }


    // edit blade
    public function edit($id)
    {

        $Id = decrypt($id);
        $sliders = Slider::find($Id);
        return view('backend.modules.slider.edit', compact('sliders'));
    }

    //store updated data
    public function editStore(Request $request)
    {

        $rules = [
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

        $sliders = Slider::find($request->id);
        $sliders->name = $request->name;
        $sliders->description = $request->description;
        $sliders->status = $request->status;
        $sliders->url = $request->url;

        // Set other attributes here following the same pattern

        // if ($request->hasFile('image')) {


        //     if (isset($sliders->image) && file_exists($sliders->image)) {
        //         unlink($sliders->image);
        //     }

        //     // $path = 'uploads/sliders/image/' . date('Y/m/d') . '/';
        //     // $imageName = uniqid() . '.webp';
        //     // $request->file('image')->move($path, $imageName);
        //     // $sliders->image = $path . $imageName;
        //     $slider_image = uploadImage($request->file('image'), 'sliders/image', '0', 60);
        // }

        // $sliders->image = $slider_image;
        $slider_image = $sliders->image; // Retain the current image if no new image is uploaded.

if ($request->hasFile('image')) {
    // Check if the old image exists and delete it
    if (isset($sliders->image) && file_exists($sliders->image)) {
        unlink($sliders->image);
    }

    // Upload the new image
    $slider_image = uploadImage($request->file('image'), 'sliders/image', '0', 60);
}

// Assign the image path to the sliders model
$sliders->image = $slider_image;

        $sliders->save();

        return response()->json([
            'message' => 'Slider Updated successfully',

        ], 201);
    }
    // delete all
    // single delete

    // change status
    public function updateStatus(Request $request)
    {
        $id = $request->id;
        $Slider = Slider::findOrFail($id);

        // Toggle the status
        $Slider->status = $Slider->status == 1 ? 0 : 1;

        // Save the category
        $Slider->save();

        // Return a response
        return response()->json(['message' => 'Slider Status updated successfully'], 200);
    }

    //destroy

    public function destroyAll(Request $request)
    {


        $allIds = base64_decode($request->token);

        foreach (json_decode($allIds) as $slidersId) {

            $sliders = Slider::find($slidersId);

            if ($sliders) {
                if (isset($sliders->image) && file_exists($sliders->image)) {
                    unlink($sliders->image);
                }
                $sliders->delete(); // Trigger the deleting event for each model
            }
        }

        return response()->json(['message' => 'Selected sliders deleted successfully']);
    }
    // single destroy
    public function destroy(Request $request, $id)
    {

        $Id = decrypt($id);

        $slider = Slider::find($Id);

        if ($slider) {

            if (isset($slider->image) && file_exists($slider->image)) {
                unlink($slider->image);
            }

            $slider->delete();
        }

        return response()->json(['message' => 'Sliders deleted successfully', 'success' => true], 200);
    }
}
