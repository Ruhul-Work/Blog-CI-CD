<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;


class SubjectController extends Controller
{


    public function index()
    {

        return view('backend.modules.subject.index');

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
            $query = Subject::query();

            $totalSubjectsCount = $query->count();

            $records = $query->orderBy($columnName, $columnSortOrder)
                ->skip($row)
                ->take($rowperpage)
                ->get();


            $totalRecords = $totalDRecords = !empty($totalSubjectsCount) ? $totalSubjectsCount : 0;
        } else {
            $query = Subject::query();


            $totalRecords = $query->count();


            $query->where('name', 'like', '%' . $searchValue . '%');

            $records = $query->orderBy($columnName, $columnSortOrder)
                ->skip($row)
                ->take($rowperpage)
                ->get();

            $totalDRecords = $totalRecords;
        }

        foreach ($records as $key => $subject) {
            $data = [];
            $data[] = '<td>
            <label class="checkboxs">
                <input type="checkbox" class="checked-row" data-value="' . $subject->id . '">
                <span class="checkmarks"></span>
            </label>
        </td>';

            $data[] = ++$key;
            $data[] = '<div class="productimgname">
    <a href="javascript:void(0);" class="product-img stock-img">
        <img src="' . image($subject->icon) . '" alt="icon">
    </a>
    <a href="javascript:void(0);">' . $subject->name . '</a>
</div>';

            $data[] = '<td>
            <a class="product-img" >
            <img src="' . image($subject->cover_image) . '" alt="cover_image" style="height: 50px!important;">
            </a>
            </td>';
            $data[] = $subject->description;
            $data[] = '<span class="badge changeStatus ' . ($subject->status == 1 ? 'badge-linesuccess' : 'badge-linedanger') . '" style="cursor:pointer;" data-subject-id="' . $subject->id . '">' . ($subject->status == 1 ? 'Active' : 'Inactive') . '</span>';

            $data[] = '<div class="action-table-data">
    <div class="edit-delete-action">
        <a class="btn btn-info me-2 p-2" href="' . route('subjects.edit', $subject->id) . '">
           <i  class="fa fa-edit text-white"></i>
        </a>

         <a class="btn btn-danger delete-btn p-2 " href="' . route('subjects.destroy', $subject->id) . '">
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

        return view('backend.modules.subject.create');
    }

    public function store(Request $request)
    {
        // Validate the form data

        $request->validate([
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


        $iconPath = uploadImage($request->file('icon'), 'subject/icons');
        $coverImagePath = uploadImage($request->file('cover_image'), 'subject/cover_images');

        $metaImagePath = null;
        if ($request->hasFile('meta_image')) {
            $metaImagePath = uploadImage($request->file('meta_image'), 'subject/meta_images');
        }

        // Create a new category instance
        $subject = new Subject();
        $subject->name = $request->name;
        $subject->slug = $request->slug;
        $subject->description = $request->description;
        $subject->status = $request->status;
        $subject->icon = $iconPath;
        $subject->cover_image = $coverImagePath;

        $subject->meta_title = $request->meta_title;
        $subject->meta_description = $request->meta_description;
        $subject->meta_image = $metaImagePath;


        $subject->save();


        // Return a response
        return response()->json(['message' => 'Subject added successfully'], 200);
    }

    public function edit($id)
    {
        $subject = Subject::findOrFail($id);

        return view('backend.modules.subject.edit', ['subject' => $subject]);
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


        $subject = Subject::findOrFail($request->id);


        $previousIcon = $subject->icon;
        $previousCoverImage = $subject->cover_image;
        $previousMetaImage = $subject->meta_image;

        // Upload icon if provided, otherwise retain the previous value
        if ($request->hasFile('icon')) {
            $iconPath = uploadImage($request->file('icon'), 'subject/icons');
            $subject->icon = $iconPath;

            if ($previousIcon) {
                unlink($previousIcon);
            }
        }

        // Upload cover image if provided, otherwise retain the previous value
        if ($request->hasFile('cover_image')) {
            $coverImagePath = uploadImage($request->file('cover_image'), 'subject/cover_images');
            $subject->cover_image = $coverImagePath;

            if ($previousCoverImage) {
                unlink($previousCoverImage);
            }
        }

        // Upload meta image if provided, otherwise retain the previous value
        if ($request->hasFile('meta_image')) {
            $metaImagePath = uploadImage($request->file('meta_image'), 'subject/meta_images');
            $subject->meta_image = $metaImagePath;

            if ($previousMetaImage) {
                unlink($previousMetaImage);
            }
        }

        // Update other fields
        $subject->name = $request->name;

        $subject->description = $request->description;
        $subject->status = $request->status;
        $subject->meta_title = $request->meta_title;
        $subject->meta_description = $request->meta_description;

        // Save the category
        $subject->save();

        // Return a response
        return response()->json(['message' => 'Subject  updated successfully'], 200);

    }


    public function destroy($id)
    {

        $subject = Subject::findOrFail($id);

        $iconPath = $subject->icon;
        $coverImagePath = $subject->cover_image;
        $metaImagePath = $subject->meta_image;


        $subject->delete();


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

        return response()->json(['message' => 'Subject deleted successfully', 'success' => true], 200);
    }

    public function destroyAll(Request $request)
    {
        $token = base64_decode($request->get("token"));
        $ids = json_decode($token);

        foreach ($ids as $id) {
            $subject = Subject::find($id);
            if ($subject) {
                // Store the paths of the images before deleting the subject
                $iconPath = $subject->icon;
                $coverImagePath = $subject->cover_image;
                $metaImagePath = $subject->meta_image;

                // Delete the subject
                $subject->delete();

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
        $id = $request->id;


        $subject = Subject::findOrFail($id);

        // Toggle the status
        $subject->status = $subject->status == 1 ? 0 : 1;


        $subject->save();

        return response()->json(['message' => 'Subject status updated successfully'], 200);
    }

    public function subjectSearch(Request $request)
    {

        $search = $request->input('q');
        $select2Json = [];
        $subjects = Subject::where('status', 1)
            ->where('name', 'like', '%' . $search . '%')
            ->select('name', 'id')
            ->get();

        foreach ($subjects as $single) {

            $select2Json[] = array(
                'id' =>  $single->id,
                'text' => $single->name,
            );
        }
        echo html_entity_decode(json_encode($select2Json));

    }


}
