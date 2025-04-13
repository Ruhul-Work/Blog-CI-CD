<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index()
    {
        return view('backend.modules.tag.index');
    }

    public function ajaxIndex(Request $request)
    {
        $columns = ["id"];
        $draw = $request->draw;
        $row = $request->start;
        $rowperpage = $request->length;

        $columnIndex = $request->order[0]['column'];
        $columnName = !empty($columns[$columnIndex]) ? $columns[$columnIndex] : $columns[0];
        $columnSortOrder = $request->order[0]['dir'];
        $searchValue = $request->search['value'];

        $query = Tag::query();
        $totalRecords = $query->count();

        if (!empty($searchValue)) {
            $query->where('name', 'like', '%' . $searchValue . '%');
        }

        $totalDisplayRecords = $query->count();
        $records = $query->orderBy($columnName, $columnSortOrder)
            ->skip($row)
            ->take($rowperpage)
            ->get();

        $data = [];
        foreach ($records as $key => $tag) {
            $row = [];
            $row[] = '<label class="checkboxs"><input type="checkbox" class="checked-row" data-value="' . $tag->id . '"><span class="checkmarks"></span></label>';
            $row[] = ++$key;
            $row[] = $tag->name;
            $row[] = '<div class="action-table-data">
                        <a class="btn btn-info me-2 p-2" href="' . route('tags.edit', $tag->id) . '">
                            <i class="fa fa-edit text-white"></i>
                        </a>
                        <a class="btn btn-danger delete-btn p-2" href="' . route('tags.destroy', $tag->id) . '">
                            <i class="fa fa-trash text-white"></i>
                        </a>
                      </div>';
            $data[] = $row;
        }

        $response = [
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalDisplayRecords,
            "aaData" => $data,
        ];

        return response()->json($response);
    }

    public function create()
    {
        return view('backend.modules.tag.create');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|unique:tags|max:255',
                'slug' => 'required|unique:blogs,slug',
            ]);

            Tag::create($request->all());

            return response()->json([
                'message' => 'Tag created successfully!',
                'redirect' => route('tags.index'),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create tag. Please try again later.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function edit($id)
    {
        $tag = Tag::findOrFail($id);
        return view('backend.modules.tag.edit', compact('tag'));
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'required|unique:tags,name,' . $id . '|max:255',
                'slug' => 'required|unique:blogs,slug',
            ]);

            $tag = Tag::findOrFail($id);
            $tag->update($request->all());

            return response()->json([
                'message' => 'Tag updated successfully!',
                'redirect' => route('tags.index'),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update tag. Please try again later.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            Tag::destroy($id);
            return response()->json([
                'message' => 'Tag deleted successfully!',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete tag. Please try again later.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroyAll(Request $request)
    {
        $token = base64_decode($request->get("token"));
        $ids = json_decode($token);

        foreach ($ids as $id) {
            $tags = Tag::find($id);
            if ($tags) {

                // Delete the tag
                $tags->delete();

            }
        }

        return back()->with('success', 'Successfully Deleted.');
    }

}
