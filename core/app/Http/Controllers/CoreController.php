<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Firewall;
use App\Models\Permission;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Order;

class CoreController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    //     public function home(Request $request)
    // {
    //     $currentYear = Carbon::now()->year;
    //     $years = [$currentYear, $currentYear - 1, $currentYear - 2];

    //     $salesOrdersData = [];
    //     foreach ($years as $year) {
    //         $salesOrdersData[$year] = $this->getSalesOrdersData($year);
    //     }

    //     // Pass the data to the view
    //     return view('backend.modules.dashboard.home', compact('years', 'salesOrdersData'));
    // }

    public function home(Request $request)
    {
        // $currentYear = date('Y');

        // // Retrieve sales and orders for each month of the current year
        // $monthlySales = Order::selectRaw('MONTH(created_at) as month, SUM(total) as total_sales')
        //     ->whereYear('created_at', $currentYear)
        //     ->groupBy('month')
        //     ->orderBy('month')
        //     ->pluck('total_sales', 'month')
        //     ->toArray();

        // $monthlyOrders = Order::selectRaw('MONTH(created_at) as month, COUNT(*) as total_orders')
        //     ->whereYear('created_at', $currentYear)
        //     ->groupBy('month')
        //     ->orderBy('month')
        //     ->pluck('total_orders', 'month')
        //     ->toArray();

        // // Prepare daily data for each month
        // $dailyData = [];
        // foreach (range(1, 12) as $month) {
        //     $dailyData[$month] = Order::selectRaw('DAY(created_at) as day, SUM(total) as total_sales, COUNT(*) as total_orders')
        //         ->whereYear('created_at', $currentYear)
        //         ->whereMonth('created_at', $month)
        //         ->groupBy('day')
        //         ->orderBy('day')
        //         ->get()
        //         ->mapWithKeys(function ($item) {
        //             return [(int)$item->day => ['sales' => $item->total_sales, 'orders' => $item->total_orders]];
        //         })->toArray();
        // }

        return view('backend.modules.dashboard.home');//, compact('monthlySales', 'monthlyOrders', 'dailyData'));
    }


    public function searchOrders(Request $request)
    {
        $query = $request->input('order_number');

        // Fetch orders that match the order number
        $orders = Order::where('order_number', 'like', '%' . $query . '%')->get(['id', 'order_number']);

        // Define relevant tags based on some logic (e.g., categories, types, etc.)
        $tags = ['Products', 'Sales', 'Customer']; // Replace this with dynamic data as needed

        return response()->json([
            'orders' => $orders,
            'tags' => $tags
        ]);
    }

    private function getSalesOrdersData($year)
    {
        $startDate = Carbon::create($year, 1, 1);
        $endDate = Carbon::create($year, 12, 31);

        return DB::table('orders')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total) as sales'),
                DB::raw('COUNT(*) as orders')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(function ($item) {
                return [
                    'date' => $item->date,
                    'sales' => $item->sales,
                    'orders' => $item->orders,
                ];
            });
    }

    public function user_list(Request $request)
    {

        return view('backend.modules.user.index');
    }

       public function user_list_ajax(Request $request)
    {

        $column = array(
            "id",
            "name",
            "created_at",
        );

        $draw = $request->draw;
        $row = $request->start;
        $rowperpage = $request->length; // Rows display per page

        $columnIndex = $request->order[0]['column']; // Column index
        $columnName = empty($column[$columnIndex]) ? $column[0] : $column[$columnIndex];
        $columnSortOrder = $request->order[0]['dir']; // asc or desc
        $searchValue = $request->search['value']; // Search value

        $totalRecords = $totalDRecords = 0;
        $allData = [];

        if ($searchValue == '') {

            $records = User::where('is_admin',1)->orderBy($columnName, $columnSortOrder)->skip($row)->take($rowperpage)->get();
            $totalRecords_count = User::where('is_admin',1)->count();
            $totalRecords = $totalDRecords = !empty($totalRecords_count) ? $totalRecords_count : 0;
        } else {
            $records = User::where('is_admin', 1)
                ->where(function($query) use ($searchValue) {
                    $query->where('name', 'like', '%' . $searchValue . '%')
                        ->orWhere('phone', 'like', '%' . $searchValue . '%')
                        ->orWhere('username', 'like', '%' . $searchValue . '%')
                        ->orWhere('email', 'like', '%' . $searchValue . '%');
                })
                ->orderBy($columnName, $columnSortOrder)
                ->skip($row)
                ->take($rowperpage)
                ->get();
            
            $totalRecords_count = User::where('is_admin', 1)
                ->where(function($query) use ($searchValue) {
                    $query->where('name', 'like', '%' . $searchValue . '%')
                        ->orWhere('phone', 'like', '%' . $searchValue . '%')
                        ->orWhere('username', 'like', '%' . $searchValue . '%')
                        ->orWhere('email', 'like', '%' . $searchValue . '%');
                })
                ->count();

            $totalRecords = $totalDRecords = !empty($totalRecords_count) ? $totalRecords_count : 0;
        }

        foreach ($records as $key => $single) {
            $data = [];
            $data[] = '<label class="checkboxs">
                                    <input type="checkbox" id="select-all" data-value="' . $single->id . '">
                                    <span class="checkmarks"></span>
                                </label>
                          <span class="checkmarks"></span>
                       </label>';
            $data[] = '<div class="userimgname rounded">
                        <a href="javascript:void(0);" class="userslist-img bg-img">
                            <img src="' . image($single->image) . '" alt="image">
                        </a>
                    </div>';
            $data[] = "<strong>" . $single->name . "</strong>";

            $data[] = "<strong>" . $single->username . "</strong>";

            $data[] = "<strong>" . $single->phone . "</strong>";

            $data[] = '<button type="button" class="btn btn-sm btn-outline-primary rounded-pill text-uppercase">' . $single->role->name ?? 'Unknown' . '</button>';


            $data[] = '<a href="#" class="btn btn-primary" role="button" id="updateUserButton" data-user-id="' . $single->id . '">Click</a>';





            if (!empty($single->last_login)) {
                $daysDifference = Carbon::now()->diffInDays($single->last_login);
            } else {
                $daysDifference = -1;
            }


            // Output the difference
            if ($daysDifference === 0) {
                $daysDifference = '<strong>Today</strong>';
            } elseif ($daysDifference === 1) {
                $daysDifference = '<strong>Yesterday</strong>';
            } elseif ($daysDifference === -1) {
                $daysDifference = '<strong>-------</strong>';
            } else {
                $daysDifference = '<strong>' . $daysDifference . ' days ago</strong>';
            }
            $data[] = $daysDifference;

            if ($single->status == 1) {

                //$data[] = '<span class="badge badge-linesuccess">Active</span>';
                $data[] = '<input type="checkbox" id="user' . $single->id . '" class="check" checked="">
                <label for="user' . $single->id . '" class="checktoggle" onclick="updateStatus(' . $single->id . ')"></label>';
            } else {

                //$data[] = '<span class="badge badge-linedanger">Inactive</span>';
                $data[] = '<input type="checkbox" id="user' . $single->id . '" class="check">
                <label for="user' . $single->id . '" class="checktoggle" onclick="updateStatus(' . $single->id . ')"></label>';
            }

            $action = '<div class="action-table-data">

                            <div class="edit-delete-action">
                                <a class="btn btn-success me-2 p-2 AjaxModal" data-size="md" data-select2="false" title="Profile" data-ajax-modal="' . route("modal.user.profile", encrypt($single->id)) . '">
                                <i  class="fa fa-eye text-white"></i>
                                </a>

                                <a class="btn btn-info me-2 p-2" href="' . route("user.profile.update", encrypt($single->id)) . '">
                                  <i  class="fa fa-edit text-white"></i>
                                </a>

                                <a class="btn btn-warning me-2 p-2 AjaxModal" data-size="xl" data-ajax-modal="' . route("modal.role.user.update", $single->id) . '">
                                   <i  class="fa fa-lock text-white"></i>
                                </a>


                                <a class="btn btn-danger delete-btn p-2" href="' . route("user.delete.ajax", encrypt($single->id)) . '">
                                    <i  class="fa fa-trash text-white"></i>
                                 </a>
                            </div>
                        </div>';

            $data[] = $action;
            $allData[] = $data;
        }

        // Response
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalDRecords,
            "aaData" => $allData
        );
        echo json_encode($response);
    }



    public function updateUserToCustomer(Request $request)
{
    $user = User::find($request->id);

    if ($user) {
        $user->user_role = 2; // Assuming 3 is the role ID for 'customer'
        $user->user_type = 'customer';
        $user->is_admin = 0;
        $user->save();

        return response()->json(['success' => true, 'message' => 'User role updated to customer.']);
    }

    return response()->json(['success' => false, 'message' => 'User not found.'], 404);
}

    public function user_create_ajax(Request $request)
    {

        try {

            $validated = $request->validate([
                'username' => 'required|min:2|max:20|unique:users',
                'phone' => 'required|numeric|regex:/01[2-9]\d{8}$/|unique:users',
                'email' => 'required|email|min:4|unique:users',
                'user_role' => 'required|integer',
                'name' => 'required|min:3|max:50',
                'password' => 'required|min:4|max:30',
                'image' => 'nullable|mimes:jpeg,jpg,png|max:10000',
                'gender' => 'required'
            ]);
            $image_name = "";
            if (isset($request['image'])) {

                $path = "uploads/users/";
                // $imageName = time() . '.webp';
                // $request->image->move($path, $imageName);
                // $image_name = $path . $imageName;
                $image_name = uploadImages($request['image'], $format = "webp", $path, 0, 200);
            }

            $role = Role::whereId($validated['user_role'])->first();
            $permission = null;
            if ($role) {
                $permission = $role->permissions;
            }

            $user = new User();
            $user->username = $validated['username'];
            $user->phone = $validated['phone'];
            $user->email = $validated['email'];
            $user->user_role = $validated['user_role'];
            $user->user_permission = $permission;
            $user->name = $validated['name'];
            $user->password = Hash::make($validated['password']);
            $user->image = $image_name;
            $user->gender = $validated['gender'];
            $user->is_admin = 1;
            $user->status = 1;
            $user->user_type = 'admin';
            $user->save();

            return response()->json(['success' => true, 'message' => 'User created successfully']);
        } catch (Exception $e) {
            // Handle the exception and return an error response
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function user_update(Request $request)
    {

        $user = User::find(decrypt($request->id));
        $roles = Role::all();
        return view('backend.modules.user.update', compact('user', 'roles'));
    }

    public function user_update_ajax(Request $request)
    {

        try {

            $validated = $request->validate([
                'username' => 'required|min:2|max:20|unique:users,username,' . $request->id,
                'phone' => 'required|numeric|regex:/01[2-9]\d{8}$/|unique:users,phone,' . $request->id,
                'email' => 'required|email|min:4|unique:users,email,' . $request->id,
                'user_role' => 'required|integer',
                'name' => 'required|min:3|max:50',
                'password' => 'nullable|min:4|max:30',
                'image' => 'nullable|mimes:jpeg,jpg,png|max:10000',
                'gender' => 'required'
            ]);




            $role = Role::whereId($validated['user_role'])->first();
            $permission = null;
            if ($role) {
                $permission = $role->permissions;
            }

            $user = User::find($request->id);
            $user->username = $validated['username'];
            $user->phone = $validated['phone'];
            $user->email = $validated['email'];
            $user->user_role = $validated['user_role'];
            if ($user->user_permission != $validated['user_role']) {
                $user->user_permission = $permission;
            }

            $user->name = $validated['name'];

            if (!empty($request->password)) {
                $user->password = Hash::make($request->password);
            }

            if (isset($request['image'])) {

                $path = "uploads/users/";
                $image_name = uploadImages($request['image'], $format = "webp", $path, 0, 200);
                $user->image = $image_name;
            }


            $user->gender = $validated['gender'];
            $user->is_admin = 1;
            $user->status = 1;
            $user->user_type = 'admin';
            $user->save();

            return response()->json(['success' => true, 'message' => 'User created successfully']);
        } catch (Exception $e) {
            // Handle the exception and return an error response
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function user_delete_ajax(Request $request)
    {

        try {
            $id = decrypt($request->id);
            $user = User::find($id);
            if ($user) {
                $user->delete();
                return response()->json(['success' => true, 'message' => 'User deleted successfully']);
            }
            return response()->json(['success' => false, 'message' => 'No user found']);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Invalid User']);
        }
    }
    public function user_delete_all_ajax(Request $request)
    {
        $allIds = base64_decode($request->token);
        User::whereIn('id', json_decode($allIds))->delete();
        return response()->json(['message' => 'Selected users deleted successfully']);
    }
    public function user_status(Request $request)
    {

        $user = User::find($request->id);
        if ($user->status == 1) {
            $user->status = 0;
        } else {
            $user->status = 1;
        }
        $user->save();

        return response()->json(['message' => 'Successfully Updated']);
    }



    public function role(Request $request)
    {

        return view('backend.modules.role.index');
    }

    public function role_ajax(Request $request)
    {

        $column = array(
            "id",
            "name",
            "created_at",
        );

        $draw = $request->draw;
        $row = $request->start;
        $rowperpage = $request->length; // Rows display per page

        $columnIndex = $request->order[0]['column']; // Column index
        $columnName = empty($column[$columnIndex]) ? $column[0] : $column[$columnIndex];
        $columnSortOrder = $request->order[0]['dir']; // asc or desc
        $searchValue = $request->search['value']; // Search value

        $totalRecords = $totalDRecords = 0;
        $allData = [];

        if ($searchValue == '') {

            $records = Role::orderBy($columnName, $columnSortOrder)->skip($row)->take($rowperpage)->get();
            $totalRecords_count = Role::count();
            $totalRecords = $totalDRecords = !empty($totalRecords_count) ? $totalRecords_count : 0;
        } else {
            $records = Role::where('name', 'like', '%' . $searchValue . '%')
                ->orderBy($columnName, $columnSortOrder)->skip($row)->take($rowperpage)->get();

            $totalRecords_count = Role::where('name', 'like', '%' . $searchValue . '%')
                ->count();

            $totalRecords = $totalDRecords = !empty($totalRecords_count) ? $totalRecords_count : 0;
        }

        foreach ($records as $key => $single) {
            $data = [];
            $data[] = '<label class="checkboxs">
                                    <input type="checkbox" id="select-all" data-value="' . $single->id . '">
                                    <span class="checkmarks"></span>
                                </label>
                          <span class="checkmarks"></span>
                       </label>';
            $data[] = "<strong class='badge bg-danger'>" . $single->name . "</strong>";
            if (empty($single->permissions)) {
                $data[] = "<strong class='badge bg-purple'>NULL</strong>";
            } else {
                // $perm = explode(",", $single->permissions);
                // $p = '';
                // $a = [];
                // foreach ($perm as $singlePerm) {
                //     $a[] = Role::getPermissionNameById($singlePerm);
                //     //$p.='<span class="badges bg-purple mx-1">'.Role::getPermissionNameById($singlePerm).'</span>';
                // }
                // // dd($a);
                // $collection = collect($a);
                // $grouped = $collection->mapToGroups(function ($item) {
                //     list($prefix, $suffix) = explode('-', $item, 2);
                //     return [$prefix => $suffix];
                // });
                // $output = $grouped->map(function ($suffixes, $prefix) {
                //     $suffixString = $suffixes->implode('|');
                //     return "<span class='badges bg-purple mx-1'>{$prefix}({$suffixString})</span>";
                // })->implode(' ');
                $data[] = "<strong class='badge bg-info'>Custom Permission</strong>";
            }

            $action = '<div class="action-table-data">

                            <div class="edit-delete-action">
                                <a class="btn btn-warning me-2 p-2 AjaxModal" data-size="xl" data-select2="false"  data-ajax-modal="' . route("modal.role.update", ($single->id)) . '">
                                   <i  class="fa fa-lock text-white"></i>
                                </a>
                                <a class="btn btn-danger delete-btn p-2" href="' . route("role.delete.ajax", encrypt($single->id)) . '">
                                    <i  class="fa fa-trash text-white"></i>
                                 </a>
                            </div>
                        </div>';

            $data[] = $action;
            $allData[] = $data;
        }

        // Response
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalDRecords,
            "aaData" => $allData
        );
        echo json_encode($response);
    }

    function new_role(Request $request)
    {

        $role = new Role();
        $role->name = $request->name;
        $role->permissions = implode(",", $request->permissions);

        $role->save();

        return response()->json(['success' => true, 'message' => 'Role created successfully']);
    }

    function update_role(Request $request)
    {


        $role = Role::find($request->id);
        $role->name = $request->name;
        $role->permissions = implode(",", $request->permissions);

        $role->save();

        return response()->json(['success' => true, 'message' => 'Role updated successfully']);
    }

    function role_delete(Request $request)
    {
        try {
            $id = decrypt($request->id);
            $role = Role::find($id);
            if ($role->id == 1) {
                return response()->json(['success' => false, 'message' => 'Cannot delete this role']);
            }
            if ($role) {
                $role->delete();
                return response()->json(['success' => true, 'message' => 'Deleted successfully']);
            }
            return response()->json(['success' => false, 'message' => 'No Role found']);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Invalid Role']);
        }
    }


    function permission(Request $request)
    {

        // Specify the path to the directory you want to scan
        $path = resource_path('views/backend/modules');

        // Get all folders in the specified directory
        $folders = File::directories($path);

        // Initialize an array to store formatted folder names
        $folderNames = [];

        // Loop through each folder and extract the base name
        foreach ($folders as $folder) {
            $name = basename($folder); // Get the base name of the directory
            $name = str_replace('_', ' ', $name); // Replace underscores with spaces
            $name = ucwords($name); // Capitalize each word
            $folderNames[] = $name; // Add the formatted name to the array
        }

        return view('backend.modules.permission.index', compact('folderNames'));
    }

    public function permission_ajax(Request $request)
    {

        $column = array(
            "module"
        );

        $draw = $request->draw;
        $row = $request->start;
        $rowperpage = $request->length; // Rows display per page

        $columnIndex = $request->order[0]['column']; // Column index
        $columnName = empty($column[$columnIndex]) ? $column[0] : $column[$columnIndex];
        $columnSortOrder = $request->order[0]['dir']; // asc or desc
        $searchValue = $request->search['value']; // Search value

        $totalRecords = $totalDRecords = 0;
        $allData = [];

        if ($searchValue == '') {

            $records = Permission::orderBy($columnName, $columnSortOrder)->skip($row)->take($rowperpage)->get();
            $totalRecords_count = Permission::count();
            $totalRecords = $totalDRecords = !empty($totalRecords_count) ? $totalRecords_count : 0;
        } else {
            $records = Permission::where('module', 'like', '%' . $searchValue . '%')
                ->orderBy($columnName, $columnSortOrder)->skip($row)->take($rowperpage)->get();

            $totalRecords_count = Permission::where('module', 'like', '%' . $searchValue . '%')
                ->count();

            $totalRecords = $totalDRecords = !empty($totalRecords_count) ? $totalRecords_count : 0;
        }

        foreach ($records as $key => $single) {
            $data = [];
            $data[] = '<label class="checkboxs">
                                    <input type="checkbox" id="select-all" data-value="' . $single->id . '">
                                    <span class="checkmarks"></span>
                                </label>
                          <span class="checkmarks"></span>
                       </label>';
            $data[] = "<strong class='badge bg-purple'>" . $single->module . "</strong>";
            $data[] = "<strong class='badge bg-info'>$single->name</strong>";



            $slug = "";
            $urls = explode(",", $single->slug);
            foreach ($urls as $key => $value) {
                $slug .= "<span class='btn btn-sm btn-outline-danger m-1'>$value</span>";
            }
            $data[] = $slug;


            $action = '<div class="action-table-data">

                            <div class="edit-delete-action">
                                <a class="btn btn-warning me-2 p-2" href="' . route("permission.update", ($single->id)) . '">
                                   <i  class="fa fa-edit text-white"></i>
                                </a>
                                <a class="btn btn-danger delete-btn p-2" href="' . route("permission.delete.ajax", encrypt($single->id)) . '">
                                    <i  class="fa fa-trash text-white"></i>
                                 </a>
                            </div>
                        </div>';

            $data[] = $action;
            $allData[] = $data;
        }

        // Response
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalDRecords,
            "aaData" => $allData
        );
        echo json_encode($response);
    }


    function search_route(Request $request)
    {
        $search = empty($request->q) ? "" : $request->q;
        $select2Json = [];

        $route_name = [];

        foreach (Route::getRoutes()->getRoutes() as $route) {
            $action = $route->uri();
            if (str_contains($action, 'backend'))
                $route_name[] = $action;
        }


        if (!empty($route_name)) {

            foreach ($route_name as $url) {

                if (str_contains($url, $search))
                    $select2Json[] = array(
                        'id' => $url,
                        'text' => $url
                    );
            }
        }
        echo html_entity_decode(json_encode($select2Json));
    }

    function new_permission(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required',
            'module' => 'required'
        ]);
        //dd($request->all());

        $permission = new Permission();

        $permission->name = $validated['name'];
        $permission->module = $validated['module'];
        $permission->slug = implode(',', $request->routes);
        $permission->save();
        return response()->json(['success' => true, 'message' => 'Permission created Successfully']);
    }

    public function permission_delete(Request $request)
    {

        try {
            $id = decrypt($request->id);
            $permission = Permission::find($id);
            if ($permission) {
                $permission->delete();
                return response()->json(['success' => true, 'message' => 'Permission deleted successfully']);
            }
            return response()->json(['success' => false, 'message' => 'Invalid']);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Invalid Data']);
        }
    }
    public function permission_delete_ajax(Request $request)
    {
        $allIds = base64_decode($request->token);
        Permission::whereIn('id', json_decode($allIds))->delete();
        return response()->json(['message' => 'Selected permission deleted successfully']);
    }
    public function update_permission(Request $request)
    {
        $id = $request->id;
        $permission = Permission::find($id);
        return view('backend.modules.permission.update', compact('permission'));
    }
    public function update_permission_ajax(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required',
            'module' => 'required'
        ]);
        //dd($request->all());

        $permission = Permission::find($request->id);

        $permission->name = $validated['name'];
        $permission->module = $validated['module'];
        $permission->slug = implode(',', $request->routes);
        $permission->save();
        return response()->json(['success' => true, 'message' => 'Permission updated Successfully']);
    }


    public function user_permission_update(Request $request)
    {


        $user = User::find($request->id);
        $user->user_permission = implode(',', $request->permissions);
        $user->save();
        return response()->json(['success' => true, 'message' => 'Permission updated Successfully']);
    }

    public function my_profile(Request $request)
    {
        return view('backend.modules.user.myprofile');
    }



    public function my_profile_ajax(Request $request)
    {

        try {

            $validated = $request->validate([
                'username' => 'required|min:2|max:20|unique:users,username,' . Auth::user()->id,
                'phone' => 'required|numeric|regex:/01[2-9]\d{8}$/|unique:users,phone,' . Auth::user()->id,
                'email' => 'required|email|min:4|unique:users,email,' . Auth::user()->id,

                'name' => 'required|min:3|max:50',
                'password' => 'nullable|min:4|max:30',
                'image' => 'nullable|mimes:jpeg,jpg,png|max:10000',
                'address' => 'nullable'
            ]);



            $user = User::find(Auth::user()->id);
            $user->username = $validated['username'];
            $user->phone = $validated['phone'];
            $user->email = $validated['email'];
            $user->name = $validated['name'];
            if (!empty($request->password)) {
                $user->password = Hash::make($request->password);
            }

            if (isset($request['image'])) {
                $path = "uploads/users/";
                $image_name = uploadImages($request['image'], $format = "webp", $path, 0, 200);
                $user->image = $image_name;
            }

            $user->address = $validated['address'];
            //$user->is_admin = 1;
            //$user->status = 1;
            //$user->user_type = 'admin';
            $user->save();

            return response()->json(['success' => true, 'message' => 'Profile updated successfully']);
        } catch (Exception $e) {
            // Handle the exception and return an error response
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function firewall(Request $request)
    {

        return view('backend.modules.firewall.index');
    }

    public function firewall_ajax(Request $request)
    {

        $column = array(
            "id",
            "created_at",
        );

        $draw = $request->draw;
        $row = $request->start;
        $rowperpage = $request->length; // Rows display per page

        $columnIndex = $request->order[0]['column']; // Column index
        $columnName = empty($column[$columnIndex]) ? $column[0] : $column[$columnIndex];
        $columnSortOrder = $request->order[0]['dir']; // asc or desc
        $searchValue = $request->search['value']; // Search value

        $totalRecords = $totalDRecords = 0;
        $allData = [];

        if ($searchValue == '') {

            $records = Firewall::orderBy($columnName, $columnSortOrder)->skip($row)->take($rowperpage)->get();
            $totalRecords_count = Firewall::count();
            $totalRecords = $totalDRecords = !empty($totalRecords_count) ? $totalRecords_count : 0;
        } else {
            $records = Firewall::where('ip_address', 'like', '%' . $searchValue . '%')
                ->orderBy($columnName, $columnSortOrder)->skip($row)->take($rowperpage)->get();

            $totalRecords_count = Firewall::where('ip_address', 'like', '%' . $searchValue . '%')
                ->count();

            $totalRecords = $totalDRecords = !empty($totalRecords_count) ? $totalRecords_count : 0;
        }

        foreach ($records as $key => $single) {
            $data = [];
            $data[] = "<strong class='badge bg-danger'>" . $single->ip_address . "</strong>";
            if ($single->type == "White_listed") {
                $data[] = "<strong class='badge bg-success'>White Listed</strong>";
            } else {
                $data[] = "<strong class='badge bg-danger'>Black Listed</strong>";
            }
            $data[] = $single->comments;
            $data[] = $single->created_at;
            $action = '<div class="action-table-data">

                            <div class="edit-delete-action">

                                <a class="btn btn-danger delete-btn p-2" href="' . route("firewall.delete.ajax", encrypt($single->id)) . '">
                                    <i  class="fa fa-trash text-white"></i>
                                 </a>
                            </div>
                        </div>';

            $data[] = $action;
            $allData[] = $data;
        }

        // Response
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalDRecords,
            "aaData" => $allData
        );
        echo json_encode($response);
    }


    public function new_firewall(Request $request)
    {

        try {

            $validated = $request->validate([
                'ip_address' => 'required|ip|unique:firewall',
                'type' => 'required',
                'comments' => 'required',
            ]);

            $firewall = new Firewall();
            $firewall->ip_address = $request->ip_address;
            $firewall->type = $request->type;
            $firewall->comments = $request->comments;
            $firewall->save();

            return response()->json(['success' => true, 'message' => 'Created successfully']);
        } catch (Exception $e) {
            // Handle the exception and return an error response
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }


    public function firewall_delete(Request $request)
    {
        $id = decrypt($request->id);
        Firewall::whereId($id)->delete();
        return response()->json(['success' => true, 'message' => 'Deleted successfully']);
    }
    
    public function getOrderNumbers(Request $request)
    {


        $orders = Order::whereNotIn('order_status_id', [5, 6, 7])->limit(100)->pluck('order_number'); // Customize this query as needed

        return response()->json(['orders' => $orders]);

    }

    public function updateOrderStatus(Request $request)
    {
        $provider = 'steadfast';  // or dynamic based on the request
        $orderNumbers = $request->order_numbers; // Array of order numbers
        $results = [];

        foreach ($orderNumbers as $order_number) {
            $status =$this->OrderDataUpdateByCourier($provider, $order_number);


            $results[] = [
                'order_number' => $order_number,
                'status' => $status
            ];

            //update

            if($status != '404'){
                
                $order= Order::where('order_number',$order_number)->first();
                
                if($status == 'delivered'){
                    $order->order_status_id=7;
                    $order->payment_status='paid';
                    $data=$order->save();
                    //dd($data);
                }
                else if($status=='partial_delivered'){
                    $order->order_status_id=6;
                    //$order->payment_status='partial';
                    $order->save();
                }
                else if($status=='cancelled'){
                    $order->order_status_id=5;
                    $order->save();
                }
                else{

                    $order->order_status_id=4;
                    $order->save();
                }

            }

            
        }

        return response()->json(['data' => $results]);
    }

    function OrderDataUpdateByCourier($provider, $order_number)
    {
        if ($provider == 'steadfast') {
            $url = 'https://portal.steadfast.com.bd/api/v1/status_by_invoice';
            //$url='https://portal.steadfastcourier.com/api/v1/status_by_invoice';

            $full_url = $url . '/' . $order_number;

            $curl = curl_init($full_url);
            curl_setopt($curl, CURLOPT_URL, $full_url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

            $headers = array(
                "Api-Key:5bijdoyciinn8x7vjxjz1shp7bbtic3i",
                "Secret-Key:vr5kcbesczbt6tog1hiicbmm",
                "Content-Type:application/json",
            );
            curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

            $result = curl_exec($curl); // run!

            if (!empty(json_decode($result))) {
                $result = json_decode($result);
            } else {
                $data = [
                    'status' => 404,
                    'delivery_status' => '404',
                ];

                $result = json_encode($data);
                $result = json_decode($result);
            }
        }

        if (!empty($result)) {
            return $result->delivery_status;
        } else {
            return 404;
        }
    }
}
