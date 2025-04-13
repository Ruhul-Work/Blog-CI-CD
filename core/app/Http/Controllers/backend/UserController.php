<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Common;
use App\Models\Role;
use App\Models\User;
use App\Models\Variant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Exception;

class UserController extends Controller
{
    public function customerIndex()
    {
        $userTypes =Common::getPossibleEnumValues('users', 'user_type');
        $role=Role::all();
        return view('backend.modules.customer.index', ['userTypes'=>$userTypes,'role'=>$role]);
    }

    public function customerAjaxIndex(Request $request)
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
            $records = User::where('user_type', '=', 'customer')
                ->orderBy($columnName, $columnSortOrder)
                ->skip($row)
                ->take($rowperpage)
                ->get();
            $totalRecords_count = User::where('user_type', '=', 'customer')->count();
            $totalRecords = $totalDRecords = !empty($totalRecords_count) ? $totalRecords_count : 0;

        } else {

            $query = User::where('user_type', '=', 'customer')
                ->where(function($query) use ($searchValue) {
                    $query->where('name', 'like', '%' . $searchValue . '%')
                        ->orWhere('phone', 'like', '%' . $searchValue . '%')
                        ->orWhere('username', 'like', '%' . $searchValue . '%')
                        ->orWhere('email', 'like', '%' . $searchValue . '%');
                });

            $records = $query->orderBy($columnName, $columnSortOrder)
                ->skip($row)
                ->take($rowperpage)
                ->get();
            $totalRecords = $query->count();
            $totalDRecords = $totalRecords;
        }
        foreach ($records as $key => $user) {
            $data = [];
            $data[] = '<td>
            <label class="checkboxs">
                <input type="checkbox" class="checked-row" data-value="' . $user->id . '">
                <span class="checkmarks"></span>
            </label>
        </td>';
            $data[] = '<div class="userimgname rounded">
                        <a href="javascript:void(0);" class="userslist-img bg-img">
                            <img src="' . image($user->image) . '" alt="image">
                        </a>
                    </div>';
            $data[] = "<strong>" . $user->name . "</strong>";
            $data[] = "<strong>" . $user->phone . "</strong>";
            // $data[] = '<button type="button" class="btn btn-sm btn-outline-primary rounded-pill text-uppercase">' . $user->role->name ?? 'Unknown' . '</button>';
            $daysDifference = Carbon::now()->diffInDays($user->last_login);
            // Output the difference
            if ($daysDifference === 0) {
                $daysDifference = '<strong>Today</strong>';
            } elseif ($daysDifference === 1) {
                $daysDifference = '<strong>Yesterday</strong>';
            } else {
                $daysDifference = '<strong>' . $daysDifference . ' days ago</strong>';
            }
            $data[] = $daysDifference;

            $data[] = '<span  class=" badge rounded-pill bg-soft-success make-admin" data-user-id="' . $user->id . '" style="cursor:pointer;">Make Admin</span>';

            $data[] = '<span class="badge changeStatus ' . ($user->status == 1 ? 'badge-linesuccess' : 'badge-linedanger') . '  " style="cursor:pointer;" data-user-id="' . $user->id . '">' . ($user->status == 1 ? 'Active' : 'Inactive') . '</span>';


            $action = '<div class="action-table-data">

                            <div class="edit-delete-action">
                                 <a class="btn btn-info me-2 p-2 openEditModal" href="' . route('users.edit', $user->id) . '">
                    <i  class="fa fa-edit text-white"></i>
                </a>
                                <a class="btn btn-danger delete-btn p-2 " href="' . route('users.destroy', $user->id) . '">
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

    public function shopIndex()
    {
        $userTypes =Common::getPossibleEnumValues('users', 'user_type');

        $role=Role::all();
        return view('backend.modules.shop.index', ['userTypes'=>$userTypes,'role'=>$role]);
    }

    public function shopAjaxIndex(Request $request)
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
            $records = User::where('user_type', '=', 'shop')
                ->orderBy($columnName, $columnSortOrder)
                ->skip($row)
                ->take($rowperpage)
                ->get();
            $totalRecords_count = User::where('user_type', '=', 'shop')->count();
            $totalRecords = $totalDRecords = !empty($totalRecords_count) ? $totalRecords_count : 0;

        } else {

            $query = User::where('user_type', '==', 'shop')
                ->where(function($query) use ($searchValue) {
                    $query->where('name', 'like', '%' . $searchValue . '%')
                        ->orWhere('phone', 'like', '%' . $searchValue . '%')
                        ->orWhere('username', 'like', '%' . $searchValue . '%')
                        ->orWhere('email', 'like', '%' . $searchValue . '%');
                });

            $records = $query->orderBy($columnName, $columnSortOrder)
                ->skip($row)
                ->take($rowperpage)
                ->get();


            $totalRecords = $query->count();
            $totalDRecords = $totalRecords;
        }


        foreach ($records as $key => $user) {
            $data = [];

            $data[] = '<td>
            <label class="checkboxs">
                <input type="checkbox" class="checked-row" data-value="' . $user->id . '">
                <span class="checkmarks"></span>
            </label>
        </td>';
            $data[] = '<div class="userimgname rounded">
                        <a href="javascript:void(0);" class="userslist-img bg-img">
                            <img src="' . image($user->image) . '" alt="image">
                        </a>
                    </div>';
            $data[] = "<strong>" . $user->name . "</strong>";

            $data[] = "<strong>" . $user->phone . "</strong>";


            $data[] = '<button type="button" class="btn btn-sm btn-outline-primary rounded-pill text-uppercase">' . $user->role->name ?? 'Unknown' . '</button>';

            $daysDifference = Carbon::now()->diffInDays($user->last_login);

            // Output the difference
            if ($daysDifference === 0) {
                $daysDifference = '<strong>Today</strong>';
            } elseif ($daysDifference === 1) {
                $daysDifference = '<strong>Yesterday</strong>';
            } else {
                $daysDifference = '<strong>' . $daysDifference . ' days ago</strong>';
            }

            $data[] = $daysDifference;
            $data[] = '<span class="badge changeStatus ' . ($user->status == 1 ? 'badge-linesuccess' : 'badge-linedanger') . '  " style="cursor:pointer;" data-user-id="' . $user->id . '">' . ($user->status == 1 ? 'Active' : 'Inactive') . '</span>';


            $action = '<div class="action-table-data">

                            <div class="edit-delete-action">
                                 <a class="btn btn-info me-2 p-2 openEditModal" href="' . route('shop.edit', $user->id) . '">
                    <i  class="fa fa-edit text-white"></i>
                </a>


                                <a class="btn btn-danger delete-btn p-2 " href="' . route('users.destroy', $user->id) . '">
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

   public function store(Request $request)
    {
        try {

            $validated = $request->validate([
                'name' => 'required|min:3|max:50',
                'phone' => 'required|numeric|regex:/01[2-9]\d{8}$/|unique:users',
                'email' => 'nullable|email|min:4|unique:users',
                // 'user_type' => ['required', function ($attribute, $value, $fail) {
                //     if ($value === 'admin') {

                //         $fail('The ' . $attribute . ' cannot be admin.Try to add different user');
                //     }
                // }],
                // 'user_role' => 'nullable|integer',

                'password' => 'required|min:4|max:30',
                'image' => 'nullable|mimes:jpeg,jpg,png|max:10000',
                'gender' => 'nullable'
            ]);


            $imagePath = "";

            if ($request->hasFile('image')) {
                $imagePath = uploadImage($request->file('image'), 'user/images');
            }

            $user = new User();
            $user->name = $validated['name'];
            $user->phone = $validated['phone'];
            $user->email = $validated['email'];
            $user->user_role = 3;
            $user->password = Hash::make($validated['password']);
            $user->image = $imagePath;
            $user->gender = $validated['gender'];
            $user->is_admin = 0;
            $user->status = 1;
            $user->user_type ='Customer';
            $user->save();

            return response()->json(['success' => true, 'message' => 'User created successfully']);

        } catch (Exception $e) {
            // Handle the exception and return an error response
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }

    }
   public function storeShop(Request $request)
    {
        try {

            $validated = $request->validate([
                'name' => 'required|min:3|max:50',
                'phone' => 'required|numeric|regex:/01[2-9]\d{8}$/|unique:users',
                'email' => 'nullable|email|min:4|unique:users',
                // 'user_type' => ['required', function ($attribute, $value, $fail) {
                //     if ($value === 'admin') {

                //         $fail('The ' . $attribute . ' cannot be admin.Try to add different user');
                //     }
                // }],
                // 'user_role' => 'nullable|integer',

                'password' => 'required|min:4|max:30',
                'image' => 'nullable|mimes:jpeg,jpg,png|max:10000',
                'gender' => 'nullable'
            ]);


            $imagePath = "";

            if ($request->hasFile('image')) {
                $imagePath = uploadImage($request->file('image'), 'user/images');
            }

            $user = new User();
            $user->name = $validated['name'];
            $user->phone = $validated['phone'];
            $user->email = $validated['email'];
            $user->user_role = 3;
            $user->password = Hash::make($validated['password']);
            $user->image = $imagePath;
            $user->gender = $validated['gender'];
            $user->is_admin = 0;
            $user->status = 1;
            $user->user_type ='shop';
            $user->save();

            return response()->json(['success' => true, 'message' => 'Shop created successfully']);

        } catch (Exception $e) {
            // Handle the exception and return an error response
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }

    }

    public function edit($id)
    {

        $user =User::findOrFail($id);
        $role=Role::all();
        $userTypes =Common::getPossibleEnumValues('users', 'user_type');

        if ($user ) {
            return response()->json([
                'html' => view('backend.modules.customer.edit', ['user'=> $user,'userTypes'=>$userTypes,'role'=>$role ])->render(),
            ]);
        } else {
            return response()->json(['error' => 'Variant   not found'], 404);
        }
    }
    public function shopEdit($id)
    {

        $user =User::findOrFail($id);
        $role=Role::all();
        $userTypes =Common::getPossibleEnumValues('users', 'user_type');

        if ($user ) {
            return response()->json([
                'html' => view('backend.modules.shop.edit', ['user'=> $user,'userTypes'=>$userTypes,'role'=>$role ])->render(),
            ]);
        } else {
            return response()->json(['error' => 'user   not found'], 404);
        }
    }

     public function update(Request $request)
    {

           $id=$request->id;
            $validated = $request->validate([
                'name' => 'required|min:3|max:50',
                'phone' => 'required|numeric|regex:/01[2-9]\d{8}$/|unique:users,phone,' . $id,
                'email' => 'nullable|email|min:4|unique:users,email,' . $id,
                // 'user_type' => ['required', function ($attribute, $value, $fail) {
                //     if ($value === 'admin') {
                //         $fail('The ' . $attribute . ' cannot be admin. Try to add a different user.');
                //     }
                // }],
                // 'user_role' => 'required|integer',
                'password' => 'nullable|min:4|max:30',
                'image' => 'nullable|mimes:jpeg,jpg,png|max:10000',
                'gender' => 'nullable|string|in:male,female'
            ]);
            $user = User::findOrFail($id);
            $user->name = $validated['name'];
            $user->phone = $validated['phone'];
            $user->email = $validated['email'];
            $user->user_role =3;
            $user->password = Hash::make($validated['password']);
            $previousImage = $user->image;
            if ($request->hasFile('image')) {
                $imagePath = uploadImage($request->file('image'), 'user/images');

                $user->image = $imagePath;

                if ($previousImage&& file_exists($previousImage)) {
                    unlink($previousImage);
                }
            }
            $user->gender = $validated['gender'];
            $user->is_admin = 0;
            $user->status = 1;
            $user->user_type ='Customer';
            $user->save();
           return response()->json(['success' => true, 'message' => 'Updated successfully']);
    }
     public function shopUpdate(Request $request)
    {

           $id=$request->id;
            $validated = $request->validate([
                'name' => 'required|min:3|max:50',
                'phone' => 'required|numeric|regex:/01[2-9]\d{8}$/|unique:users,phone,' . $id,
                'email' => 'nullable|email|min:4|unique:users,email,' . $id,
                // 'user_type' => ['required', function ($attribute, $value, $fail) {
                //     if ($value === 'admin') {
                //         $fail('The ' . $attribute . ' cannot be admin. Try to add a different user.');
                //     }
                // }],
                // 'user_role' => 'required|integer',
                'password' => 'nullable|min:4|max:30',
                'image' => 'nullable|mimes:jpeg,jpg,png|max:10000',
                'gender' => 'nullable|string|in:male,female'
            ]);
            $user = User::findOrFail($id);
            $user->name = $validated['name'];
            $user->phone = $validated['phone'];
            $user->email = $validated['email'];
            $user->user_role =3;
            $user->password = Hash::make($validated['password']);
            $previousImage = $user->image;
            if ($request->hasFile('image')) {
                $imagePath = uploadImage($request->file('image'), 'user/images');

                $user->image = $imagePath;

                if ($previousImage&& file_exists($previousImage)) {
                    unlink($previousImage);
                }
            }
            $user->gender = $validated['gender'];
            $user->is_admin = 0;
            $user->status = 1;
            $user->user_type ='shop';
            $user->save();
           return response()->json(['success' => true, 'message' => 'Updated successfully']);
    }


    public function destroy($id)
    {
        $user =User::find($id);


        if ($user) {
            $user->delete();
            return response()->json(['success' => true, 'message' => 'Deleted successfully']);
        } else {
             return response()->json(['success' => false, 'message' => 'Profile not found']);
        }
    }


    public function destroyAll(Request $request)
    {
        $token = base64_decode($request->get("token"));
        $ids = json_decode($token);


        foreach ($ids as $id) {
            $user = User::find($id);
            if ($user) {
                dd($user);
                $user->delete();
            }
        }

        return response()->json(['success' => true, 'message' => 'Deleted successfully']);
    }



    public function updateStatus(Request $request)
    {
        $id = $request->id;

        $user = User::findOrFail($id);

        // Toggle the status
        $user->status = $user->status == 1 ? 0 : 1;

        $user->save();

       return response()->json(['success' => true, 'message' => 'Updated successfully']);
    }



    public function makeAdmin(Request $request)
    {
        $userId = $request->input('id');

        try {
            $user = User::findOrFail($userId);
            $user->user_type = 'admin';
            $user->is_admin = 1;
            $user->user_role= $request->user_role;
            $user->save();

            return response()->json(['success' => true, 'message' => 'User has been made an admin.']);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to make user an admin.']);
        }
    }

}
