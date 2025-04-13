<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;

use App\Models\Author;

class AuthorsController extends Controller
{
    // author view
    public function index()
    {
        return view('backend.modules.author.index');
    }
    // create form
    public function create()
    {

        return view('backend.modules.author.create');
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
            'name' => 'Author Name is required',
            'status' => 'Status required',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }
        $author = new Author();
        $author->name = $request->name;
        $author->description = $request->description;
        $author->meta_title = $request->meta_title;
        $author->meta_description = $request->meta_description;
        $author->status = $request->status;
        $author->slug = $request->slug;
        // Set other attributes here following the same pattern
        if ($request->hasFile('icon')) {
            $path = 'uploads/authors/icon/' . date('Y/m/d') . '/';
            $imageName = uniqid() . '.webp';
            $request->file('icon')->move($path, $imageName);
            $author->icon = $path . $imageName;
        }

        //$uploadedImage = uploadImage($request->file('icon'), 'authors/icon', '0', 60);



        // dd($uploadedImage);
        if ($request->hasFile('cover_image')) {
            $path = 'uploads/authors/cover_image/' . date('Y/m/d') . '/';
            $imageName = uniqid() . '.webp';
            $request->file('cover_image')->move($path, $imageName);
            $author->cover_image = $path . $imageName;
        }

        if ($request->hasFile('meta_image')) {
            $path = 'uploads/authors/meta_image/' . date('Y/m/d') . '/';
            $imageName = uniqid() . '.webp';
            $request->file('meta_image')->move($path, $imageName);
            $author->meta_image = $path . $imageName;
        }

        $author->save();

        return response()->json([
            'message' => 'Author created successfully',

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

        $query = Author::query()->orderBy('created_at', 'desc');


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
            <a href=" '.route("author.single", ['slug' => $author->slug ??$author->id]).'" class="product-img stock-img" title="view details" target="_blank">
                <img src="' . image($author->icon) . '" alt="Icon" style="border-radius: 30px;">
            </a>
            <a  href=" '.route("author.single", ['slug' => $author->slug ??$author->id]).'" title="view details" target="_blank">' . $author->name ?? '' . '</a>
        </div>';


    //         $data[] = '<div class="productimgname">
    //   <button type="button" class="btn btn-sm btn-outline-info view-image-btn" data-bs-toggle="modal" data-bs-target="#imageViewModal" data-cover-url="' . image($author->cover_image) . '">View</button>
    // </div>';

           $data[] = Str::limit($author->description ?? '', 100);

            $data[] = '<span class="badge changeStatus ' . ($author->status == 1 ? 'badge-linesuccess' : 'badge-linedanger') . '" data-author-id="' . $author->id . '" style="cursor: pointer;">' . ($author->status == 1 ? 'Active' : 'Inactive') . '</span>';

            $data[] = $author->user->name ?? '';

            $data[] = '
            <div class="action-table-data">
            <div class="edit-delete-action">

                <a class="btn btn-info me-2 p-2" href="'  . route("authors.edit", ['id' => encrypt($author->id)]) . '">
                <i  class="fa fa-edit text-white"></i>
                </a>
                <a class="btn btn-danger delete-btn p-2 " href="' . route("authors.destroy", ['id' => encrypt($author->id)]) . '">
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
        $authors = Author::find($Id);
        return view('backend.modules.author.edit', compact('authors'));
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

            'name' => 'Author Name is required',
            'status' => 'Status required',

        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $author = Author::find($request->id);
        $author->name = $request->name;
        $author->description = $request->description;
        $author->meta_title = $request->meta_title;
        $author->meta_description = $request->meta_description;
        $author->status = $request->status;

        // Set other attributes here following the same pattern

        if ($request->hasFile('icon')) {

            if (isset($author->icon)) {
                $oldImagePath = $author->icon;
                unlink($oldImagePath);
            }

            $path = 'uploads/authors/icon/' . date('Y/m/d') . '/';
            $imageName = uniqid() . '.webp';
            $request->file('icon')->move($path, $imageName);
            $author->icon = $path . $imageName;
        }


        if ($request->hasFile('cover_image')) {
            if (isset($author->cover_image)) {
                $oldImagePath = $author->cover_image;
                unlink($oldImagePath);
            }

            $path = 'uploads/authors/cover_image/' . date('Y/m/d') . '/';
            $imageName = uniqid() . '.webp';
            $request->file('cover_image')->move($path, $imageName);
            $author->cover_image = $path . $imageName;
        }

        if ($request->hasFile('meta_image')) {

            if (isset($author->meta_image)) {

                $oldImagePath = $author->meta_image;
                // dd($oldImagePath);
                unlink($oldImagePath);
            }
            $path = 'uploads/authors/meta_image/' . date('Y/m/d') . '/';
            $imageName = uniqid() . '.webp';
            $request->file('meta_image')->move($path, $imageName);
            $author->meta_image = $path . $imageName;
        }

        $author->save();

        return response()->json([
            'message' => 'Author Updated successfully',
        ], 201);
    }

    // change status
    public function updateStatus(Request $request)
    {
        $id = $request->id;
        $Author = Author::findOrFail($id);

        // Toggle the status
        $Author->status = $Author->status == 1 ? 0 : 1;

        // Save the category
        $Author->save();

        // Return a response
        return response()->json(['message' => 'Author Status updated successfully'], 200);
    }


    public function destroyAll(Request $request)
    {

        $allIds = base64_decode($request->token);

        foreach (json_decode($allIds) as $authorId) {

            $authors = Author::find($authorId);
            if ($authors) {
                if (isset($authors->icon) && file_exists($authors->icon)) {
                    unlink($authors->icon);
                }

                if (isset($authors->cover_image) && file_exists($authors->cover_image)) {
                    unlink($authors->cover_image);
                }

                if (isset($authors->meta_image) && file_exists($authors->meta_image)) {
                    unlink($authors->meta_image);
                }

                $authors->delete(); // Trigger the deleting event for each model
            }
        }

        return response()->json(['message' => 'Selected authors deleted successfully']);
    }
    // single destroy
    public function destroy(Request $request, $id)
    {
        $Id = decrypt($id);
        $author = Author::find($Id);

        if ($author) {
            if (isset($author->icon) && file_exists($author->icon)) {
                unlink($author->icon);
            }
            if (isset($author->cover_image) && file_exists($author->cover_image)) {
                unlink($author->cover_image);
            }
            if (isset($author->meta_image) && file_exists($author->meta_image)) {
                unlink($author->meta_image);
            }

            $author->delete();

            return response()->json(['message' => 'Author deleted successfully', 'success' => true], 200);
        }

        return response()->json(['message' => 'Author not found', 'success' => false], 404);
    }




    public function authorSearch(Request $request)
    {
        $search = $request->input('q');
        $select2Json = [];

        $authors = Author::where('name', 'like', '%' . $search . '%')
            -> where('status', 1)
            ->get();

        foreach ($authors as $author) {
            $select2Json[] = array(
                'id' =>  $author->id,
                'text' => $author->name,
            );
        }

        return response()->json($select2Json);
    }
}
