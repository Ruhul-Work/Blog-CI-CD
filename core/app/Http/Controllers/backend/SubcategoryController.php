<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SubcategoryController extends Controller
{
    public function   index(){


        return view ('backend.modules.subcategory.index');

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
            $query = Subcategory::query();

            $totalSubcategoriesCount = $query->count();

            $records = $query->orderBy($columnName, $columnSortOrder)
                ->skip($row)
                ->take($rowperpage)
                ->get();


            $totalRecords = $totalDRecords = !empty($totalSubcategoriesCount) ? $totalSubcategoriesCount : 0;
        } else {
            $query = Subcategory::query();


            $totalRecords = $query->count();


            $query->where('name', 'like', '%' . $searchValue . '%');

            $records = $query->orderBy($columnName, $columnSortOrder)
                ->skip($row)
                ->take($rowperpage)
                ->get();

            $totalDRecords = $totalRecords;
        }

        foreach ($records as $key => $subcategory) {


            $data = [];


            $data[] = '<td>
            <label class="checkboxs">
                <input type="checkbox" class="checked-row" data-value="'.$subcategory->id . '">
                <span class="checkmarks"></span>
            </label>
        </td>';

            $data[] = ++$key;

            $data[] = $subcategory->category->name;


            $data[] = '<div class="productimgname">
    <a href="javascript:void(0);" class="product-img stock-img">
        <img src="' . image($subcategory->icon) . '" alt="icon">
    </a>
    <a href="javascript:void(0);">' . $subcategory->name . '</a>
</div>';


            $data[] = '<td>
            <a class="product-img" >
            <img src="' . image($subcategory->cover_image) . '" alt="cover_image" style="height: 50px!important;">
            </a>
            </td>';


            $data[] = $subcategory->description;
            $data[] = '<span class="badge changeStatus ' . ($subcategory->status == 1 ? 'badge-linesuccess' : 'badge-linedanger') . ' " style="cursor:pointer;" " data-subcategory-id="' . $subcategory->id . '">' . ($subcategory->status == 1 ? 'Active' : 'Inactive') . '</span>';


            $data[] = '<div class="action-table-data">
    <div class="edit-delete-action">
         <a class="btn btn-info me-2 p-2"  href="' . route('subcategories.edit', $subcategory->id) . '">
            <i  class="fa fa-edit text-white"></i>
        </a>
         <a class="btn btn-danger delete-btn p-2 " href="' . route('subcategories.destroy', $subcategory->id) . '">
            <i  class="fa fa-trash text-white"></i>
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

    public function   create(){

        return view ('backend.modules.subcategory.create');
    }

    public function store(Request $request)
    {
        // Validate the form data


        $request->validate([
            'category_id' => [
                'required',
                Rule::exists('categories', 'id'),
            ],
            'name' => 'required',
            'icon' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'nullable',
            'description' => 'nullable',
            'slug' => 'required|unique:categories',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'meta_title' => 'nullable',
            'meta_description' => 'nullable',
            'meta_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);


        $iconPath =uploadImage($request->file('icon'), 'subcategory/icons');
        $coverImagePath =uploadImage($request->file('cover_image'), 'subcategory/cover_images');

        $metaImagePath = null;
        if ($request->hasFile('meta_image')) {
            $metaImagePath =uploadImage($request->file('meta_image'), 'subcategory/meta_images');
        }

        // Create a new category instance
        $subcategory = new Subcategory;
        $subcategory->category_id = $request->category_id;
        $subcategory->name = $request->name;
        $subcategory->slug = $request->slug;
        $subcategory->description = $request->description;
        $subcategory->status = $request->status;
        $subcategory->icon = $iconPath;
        $subcategory->cover_image = $coverImagePath;

        $subcategory->meta_title = $request->meta_title;
        $subcategory->meta_description = $request->meta_description;
        $subcategory->meta_image = $metaImagePath;



        $subcategory->save();

        // Return a response
        return response()->json(['message' => 'Subcategory added successfully'], 200);
    }
    public function edit($id)
    {
        $subcategory = Subcategory::findOrFail($id);

        return view('backend.modules.subcategory.edit', ['subcategory' => $subcategory]);
    }

    public function update(Request $request)
    {
        // Validate the form data
        $request->validate([
            'name' => 'required',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'nullable',
            'description' => 'nullable',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'meta_title' => 'nullable',
            'meta_description' => 'nullable',
            'meta_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);


        $subcategory = Subcategory::findOrFail($request->id);

        // Upload icon if provided, otherwise retain the previous value


        $previousIcon = $subcategory->icon;
        $previousCoverImage = $subcategory->cover_image;
        $previousMetaImage = $subcategory->meta_image;

        if ($request->hasFile('icon')) {
            $iconPath =uploadImage($request->file('icon'), 'subcategory/icons');
            $subcategory->icon = $iconPath;

            if ($previousIcon) {
                unlink($previousIcon);
            }
        }

        // Upload cover image if provided, otherwise retain the previous value
        if ($request->hasFile('cover_image')) {
            $coverImagePath =uploadImage($request->file('cover_image'), 'subcategory/cover_images');
            $subcategory->cover_image = $coverImagePath;



            if ($previousCoverImage) {
                unlink($previousCoverImage);
            }
        }

        // Upload meta image if provided, otherwise retain the previous value
        if ($request->hasFile('meta_image')) {
            $metaImagePath =uploadImage($request->file('meta_image'), 'subcategory/meta_images');
            $subcategory->meta_image = $metaImagePath;

            if ($previousMetaImage) {
                unlink($previousMetaImage);
            }

        }

        // Update other fields
        $subcategory->name = $request->name;

        $subcategory->description = $request->description;
        $subcategory->status = $request->status;
        $subcategory->meta_title = $request->meta_title;
        $subcategory->meta_description = $request->meta_description;

        // Save the category
        $subcategory->save();

        // Return a response
        return response()->json(['message' => 'Subcategory  updated successfully'], 200);
    }


    public function destroy($id)
    {

        $subcategory = Subcategory::findOrFail($id);

        $iconPath = $subcategory->icon;
        $coverImagePath = $subcategory->cover_image;
        $metaImagePath = $subcategory->meta_image;


        $subcategory->delete();


        // Unlink the images if they exist
        if ($iconPath) {
            unlink($iconPath);
        }
        if ($coverImagePath) {
            unlink($coverImagePath);
        }
        if ($metaImagePath) {
            unlink($metaImagePath);
        }


        return response()->json(['message' => 'Subcategory deleted successfully'], 200);
    }
    public function destroyAll(Request $request)
    {
        $token = base64_decode($request->get("token"));
        $ids = json_decode($token);

        foreach ($ids as $id) {
            $subcategory = Subcategory::find($id);

            if ($subcategory) {

                // Store the paths of the images before deleting the subcategory
                $iconPath = $subcategory->icon;
                $coverImagePath = $subcategory->cover_image;
                $metaImagePath = $subcategory->meta_image;

                // Delete the subcategory
                $subcategory->delete();


                // Unlink the images if they exist
                if ($iconPath) {
                    unlink($iconPath);
                }
                if ($coverImagePath) {
                    unlink($coverImagePath);
                }
                if ($metaImagePath) {
                    unlink($metaImagePath);
                }
            }
        }

        return back()->with('message', 'Successfully Deleted.');
    }


    public function updateStatus(Request $request)
    {
        $id=$request->id;
        $subcategory = Subcategory::findOrFail($id);
        // Toggle the status
        $subcategory->status = $subcategory->status == 1 ? 0 : 1;
        $subcategory->save();
        // Return a response
        return response()->json(['message' => 'Subcategory status updated successfully'], 200);
    }




    public function fetchSubcategories(Request $request)
    {
        $categoryId = $request->input('category_id');
        $subcategories = Subcategory::where('category_id', $categoryId)->get();
        $options = [];
        foreach ($subcategories as $subcategory) {
            $options[] = [
                'id' => $subcategory->id,
                'name' => $subcategory->name
            ];
        }
        return response()->json($options);
    }





}
