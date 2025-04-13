<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\HomeCategory;
use App\Models\CategorySection;
use App\Models\Category;
use App\Models\Author;
use App\Models\AuthorSection;
use App\Models\Publisher;
use App\Models\PublisherSection;
use App\Models\Review;
use App\Models\ReviewSection;

class HomeCategoryController extends Controller
{
    //view
    public function index()
    {
        $totalCategories = HomeCategory::count();
        return view('backend.modules.homecategory.index',compact('totalCategories'));
    }
    // for api store
    public function store(Request $request)
    {

        // Define validation rules and custom messages
        $rules = [
            'name' => 'required',
        ];
        $messages = [
            'name.required' => 'Name is required',
        ];
        // Validate the request data
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        // Create a new HomeCategory instance and assign values from the request
        $homeCategory = new HomeCategory();
        $homeCategory->name = $request->name;
        // Save the model to the database
        $homeCategory->save();

        // Return success response
        return response()->json([
            'message' => 'Home Category Created successfully',
        ], 201); // Use 201 status code for resource creation
    }
    //
    public function viewHomeCategory()
    {

        $homeCategories = HomeCategory::orderBy('created_at', 'desc')->get();

        foreach ($homeCategories as $homeCategory) {

            $hasCategorySection = CategorySection::where('home_category_id', $homeCategory->id)->exists();
            $homeCategory->has_category_section = $hasCategorySection;

            $hasCategorySection = PublisherSection::where('home_publisher_id', $homeCategory->id)->exists();
            $homeCategory->has_publishers_section = $hasCategorySection;

            $hasCategorySection = ReviewSection::where('home_review_id', $homeCategory->id)->exists();
            $homeCategory->has_review_section = $hasCategorySection;

            $hasCategorySection = AuthorSection::where('home_author_id', $homeCategory->id)->exists();
            $homeCategory->has_author_section = $hasCategorySection;
        }

        return response()->json([
            'data' => $homeCategories,
        ]);
    }
    //
    public function homeCategorySingle($id)
    {
        $hCategory = HomeCategory::find($id);
        return view('backend.modules.homecategory.category-view', compact('hCategory'));
    }
    //
    public function homeCategoryView($id)
    {
        $hCategory = HomeCategory::find($id);

        return view('backend.modules.homecategory.category', compact('hCategory'));
    }
    public function Reviews($id)
    {
        $hCategory = HomeCategory::find($id);

        return view('backend.modules.homecategory.reviews', compact('hCategory'));
    }
    public function Authors($id)
    {
        $hCategory = HomeCategory::find($id);

        return view('backend.modules.homecategory.authors', compact('hCategory'));
    }
    public function authorsAjax(Request $request)
    {
        // Define columns and default values
        $columns = [
            'id',
        ];
        // Extract parameters from the request
        $draw = $request->draw;
        $row = $request->start;
        $rowPerPage = $request->length;
        $columnIndex = $request->order[0]['column'] ?? 0;
        $columnName = $columns[$columnIndex] ?? $columns[0];
        $columnSortOrder = $request->order[0]['dir'] ?? 'asc';
        $searchValue = $request->search['value'] ?? '';
        $totalProducts = 0;
        $allData = [];
        // Start building the query
        $query = Author::query()
            ->withCount('products') // Count of associated products
            ->orderBy('created_at', 'desc');

        // Apply search filter if searchValue is provided
        if (!empty($searchValue)) {
            $query->where('name', 'like', '%' . $searchValue . '%');
        }
        // Count total number of categories in database
        $totalProducts = $query->count();
        // Retrieve paginated categories
        $allAuthors = $query
            ->orderBy($columnName, $columnSortOrder)
            ->skip($row)
            ->take($rowPerPage)
            ->get();
        // Prepare data for DataTables response
        foreach ($allAuthors as $key => $author) {
            $checkMark = '<td><label class="checkboxs"><input type="checkbox" data-value="' . $author->id . '"><span class="checkmarks"></span></label></td>';
            $data = [];
            $data[] = $checkMark;
            $data[] = ++$key + $row;
            $data[] = '<div class="productimgname">
                      <a href="javascript:void(0);" class="product-img stock-img">
                          <img src="' . image($author->thumb_image) . '" alt="Icon" style="border-radius: 30px;">
                      </a>
                      <a href="javascript:void(0);">' . ($author->name ?? '') . '</a>
                  </div>';
            $data[] = $author->products_count ?? 0; // Number of associated products

            $allData[] = $data;
        }
        // Prepare response JSON for DataTables
        $response = [
            "draw" => intval($draw),
            "recordsTotal" => $totalProducts, // Total categories without filtering
            "recordsFiltered" => $totalProducts, // Total categories after filtering (same for this case)
            "data" => $allData,
        ];
        // Return JSON response
        return response()->json($response);
    }
    // author store
    public function authorStore(Request $request)
    {
        $ids = $request->ids;

        $hCategory = HomeCategory::find($request->parent_id);
        if (!$hCategory) {
            return response()->json(['message' => 'No  found.'], 404);
        }

        foreach ($ids as $id) {
            // Check if the product is already associated with the coupon
            $existingCategory = AuthorSection::where('home_author_id', $hCategory->id)
                ->where('author_id', $id)
                ->first();

            if (!$existingCategory) {
                $catSection = new AuthorSection();
                $catSection->home_author_id = $hCategory->id;
                $catSection->author_id = $id;
                $catSection->save();
                $catSection->sort_order = $catSection->id;
                $catSection->save();
            }
        }

        return response()->json(['message' => 'Successfully Saved'], 200);
    }
    public function homeAuthorView($id)
    {
        $hCategory = HomeCategory::find($id);

        return view('backend.modules.homecategory.author-view', compact('hCategory'));
    }
    public function authorsViewAjax(Request $request)
    {
        $columns = ['id', 'name'];
        $draw = $request->draw;
        $row = $request->start;
        $rowPerPage = $request->length;
        $columnIndex = $request->order[0]['column'] ?? 0;
        $columnName = $columns[$columnIndex] ?? 'id';
        $columnSortOrder = $request->order[0]['dir'] ?? 'asc';
        $searchValue = $request->search['value'] ?? '';

        try {
            // Find the home category by ID
            $homeCategory = HomeCategory::with('authors')->findOrFail($request->id);
            // Query related categories
            $query = $homeCategory->authors()->orderBy('pivot_sort_order', 'asc');
            // Apply filtering if search value exists
            if ($searchValue) {
                $query->where('name', 'like', "%$searchValue%");
            }

            // Total records without pagination
            $totalDbProducts = $query->count();

            // Fetch paginated records
            $allCategories = $query->orderBy($columnName, $columnSortOrder)
                ->skip($row)
                ->take($rowPerPage)
                ->get();

            $allData = [];
            foreach ($allCategories as $key => $category) {

                $homeId = $category->pivot->id;
                $categoryName = $category->name ?? '';
                $dragIcon = '<i style="cursor: pointer;" class="fas fa-arrows-alt drag-handle"></i>';
                $data = [];
                $data[] = $dragIcon;
                $data[] = ++$key + $row;
                $data[] = $categoryName;
                $data[] = '
                <div class="action-table-data">
                    <div class="edit-delete-action">
                        <a class="btn btn-danger p-2 delete-btn" href="' . route("home-category.author-delete", ['id' => $homeId]) . '">
                            <i class="fa fa-trash text-white"></i>
                        </a>
                    </div>
                </div>';
                $data['DT_RowId'] = 'row_' . $homeId;
                $allData[] = $data;
            }

            // Prepare JSON response for DataTables
            $response = [
                "draw" => intval($draw),
                "iTotalRecords" => $totalDbProducts,
                "iTotalDisplayRecords" => $totalDbProducts,
                "aaData" => $allData,
            ];

            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function homeAuthorSorting(Request $request)
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
        $oldSort = AuthorSection::findOrFail($oldId);

        $newSort = AuthorSection::findOrFail($newId);
        // dd($table1,$table2);

        // Swap their sort_order values
        $temp = $oldSort->sort_order;

        $oldSort->sort_order = $newSort->sort_order;
        $newSort->sort_order = $temp;

        $oldSort->save();
        $newSort->save();


        return response()->json(['success' => true]);
    }
    public function authorSingleDestroy($id)
    {

        $author = AuthorSection::find($id);

        if ($author) {
            $author->delete();
            return response()->json(['message' => 'Deleted successfully']);
        } else {
            return response()->json(['message' => 'Author not found'], 404);
        }
    }
    // publishers
    public function Publishers($id)
    {
        $hCategory = HomeCategory::find($id);

        return view('backend.modules.homecategory.publishers', compact('hCategory'));
    }
    public function publisherAjax(Request $request)
    {


        // Define columns and default values
        $columns = [
            'id',
        ];
        // Extract parameters from the request
        $draw = $request->draw;
        $row = $request->start;
        $rowPerPage = $request->length;
        $columnIndex = $request->order[0]['column'] ?? 0;
        $columnName = $columns[$columnIndex] ?? $columns[0];
        $columnSortOrder = $request->order[0]['dir'] ?? 'asc';
        $searchValue = $request->search['value'] ?? '';
        $totalProducts = 0;
        $allData = [];
        // Start building the query
        $query = Publisher::query()
            ->withCount('products') // Count of associated products
            ->orderBy('created_at', 'desc');

        // Apply search filter if searchValue is provided
        if (!empty($searchValue)) {
            $query->where('name', 'like', '%' . $searchValue . '%');
        }
        // Count total number of categories in database
        $totalProducts = $query->count();
        // Retrieve paginated categories
        $allAuthors = $query
            ->orderBy($columnName, $columnSortOrder)
            ->skip($row)
            ->take($rowPerPage)
            ->get();
        // Prepare data for DataTables response
        foreach ($allAuthors as $key => $author) {
            $checkMark = '<td><label class="checkboxs"><input type="checkbox" data-value="' . $author->id . '"><span class="checkmarks"></span></label></td>';
            $data = [];
            $data[] = $checkMark;
            $data[] = ++$key + $row;
            $data[] = '<div class="productimgname">
                      <a href="javascript:void(0);" class="product-img stock-img">
                          <img src="' . image($author->thumb_image) . '" alt="Icon" style="border-radius: 30px;">
                      </a>
                      <a href="javascript:void(0);">' . ($author->name ?? '') . '</a>
                  </div>';
            $data[] = $author->products_count ?? 0; // Number of associated products

            $allData[] = $data;
        }
        // Prepare response JSON for DataTables
        $response = [
            "draw" => intval($draw),
            "recordsTotal" => $totalProducts, // Total categories without filtering
            "recordsFiltered" => $totalProducts, // Total categories after filtering (same for this case)
            "data" => $allData,
        ];
        // Return JSON response
        return response()->json($response);
    }
    public function publisherStore(Request $request)
    {
        $ids = $request->ids;

        $hCategory = HomeCategory::find($request->parent_id);
        if (!$hCategory) {
            return response()->json(['message' => 'No found.'], 404);
        }
        foreach ($ids as $id) {
            // Check if the product is already associated with the coupon
            $existingCategory = PublisherSection::where('home_publisher_id', $hCategory->id)
                ->where('publisher_id', $id)
                ->first();

            if (!$existingCategory) {
                $catSection = new PublisherSection();
                $catSection->home_publisher_id = $hCategory->id;
                $catSection->publisher_id = $id;
                $catSection->save();
                $catSection->sort_order = $catSection->id;
                $catSection->save();
            }
        }
        return response()->json(['message' => 'Successfully Saved'], 200);
    }
    public function publisherView($id)
    {
        $hCategory = HomeCategory::find($id);
        return view('backend.modules.homecategory.publishers-view', compact('hCategory'));
    }
    public function publisherViewAjax(Request $request)
    {


        $columns = ['id', 'name'];
        $draw = $request->draw;
        $row = $request->start;
        $rowPerPage = $request->length;
        $columnIndex = $request->order[0]['column'] ?? 0;
        $columnName = $columns[$columnIndex] ?? 'id';
        $columnSortOrder = $request->order[0]['dir'] ?? 'asc';
        $searchValue = $request->search['value'] ?? '';

        try {
            // Find the home category by ID
            $homeCategory = HomeCategory::with('publishers')->findOrFail($request->id);
            // Query related categories
            $query = $homeCategory->publishers()->orderBy('pivot_sort_order', 'asc');
            // Apply filtering if search value exists
            if ($searchValue) {
                $query->where('name', 'like', "%$searchValue%");
            }

            // Total records without pagination
            $totalDbProducts = $query->count();

            // Fetch paginated records
            $allCategories = $query->orderBy($columnName, $columnSortOrder)
                ->skip($row)
                ->take($rowPerPage)
                ->get();

            $allData = [];
            foreach ($allCategories as $key => $category) {

                $homeId = $category->pivot->id;
                $categoryName = $category->name ?? '';
                $dragIcon = '<i style="cursor: pointer;" class="fas fa-arrows-alt drag-handle"></i>';
                $data = [];
                $data[] = $dragIcon;
                $data[] = ++$key + $row;
                $data[] = $categoryName;
                $data[] = '
                <div class="action-table-data">
                    <div class="edit-delete-action">
                        <a class="btn btn-danger p-2 delete-btn" href="' . route("home-category.publisher-delete", ['id' => $homeId]) . '">
                            <i class="fa fa-trash text-white"></i>
                        </a>
                    </div>
                </div>';
                $data['DT_RowId'] = 'row_' . $homeId;
                $allData[] = $data;
            }

            // Prepare JSON response for DataTables
            $response = [
                "draw" => intval($draw),
                "iTotalRecords" => $totalDbProducts,
                "iTotalDisplayRecords" => $totalDbProducts,
                "aaData" => $allData,
            ];

            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function publisherSingleDestroy($id)
    {

        $publisher = PublisherSection::find($id);

        if ($publisher) {
            $publisher->delete();
            return response()->json(['message' => 'Deleted successfully']);
        } else {
            return response()->json(['message' => 'Publisher not found'], 404);
        }
    }
    public function homePublisherSorting(Request $request)
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

        $oldSort = PublisherSection::findOrFail($oldId);
        $newSort = PublisherSection::findOrFail($newId);

        $temp = $oldSort->sort_order;
        $oldSort->sort_order = $newSort->sort_order;
        $newSort->sort_order = $temp;
        $oldSort->save();
        $newSort->save();
        return response()->json(['success' => true]);
    }
    //
    public function homeCategoryAjax(Request $request)
    {
        $columns = ['id', 'name'];
        $draw = $request->draw;
        $row = $request->start;
        $rowPerPage = $request->length;
        $columnIndex = $request->order[0]['column'] ?? 0;
        $columnName = $columns[$columnIndex] ?? 'id';
        $columnSortOrder = $request->order[0]['dir'] ?? 'asc';
        $searchValue = $request->search['value'] ?? '';

        try {
            // Find the home category by ID
            $homeCategory = HomeCategory::with('categories')->findOrFail($request->id);
            // Query related categories
            $query = $homeCategory->categories()->orderBy('pivot_sort_order', 'asc');
            // Apply filtering if search value exists
            if ($searchValue) {
                $query->where('name', 'like', "%$searchValue%");
            }

            // Total records without pagination
            $totalDbProducts = $query->count();

            // Fetch paginated records
            $allCategories = $query->orderBy($columnName, $columnSortOrder)
                ->skip($row)
                ->take($rowPerPage)
                ->get();

            $allData = [];
            foreach ($allCategories as $key => $category) {

                $homeId = $category->pivot->id;
                $categoryName = $category->name ?? '';
                $dragIcon = '<i style="cursor: pointer;" class="fas fa-arrows-alt drag-handle"></i>';
                $data = [];
                $data[] = $dragIcon;
                $data[] = ++$key + $row;
                $data[] = $categoryName;
                $data[] = '
                <div class="action-table-data">
                    <div class="edit-delete-action">
                        <a class="btn btn-danger p-2 delete-btn" href="' . route("home-category.single-destroy-category", ['id' => $homeId]) . '">
                            <i class="fa fa-trash text-white"></i>
                        </a>
                    </div>
                </div>';
                $data['DT_RowId'] = 'row_' . $homeId;
                $allData[] = $data;
            }

            // Prepare JSON response for DataTables
            $response = [
                "draw" => intval($draw),
                "iTotalRecords" => $totalDbProducts,
                "iTotalDisplayRecords" => $totalDbProducts,
                "aaData" => $allData,
            ];

            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function homeCategorySorting(Request $request)
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
        $oldSort = CategorySection::findOrFail($oldId);

        $newSort = CategorySection::findOrFail($newId);
        // dd($table1,$table2);

        // Swap their sort_order values
        $temp = $oldSort->sort_order;

        $oldSort->sort_order = $newSort->sort_order;
        $newSort->sort_order = $temp;

        $oldSort->save();
        $newSort->save();


        return response()->json(['success' => true]);
    }
    public function addCategoryAjax(Request $request)
    {
        // Define columns and default values
        $columns = [
            'id',
        ];

        // Extract parameters from the request
        $draw = $request->draw;
        $row = $request->start;
        $rowPerPage = $request->length;
        $columnIndex = $request->order[0]['column'] ?? 0;
        $columnName = $columns[$columnIndex] ?? $columns[0];
        $columnSortOrder = $request->order[0]['dir'] ?? 'asc';
        $searchValue = $request->search['value'] ?? '';
        $totalProducts = 0;
        $allData = [];

        // Start building the query
        $query = Category::query()
            ->withCount('products') // Count of associated products
            ->orderBy('created_at', 'desc');

        // Apply search filter if searchValue is provided
        if (!empty($searchValue)) {
            $query->where('name', 'like', '%' . $searchValue . '%');
        }

        // Count total number of categories in database
        $totalProducts = $query->count();

        // Retrieve paginated categories
        $allCategories = $query
            ->orderBy($columnName, $columnSortOrder)
            ->skip($row)
            ->take($rowPerPage)
            ->get();

        // Prepare data for DataTables response
        foreach ($allCategories as $key => $category) {
            $checkMark = '<td><label class="checkboxs"><input type="checkbox" data-value="' . $category->id . '"><span class="checkmarks"></span></label></td>';
            $data = [];
            $data[] = $checkMark;
            $data[] = ++$key + $row;
            $data[] = '<div class="productimgname">
                      <a href="javascript:void(0);" class="product-img stock-img">
                          <img src="' . image($category->thumb_image) . '" alt="Icon" style="border-radius: 30px;">
                      </a>
                      <a href="javascript:void(0);">' . ($category->name ?? '') . '</a>
                  </div>';
            $data[] = $category->products_count ?? 0; // Number of associated products

            $allData[] = $data;
        }

        // Prepare response JSON for DataTables
        $response = [
            "draw" => intval($draw),
            "recordsTotal" => $totalProducts, // Total categories without filtering
            "recordsFiltered" => $totalProducts, // Total categories after filtering (same for this case)
            "data" => $allData,
        ];

        // Return JSON response
        return response()->json($response);
    }
    //
    public function homeCategoryStore(Request $request)
    {
        $ids = $request->ids;

        $hCategory = HomeCategory::find($request->parent_id);
        if (!$hCategory) {
            return response()->json(['message' => 'No Category found.'], 404);
        }

        foreach ($ids as $id) {
            // Check if the product is already associated with the coupon
            $existingCategory = CategorySection::where('home_category_id', $hCategory->id)
                ->where('category_id', $id)
                ->first();

            if (!$existingCategory) {
                $catSection = new CategorySection();
                $catSection->home_category_id = $hCategory->id;
                $catSection->category_id = $id;
                $catSection->save();
                $catSection->sort_order = $catSection->id;
                $catSection->save();
            }
        }

        return response()->json(['message' => 'Category added successfully'], 200);
    }
    public function categoryDestroy($id)
    {

        $Id = $id;

        $hCategory = HomeCategory::find($Id);

        if ($hCategory->name == 'category') {

            $hCategory->categories()->detach();

            $hCategory->delete();
        }

        if ($hCategory->name == 'author') {

            $hCategory->authors()->detach();

            $hCategory->delete();
        }
        if ($hCategory->name == 'publisher') {

            $hCategory->publishers()->detach();

            $hCategory->delete();
        }
        if ($hCategory->name == 'review') {

            $hCategory->reviews()->detach();

            $hCategory->delete();
        }

        return response()->json(['message' => 'Deleted successfully']);
    }
    //
    public function showAllCategory(Request $request)
    {
    }
    // single destroy
    public function singleDestroy($id)
    {

        $Id = $id;
        $category = CategorySection::find($Id);
        $category->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
    // review
    public function review($id)
    {
        $hCategory = HomeCategory::find($id);
        return view('backend.modules.homecategory.reviews', compact('hCategory'));
    }
    // public function allReviews()
    // {
    //     return view('backend.modules.homecategory.all-review');
    // }
    public function allReviews($id)
    {
        $hCategory = HomeCategory::find($id);
        return view('backend.modules.homecategory.all-review',compact('hCategory'));
    }

    public function storeReview(Request $request)
    {

        $ids = $request->ids;
        $hCategory = HomeCategory::find($request->parent_id);
        if (!$hCategory) {
            return response()->json(['message' => 'No  found.'], 404);
        }

        foreach ($ids as $id) {
            // Check if the product is already associated with the coupon
            $existingCategory = AuthorSection::where('home_author_id', $hCategory->id)
                ->where('author_id', $id)
                ->first();

            if (!$existingCategory) {
                $catSection = new AuthorSection();
                $catSection->home_author_id = $hCategory->id;
                $catSection->author_id = $id;
                $catSection->save();
                $catSection->sort_order = $catSection->id;
                $catSection->save();
            }
        }

        return response()->json(['message' => 'Successfully Saved'], 200);
    }

    public function reviewallStore(Request $request)
    {
        $ids = $request->ids;
        $hCategory = HomeCategory::find($request->parent_id);
        if (!$hCategory) {
            return response()->json(['message' => 'No  found.'], 404);
        }
        foreach ($ids as $id) {
            // Check if the product is already associated with the coupon
            $existingCategory = ReviewSection::where('home_review_id', $hCategory->id)
                ->where('review_id', $id)
                ->first();

            if (!$existingCategory) {
                $reviewSection = new ReviewSection();
                $reviewSection->home_review_id = $hCategory->id;
                $reviewSection->review_id = $id;
                $reviewSection->save();
                $reviewSection->sort_order = $reviewSection->id;
                $reviewSection->save();
            }
        }

        return response()->json(['message' => 'Successfully Saved'], 200);
    }

    public function reviewsAjax(Request $request)
    {
        // Define columns and default values
        $columns = [
            'id',
            'name',
            'comment',
            'created_at'
        ];

        // Extract parameters from the request
        $draw = $request->draw;
        $row = $request->start;
        $rowPerPage = $request->length;
        $columnIndex = $request->order[0]['column'] ?? 0;
        $columnName = $columns[$columnIndex] ?? $columns[0];
        $columnSortOrder = $request->order[0]['dir'] ?? 'asc';
        $searchValue = $request->search['value'] ?? '';

        // Start building the query
        $query = Review::query()
            ->orderBy('created_at', 'desc');

        // Apply search filter if searchValue is provided
        if (!empty($searchValue)) {
            $query->where('name', 'like', '%' . $searchValue . '%');
        }

        // Count total number of reviews in the database
        $totalReviews = $query->count();

        // Retrieve paginated reviews
        $allReviews = $query
            ->orderBy($columnName, $columnSortOrder)
            ->skip($row)
            ->take($rowPerPage)
            ->get();

        // Prepare data for DataTables response
        $allData = [];
        foreach ($allReviews as $key => $review) {
            $checkMark = '<td><label class="checkboxs"><input type="checkbox" data-value="' . $review->id . '"><span class="checkmarks"></span></label></td>';
            $data = [];
            $data[] = $checkMark;
            $data[] = ++$key + $row;
            $data[] = '<div class="productimgname">
                        <a href="javascript:void(0);" class="product-img stock-img">
                            <img src="' . image($review->image) . '" alt="Icon" style="border-radius: 30px;">
                        </a>
                        <a href="javascript:void(0);">' . ($review->name ?? '') . '</a>
                       </div>';
            $data[] = $review->comment;
            $allData[] = $data;
        }

        // Prepare response JSON for DataTables
        $response = [
            "draw" => intval($draw),
            "recordsTotal" => $totalReviews, // Total reviews without filtering
            "recordsFiltered" => $totalReviews, // Total reviews after filtering (same for this case)
            "data" => $allData,
        ];

        // Return JSON response
        return response()->json($response);
    }

    // review view all ajax
    public function reviewAllAjax(Request $request){

        $columns = ['id', 'name', 'comment', 'created_at'];
        $draw = $request->draw;
        $row = $request->start;
        $rowPerPage = $request->length;
        $columnIndex = $request->order[0]['column'] ?? 0;
        $columnName = $columns[$columnIndex] ?? 'id';
        $columnSortOrder = $request->order[0]['dir'] ?? 'asc';
        $searchValue = $request->search['value'] ?? '';

        try {
            // Find the home category by ID
            $homeCategory = HomeCategory::with('reviews')->findOrFail($request->id);
            // Query related reviews
            $query = $homeCategory->reviews()->orderBy('pivot_sort_order', 'asc');

            // Apply filtering if search value exists
            if ($searchValue) {
                $query->where('name', 'like', "%$searchValue%")
                      ->orWhere('comment', 'like', "%$searchValue%");
            }

            // Total records without pagination
            $totalDbProducts = $query->count();

            // Fetch paginated records
            $allReviews = $query->orderBy($columnName, $columnSortOrder)
                                ->skip($row)
                                ->take($rowPerPage)
                                ->get();

            $allData = [];
            foreach ($allReviews as $key => $review) {
                $homeId = $review->pivot->id;
                $reviewName = $review->name ?? '';
                $reviewComment = $review->comment ?? '';

                // $reviewCreatedAt = $review->created_at->format('Y-m-d H:i:s');

                $dragIcon = '<i style="cursor: pointer;" class="fas fa-arrows-alt drag-handle"></i>';

                $data = [];
                $data[] = $dragIcon;
                $data[] = ++$key + $row;
                $data[] = $reviewName;
                $data[] = $reviewComment;
                // $data[] = $reviewCreatedAt;
                $data[] = '
                <div class="action-table-data">
                    <div class="edit-delete-action">
                        <a class="btn btn-danger p-2 delete-btn" href="' . route("home-category.review-delete", ['id' => $homeId]) . '">
                            <i class="fa fa-trash text-white"></i>
                        </a>
                    </div>
                </div>';
                $data['DT_RowId'] = 'row_' . $homeId;
                $allData[] = $data;
            }

            // Prepare JSON response for DataTables
            $response = [
                "draw" => intval($draw),
                "iTotalRecords" => $totalDbProducts,
                "iTotalDisplayRecords" => $totalDbProducts,
                "aaData" => $allData,
            ];

            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function reviewSorting(Request $request)
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

        $oldSort = ReviewSection::findOrFail($oldId);
        $newSort = ReviewSection::findOrFail($newId);

        $temp = $oldSort->sort_order;
        $oldSort->sort_order = $newSort->sort_order;
        $newSort->sort_order = $temp;
        $oldSort->save();
        $newSort->save();
        return response()->json(['success' => true]);
    }


    public function reviewSingleDestroy($id)
    {

        $Id = $id;
        $category = ReviewSection::find($Id);
        $category->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }

}
