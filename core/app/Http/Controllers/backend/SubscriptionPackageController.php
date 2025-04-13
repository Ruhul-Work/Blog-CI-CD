<?php
namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionFeature;
use App\Models\SubscriptionPackage;
use Illuminate\Http\Request;

class SubscriptionPackageController extends Controller
{
    public function index()
    {

        return view('backend.modules.subscription-packages.index');
    }

    public function create()
    {
        return view('backend.modules.subscription-packages.create');
    }

    public function ajaxIndex(Request $request)
    {
        $columns    = ["id", "title", "name", "mrp_price", "current_price", "discount_amount", "duration", "created_by", "status"];
        $draw       = $request->draw;
        $row        = $request->start;
        $rowperpage = $request->length;

        $columnIndex     = $request->order[0]['column'];
        $columnName      = ! empty($columns[$columnIndex]) ? $columns[$columnIndex] : $columns[0];
        $columnSortOrder = $request->order[0]['dir'];
        $searchValue     = $request->search['value'];

        $query = SubscriptionPackage::with('creator');

        $totalRecords = $query->count();

        if (! empty($searchValue)) {
            $query->where('title', 'like', '%' . $searchValue . '%')
                ->orWhere('name', 'like', '%' . $searchValue . '%')
                ->orWhere('mrp_price', 'like', '%' . $searchValue . '%')
                ->orWhere('current_price', 'like', '%' . $searchValue . '%');
        }

        $totalDisplayRecords = $query->count();

        $records = $query->orderBy($columnName, $columnSortOrder)
            ->skip($row)
            ->take($rowperpage)
            ->get();

        $data = [];
        foreach ($records as $key => $package) {
            $row   = [];
            $row[] = '<label class="checkboxs"><input type="checkbox" class="checked-row" data-value="' . $package->id . '"><span class="checkmarks"></span></label>';
            $row[] = ++$key;
            $row[] = $package->title;
            $row[] = $package->name;
            $row[] = number_format($package->mrp_price, 2);
            $row[] = number_format($package->current_price, 2);
            $row[] = ucfirst($package->discount_type) . ' (' . number_format($package->discount_amount, 2) . ')';
            $row[] = $package->duration . ' Days';
            $row[] = $package->creator ? $package->creator->name : 'N/A';
            $row[] = '<span class="badge changeStatus ' . ($package->status ? 'badge-linesuccess' : 'badge-linedanger') . '" style="cursor:pointer;" data-id="' . $package->id . '">' . ($package->status ? 'Active' : 'Inactive') . '</span>';
            $row[] = '<div class="action-table-data">
                <a class="btn btn-info me-2 p-2" href="' . route('subscription-packages.edit', $package->id) . '">
                    <i class="fa fa-edit text-white"></i>
                </a>
                <a class="btn btn-danger delete-btn p-2" href="' . route('subscription-packages.destroy', $package->id) . '">
                    <i class="fa fa-trash text-white"></i>
                </a>
              </div>';
            $data[] = $row;
        }

        $response = [
            "draw"                 => intval($draw),
            "iTotalRecords"        => $totalRecords,
            "iTotalDisplayRecords" => $totalDisplayRecords,
            "aaData"               => $data,
        ];

        return response()->json($response);
    }

    //store function
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title'           => 'required|string|max:255',
            'name'            => 'required|string|max:255',
            'duration'        => 'required|numeric|min:0',
            'description'     => 'nullable|string',
            'mrp_price'       => 'required|numeric|min:0',
            'discount_type'   => 'required|in:percent,amount',
            'discount_amount' => 'required|numeric|min:0',
            'current_price'   => 'required|numeric|min:0',
            'status'          => 'required|boolean',
            'features'        => 'array',
            'features.*'      => 'nullable|string|max:255',
        ]);

        $subscriptionPackage                  = new SubscriptionPackage();
        $subscriptionPackage->title           = $validatedData['title'];
        $subscriptionPackage->name            = $validatedData['name'];
        $subscriptionPackage->duration        = $validatedData['duration'];
        $subscriptionPackage->description     = $validatedData['description'] ?? null;
        $subscriptionPackage->mrp_price       = $validatedData['mrp_price'];
        $subscriptionPackage->discount_type   = $validatedData['discount_type'];
        $subscriptionPackage->discount_amount = $validatedData['discount_amount'];
        $subscriptionPackage->current_price   = $validatedData['current_price'];
        $subscriptionPackage->status          = $validatedData['status'];
        $subscriptionPackage->created_by;
        $subscriptionPackage->save();

        // if (!empty($validatedData['features'])) {
        //     foreach ($validatedData['features'] as $featureName) {
        //         if (!empty($featureName)) {
        //             $feature = new SubscriptionFeature();
        //             $feature->subscription_package_id = $subscriptionPackage->id;
        //             $feature->name = $featureName;
        //             $feature->save();
        //         }
        //     }
        // }
        if (! empty($validatedData['features'])) {
            foreach ($validatedData['features'] as $index => $featureName) {
                if (! empty($featureName)) {
                    $feature                          = new SubscriptionFeature();
                    $feature->subscription_package_id = $subscriptionPackage->id;
                    $feature->name                    = $featureName;
                    $feature->icon                    = $request->features_icon[$index] ?? 1; // Default to ✔ if not provided
                    $feature->save();
                }
            }
        }

        return response()->json([
            'message' => 'Subscription Package created successfully with features!',
        ], 200);
    }

    //for edit funtion
    public function edit($id)
    {
        $subscriptionPackage = SubscriptionPackage::with('features')->findOrFail($id);

        return view('backend.modules.subscription-packages.edit', compact('subscriptionPackage'));
    }

    //for update

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'title'           => 'required|string|max:255',
            'name'            => 'required|string|max:255',
            'duration'        => 'required|numeric|min:0',
            'description'     => 'nullable|string',
            'mrp_price'       => 'required|numeric|min:0',
            'discount_type'   => 'required|in:percent,amount',
            'discount_amount' => 'required|numeric|min:0',
            'current_price'   => 'required|numeric|min:0',
            'status'          => 'required|boolean',
            'features'        => 'array',
            'features.*'      => 'nullable|string|max:255',
            'features_icon'   => 'array',
            'features_icon.*' => 'required|boolean',
        ]);

        $subscriptionPackage = SubscriptionPackage::findOrFail($id);

        $subscriptionPackage->update([
            'title'           => $validatedData['title'],
            'name'            => $validatedData['name'],
            'duration'        => $validatedData['duration'],
            'description'     => $validatedData['description'] ?? null,
            'mrp_price'       => $validatedData['mrp_price'],
            'discount_type'   => $validatedData['discount_type'],
            'discount_amount' => $validatedData['discount_amount'],
            'current_price'   => $validatedData['current_price'],
            'status'          => $validatedData['status'],
        ]);

        // Update or create features dynamically
        $existingFeatures = $subscriptionPackage->features()->pluck('id')->toArray();
        $newFeatureIds    = [];

        if (! empty($validatedData['features'])) {
            foreach ($validatedData['features'] as $index => $featureName) {
                if (! empty($featureName)) {
                    $feature = SubscriptionFeature::updateOrCreate(
                        [
                            'subscription_package_id' => $subscriptionPackage->id,
                            'name'                    => $featureName,
                        ],
                        [
                            'icon' => $validatedData['features_icon'][$index] ?? 1, 
                        ]
                    );
                    $newFeatureIds[] = $feature->id;
                }
            }
        }

        // Delete removed features
        SubscriptionFeature::where('subscription_package_id', $subscriptionPackage->id)
            ->whereNotIn('id', $newFeatureIds)
            ->delete();

        return response()->json([
            'message' => 'Subscription Package updated successfully!',
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $subscriptionPackage = SubscriptionPackage::with('features')->findOrFail($id);

        $subscriptionPackage->features()->delete();

        $subscriptionPackage->delete();

        return response()->json([
            'success' => 'Subscription package and its features deleted successfully!',
        ], 200);
    }

    public function destroyAll(Request $request)
    {

        $token = base64_decode($request->get("token"));
        $ids   = json_decode($token, true);

        foreach ($ids as $id) {
            $subscriptionPackage = SubscriptionPackage::with('features')->find($id);

            if ($subscriptionPackage) {

                $subscriptionPackage->features()->delete();

                $subscriptionPackage->delete();
            }
        }

        return response()->json([
            'message' => 'Selected subscription packages and their features deleted successfully!',
        ], 200);
    }

    public function updateStatus(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required|exists:subscription_packages,id',
        ]);

        // Find the subscription package by ID
        $subscriptionPackage = SubscriptionPackage::find($validatedData['id']);

        // Toggle the status
        $subscriptionPackage->status = $subscriptionPackage->status ? 0 : 1;

        // Save the updated status
        $subscriptionPackage->save();

        return response()->json([
            'message' => 'Subscription package status updated successfully!',
            'status'  => $subscriptionPackage->status ? 'Active' : 'Inactive',
        ]);
    }

}
