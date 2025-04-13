<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Author;
use App\Models\Publisher;
use App\Models\ProductSection;
use App\Models\SectionProduct;
use App\Models\Product;
use DB;



class ProductSectionController extends Controller
{

    // sections view
    public function index()
    {
        return view('backend.modules.productsection.index');
    }
    // store sections
    public function store(Request $request)
    {

        $rules = [
            'name' => 'required',
            'status' => 'required',
        ];

        $messages = [
            'name' => 'Section Name is Required',
            'status' => 'Status required',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $productSection = new ProductSection();
        $productSection->name = $request->name;
        $productSection->section_type = $request->section_type;
        $productSection->link = $request->link;
        $productSection->status = $request->status;
        $productSection->save();
        // Update sort_order field with the product section's id
        $productSection->sort_order = $productSection->id;
        $productSection->save();

        return response()->json([
            'message' => 'Section Created Successfully',
        ], 201);
    }
    //sections datatable
    public function ajaxIndex(Request $request)
    {
        $columns = [
            'id',
            'name',
            'status',
            'user.name', // Assuming there is a relation to user and you want to sort by user's name
            // Add other column names here as needed
        ];

        $draw = $request->draw;
        $row = $request->start;
        $rowPerPage = $request->length;
        $columnIndex = $request->order[0]['column'];
        $columnName = $columns[$columnIndex] ?? $columns[0];
        $columnSortOrder = $request->order[0]['dir'];
        $searchValue = $request->search['value'];

        $query = ProductSection::with('user')->orderBy('sort_order', 'asc');

        if (!empty($searchValue)) {
            $query->where('name', 'like', '%' . $searchValue . '%');
        }

        $totalDbSections = $query->count(); // Count total records without pagination

        $allSections = $query->orderBy($columnName, $columnSortOrder)
            ->skip($row)
            ->take($rowPerPage)
            ->get();

        $allData = [];
        foreach ($allSections as $key => $section) {

            $hasProduct = SectionProduct::where('section_id', $section->id)->exists();
            // Drag-and-drop icon
            $dragIcon = '<i style="cursor: pointer;" class="fas fa-arrows-alt drag-handle"></i>';

            // Status HTML
            $statusHtml = '<input type="checkbox" id="status_' . $section->id . '" class="check" ' . ($section->status == 1 ? 'checked' : '') . '>
            <label style="width: 42px !important;" for="status_' . $section->id . '" class="checktoggle changeStatus" data-section-id="' . $section->id . '"></label>';

            $data = [];
            $data[] = $dragIcon; // Add drag icon
            $data[] = ++$key + $row;
            $data[] = '<div class="productimgname">
                <a href="javascript:void(0);">' . ($section->name ?? '') . '</a>
            </div>';

            if ($hasProduct && $section->section_type == 'custom') {
                $data[] = '<p><a class="badges status-badge text-white" href="' . route('section.products.show', ['id' => $section->id]) . '">Show Products</a></p>';
            } elseif ($section->section_type == 'custom') {
                $data[] = '<p><a class="badges unstatus-badge text-white" href="' . route('sections.create', ['id' => $section->id]) . '">Add Product</a></p>';
            } elseif ($section->section_type == 'recent') {
                $data[] = '<p><a class="badges unstatus-badge text-white" href="' . route('section.products.show', ['id' => $section->id]) . '">Recent</a></p>';
            }

            $data[] = $statusHtml; // Display status checkbox

            $data[] = $section->user->name ?? '';

            $data[] = '
            <div class="action-table-data">
                <div class="edit-delete-action">
                <a class="btn btn-info me-2 p-2 edit_status" data-bs-toggle="modal"
                data-bs-target="#statusUpdateModal" data-id="' . $section->id . '">
                <i  class="fa fa-edit text-white"></i>

                </a>
                    <a class="btn btn-danger delete-btn p-2" href="' . route("sections.destroy", ['id' => encrypt($section->id)]) . '">
                        <i  class="fa fa-trash text-white"></i>
                    </a>
                </div>
            </div>';

            // Adding the data-id attribute to each row
            $data['DT_RowId'] = 'row_' . $section->id;

            $allData[] = $data;
        }

        $response = [
            "draw" => intval($draw),
            "iTotalRecords" => $totalDbSections, // Total records in your database table
            "iTotalDisplayRecords" => $totalDbSections, // Total records after filtering
            "aaData" => $allData,
        ];

        return response()->json($response);
    }
    public function update(Request $request)
    {


        $rules = [
            'name' => 'required',
            'status' => 'required',
        ];

        $messages = [
            'section_name' => 'Section Name is Required',
            'status' => 'Status required',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $section = ProductSection::find($request->id);

        $section->name = $request->name;
        $section->section_type = $request->section_type;
        $section->link = $request->link;
        $section->status = $request->status;
        // Set other attributes here following the same pattern
        $section->save();

        return response()->json([
            'message' => 'Section Info Updated successfully',
        ], 201);
    }
    // sorting the row
    public function updateSortOrder(Request $request)
    {
        // Validate the request
        $request->validate([
            'tableId' => 'required|string',
            'newOrder' => 'required|array',
            'newOrder.*.id' => 'required|integer',
            'newOrder.*.newPosition' => 'required|integer',
        ]);
        $oldId = $request->newOrder[0]['id'];

        $newId = $request->newOrder[1]['id'];

        // Get the two table records
        $oldSort = ProductSection::findOrFail($oldId);

        $newSort = ProductSection::findOrFail($newId);
        // dd($table1,$table2);

        // Swap their sort_order values
        $temp = $oldSort->sort_order;

        $oldSort->sort_order = $newSort->sort_order;
        $newSort->sort_order = $temp;


        $oldSort->save();
        $newSort->save();


        return response()->json(['success' => true]);
    }
    public function productSort(Request $request)
    {
        $reorderData = $request->input('reorderData');
        $oldId = $reorderData[0]['oldId'];
        $newId = $reorderData[0]['newId'];
        $oldSort = SectionProduct::find($oldId);
        $newSort = SectionProduct::find($newId);
        $oldId = $oldSort->sort_order;
        $newId = $newSort->sort_order;
        // dd($oldId,$newId);
        $temp = $oldSort->sort_order;
        $oldSort->sort_order = $newSort->sort_order;
        $newSort->sort_order = $temp;
        $oldSort->save();
        $newSort->save();


        return response()->json(['success' => true]);
    }
    // edit blade
    public function edit(Request $request)
    {
        $Id = $request->id;

        $singleSection = ProductSection::find($Id);

        $statData = view('backend.modules.productsection.edit_modal', compact('singleSection'))->render();

        return response()->json([
            'statData' => $statData,
            'success' => true
        ]);
    }
    //store updated data
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

        $singleSection = ProductSection::find($request->id);
        $singleSection->name = $request->name;

        $singleSection->status = $request->status;
        // Set other attributes here following the same pattern
        $singleSection->save();

        return response()->json([
            'message' => 'Updated SuccessFully',
        ], 201);
    }
    // section delete
    public function destroy(Request $request, $id)
    {
        $decryptedId = decrypt($id);

        $section = ProductSection::find($decryptedId);

        if ($section) {
            $products = SectionProduct::where('section_id', $section->id)->get();
            foreach ($products as $product) {
                $product->delete();
            }

            $section->delete();
        }
        return response()->json(['message' => 'Section Deleted successfully', 'success' => true], 200);
    }
    // update sections status
    public function updateStatus(Request $request)
    {
        $id = $request->id;

        $Campaign = ProductSection::findOrFail($id);

        // Toggle the status
        $Campaign->status = $Campaign->status == 1 ? 0 : 1;

        // Save the category
        $Campaign->save();

        // Return a response
        return response()->json(['message' => 'Status updated successfully'], 200);
    }
    //section products add methods start
    public function create($id)
    {
        $singleSection = ProductSection::find($id);
        $categories = Category::orderBy('created_at', 'desc')->get();
        $subcategory = Subcategory::orderBy('created_at', 'desc')->get();
        $authors = Author::orderBy('created_at', 'desc')->get();
        $publishers = Publisher::orderBy('created_at', 'desc')->get();

        return view('backend.modules.productsection.create', compact('singleSection', 'categories', 'subcategory', 'authors', 'publishers',));
    }
    //store sections product
    public function storeSectionProduct(Request $request)
    {
        $ids = $request->ids;
        $section = ProductSection::find($request->section_id);
        if (!$section) {
            return response()->json(['message' => 'No campaign found.'], 404);
        }
        foreach ($ids as $id) {
            // Check if the product is already associated with the section
            $existingProduct = SectionProduct::where('section_id', $section->id)
                ->where('product_id', $id)
                ->first();
            if (!$existingProduct) {
                $product = new SectionProduct();
                $product->section_id = $section->id;
                $product->product_id = $id;
                $product->save();
                $product->sort_order = $product->id;
                $product->save();
            }
        }
        return response()->json(['message' => 'Sections products added successfully.'], 200);
    }
    //single section products view
    public function singleSectionProducts($id)
    {

        $singleSection = ProductSection::find($id);

        if ($singleSection->section_type == 'recent') {
            return view('backend.modules.productsection.single-section-others', compact('singleSection'));
        } else {
            return view('backend.modules.productsection.single-section', compact('singleSection'));
        }
    }
    // single section product view ajax
    public function singleSectionProductsAjax(Request $request)
    {
        $columns = [
            'id'
        ];

        $draw = $request->draw;
        $row = $request->start;
        $rowPerPage = $request->length;
        $columnIndex = $request->order[0]['column'] ?? 0;
        $columnName = $columns[$columnIndex] ?? 'id';
        $columnSortOrder = $request->order[0]['dir'] ?? 'asc';
        $searchValue = $request->search['value'] ?? '';

        $query = SectionProduct::with('product')
            ->where('section_id', $request->id)
            ->orderBy('sort_order', 'asc');

        if (!empty($searchValue)) {
            $query->whereHas('product', function ($q) use ($searchValue) {
                $q->where('english_name', 'like', '%' . $searchValue . '%');
            });
        }

        $totalDbProducts = $query->count();
        $allProducts = $query->orderBy($columnName, $columnSortOrder)
            ->skip($row)
            ->take($rowPerPage)
            ->get();
        $allData = [];
        foreach ($allProducts as $key => $sectionProduct) {

            $product = $sectionProduct->product;
            $productId = $product->id;
            if ($productId) {
                $data = [];
                $data[] = '<div style="cursor: pointer;" class="drag-handle" data-product-id="' . $sectionProduct->id . '"><i class="fas fa-arrows-alt"></i></div>';
                $data[] = ++$key + $row;
                $data[] = '<div class="productimgname"><a href="javascript:void(0);" class="product-img stock-img"><img src="' . image($product->thumb_image) . '" alt="Icon" style="border-radius: 30px;"></a><a href="javascript:void(0);">' . ($product->english_name ?? '') . '</a></div>';

                $data[] = '<ul>' . $product->categories->pluck('name')->unique()->map(function ($name) {
                    return "<li>$name</li>";
                })->implode('') . '</ul>';

                $data[] = '<ul>' . $product->authors->pluck('name')->unique()->map(function ($name) {
                    return "<li>$name</li>";
                })->implode('') . '</ul>';

                $data[] = $product->publisher->name ?? '';

                $data[] = $product->current_price ?? '';

                //     $data[] = '
                // <div class="action-table-data">
                //     <div class="edit-delete-action">
                //         <a class="btn btn-danger delete-btn p-2" href="' . route("sections-product-single.delete", ['id' => $sectionProduct->id]) . '">
                //             <i class="fa fa-trash text-white"></i>
                //         </a>
                //     </div>
                // </div>';
                $data[] = '<div class="action-table-data">
    <div class="edit-delete-action">
        <a class="btn btn-danger delete-btn p-2" href="' . route("sections-product-single.delete", ['id' => $sectionProduct->id]) . '">
            <i class="fa fa-trash text-white"></i>
        </a>
    </div>
</div>';

                $allData[] = $data;
            }
        }

        $response = [
            "draw" => intval($draw),
            "iTotalRecords" => $totalDbProducts,
            "iTotalDisplayRecords" => $totalDbProducts,
            "aaData" => $allData,
        ];
        return response()->json($response);
    }
    // for editing add new product
    public function addNewProduct($id)
    {
        $singleSection = ProductSection::find($id);
        $singleSection = ProductSection::find($id);
        $categories = Category::orderBy('created_at', 'desc')->get();
        $subcategory = Subcategory::orderBy('created_at', 'desc')->get();
        $authors = Author::orderBy('created_at', 'desc')->get();
        $publishers = Publisher::orderBy('created_at', 'desc')->get();
        return view('backend.modules.productsection.add-new-product', compact('singleSection', 'categories', 'subcategory', 'authors', 'publishers'));
    }
    // single product delete from child section table
    public function singleDestroy($id)
    {

        $singleProduct = SectionProduct::find($id);

        if($singleProduct){
            $singleProduct->delete();

        }
        return response()->json(['message' => "Delete SuccessFully",'success' => true], 200);
    }
    // public function singleDestroy($id)
    // {

    //     $singleProduct = SectionProduct::find($id);

    //     $singleProduct->delete();
    //     return response()->json([
    //         'message' => "Delete SuccessFully"
    //     ]);
    // }
}
