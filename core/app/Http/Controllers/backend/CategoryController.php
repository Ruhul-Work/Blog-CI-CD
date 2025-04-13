<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function index()
    {


        return view('backend.modules.category.index');

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
            $query = Category::query();

            $totalCategoriesCount = $query->count();

            $records = $query->orderBy($columnName, $columnSortOrder)
                ->skip($row)
                ->take($rowperpage)
                ->get();


            $totalRecords = $totalDRecords = !empty($totalCategoriesCount) ? $totalCategoriesCount : 0;
        } else {
            $query = Category::query();


            $totalRecords = $query->count();


            $query->where('name', 'like', '%' . $searchValue . '%');

            $records = $query->orderBy($columnName, $columnSortOrder)
                ->skip($row)
                ->take($rowperpage)
                ->get();

            $totalDRecords = $totalRecords;
        }

        foreach ($records as $key => $category) {


            $data = [];


            $data[] = '<td>
            <label class="checkboxs">
                <input type="checkbox" class="checked-row" data-value="' . $category->id . '">
                <span class="checkmarks"></span>
            </label>
        </td>';

            $data[] = ++$key;


            $data[] = '<div class="productimgname">
    <a href="javascript:void(0);" class="product-img stock-img">
        <img src="' . image($category->icon) . '" alt="icon">
    </a>
    <a href="javascript:void(0);">' . $category->name . '</a>
</div>';


            $data[] = '<td>
            <a class="product-img" >
            <img src="' . image($category->cover_image) . '" alt="cover_image" style="height: 50px!important;">
            </a>
            </td>';


            $data[] = $category->description;

            $data[] = '<span class="badge changeIsmenu ' . ($category->is_menu == 1 ? 'badge-linesuccess' : 'badge-linedanger') . '" style="cursor:pointer;" data-category-id="' . $category->id . '">' . ($category->is_menu == 1 ? 'Active' : 'Inactive') . '</span>';

            $data[] = '<span class="badge changeStatus ' . ($category->status == 1 ? 'badge-linesuccess' : 'badge-linedanger') . '" style="cursor:pointer;" data-category-id="' . $category->id . '">' . ($category->status == 1 ? 'Active' : 'Inactive') . '</span>';


            $data[] = '
            <div class="action-table-data">
            <div class="edit-delete-action">

                <a class="btn btn-info me-2 p-2" href="' . route('categories.edit', $category->id) . '">
                <i  class="fa fa-edit text-white"></i>
                </a>
                <a class="btn btn-danger delete-btn p-2 " href="' . route('categories.destroy', $category->id) . '">
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


    public function create()
    {


        return view('backend.modules.category.create');

    }

    public function store(Request $request)
    {
        // Validate the form data
        $request->validate([
            'name' => 'required',
            'icon' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'nullable',
            'is_menu' => 'nullable|boolean',
            'description' => 'nullable',
            'slug' => 'required|unique:categories',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'meta_title' => 'nullable',
            'meta_description' => 'nullable',
            'meta_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',

        ]);

        //uploadImage in helper function

        $iconPath = uploadImage($request->file('icon'), 'category/icons');

        $coverImagePath = uploadImage($request->file('cover_image'), 'category/cover_images');

        $metaImagePath = null;

        if ($request->hasFile('meta_image')) {
            $metaImagePath = uploadImage($request->file('meta_image'), 'category/meta_images');
        }

        // Create a new category instance
        $category = new Category;
        $category->name = $request->name;
        $category->slug = $request->slug;
        $category->description = $request->description;
        $category->status = $request->status;
        //$category->is_menu = $request->has('is_menu') ? 1 : 0;
        $category->is_menu = (int) $request->is_menu;
        $category->icon = $iconPath;
        $category->cover_image = $coverImagePath;

        $category->meta_title = $request->meta_title;
        $category->meta_description = $request->meta_description;
        $category->meta_image = $metaImagePath;


        $category->save();

        // Return a response
        return response()->json(['message' => 'Category added successfully'], 200);
    }


    public function edit($id)
    {
        $category = Category::findOrFail($id);

        return view('backend.modules.category.edit', ['category' => $category]);
    }


    public function update(Request $request)
    {
        // Validate the form data
        $request->validate([
            'name' => 'required',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'nullable',
            'is_menu' => 'nullable|boolean',
            'description' => 'nullable',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'meta_title' => 'nullable',
            'meta_description' => 'nullable',
            'meta_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);


        $category = Category::findOrFail($request->id);


        $previousIcon = $category->icon;
        $previousCoverImage = $category->cover_image;
        $previousMetaImage = $category->meta_image;


        // Upload icon if provided, otherwise retain the previous value
        if ($request->hasFile('icon')) {
            $iconPath = uploadImage($request->file('icon'), 'category/icons');
            $category->icon = $iconPath;

            if ($previousIcon) {
                unlink($previousIcon);
            }
        }

        // Upload cover image if provided, otherwise retain the previous value
        if ($request->hasFile('cover_image')) {
            $coverImagePath = uploadImage($request->file('cover_image'), 'category/cover_images');
            $category->cover_image = $coverImagePath;

            if ($previousCoverImage) {
                unlink($previousCoverImage);
            }
        }

        // Upload meta image if provided, otherwise retain the previous value
        if ($request->hasFile('meta_image')) {
            $metaImagePath = uploadImage($request->file('meta_image'), 'category/meta_images');
            $category->meta_image = $metaImagePath;

            if ($previousMetaImage) {
                unlink($previousMetaImage);
            }
        }

        // Update other fields
        $category->name = $request->name;
        $category->description = $request->description;
        $category->status = $request->status;
        $category->is_menu = (int) $request->is_menu;
        $category->meta_title = $request->meta_title;
        $category->meta_description = $request->meta_description;

        // Save the category
        $category->save();

        // Return a response
        return response()->json(['message' => 'Category  updated successfully'], 200);
    }


    public function destroy($id)
    {
        // Find the category by ID
        $category = Category::findOrFail($id);

        // Store the paths of the images before deleting the category
        $iconPath = $category->icon;
        $coverImagePath = $category->cover_image;
        $metaImagePath = $category->meta_image;

        // Delete the category
        $category->delete();
        // Unlink the images if they exist
        if (isset($iconPath) && file_exists($iconPath)) {
            unlink($iconPath);
        }
        if (isset($coverImagePath) && file_exists($coverImagePath)) {
            unlink($coverImagePath);
        }
        if (isset($metaImagePath) && file_exists($metaImagePath)) {
            unlink($metaImagePath);
        }
        // Return a response
        // return response()->json(['message' => 'Category deleted successfully'], 200);
        return response()->json(['message' => 'Category deleted successfully', 'success' => true], 200);
    }


    public function destroyAll(Request $request)
    {
        $token = base64_decode($request->get("token"));
        $ids = json_decode($token);

        foreach ($ids as $id) {
            $category = Category::find($id);
            if ($category) {

                // Store the paths of the images before deleting the category
                $iconPath = $category->icon;
                $coverImagePath = $category->cover_image;
                $metaImagePath = $category->meta_image;

                // Delete the category
                $category->delete();

                // Unlink the images if they exist

                if (isset($iconPath) && file_exists($iconPath)) {
                    unlink($iconPath);
                }

                if (isset($coverImagePath) && file_exists($coverImagePath)) {
                    unlink($coverImagePath);
                }

                if (isset($metaImagePath) && file_exists($metaImagePath)) {
                    unlink($metaImagePath);
                }

            }
        }

        return back()->with('success', 'Successfully Deleted.');
    }


    public function updateStatus(Request $request)
    {
        $id = $request->id;
        $category = Category::findOrFail($id);

        // Toggle the status
        $category->status = $category->status == 1 ? 0 : 1;

        // Save the category
        $category->save();

        // Return a response
        return response()->json(['message' => 'Category status updated successfully'], 200);
    }

    public function updateIsmenu(Request $request)
    {
        $id = $request->id;
        $category = Category::findOrFail($id);

        // Toggle the status
        $category->is_menu = $category->is_menu == 1 ? 0 : 1;

        // Save the category
        $category->save();

        // Return a response
        return response()->json(['message' => 'Category Show Menu updated successfully'], 200);
    }


    public function categorySearch(Request $request)
    {
        $search = $request->input('q');
        $select2Json = [];

        $categories = Category::where('name', 'like', '%' . $search . '%')
            ->where('status', 1)
            ->get();

        foreach ($categories as $category) {

            $select2Json[] = array(
                'id' => $category->id,
                'text' => $category->name,
            );
        }

        return response()->json($select2Json);
    }

}
