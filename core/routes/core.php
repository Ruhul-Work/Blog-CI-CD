<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\CoreController;
use App\Http\Controllers\ModalController;
use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| Core Routes
|--------------------------------------------------------------------------
|
| Define routes for backend login, recovery, and logout functionalities here.
  These routes are loaded by the RouteServiceProvider within the "web" middleware group.
  Now create something great!
|
*/

/* Backend Login Route */
Route::get('/cp', function () {
  return view('auth.layouts.login');
})->middleware('guest')->name("backend.login");

Route::post('/do-login', [LoginController::class, 'login'])->middleware('guest')->name("backend.login.action");
//remaining work
/* Backend Recovery Route */
Route::get('/forget-password', function () {
  //abort(403);
  return view('auth.layouts.forget');
})->middleware('guest')->name("backend.forget.password");
Route::post('/forget-password', [LoginController::class, 'forget'])->middleware('guest')->name("backend.forget.password.action");

Route::post('/verify-forget-password', [LoginController::class, 'verify_forget'])->middleware('guest')->name("backend.forget.password.verify");

Route::get('/backend-logout', [LoginController::class, 'logout'])->middleware('auth')->name("backend.logout");

Route::post('/ck-uploader', function (Request $request) {
  //dd($request->upload);
  $image_name = uploadImage($request->upload, "blogs");
  //work pending
  //$url = asset('theme/common/no_image.jpg');
  //return response()->json(['fileName' => "Bintel", 'uploaded' => 1, 'url' => get_option("site_url").'/'.$image_name]);
  
  return response()->json([
    'uploaded' => true,               // Must be boolean: true
    'fileName' => "image",           // Optional, the file name (not strictly required)
    'url' => get_option("site_url") . '/' . $image_name // Full URL to the uploaded image
]);


})->name("ck.upload");

//Core Route

Route::prefix('backend')->middleware(['auth', 'admin', 'HasAccess'])->group(function () {


  Route::get('/dashboard', [CoreController::class, 'home'])->name('dash.home');

  Route::get('/profile', [CoreController::class, 'my_profile'])->name('my.profile');
  Route::post('/profile', [CoreController::class, 'my_profile_ajax'])->name('my.profile.ajax');
  Route::get('/route/search', [CoreController::class, 'searchOrders'])->name('quick.search.orders');


  Route::prefix('users')->group(function () {
    Route::get('/', [CoreController::class, 'user_list'])->name('user.list');
    Route::post('/', [CoreController::class, 'user_list_ajax'])->name('user.list.ajax');
    Route::post('/create', [CoreController::class, 'user_create_ajax'])->name('user.create.ajax');
    Route::get('/delete/{id}', [CoreController::class, 'user_delete_ajax'])->name('user.delete.ajax');
    Route::get('/delete-all', [CoreController::class, 'user_delete_all_ajax'])->name('user.delete.all.ajax');
    Route::get('/status', [CoreController::class, 'user_status'])->name('user.status');
    Route::get('/update/{id}', [CoreController::class, 'user_update'])->name('user.profile.update');
    Route::post('/update', [CoreController::class, 'user_update_ajax'])->name('user.profile.update.ajax');
    
    Route::post('/to-customer', [CoreController::class, 'updateUserToCustomer'])->name('user-to-customer');

    Route::post('/permission-update/{id}', [CoreController::class, 'user_permission_update'])->name('user.permission.update.ajax');

  });
  Route::prefix('role')->group(function () {

    Route::get('/', [CoreController::class, 'role'])->name('role.list');
    Route::post('/', [CoreController::class, 'role_ajax'])->name('role.list.ajax');
    Route::get('/delete/{id}', [CoreController::class, 'role_delete'])->name('role.delete.ajax');
    Route::post('/new-role', [CoreController::class, 'new_role'])->name('role.new.ajax');
    Route::post('/update-role/{id}', [CoreController::class, 'update_role'])->name('role.update.ajax');
  });

  Route::prefix('permission')->group(function () {

    Route::get('/', [CoreController::class, 'permission'])->name('permission.list');
    Route::post('/', [CoreController::class, 'permission_ajax'])->name('permission.list.ajax');
    Route::get('/delete/{id}', [CoreController::class, 'permission_delete'])->name('permission.delete.ajax');
    Route::get('/delete-all', [CoreController::class, 'permission_delete_ajax'])->name('permission.all.delete.ajax');
    Route::post('/new-role', [CoreController::class, 'new_permission'])->name('permission.new.ajax');
    Route::get('/update-role/{id}', [CoreController::class, 'update_permission'])->name('permission.update');
    Route::post('/update-role/{id}', [CoreController::class, 'update_permission_ajax'])->name('permission.update.ajax');

  });


  Route::prefix('firewall')->group(function () {
    Route::get('/', [CoreController::class, 'firewall'])->name('firewall.list');
    Route::post('/', [CoreController::class, 'firewall_ajax'])->name('firewall.list.ajax');
    Route::get('/delete/{id}', [CoreController::class, 'firewall_delete'])->name('firewall.delete.ajax');
    Route::post('/new-firewall', [CoreController::class, 'new_firewall'])->name('firewall.new.ajax');
  });


});


Route::get('/route/search', [CoreController::class, 'search_route'])->name('permission.routes');

//Ajax Modal
Route::prefix('backend/modal/')->middleware(['auth', 'admin', 'HasAccess'])->group(function () {

  Route::get('/new-users', [ModalController::class, 'new_user'])->name('modal.user.new');
  Route::get('/profile/{id}', [ModalController::class, 'profile'])->name('modal.user.profile');
  Route::get('/new-role', [ModalController::class, 'new_role'])->name('modal.role.new');
  Route::get('/update-role/{id}', [ModalController::class, 'update_role'])->name('modal.role.update');
  Route::get('/update-user-role/{id}', [ModalController::class, 'update_user_role'])->name('modal.role.user.update');
  Route::get('/new-firewall', [ModalController::class, 'new_firewall'])->name('modal.firewall.new');

});



