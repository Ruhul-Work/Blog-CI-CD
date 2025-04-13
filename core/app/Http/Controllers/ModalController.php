<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;

class ModalController extends Controller
{

  public function new_user(Request $request)
  {
    $role = Role::all();
    return view('backend.modules.user.create', compact('role'));

  }

  public function new_firewall(Request $request)
  {
    
    return view('backend.modules.firewall.create');

  }

  public function profile(Request $request)
  {
    $user = User::find(decrypt($request->id));
    if ($user && $user->user_type == "admin") {
      return view('backend.modules.user.profile', compact('user'));
    } else {
      abort(404);
    }
  }

  public function new_role(Request $request)
  {
    $permissions = Permission::orderBy('module')
      ->orderBy('name')
      ->get()
      ->groupBy('module');
    return view('backend.modules.role.create', compact('permissions'));
  }

  public function update_role(Request $request)
  {
    $permissions = Permission::orderBy('module')
      ->orderBy('name')
      ->get()
      ->groupBy('module');
    $role = Role::find($request->id);

    return view('backend.modules.role.update', compact('permissions', 'role'));
  }

  public function update_user_role(Request $request)
  {
     $permissions = Permission::orderBy('module')
      ->orderBy('name')
      ->get()
      ->groupBy('module');
      $user = User::find($request->id);

     

    return view('backend.modules.user.user-update', compact('permissions', 'user'));
  }


}
