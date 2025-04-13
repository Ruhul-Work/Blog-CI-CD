<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Enums\DiscountEnum;
use App\Models\Campaign;
use App\Models\CampaignProduct;
use App\Models\Product;
use App\Models\Author;
use App\Models\Publisher;
use App\Models\Category;
use App\Models\Subcategory;
use Carbon\Carbon;


class CampaignController extends Controller
{
    // campaign view
    public function index()
    {
        return view('backend.modules.campaign.index');
    }

    // for filtering product table
    public function campaignProducts(Request $request, $id)
    {
        $categories = Category::orderBy('created_at', 'desc')->get();
        $subcategory = Subcategory::orderBy('created_at', 'desc')->get();
        $authors = Author::orderBy('created_at', 'desc')->get();
        $publishers = Publisher::orderBy('created_at', 'desc')->get();
        $campaign = Campaign::where('id', $id)->first();

        return view('backend.modules.campaign.products', compact('categories', 'subcategory', 'authors', 'publishers', 'campaign'));
    }
    // datatable view ajax
    public function campaignProductsAjax(Request $request)
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
        $totalProducts = 0;
        $allData = [];

        $query = Product::query()->orderBy('created_at', 'desc');

        if ($request->has('category_id') && !empty($request->category_id)) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        }

        // if ($request->has('subcategory_id') && !empty($request->subcategory_id)) {
        //     $query->whereHas('subcategories', function ($q) use ($request) {
        //         $q->where('subcategory_id', $request->subcategory_id);
        //     });
        // }

        if ($request->has('author_id') && !empty($request->author_id)) {
            $query->whereHas('authors', function ($q) use ($request) {
                $q->where('author_id', $request->author_id);
            });
        }

        if ($request->has('publisher_id') && !empty($request->publisher_id)) {
            $query->where('publisher_id', $request->publisher_id);
        }

        if (!empty($searchValue)) {
            $query->where('english_name', 'like', '%' . $searchValue . '%');
        }

        $totalDbProducts = $query->count();

        $allProducts = $query->orderBy($columnName, $columnSortOrder)
            ->skip($row)
            ->take($rowPerPage)
            ->get();

        foreach ($allProducts as $key => $product) {

            $checkMark = '<td><label class="checkboxs"><input type="checkbox" data-value="' . $product->id . '"><span class="checkmarks"></span></label></td>';

            $data = [];
            $data[] = $checkMark;
            $data[] = ++$key + $row;

            $data[] = '<div class="productimgname">
          <a href="javascript:void(0);" class="product-img stock-img">
              <img src="' . image($product->thumb_image) . '" alt="Icon" style="border-radius: 30px;">
          </a>
          <a href="javascript:void(0);">' . $product->english_name ?? '' . '</a>
          </div>';

            $data[] = '<ul>' . $product->categories->pluck('name')->unique()->map(function ($name) {
                return "<li>$name</li>";
            })->implode('') . '</ul>';

            // $data[] = '<ul>' . $product->subcategories->pluck('name')->unique()->map(function ($name) {
            //     return "<li>$name</li>";
            // })->implode('') . '</ul>';
            $data[] = '<ul>' . $product->authors->pluck('name')->unique()->map(function ($name) {
                return "<li>$name</li>";
            })->implode('') . '</ul>';

            $data[] = $product->publisher->name ?? '';
            $data[] = $product->current_price ?? '';
            $data[] = $product->stock ?? '';
            $allData[] = $data;
        }

        $response = [
            "draw" => intval($draw),
            "iTotalRecords" => $totalProducts,
            "iTotalDisplayRecords" => $totalDbProducts,
            "aaData" => $allData,
        ];

        return response()->json($response);
    }

    // edit or add new product this campaign
    public function campaignnewProductAdd(Request $request, $id)
    {
        $categories = Category::orderBy('created_at', 'desc')->get();
        $subcategory = Subcategory::orderBy('created_at', 'desc')->get();
        $authors = Author::orderBy('created_at', 'desc')->get();
        $publishers = Publisher::orderBy('created_at', 'desc')->get();
        $campaign = Campaign::where('id', $id)->first();

        return view('backend.modules.campaign.new-products', compact('categories', 'subcategory', 'authors', 'publishers', 'campaign'));
    }

    public function campaignProductViewAjaxEdit(Request $request)
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
        $totalProducts = 0;
        $allData = [];

        $query = Product::query()->orderBy('created_at', 'desc');

        if ($request->has('category_id') && !empty($request->category_id)) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        }

        if ($request->has('subcategory_id') && !empty($request->subcategory_id)) {
            $query->whereHas('subcategories', function ($q) use ($request) {
                $q->where('subcategory_id', $request->subcategory_id);
            });
        }

        if ($request->has('author_id') && !empty($request->author_id)) {
            $query->whereHas('authors', function ($q) use ($request) {
                $q->where('author_id', $request->author_id);
            });
        }

        if ($request->has('publisher_id') && !empty($request->publisher_id)) {
            $query->where('publisher_id', $request->publisher_id);
        }


        if (!empty($searchValue)) {
            $query->where('english_name', 'like', '%' . $searchValue . '%');
        }

        $totalDbProducts = $query->count();

        $allProducts = $query->orderBy($columnName, $columnSortOrder)
            ->skip($row)
            ->take($rowPerPage)
            ->get();

        foreach ($allProducts as $key => $product) {

            $checkMark = '<td><label class="checkboxs"><input type="checkbox" data-value="' . $product->id . '"><span class="checkmarks"></span></label></td>';


            $data = [];

            $data[] = $checkMark;

            $data[] = ++$key + $row;

            $data[] = '<div class="productimgname">
          <a href="javascript:void(0);" class="product-img stock-img">
              <img src="' . image($product->thumb_image) . '" alt="Icon" style="border-radius: 30px;">
          </a>
          <a href="javascript:void(0);">' . $product->english_name ?? '' . '</a>
          </div>';


            $data[] = '<ul>' . $product->categories->pluck('name')->unique()->map(function ($name) {
                return "<li>$name</li>";
            })->implode('') . '</ul>';

            $data[] = '<ul>' . $product->subcategories->pluck('name')->unique()->map(function ($name) {
                return "<li>$name</li>";
            })->implode('') . '</ul>';

            $data[] = '<ul>' . $product->authors->pluck('name')->unique()->map(function ($name) {
                return "<li>$name</li>";
            })->implode('') . '</ul>';

            $data[] = $product->publisher->name ?? '';

            $data[] = $product->current_price ?? '';

            $data[] = $product->stock ?? '';

            $allData[] = $data;
        }

        $response = [
            "draw" => intval($draw),
            "iTotalRecords" => $totalProducts,
            "iTotalDisplayRecords" => $totalDbProducts,
            "aaData" => $allData,
        ];

        return response()->json($response);
    }

    // store campaign product
    public function campaignCreate(Request $request)
    {

        $ids = $request->ids;

        $campaign = Campaign::where('id', $request->campaign_id)->first();

        if (!$campaign) {
            return response()->json(['message' => 'No campaign found.'], 404);
        }

        foreach ($ids as $id) {
            $product = new CampaignProduct();
            $product->campaign_id = $campaign->id;
            $product->product_id = $id;
            $product->save();
        }

        return response()->json(['message' => 'Campaign products added successfully.'], 200);
    }

    // create form
    public function create()
    {
        $discountenums = DiscountEnum::cases();
        return view('backend.modules.campaign.create', compact('discountenums'));
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|unique:campaigns,name',
            'icon' => 'required',
            'meta_title' => 'nullable',
            'meta_description' => 'nullable',
            'meta_image' => 'nullable',
            'status' => 'required',
            'slug' => 'required',
            'discount' => 'required',
            'discount_type' => 'required',
            'notes' => 'nullable'
        ];

        $messages = [
            'status' => 'Status required',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        // date range
        $range = explode(" - ", $request->daterange);
        $startDate = trim($range[0]);
        $endDate = trim($range[1]);
        // store
        $campaign = new Campaign();
        $campaign->name = $request->name;
        $campaign->meta_title = $request->meta_title;
        $campaign->meta_description = $request->meta_description;
        $campaign->status = $request->status;
        $campaign->slug = $request->slug;
        $campaign->discount = $request->discount;
        $campaign->discount_type = $request->discount_type;
        $campaign->start_date = $startDate;
        $campaign->end_date = $endDate;
        $campaign->notes = $request->notes;
        $campaign->meta_title = $request->meta_title;
        $campaign->meta_description = $request->meta_description;
        $campaign->is_featured = $request->is_featured;
        // Set other attributes here following the same pattern
        if ($request->hasFile('icon')) {
            $path = 'uploads/campaign/icon/' . date('Y/m/d') . '/';
            $imageName = uniqid() . '.webp';
            $request->file('icon')->move($path, $imageName);
            $campaign->icon = $path . $imageName;
        }

        if ($request->hasFile('meta_image')) {
            $path = 'uploads/campaign/meta_image/' . date('Y/m/d') . '/';
            $imageName = uniqid() . '.webp';
            $request->file('meta_image')->move($path, $imageName);
            $campaign->meta_image = $path . $imageName;
        }

        if ($request->hasFile('cover_image')) {
            $path = 'uploads/campaign/' . date('Y/m/d') . '/';
            $imageName = uniqid() . '.webp';
            $request->file('cover_image')->move($path, $imageName);
            $campaign->cover_image = $path . $imageName;
        }

        $campaign->save();

        // $campaign->products()->attach($request->product_id, ['campaign_id' => $campaign->id]);


        return response()->json([
            'message' => 'Campaign created successfully',
        ], 201);
    }

    public function getProducts(Request $request)
    {
        $query = $request->get('q');

        $products = Product::where('english_name', 'LIKE', "%{$query}%")->get();

        $results = $products->map(function ($product) {
            return [
                'id' => $product->id,
                'text' =>  $product->english_name
            ];
        });

        return response()->json($results);
    }


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

        $query = Campaign::query()->with('products')->orderBy('created_at', 'desc');

        if (!empty($searchValue)) {

            $query->where('name', 'like', '%' . $searchValue . '%');
        }

        $totalDbAuthors = $query->count();

        $allCampaigns = $query->orderBy($columnName, $columnSortOrder)
            ->skip($row)
            ->take($rowPerPage)
            ->get();

        foreach ($allCampaigns as $key => $campaign) {

            if ($campaign->products->isNotEmpty()) {

                $productNames = '<p><a class="badge badge-info p-2"  href="' . route("campaigns.products.view", ['id' => ($campaign->id)]) . '" >view</a></p>';
            } else {
                $productNames = '<p><a class="badge badge-info p-2"   href="' . route('campaigns.products', ['id' => ($campaign->id)]) . '">Add Product</a></p>';
            }

            $checkMark = '<td><label class="checkboxs"><input type="checkbox" data-value="' . $campaign->id . '"><span class="checkmarks"></span></label></td>';

            $data = [];

            $data[] = $checkMark;

            $data[] = ++$key + $row;

            $data[] = '<div class="productimgname">
                    <a href="javascript:void(0);" class="product-img stock-img">
                        <img src="' . image($campaign->icon) . '" alt="Icon" style="border-radius: 30px;">
                    </a>
                    <a href="javascript:void(0);">' . $campaign->name ?? '' . '</a>
                </div>';

            $data[] = $productNames;

            $data[] = $campaign->discount ?? '';

            $data[] = $campaign->discount_type ?? '';

            $data[] = '<div class="m-0">
                    <p>' . (!empty($campaign->start_date) ? Carbon::parse($campaign->start_date)->format('Y-m-d') : '') . '</p>
                    <p>' . (!empty($campaign->end_date) ? Carbon::parse($campaign->end_date)->format('Y-m-d') : '') . '</p>
                </div>';

            $data[] = '<input type="checkbox" id="user' . $campaign->id . '" class="check" ' . ($campaign->status == 1 ? 'checked' : '') . '>
                <label style="width: 42px !important;" for="user' . $campaign->id . '" class="checktoggle changeStatus" data-campaign-id="' . $campaign->id . '"></label>';

            // $data[] = '<input type="checkbox" id="user_featured' . $campaign->id . '" class="check" ' . ($campaign->is_featured == 1 ? 'checked' : '') . '>
            //     <label style="width: 42px !important;" for="user_featured' . $campaign->id . '" class="checktoggle change_is_featured" data-campaign-id="' . $campaign->id . '"></label>';

            $data[] = '
    <input type="checkbox" id="user_featured' . $campaign->id . '" class="check" ' . ($campaign->is_featured == 1 ? 'checked' : '') . '>
    <label style="width: 42px !important;" for="user_featured' . $campaign->id . '" class="checktoggle change_is_featured" data-campaign-id="' . $campaign->id . '"></label>';


            $data[] = $campaign->user->name ?? '';

            $data[] = '<div class="action-table-data">
                    <div class="edit-delete-action">
                        <a class="btn btn-info me-2 p-2" href="' . route("campaigns.edit", ['id' => encrypt($campaign->id)]) . '">
                            <i class="fa fa-edit text-white"></i>
                        </a>
                        <a class="btn btn-danger delete-btn p-2" href="' . route("campaigns.destroy", ['id' => encrypt($campaign->id)]) . '">
                            <i class="fa fa-trash text-white"></i>
                        </a>
                    </div>
                </div>';

            $allData[] = $data;
        }


        $response = [
            "draw" => intval($draw),
            "iTotalRecords" => $totalAuthors,
            "iTotalDisplayRecords" => $totalDbAuthors,
            "aaData" => $allData,
        ];

        return response()->json($response);
    }


    public function campaignProductView($id)
    {

        $campaign = Campaign::with('products')->find($id);
        $campaignName = Campaign::find($id);
        // Check if the campaign exists
        if (!$campaign) {
            // Handle the case when the campaign with the given id is not found
            abort(404);
        }

        // Access the products associated with the campaign
        $products = $campaign->products;

        return view('backend.modules.campaign.product-view', compact('products', 'campaignName'));
    }

    public function campaignProductViewAjax(Request $request)
    {
        // Initialize variables
        $columns = [
            'id',
        ];

        $draw = $request->draw;
        $row = $request->start;
        $rowPerPage = $request->length;
        $columnIndex = $request->order[0]['column'] ?? 0;
        $columnName = $columns[$columnIndex] ?? 'id';
        $columnSortOrder = $request->order[0]['dir'] ?? 'asc';
        $searchValue = $request->search['value'] ?? '';
        $totalProducts = 0;
        $allData = [];

        try {
            // Retrieve Campaign and Products
            $campaign = Campaign::with('products')->findOrFail($request->id);

            // Filter products
            $query = $campaign->products()->withPivot('id');

            if (!empty($request->category_id)) {
                $query->whereHas('categories', function ($q) use ($request) {
                    $q->where('category_id', $request->category_id);
                });
            }

            if (!empty($request->subcategory_id)) {
                $query->whereHas('subcategories', function ($q) use ($request) {
                    $q->where('subcategory_id', $request->subcategory_id);
                });
            }

            if (!empty($request->author_id)) {
                $query->whereHas('authors', function ($q) use ($request) {
                    $q->where('author_id', $request->author_id);
                });
            }

            if (!empty($request->publisher_id)) {
                $query->where('publisher_id', $request->publisher_id);
            }

            // Search products
            if (!empty($searchValue)) {
                $query->where('english_name', 'like', '%' . $searchValue . '%');
            }

            // Count total number of products
            $totalDbProducts = $query->count();

            // Retrieve paginated and sorted products
            $allProducts = $query->orderBy($columnName, $columnSortOrder)
                ->skip($row)
                ->take($rowPerPage)
                ->get();

            // Format data for DataTables
            foreach ($allProducts as $key => $product) {

                $campaignProductId = $product->pivot->id; // Get the CampaignProduct ID from the pivot table
                $checkMark = '<td><label class="checkboxs"><input type="checkbox" data-value="' . $campaignProductId . '"><span class="checkmarks"></span></label></td>';

                // Initialize data array
                $data = [];

                $data[] = $checkMark;
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

                $data[] = '
                <div class="action-table-data">
                    <div class="edit-delete-action">
                        <a class="btn btn-danger delete-btn p-2" href="' . route("campaigns-product-single.delete", ['id' => $campaignProductId]) . '">
                            <i class="fa fa-trash text-white"></i>
                        </a>
                    </div>
                </div>';

                $allData[] = $data;
            }

            // Prepare response
            $response = [
                "draw" => intval($draw),
                "iTotalRecords" => $totalProducts,
                "iTotalDisplayRecords" => $totalDbProducts,
                "aaData" => $allData,
            ];

            return response()->json($response);
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function edit($id)
    {

        $Id = decrypt($id);
        $campaigns = Campaign::find($Id);
        $camProduct = CampaignProduct::where('campaign_id', $campaigns->id)->get();
        $discountenums = DiscountEnum::cases();

        // Assuming you have a Product model to fetch product details
        $selectedProducts = [];
        foreach ($camProduct as $product) {
            $productDetails = Product::find($product->product_id); // Replace 'Product' with your actual model
            $selectedProducts[] = [
                'id' => $productDetails->id,
                'text' => $productDetails->english_name // Adjust based on your product's attributes
            ];
        }

        return view('backend.modules.campaign.edit', compact('campaigns', 'discountenums', 'camProduct', 'selectedProducts'));
    }

    public function editStore(Request $request)
    {
        $rules = [
            'name' => 'required',
            'icon' => 'nullable',
            'meta_title' => 'nullable',
            'meta_description' => 'nullable',
            'meta_image' => 'nullable',
            'status' => 'required',
            'discount' => 'required',
            'discount_type' => 'required',
            'notes' => 'nullable'
        ];

        $messages = [
            'status' => 'Status required',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        // date range
        $range = explode(" - ", $request->daterange);
        $startDate = trim($range[0]);
        $endDate = trim($range[1]);
        // store
        $campaign = Campaign::find($request->id);

        $campaign->name = $request->name;
        $campaign->meta_title = $request->meta_title;
        $campaign->meta_description = $request->meta_description;
        $campaign->status = $request->status;
        $campaign->discount = $request->discount;
        $campaign->discount_type = $request->discount_type;
        $campaign->start_date = $startDate;
        $campaign->end_date = $endDate;
        $campaign->notes = $request->notes;
        $campaign->meta_title = $request->meta_title;
        $campaign->meta_description = $request->meta_description;
        $campaign->is_featured = 0;
        // Set other attributes here following the same pattern
        if ($request->hasFile('icon')) {
            if (isset($campaign->icon)) {
                $oldImagePath = $campaign->icon;
                unlink($oldImagePath);
            }

            $path = 'uploads/campaign/icon/' . date('Y/m/d') . '/';
            $imageName = uniqid() . '.webp';
            $request->file('icon')->move($path, $imageName);
            $campaign->icon = $path . $imageName;
        }

        if ($request->hasFile('cover_image')) {

            if (isset($campaign->cover_image)) {
                $oldImagePath = $campaign->cover_image;
                unlink($oldImagePath);
            }

            $path = 'uploads/campaign/' . date('Y/m/d') . '/';
            $imageName = uniqid() . '.webp';
            $request->file('cover_image')->move($path, $imageName);
            $campaign->cover_image = $path . $imageName;
        }

        if ($request->hasFile('meta_image')) {

            if (isset($campaign->meta_image)) {
                $oldImagePath = $campaign->meta_image;
                unlink($oldImagePath);
            }

            $path = 'uploads/campaign/meta_image/' . date('Y/m/d') . '/';
            $imageName = uniqid() . '.webp';
            $request->file('meta_image')->move($path, $imageName);
            $campaign->meta_image = $path . $imageName;
        }

        $campaign->save();

        // $campaign->products()->sync($request->product_id);


        return response()->json([

            'message' => 'Campaign Updated Successfully',

        ], 201);
    }

    public function updateStatus(Request $request)
    {
        $id = $request->id;

        // Find the campaign to be updated
        $Campaign = Campaign::findOrFail($id);

        // Update the status field
        $Campaign->status = $request->status;

        // Save the campaign
        $Campaign->save();

        // Return a response
        return response()->json(['message' => 'Campaign status updated successfully'], 200);
    }

    // public function updateStatus(Request $request)
    // {
    //     $id = $request->id;

    //     $Campaign = Campaign::findOrFail($id);

    //     // Toggle the status
    //     $Campaign->status = $Campaign->status == 1 ? 0 : 1;

    //     // Save the category
    //     $Campaign->save();

    //     // Return a response
    //     return response()->json(['message' => 'Campaign Status updated successfully'], 200);
    // }

    // public function updateIsFeatured(Request $request)
    // {
    //     $id = $request->id;

    //     $Campaign = Campaign::findOrFail($id);

    //     // Toggle the status
    //     $Campaign->is_featured = $Campaign->is_featured == 1 ? 0 : 1;

    //     // Save the category
    //     $Campaign->save();

    //     // Return a response
    //     return response()->json(['message' => 'Feature Status updated successfully'], 200);
    // }

    // public function updateIsFeatured(Request $request)
    // {
    //     $id = $request->id;
    //     // Find the campaign to be updated
    //     $Campaign = Campaign::findOrFail($id);
    //     // Deactivate all campaigns
    //     Campaign::where('is_featured', 1)->update(['is_featured' => 0]);
    //     // Set the selected campaign as featured
    //     $Campaign->is_featured = 1;
    //     // Save the campaign
    //     $Campaign->save();
    //     // Return a response
    //     return response()->json(['message' => 'Feature status updated successfully'], 200);
    // }

    // public function updateIsFeatured(Request $request)
    // {
    //     $id = $request->id;

    //     // Deactivate all campaigns
    //     Campaign::where('is_featured', 1)->update(['is_featured' => 0]);

    //     // Find the campaign to be updated
    //     $Campaign = Campaign::findOrFail($id);

    //     // Set the selected campaign as featured
    //     $Campaign->is_featured = 1;

    //     // Save the campaign
    //     $Campaign->save();

    //     // Return a response
    //     return response()->json(['message' => 'Feature status updated successfully'], 200);
    // }

    public function updateIsFeatured(Request $request)
    {
        $id = $request->id;

        // Deactivate all campaigns
        Campaign::where('is_featured', 1)->update(['is_featured' => 0]);

        // Find the campaign to be updated
        $campaign = Campaign::findOrFail($id);

        // Set the selected campaign as featured
        $campaign->is_featured = 1;

        // Save the campaign
        $campaign->save();

        // Return a response
        return response()->json(['message' => 'Feature status updated successfully'], 200);
    }



    public function destroyAll(Request $request)
    {

        $allIds = base64_decode($request->token);

        foreach (json_decode($allIds) as $campaignId) {

            $campaign = Campaign::find($campaignId);

            if ($campaign) {
                $campaign->products()->detach();

                if (isset($campaign->icon) && file_exists($campaign->icon)) {
                    unlink($campaign->icon);
                }


                if (isset($campaign->meta_image) && file_exists($campaign->meta_image)) {
                    unlink($campaign->meta_image);
                }


                $campaign->delete();
            }
        }

        return response()->json(['message' => 'Selected campaigns deleted successfully', 'success' => true], 200);
    }

    // single destroy
    public function destroy(Request $request, $id)
    {
        $Id = decrypt($id);

        $campaign = Campaign::find($Id);

        if ($campaign) {

            if (isset($campaign->icon) && file_exists($campaign->icon)) {
                unlink($campaign->icon);
            }

            if (isset($campaign->meta_image) && file_exists($campaign->meta_image)) {
                unlink($campaign->meta_image);
            }


            $campaign->products()->detach();

            $campaign->delete();
        }

        return response()->json(['message' => 'Campaign deleted successfully', 'success' => true], 200);
    }
    // single destroy
    public function singleDesteroyProduct(Request $request, $id)
    {


        $campaignProduct = CampaignProduct::find($id);

        if ($campaignProduct) {

            $campaignProduct->delete();
        }

        return response()->json(['message' => 'Campaign Product deleted successfully', 'success' => true], 200);
    }
}
