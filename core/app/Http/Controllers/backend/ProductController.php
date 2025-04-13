<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\BundleProduct;
use App\Models\Common;
use App\Models\Product;
use App\Models\User;
use App\Models\Variant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\PagesPhotos;


class ProductController extends Controller
{

    public function index()
    {
        $enumOptions = Common::getPossibleEnumValues('products', 'stock_status');

        return view('backend.modules.product.index', ['enumStatusValues' => $enumOptions]);
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
            $query = Product::query();

            $totalProductsCount = $query->count();

            $records = $query->orderBy($columnName, $columnSortOrder)
                ->skip($row)
                ->take($rowperpage)
                ->get();


            $totalRecords = $totalDRecords = !empty($totalProductsCount) ? $totalProductsCount : 0;
        } else {
            $query = Product::with(['publisher', 'categories'])
                ->where(function ($query) use ($searchValue) {
                    $query->where('bangla_name', 'like', '%' . $searchValue . '%')
                        ->orWhere('english_name', 'like', '%' . $searchValue . '%')
                        ->orWhere('stock_status', 'like', '%' . $searchValue . '%')
                        ->orWhere('product_code', 'like', '%' . $searchValue . '%')
                        ->orWhereHas('publisher', function ($query) use ($searchValue) {
                            $query->where('name', 'like', '%' . $searchValue . '%');
                        })
                        ->orWhereHas('categories', function ($query) use ($searchValue) {
                            $query->where('name', 'like', '%' . $searchValue . '%');
                        });
                })
                ->distinct();

            $records = $query->orderBy($columnName, $columnSortOrder)
                ->skip($row)
                ->take($rowperpage)
                ->get();
            $totalRecords = $query->count();

            $totalDRecords = $totalRecords;
        }

        foreach ($records as $key => $product) {
            $data = [];
            $data[] = '<td>
            <label class="checkboxs">
                <input type="checkbox" class="checked-row" data-value="' . $product->id . '">
                <span class="checkmarks"></span>
            </label>
        </td>';
            $data[] = '<div style="display: flex; align-items: center;">
    <a href="' . route('product.details', ['slug_or_id' => $product->slug ?: $product->id]) . '" title="View Product"  target="_blank"  style="margin-right: 10px; width: 35px; height: 35px; border-radius: 10%;">
        <img src="' . image($product->thumb_image) . '" alt="icon" style="width: 100%; height: 100%; object-fit: cover; border-radius: 10%;">
    </a>
    <div style="flex-grow: 1;">
        <a href="' . route('product.details', ['slug_or_id' => $product->slug ?: $product->id]) . '" title="View Product"  target="_blank" style="display: block;">' . Str::limit($product->bangla_name, 35) . '</a>
        <a href="' . route('product.details', ['slug_or_id' => $product->slug ?: $product->id]) . '" title="View Product"  target="_blank" style="display: block;">' . Str::limit($product->english_name, 35) . '</a>
    </div>
</div>';
            $data[] = $product->product_code;
            $data[] = $product->categories->pluck('name')->implode(',');
            $data[] = '<strong>MRP :</strong> ' . $product->mrp_price . '<br>' .
                '<strong>Current:</strong> ' . $product->current_price;
                
                
//             $data[] = '<div class="card-body d-flex flex-wrap gap-2">
// <span class="badge rounded-pill ' .
//                 ($product->stock_status == 'out_of_stock' ? 'bg-soft-danger' : ($product->stock_status == 'upcoming' ? 'bg-soft-warning' : 'bg-soft-success')) . ' ">' .

//                 ($product->stock_status == 'out_of_stock' ? 'Out of Stock' : ($product->stock_status == 'upcoming' ? 'Upcoming' : 'In Stock')) .
//                 '</span>
// </div>';


$data[] = '<div class="card-body d-flex flex-wrap gap-2">
<span class="badge rounded-pill ' .
    ($product->stock_status == 'out_of_stock' ? 'bg-soft-danger' : 
    ($product->stock_status == 'upcoming' ? 'bg-soft-warning' : 
    ($product->stock_status == 'next_edition' ? 'bg-soft-info' : 'bg-soft-success'))) . ' ">' .
    
    ($product->stock_status == 'out_of_stock' ? 'Out of Stock' : 
    ($product->stock_status == 'upcoming' ? 'Upcoming' : 
    ($product->stock_status == 'next_edition' ? 'Next Edition' : 'In Stock'))) .
    '</span>
</div>';



            $data[] = '<strong>'.$product->stock.'</strong>';
            $data[] = $product->isBundle == 1 ? 'Yes' : 'No';

            $user = User::find($product->created_by);
            if ($user) {
                $data[] = '<div class="userimgname">
                    <a href="javascript:void(0);" class="product-img">
                        <img src="' . image($user->image) . '" alt="user-image">
                    </a>
                    <a href="javascript:void(0);">' . ($user->name ?? '') . '</a>
                </div>';
            } else {
                $data[] = '<p>Not Found</p>';
            }


            $data[] = '<span class="badge changeStatus ' . ($product->status == 1 ? 'badge-linesuccess' : 'badge-linedanger') . '  " style="cursor:pointer;" data-product-id="' . $product->id . '">' . ($product->status == 1 ? 'Publish' : 'Unpublish') . '</span>';


            $data[] = '<div class="action-table-data">
    <div class="edit-delete-action">

        <a class="btn btn-info me-2 p-2" href="' . route('products.edit', $product->id) . '" title="Edit Product">
            <i class="fa fa-edit text-white"></i>
        </a>
        
        
         <a class="btn btn-info me-2 p-2" href="' . route('products.add-pages', $product->slug) . '" title="add inner pages">
            <i class="fa-solid fa-plus"></i>
        </a>
        
        <a class="btn btn-danger delete-btn p-2 me-2" href="' . route('products.destroy', $product->id) . '" title="Delete Product">
            <i class="fa fa-trash text-white"></i>
        </a> 
        <a class="btn btn-secondary p-2" href="' . route('product.details', ['slug_or_id' => $product->slug ?: $product->id]) . '" title="View Product"  target="_blank">
            <i class="fa fa-eye text-white"></i>
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

        $enumOptions = Common::getPossibleEnumValues('products', 'stock_status');



        return view('backend.modules.product.create', ['enumStatusValues' => $enumOptions]);
    }

    public function store(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            // Validation rules for basic information
            'english_name' => 'required|string',
            'bangla_name' => 'required|string',
            'slug' => 'required|unique:products',
            'status' => 'nullable|boolean',
            'product_type' => 'required|string',
            'publisher_id' => 'required|integer',
            'published_year' => 'nullable|date',
            'edition' => 'nullable|string',
            'pages_no' => 'nullable|integer',
            'cover_type' => 'nullable|string',
            'weight' => 'nullable|numeric',
            'isbn' => 'nullable|string',
            'review_url' => 'nullable|url',
            'language' => 'nullable',
            'array',
            'language.*' => 'string',
            // Validation rules for pricing and stocks
            'purchase_price' => 'required|numeric',
            'mrp_price' => 'required|numeric',
            'current_price' => 'required|numeric',
            'discount_type' => 'nullable|in:percentage,amount',
            'discount_amount' => 'nullable|numeric',
            'show_discount' => 'nullable|boolean',
            // 'stock' => 'nullable|integer',
            'stock_status' => 'nullable',
            // Validation rules for descriptions
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            // Validation rules for images
            'thumb_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust max file size as needed
            //            'pages_photos' => 'nullable|array',
            //            'pages_photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',

            // Validation rules for meta option
            'meta_title' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'meta_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust max file size as needed


            // Validation rules for related entities
            'category_id' => 'nullable|array',
            'category_id.*' => 'exists:categories,id',
            'subcategory_id' => 'nullable|array',
            // 'subcategory_id.*' => 'exists:subcategories,id',
            'author_id' => 'nullable|array',
            'author_id.*' => 'exists:authors,id',
            'subject_id' => 'nullable|array',
            'subject_id.*' => 'exists:subjects,id',
        ]);

        // Upload and store the thumb image


        $thumbImagePath = null;
        if ($request->hasFile('thumb_image')) {
            // $thumbImagePath = uploadImage($request->file('thumb_image'), 'products/thumbs');

            $thumbImagePath = uploadImage($request->file('thumb_image'), 'products/thumbs', '0', 80);
        }


        // Upload and store the meta image
        $metaImagePath = null;
        if ($request->hasFile('meta_image')) {
            // $metaImagePath = uploadImage($request->file('meta_image'), 'products/meta_images');
            $metaImagePath = uploadImage($request->file('meta_image'), 'products/meta_images', '0', 80);
        }

        $languageString = null;
        if ($request->has('language')) {
            $languageString = implode(',', $validatedData['language']);
        }


        // Create new product
        $product = new Product();

        // Assign individual attributes
        $product->english_name = $validatedData['english_name'];
        $product->bangla_name = $validatedData['bangla_name'];
        $product->slug = $validatedData['slug'];
        $product->status = $validatedData['status'] ?? null;
        $product->product_type = $validatedData['product_type'] ?? null;
        $product->publisher_id = $validatedData['publisher_id'] ?? null;
        $product->published_year = $validatedData['published_year'] ?? null;
        $product->edition = $validatedData['edition'] ?? null;
        $product->pages_no = $validatedData['pages_no'] ?? null;
        $product->cover_type = $validatedData['cover_type'] ?? null;
        $product->weight = $validatedData['weight'] ?? null;
        $product->isbn = $validatedData['isbn'] ?? null;
        $product->review_url = $validatedData['review_url'] ?? null;
        $product->language = $languageString;
        // price section and stock
        $product->purchase_price = $validatedData['purchase_price'] ?? null;
        $product->mrp_price = $validatedData['mrp_price'] ?? null;
        $product->current_price = $validatedData['current_price'] ?? null;
        $product->discount_type = $validatedData['discount_type'] ?? null;
        $product->discount_amount = $validatedData['discount_amount'] ?? null;
        $product->show_discount = $validatedData['show_discount'] ?? null;
        // $product->stock = $validatedData['stock'] ?? null;
        $product->stock_status = $validatedData['stock_status'] ?? null;

        //image & descriptions
        $product->short_description = $validatedData['short_description'] ?? null;
        $product->description = $validatedData['description'] ?? null;
        $product->thumb_image = $thumbImagePath ?? null;
        // meta section
        $product->meta_title = $validatedData['meta_title'] ?? null;
        $product->meta_description = $validatedData['meta_description'] ?? null;
        $product->meta_image = $metaImagePath ?? null;

        // Save the product to the database
        $product->save();


        // Upload and save page photos paths
        $pagePhotosPaths = [];
        if ($request->hasFile('pages_photos')) {
            foreach ($request->file('pages_photos') as $photo) {
                // $pagePhotosPaths[] = uploadImage($photo, 'products/pages_photos');

                $pagePhotosPaths[] = uploadImage($photo, 'products/pages_photos', 'theme/watermark.png', 80);
            }
        }


        // Save the page photos paths using Eloquent relationships
        if (!empty($pagePhotosPaths)) {
            $product->pages()->createMany(array_map(function ($path) {
                return ['pages_photos' => $path];
            }, $pagePhotosPaths));
        }

        //
        //        // Save the page photos paths individually
        //        if (!empty($pagePhotosPaths)) {
        //            foreach ($pagePhotosPaths as $path) {
        //
        //                $page = new Page();
        //                $page->pages_photos = $path;
        //                $page->product_id = $product->id;
        //
        //                $page->save();
        //            }
        //        }

        //attach categories, subcategories, and authors
        $product->categories()->attach($request->input('category_id'));
        // $product->subcategories()->attach($request->input('subcategory_id'));
        $product->authors()->attach($request->input('author_id'));
        $product->subjects()->attach($request->input('subject_id'));

        // Redirect to a success page or return a response
        return response()->json(['message' => 'Product created successfully'], 200);
    }

    public function edit($id)
    {

        $product = Product::findOrFail($id);

        $enumOptions = Common::getPossibleEnumValues('products', 'stock_status');

        $productAuthorIds = $product->authors->pluck('id')->toArray();
        $productCategoryIds = $product->categories->pluck('id')->toArray();
        $productSubcategoryIds = $product->subcategories->pluck('id')->toArray();
        $productSubjectIds = $product->subjects->pluck('id')->toArray();

        if ($product->isBundle == 0) {
            return view('backend.modules.product.edit', [
                'product' => $product,
                'productAuthorIds' => $productAuthorIds,
                'productCategoryIds' => $productCategoryIds,
                'productSubcategoryIds' => $productSubcategoryIds,
                'productSubjectIds' => $productSubjectIds,
                'enumStatusValues' => $enumOptions,
            ]);
        } else {
            return view('backend.modules.product.bundle_edit', [
                'product' => $product,
                'productAuthorIds' => $productAuthorIds,
                'productCategoryIds' => $productCategoryIds,
                'productSubcategoryIds' => $productSubcategoryIds,
                'productSubjectIds' => $productSubjectIds,
                'enumStatusValues' => $enumOptions,
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        // Find the product instance
        $product = Product::findOrFail($id);

        $validatedData = $request->validate([
            // Validation rules for basic information
            'english_name' => 'required|string',
            'bangla_name' => 'required|string',
            'slug' => 'required|unique:products,slug,' . $product->id,
            'status' => 'nullable|boolean',
            'product_type' => 'required|string',
            'publisher_id' => 'nullable|integer',
            'published_year' => 'nullable|date',
            'edition' => 'nullable|string',
            'pages_no' => 'nullable|integer',
            'cover_type' => 'nullable|string',
            'weight' => 'nullable|numeric',
            'isbn' => 'nullable|string',
            'review_url' => 'nullable|url',
            'language' => 'nullable|array',
            'language.*' => 'string',

            // Validation rules for pricing and stocks
            'purchase_price' => 'required|numeric',

            'mrp_price' => 'required|numeric',
            'current_price' => 'required|numeric',
            'discount_type' => 'nullable|in:percentage,amount',
            'discount_amount' => 'nullable|numeric',
            'show_discount' => 'nullable|boolean',
            'stock' => 'nullable|integer',
            'stock_status' => 'nullable',

            // Validation rules for descriptions
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',

            // Validation rules for images
            'thumb_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust max file size as needed
            'meta_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust max file size as needed

            // Validation rules for related entities
            'category_id' => 'nullable|array',
            'category_id.*' => 'exists:categories,id',
            // 'subcategory_id' => 'nullable|array',
            // 'subcategory_id.*' => 'exists:subcategories,id',
            'author_id' => 'nullable|array',
            'author_id.*' => 'exists:authors,id',

            'subject_id' => 'nullable|array',
            'subject_id.*' => 'exists:subjects,id',
        ]);;

        // Update the product instance with validated data
        $product->english_name = $validatedData['english_name'];
        $product->bangla_name = $validatedData['bangla_name'];
        $product->slug = $validatedData['slug'];
        $product->status = $validatedData['status'] ?? null;
        $product->product_type = $validatedData['product_type'] ?? null;
        $product->publisher_id = $validatedData['publisher_id'] ?? null;
        $product->published_year = $validatedData['published_year'] ?? null;
        $product->edition = $validatedData['edition'] ?? null;
        $product->pages_no = $validatedData['pages_no'] ?? null;
        $product->cover_type = $validatedData['cover_type'] ?? null;
        $product->weight = $validatedData['weight'] ?? null;
        $product->isbn = $validatedData['isbn'] ?? null;
        $product->review_url = $validatedData['review_url'] ?? null;
        // Handle language update
        if ($request->has('language')) {
            // If language data is provided, update the language attribute
            $languageString = implode(',', $validatedData['language']);
            $product->language = $languageString;
        }
        // price section and stock
        $product->purchase_price = $validatedData['purchase_price'] ?? null;
        $product->mrp_price = $validatedData['mrp_price'] ?? null;
        $product->current_price = $validatedData['current_price'] ?? null;
        $product->discount_type = $validatedData['discount_type'] ?? null;
        $product->discount_amount = $validatedData['discount_amount'] ?? null;
        $product->show_discount = $validatedData['show_discount'];
        $product->stock = $validatedData['stock'] ?? null;
        $product->stock_status = $validatedData['stock_status'] ?? null;
        //image & descriptions
        $product->short_description = $validatedData['short_description'] ?? null;
        $product->description = $validatedData['description'] ?? null;
        // meta section
        $product->meta_title = $validatedData['meta_title'] ?? null;
        $product->meta_description = $validatedData['meta_description'] ?? null;
        // Save the updated product attributes
        $product->save();
        // Handle image uploads and updates
        if ($request->hasFile('thumb_image')) {
            // Upload new thumb image
            // $thumbImagePath = uploadImage($request->file('thumb_image'), 'products/thumbs');
            $thumbImagePath = uploadImage($request->file('thumb_image'), 'products/thumbs', '0', 80);
            // Delete previous thumb image if it exists
            if ($product->thumb_image && file_exists($product->thumb_image)) {
                unlink($product->thumb_image);
            }
            $product->thumb_image = $thumbImagePath;

            $product->save();
        }

        if ($request->hasFile('meta_image')) {
            // Upload new meta image
            // $metaImagePath = uploadImage($request->file('meta_image'), 'products/meta_images');
            $metaImagePath = uploadImage($request->file('meta_image'), 'products/meta_images', '0', 80);
            // Delete previous meta image if it exists
            if ($product->meta_image && file_exists($product->meta_image)) {
                unlink($product->meta_image);
            }
            // Update meta image path
            $product->meta_image = $metaImagePath;
            $product->save();
        }





        if ($request->hasFile('pages_photos')) {

            if ($product->pages) {
                foreach ($product->pages as $page) {
                    // Delete the file from the server
                    if (file_exists($page->pages_photos)) {
                        unlink($page->pages_photos);
                    }
                }

                $product->pages()->delete();
            }


            foreach ($request->file('pages_photos') as $photo) {
                // Upload each page photo
                // $pagePhotoPath = uploadImage($photo, 'products/pages_photos');

                $pagePhotoPath = uploadImage($photo, 'products/pages_photos', 'theme/watermark.png', 80);

                //                // Get the current product's page photos with the same path
                //                $existingPagePhoto = $product->pages()->where('pages_photos', $pagePhotoPath)->first();
                //
                //                if ($existingPagePhoto) {
                //                    // If a page photo with the same path exists for the current product, delete it
                //                    if (file_exists($existingPagePhoto->pages_photos)) {
                //                        unlink($existingPagePhoto->pages_photos);
                //                    }
                //
                //                    $existingPagePhoto->delete();
                //                }

                // Create a new page photo for the current product
                $product->pages()->create([
                    'pages_photos' => $pagePhotoPath
                ]);
            }
        }


        $product->categories()->sync($request->input('category_id'));
        // $product->subcategories()->sync($request->input('subcategory_id'));
        $product->authors()->sync($request->input('author_id'));
        $product->subjects()->attach($request->input('subject_id'));

        return response()->json(['message' => 'Product updated successfully'], 200);
    }
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // Get paths of previous images (Ensure these are relative or absolute paths on the server)
        $thumbImagePath =$product->thumb_image; // Assuming the images are in the public directory
        $metaImagePath =$product->meta_image;

        // Get paths of previous page photos
        $previousPagePhotoPaths = $product->pages()->pluck('pages_photos')->toArray();

        // Delete the product
        $product->delete();

        // Unlink the images if they exist
        if ($thumbImagePath && file_exists($thumbImagePath)) {
            unlink($thumbImagePath);
        }

        if ($metaImagePath && file_exists($metaImagePath)) {
            unlink($metaImagePath);
        }

        // Unlink previous page photos
        if ($previousPagePhotoPaths) {
            foreach ($previousPagePhotoPaths as $pagePhotoPath) {
                $pagePhotoFullPath =$pagePhotoPath; // Convert to server path
                if ($pagePhotoFullPath && file_exists($pagePhotoFullPath)) {
                    unlink($pagePhotoFullPath);
                }
            }
        }

        return response()->json(['message' => 'Product deleted successfully', 'success' => true], 200);
        // return response()->json(['message' => 'Product deleted successfully'], 200);
    }

    public function destroyAll(Request $request)
    {
        $token = base64_decode($request->get("token"));
        $ids = json_decode($token);

        foreach ($ids as $id) {
            $product = Product::find($id);
            if ($product) {
                // Store the paths of the images before deleting the product
                $thumbImagePath =$product->thumb_image; // Assuming the images are in the public directory
                $metaImagePath =$product->meta_image;

                // Get paths of previous page photos
                $previousPagePhotoPaths = $product->pages()->pluck('pages_photos')->toArray();

                // Delete the product
                $product->delete();

                // Unlink the images if they exist
                if ($thumbImagePath && file_exists($thumbImagePath)) {
                    unlink($thumbImagePath);
                }
                if ($metaImagePath && file_exists($metaImagePath)) {
                    unlink($metaImagePath);
                }

                // Unlink previous page photos
                if ($previousPagePhotoPaths) {
                    foreach ($previousPagePhotoPaths as $pagePhotoPath) {
                        $pagePhotoFullPath =$pagePhotoPath; // Convert to server path
                        if ($pagePhotoFullPath && file_exists($pagePhotoFullPath)) {
                            unlink($pagePhotoFullPath);
                        }
                    }
                }
            }
        }

        return response()->json(['message' => 'Products deleted successfully', 'success' => true], 200);
    }

    public function show($id)
    {

        $product = Product::findOrFail($id);

        //        dd($product);


        return view('backend.modules.product.show_details', ['product' => $product,]);
    }

    // bundle product
    public function bundleCreate()
    {
        $enumOptions = Common::getPossibleEnumValues('products', 'stock_status');

        return view('backend.modules.product.bundle_create', ['enumStatusValues' => $enumOptions]);
    }
    public function bundleStore(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            // Validation rules for basic information
            'english_name' => 'required|string',
            'bangla_name' => 'required|string',
            'slug' => 'required|unique:products',
            'status' => 'nullable|boolean',
            'product_type' => 'required|string',
            'publisher_id' => 'nullable|integer',
            'published_year' => 'required|date',
            'edition' => 'nullable|string',
            'pages_no' => 'nullable|integer',
            'cover_type' => 'nullable|string',
            'weight' => 'nullable|numeric',
            'isbn' => 'nullable|string',


            'language' => 'nullable',
            'array',
            'language.*' => 'string',
            // Validation rules for pricing and stocks
            'purchase_price' => 'required|numeric',
            'mrp_price' => 'required|numeric',
            'current_price' => 'required|numeric',
            'discount_type' => 'nullable|in:percentage,amount',
            'discount_amount' => 'nullable|numeric',
            'show_discount' => 'nullable|boolean',
            // 'stock' => 'nullable|integer',
            'stock_status' => 'nullable',
            // Validation rules for descriptions
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',

            // Validation rules for images
            'thumb_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust max file size as needed
            //            'pages_photos' => 'nullable|array',
            //            'pages_photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',

            // Validation rules for meta option
            'meta_title' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'meta_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust max file size as needed


            // Validation rules for related entities
            'category_id' => 'nullable|array',
            'category_id.*' => 'exists:categories,id',
            // 'subcategory_id' => 'nullable|array',
            // 'subcategory_id.*' => 'exists:subcategories,id',
            'author_id' => 'nullable|array',
            'author_id.*' => 'exists:authors,id',

            'subject_id' => 'nullable|array',
            'subject_id.*' => 'exists:subjects,id',
            //bundle product validation info
            'bundle_product_id.*' => 'required|exists:products,id',
            'name.*' => 'required',
            'bundle_current_price.*' => 'required',
            'quantity.*' => 'required|integer',
            'isBundle' => 'nullable',
        ]);

        // Upload and store the thumb image


        $thumbImagePath = null;
        if ($request->hasFile('thumb_image')) {
            // $thumbImagePath = uploadImage($request->file('thumb_image'), 'products/thumbs');
            $thumbImagePath = uploadImage($request->file('thumb_image'), 'products/thumbs', '0', 80);
        }
        // Upload and store the meta image
        $metaImagePath = null;
        if ($request->hasFile('meta_image')) {
            // $metaImagePath = uploadImage($request->file('meta_image'), 'products/meta_images');
            $metaImagePath = uploadImage($request->file('meta_image'), 'products/meta_images', '0', 80);
        }
        $languageString = null;
        if ($request->has('language')) {
            $languageString = implode(',', $validatedData['language']);
        }

        // Create new product
        $product = new Product();

        // Assign individual attributes
        $product->english_name = $validatedData['english_name'];
        $product->bangla_name = $validatedData['bangla_name'];
        $product->slug = $validatedData['slug'];
        $product->status = $validatedData['status'] ?? null;
        $product->product_type = $validatedData['product_type'] ?? null;
        $product->publisher_id = $validatedData['publisher_id'] ?? null;
        $product->published_year = $validatedData['published_year'] ?? null;
        $product->edition = $validatedData['edition'] ?? null;
        $product->pages_no = $validatedData['pages_no'] ?? null;
        $product->cover_type = $validatedData['cover_type'] ?? null;
        $product->weight = $validatedData['weight'] ?? null;
        $product->isbn = $validatedData['isbn'] ?? null;

        $product->language = $languageString;
        // price section and stock
        $product->purchase_price = $validatedData['purchase_price'] ?? null;
        $product->mrp_price = $validatedData['mrp_price'] ?? null;
        $product->current_price = $validatedData['current_price'] ?? null;
        $product->discount_type = $validatedData['discount_type'] ?? null;
        $product->discount_amount = $validatedData['discount_amount'] ?? null;
        $product->show_discount = $validatedData['show_discount'] ?? null;
        // $product->stock = $validatedData['stock'] ?? null;
        $product->stock_status = $validatedData['stock_status'] ?? null;
        //image & descriptions
        $product->short_description = $validatedData['short_description'] ?? null;
        $product->description = $validatedData['description'] ?? null;
        $product->thumb_image = $thumbImagePath ?? null;
        // meta section
        $product->meta_title = $validatedData['meta_title'] ?? null;
        $product->meta_description = $validatedData['meta_description'] ?? null;
        $product->meta_image = $metaImagePath ?? null;

        if ($request->has('isBundle')) {
            $product->isBundle = $validatedData['isBundle'];
        }


        // Save the product to the database
        $product->save();


        if ($request->has('isBundle'))

            foreach ($request->bundle_product_id as $key => $productId) {
                // Create a new bundle product associated with the product
                $bundleProduct = new BundleProduct();
                $bundleProduct->product_id = $product->id;
                $bundleProduct->bundle_product_id = $productId;
                $bundleProduct->name = $request->name[$key];
                $bundleProduct->current_price = $request->bundle_current_price[$key];
                $bundleProduct->quantity = $request->quantity[$key];
                $bundleProduct->total = $bundleProduct->current_price * $bundleProduct->quantity;
                // Add other fields as needed
                $bundleProduct->save();
            }



        // Upload and save page photos paths
        $pagePhotosPaths = [];
        if ($request->hasFile('pages_photos')) {
            foreach ($request->file('pages_photos') as $photo) {
                // $pagePhotosPaths[] = uploadImage($photo, 'products/pages_photos');
                $pagePhotosPaths[] = uploadImage($photo, 'products/pages_photos', 'theme/watermark.png', 80);
            }
        }


        // Save the page photos paths using Eloquent relationships
        if (!empty($pagePhotosPaths)) {
            $product->pages()->createMany(array_map(function ($path) {
                return ['pages_photos' => $path];
            }, $pagePhotosPaths));
        }

        //
        //        // Save the page photos paths individually
        //        if (!empty($pagePhotosPaths)) {
        //            foreach ($pagePhotosPaths as $path) {
        //
        //                $page = new Page();
        //                $page->pages_photos = $path;
        //                $page->product_id = $product->id;
        //
        //                $page->save();
        //            }
        //        }

        //attach categories, subcategories, and authors
        $product->categories()->attach($request->input('category_id'));
        // $product->subcategories()->attach($request->input('subcategory_id'));
        $product->authors()->attach($request->input('author_id'));
        $product->subjects()->attach($request->input('subject_id'));

        // Redirect to a success page or return a response
        return response()->json(['message' => 'Product created successfully'], 200);
    }

    public function bundleUpdate(Request $request, $id)
    {
        // Find the product instance
        $product = Product::findOrFail($id);

        $validatedData = $request->validate([
            // Validation rules for basic information
            'english_name' => 'required|string',
            'bangla_name' => 'required|string',
            'slug' => 'required|unique:products,slug,' . $product->id,
            'status' => 'nullable|boolean',
            'product_type' => 'required|string',
            'publisher_id' => 'nullable|integer',
            'published_year' => 'nullable|date',
            'edition' => 'nullable|string',
            'pages_no' => 'nullable|integer',
            'cover_type' => 'nullable|string',
            'weight' => 'nullable|numeric',
            'isbn' => 'nullable|string',

            'language' => 'nullable|array',
            'language.*' => 'string',

            // Validation rules for pricing and stocks
            'purchase_price' => 'required|numeric',

            'mrp_price' => 'required|numeric',
            'current_price' => 'required|numeric',
            'discount_type' => 'nullable|in:percentage,amount',
            'discount_amount' => 'nullable|numeric',
            'show_discount' => 'nullable|boolean',
            'stock' => 'nullable|integer',
            'stock_status' => 'nullable',
            // Validation rules for descriptions
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            // Validation rules for images
            'thumb_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust max file size as needed
            'meta_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust max file size as needed

            // Validation rules for related entities
            'category_id' => 'nullable|array',
            'category_id.*' => 'exists:categories,id',
            // 'subcategory_id' => 'nullable|array',
            // 'subcategory_id.*' => 'exists:subcategories,id',
            'author_id' => 'nullable|array',
            'author_id.*' => 'exists:authors,id',

            'subject_id' => 'nullable|array',
            'subject_id.*' => 'exists:subjects,id',

            //bundle product validation info

            'bundle_product_id.*' => 'required|exists:products,id',
            'name.*' => 'required',
            'bundle_current_price.*' => 'required',
            'quantity.*' => 'required|integer',
            'isBundle' => 'nullable',
        ]);

        // Update the product instance with validated data
        $product->english_name = $validatedData['english_name'];
        $product->bangla_name = $validatedData['bangla_name'];
        $product->slug = $validatedData['slug'];
        $product->status = $validatedData['status'] ?? null;
        $product->product_type = $validatedData['product_type'] ?? null;
        $product->publisher_id = $validatedData['publisher_id'] ?? null;
        $product->published_year = $validatedData['published_year'] ?? null;
        $product->edition = $validatedData['edition'] ?? null;
        $product->pages_no = $validatedData['pages_no'] ?? null;
        $product->cover_type = $validatedData['cover_type'] ?? null;
        $product->weight = $validatedData['weight'] ?? null;
        $product->isbn = $validatedData['isbn'] ?? null;


        // Handle language update
        if ($request->has('language')) {
            // If language data is provided, update the language attribute
            $languageString = implode(',', $validatedData['language']);
            $product->language = $languageString;
        }

        // price section and stock
        $product->purchase_price = $validatedData['purchase_price'] ?? null;
        $product->mrp_price = $validatedData['mrp_price'] ?? null;
        $product->current_price = $validatedData['current_price'] ?? null;
        $product->discount_type = $validatedData['discount_type'] ?? null;
        $product->discount_amount = $validatedData['discount_amount'] ?? null;
        $product->show_discount = $validatedData['show_discount'];
        $product->stock = $validatedData['stock'] ?? null;
        $product->stock_status = $validatedData['stock_status'] ?? null;


        //image & descriptions
        $product->short_description = $validatedData['short_description'] ?? null;
        $product->description = $validatedData['description'] ?? null;

        // meta section
        $product->meta_title = $validatedData['meta_title'] ?? null;
        $product->meta_description = $validatedData['meta_description'] ?? null;


        if ($request->has('isBundle')) {
            $product->isBundle = $validatedData['isBundle'];
        }

        // Save the updated product attributes
        $product->save();



        if ($request->has('bundle_product_id')) {
            foreach ($request->bundle_product_id as $key => $productId) {

                BundleProduct::updateOrCreate(
                    [
                        'product_id' => $product->id,
                        'bundle_product_id' => $productId,
                    ],
                    [
                        'name' => $request->name[$key], // Ensure 'name' is provided in the request
                        'current_price' => $request->bundle_current_price[$key],
                        'quantity' => $request->quantity[$key],
                        'total' => $request->bundle_current_price[$key] * $request->quantity[$key],

                    ]
                );
            }
        }



        // Handle image uploads and updates
        if ($request->hasFile('thumb_image')) {
            // Upload new thumb image
            // $thumbImagePath = uploadImage($request->file('thumb_image'), 'products/thumbs');

            $thumbImagePath = uploadImage($request->file('thumb_image'), 'products/thumbs', '0', 80);
            // Delete previous thumb image if it exists
            if ($product->thumb_image && file_exists($product->thumb_image)) {
                unlink($product->thumb_image);
            }
            $product->thumb_image = $thumbImagePath;

            $product->save();
        }

        if ($request->hasFile('meta_image')) {
            // Upload new meta image
            $metaImagePath = uploadImage($request->file('meta_image'), 'products/meta_images');
            // Delete previous meta image if it exists
            if ($product->meta_image && file_exists($product->meta_image)) {
                unlink($product->meta_image);
            }
            // Update meta image path
            $product->meta_image = $metaImagePath;

            $product->save();
        }


        if ($request->hasFile('pages_photos')) {

            if ($product->pages) {
                foreach ($product->pages as $page) {
                    // Delete the file from the server
                    if (file_exists($page->pages_photos)) {
                        unlink($page->pages_photos);
                    }
                }

                $product->pages()->delete();
            }


            $pagePhotosPaths = [];
            foreach ($request->file('pages_photos') as $photo) {
                // $pagePhotosPaths[] = uploadImage($photo, 'products/pages_photos');
                $pagePhotosPaths[] = uploadImage($photo, 'products/pages_photos', 'theme/watermark.png', 80);
            }


            // Save the page photos paths using Eloquent relationships
            if (!empty($pagePhotosPaths)) {

                $product->pages()->createMany(array_map(function ($path) {
                    return ['pages_photos' => $path];
                }, $pagePhotosPaths));
            }
        }


        $product->categories()->sync($request->input('category_id'));
        // $product->subcategories()->sync($request->input('subcategory_id'));
        $product->authors()->sync($request->input('author_id'));
        $product->subjects()->attach($request->input('subject_id'));

        return response()->json(['message' => 'Product updated successfully'], 200);
    }

    public function deleteBundleItem(Request $request)
    {
        $bundleProductId = $request->input('bundleProductId');
        $productId = $request->input('productId');

        // Find the bundle product with the given bundle_product_id or product_id
        $bundleProduct = BundleProduct::where($bundleProductId)
            ->where('product_id', $productId)
            ->first();

        // Check if the bundle product exists
        if ($bundleProduct) {
            // Delete the bundle product
            $bundleProduct->delete();
            return response()->json(['message' => 'Bundle item deleted successfully']);
        } else {
            // Return an error response if the bundle product does not exist
            return response()->json(['error' => 'Bundle item not found'], 404);
        }
    }



    public function updateStatus(Request $request)
    {
        $id = $request->id;

        $product = Product::findOrFail($id);

        // Toggle the status
        $product->status = $product->status == 1 ? 0 : 1;

        $product->save();

        return response()->json(['message' => 'Product status updated successfully'], 200);
    }
    public function productSearch(Request $request)
    {

        $search = $request->input('q');
        $select2Json = [];

        $products = Product::where('status', 1)
            ->where(function ($query) use ($search) {
                $query->where('bangla_name', 'like', '%' . $search . '%');
            })
            ->orWhere('english_name', 'like', '%' . $search . '%')
            ->select('bangla_name','id')
            ->get();



        foreach ($products as $single) {

            $select2Json[] = array(
                'id' =>  $single->id,
                'text' => $single->bangla_name,
            );
        }
        echo html_entity_decode(json_encode($select2Json));
    }
    public function getProductDetails(Request $request)
    {

        $productId = $request->input('productId');

        $product = Product::find($productId);

        if ($product) {
            return response()->json([
                'id' =>  $product->id,
                'name' => $product->bangla_name,
                'current_price' => $product->current_price,
            ]);
        } else {
            return response()->json([], 404);
        }
    }

    public function updateStockStatus(Request $request)
    {
        try {
            $token = base64_decode($request->get("product_ids"));

            $ids = json_decode($token);

            foreach ($ids as $id) {
                $product = Product::find($id);
                if ($product) {
                    $product->stock_status = $request->stock_status;
                    $product->save();
                    //                    $order->logAction('Payment Status Updated');
                }
            }
            // If the loop completes without errors, return a success response
            return response()->json(['status' => 'success', 'message' => 'Stock Status Successfully Updated']);
        } catch (\Exception $e) {
            // If an exception occurs, return an error response
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }


    public function productDiscountMultiple(Request $request)
    {
        try {
            $token = base64_decode($request->get("token"));
            $ids = json_decode($token);

            foreach ($ids as $id) {

                $product = Product::find($id);

                // Check if product is found before trying to update
                if ($product) {
                    // Validate the discount
                    $request->validate([
                        'discount' => ['required', 'regex:/^(\d+(\.\d{1,2})?|\d+%)$/'],
                    ]);

                    // Called discount function here
                    $discountAmount = $this->calculateDiscountAmount($request->discount, $product->mrp_price);

                    $product->discount_amount = $discountAmount;
                    $product->current_price = $product->mrp_price - $discountAmount;
                    $product->save();
                }
            }

            // If the loop completes without errors, return a success response
            return response()->json(['status' => 'success', 'message' => 'Discount Successfully Updated']);
        } catch (\Exception $e) {
            // If an exception occurs, return an error response
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    private function calculateDiscountAmount($discount, $mrpPrice)
    {
        if (str_ends_with($discount, '%')) {
            // If the discount is in percentage format (contains '%')
            //            rtrim is a PHP function ,stands for "right trim." used to remove characters from the end (right side) of a string
            $percentage = (float) rtrim($discount, '%');

            return $mrpPrice * ($percentage / 100);
        } else {
            // If the discount is in fixed amount format
            return (float) $discount;
        }
    }


    public function indexApi()
    {
        $products = Product::select('english_name', 'current_price', 'thumb_image')->get()->map(function ($product) {
            return [
                'english_name' => $product->english_name,
                'current_price' => $product->current_price,
                'thumb_image' => $product->thumb_image
            ];
        });

        return response()->json($products);
    }




    //variation

    public function variationCreate(Request $request)
    {

        $product = Product::findorFail($request->id);
        $variations = Variant::all();

        return view('backend.modules.product.variation_create', ['product' => $product, 'variations' => $variations]);
    }

    public function variationStore(Request $request, Product $product)
    {

        $validatedData = $request->validate([
            'variant_id' => 'required|integer|exists:variants,id', // Ensure variation ID exists
            'price' => 'nullable|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
        ]);

        $product->variants()->attach($validatedData['variant_id'], $validatedData);

        return response()->json([
            'message' => 'Variation added successfully!',
        ]);
    }

    public function variationEdit($productId, $variationId)
    {
        $variations = Variant::all();
        $product = Product::findOrFail($productId);

        $variant = $product->variants()->findOrFail($variationId);
        return view('backend.modules.product.variation_edit', ['product' => $product, 'variant' => $variant, 'variations' => $variations]);
    }
    public function variationUpdate(Request $request, $productId, $variantId)
    {
        $validatedData = $request->validate([
            'variant_id' => 'required|integer|exists:variants,id', // Ensure variation ID exists
            'price' => 'nullable|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
        ]);

        $product = Product::find($productId);
        $variant = $product->variants->find($variantId);



        if ($variant !== null) {
            // If the variant exists, update its pivot data
            $product->variants()->updateExistingPivot($variantId, $validatedData);
            return response()->json([
                'message' => 'Variation updated successfully!',
            ]);
        } else {
            // If the variant does not exist, return an error response
            return response()->json([
                'error' => 'The variation does not exist for this product.',
            ], 404);
        }
    }
    public function deleteVariationItem(Request $request)
    {
        try {
            $variantId = $request->input('variantId');
            $productId = $request->input('productId');

            $product = Product::findOrFail($productId);

            // Detach the variant
            $product->variants()->detach($variantId);

            return response()->json(['success' => true, 'message' => 'Variant removed successfully']);
        } catch (\Exception $e) {


            return response()->json(['success' => false, 'message' => 'An error occurred while removing the variant.'], 500);
        }
    }
    
    // all pages 
    public function addPages(Request $request, $slug)
    {

        $slug = $slug;

        $product=Product::where('slug',$slug)->first();
        
        $Id=$product->id;

        $product_pages=PagesPhotos::where('product_id',$Id)->get();

        return view('backend.modules.product.create-pages', compact('Id','product','product_pages'));
    }
    
      public function uploadPages(Request $request)
    {
        
       
        // Validate the uploaded image and the product ID
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif', // Validate one image at a time
            'compacted_id' => 'required|exists:products,id' // Validate product ID
        ]);

        // Create a new PagesPhotos instance for each image
        $pages = new PagesPhotos();

        // Store the product ID
        $pages->product_id = $request->compacted_id;

        // Handle the image upload (store the image and apply watermark)
        if ($request->hasFile('image')) {
            $image = $request->file('image'); // Get the uploaded image
            $pagePhotosPaths = uploadImage($image, 'products/pages_photos', 'theme/watermark.png', 80);

            // Store the image path in the database
            $pages->pages_photos = $pagePhotosPaths;
        }
        // Save the PagesPhotos instance to the database
        $pages->save();

        return response()->json(['success' => 'Image uploaded successfully']);
    }
    
    
    public function destroyPage($id)
{
    $page = PagesPhotos::find($id);
    
    if ($page) {
        if (isset($page->pages_photos) && file_exists(public_path($page->pages_photos))) {
            unlink(public_path($page->pages_photos)); // Use public_path() for proper file path
        }

        $page->delete();

        return response()->json(['message' => 'Page Deleted Successfully', 'success' => true], 200);
    }

    return response()->json(['message' => 'Page not found', 'success' => false], 404);
}
    
    
     public function destroyAllPage($id)
    {

        $pages = PagesPhotos::where('product_id', $id)->get();
        foreach ($pages as $page) {
            if ($page) {
                if (isset($page->pages_photos) && file_exists(public_path($page->pages_photos))) {
                    unlink($page->pages_photos);
                }

                $page->delete(); // Trigger the deleting event for each model
            }
        }

        return response()->json(['message' => 'Pages Deleted Successfully', 'success' => true], 200);
    }
    
    
    
    
}
