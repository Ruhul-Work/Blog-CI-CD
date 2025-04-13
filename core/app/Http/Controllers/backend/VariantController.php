<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Variant;
use Illuminate\Http\Request;

class VariantController extends Controller
{

    public function index()
    {
        $variants = Variant::all();
        return view('backend.modules.variant.index', compact('variants'));
    }


    public function create()
    {
        return view('backend.modules.variant.create');
    }



    public function store(Request $request)
    {
        $validatedData= $request->validate([
            'type' => 'required|string',
            'name' => 'required|string|max:255',

        ]);

        $variant = new Variant();
        $variant->type = $validatedData['type'];
        $variant->name = $validatedData['name'];

        $variant->save();

        // Return JSON response
        return response()->json([
            'message' => 'Variant created successfully!',
        ]);

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
            $query = Variant::query();

            $totalVariantsCount = $query->count();

            $records = $query->orderBy($columnName, $columnSortOrder)
                ->skip($row)
                ->take($rowperpage)
                ->get();

            $totalRecords = $totalDRecords = !empty($totalVariantsCount) ? $totalVariantsCount : 0;
        } else {
            $query = Variant::query();

            $totalRecords = $query->count();

            $query->where('name', 'like', '%' . $searchValue . '%');

            $records = $query->orderBy($columnName, $columnSortOrder)
                ->skip($row)
                ->take($rowperpage)
                ->get();

            $totalDRecords = $totalRecords;
        }

        foreach ($records as $key => $variant) {
            $data = [];
            $data[] = '<td>
            <label class="checkboxs">
                <input type="checkbox" class="checked-row" data-value="' . $variant->id . '">
                <span class="checkmarks"></span>
            </label>
        </td>';

            $data[] = ++$key;

            $data[] = $variant->name;

            $data[] = $variant->type;

            $data[] = '<div class="action-table-data">
            <div class="edit-delete-action">
                <a class="btn btn-info me-2 p-2 openEditModal" href="' . route('variants.edit', $variant->id) . '">
                    <i  class="fa fa-edit text-white"></i>
                </a>

                <a class="btn btn-danger delete-btn p-2 " href="' . route('variants.destroy', $variant->id) . '">
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


    public function destroyAll(Request $request)
    {
        $token = base64_decode($request->get("token"));
        $ids = json_decode($token);

        foreach ($ids as $id) {
            $variant = Variant::find($id);
            if ($variant) {
                $variant->delete();
            }
        }

        return response()->json(['message' => 'Variants deleted successfully'], 200);
    }


    public function destroy($id)
    {
        $variant = Variant::find($id);

        if ($variant) {
            $variant->delete();
            return response()->json(['message' => 'Variant deleted successfully'], 200);
        } else {
            return response()->json(['error' => 'Variant not found'], 404);
        }
    }




    public function edit($id)
    {



        $variant = Variant::findOrFail($id);

        if ($variant ) {
            return response()->json([
                'html' => view('backend.modules.variant.edit', ['variant'=> $variant ])->render(),
            ]);
        } else {
            return response()->json(['error' => 'Variant   not found'], 404);
        }
    }


    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'type' => 'required|string',
            'name' => 'required|string|max:255',


        ]);

        // Create a new blog category instance with the validated data
        $variant = Variant::findOrFail($request->id);
        $variant->type = $validatedData['type'];
        $variant->name = $validatedData['name'];
        $variant->save();


        return response()->json(['message' => ' Variation Updated successfully!']);

    }

}
