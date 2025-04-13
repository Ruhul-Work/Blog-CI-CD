<?php
namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionOrder;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class BlogDashboardController extends Controller
{
    public function myAccount()
    {
        $users = User::with('package')->where('id', Auth::id())->first();
        return view('frontend.modules.dashboard.my_account', ['isDashboard' => true, 'users' => $users]);
    }

    public function myPlan()
    {
        $subscriptionPackages = SubscriptionOrder::with('package')
            ->where('user_id', Auth::id())
            ->first();
        return view('frontend.modules.dashboard.my_plan', ['isDashboard' => true, 'subscriptionPackages' => $subscriptionPackages]);
    }

    public function point()
    {

        $users = User::where('id', Auth::id())->first();

        return view('frontend.modules.dashboard.point', ['isDashboard' => true, 'users' => $users]);
    }

    // public function profileEdit(Request $request)
    // {
    //     $request->validate([
    //         'name'    => 'required|string|max:255',
    //         'email'   => 'required|email|max:255',
    //         'phone'   => 'required|string|max:15',
    //         'address' => 'nullable|string|max:255',
    //         'image'   => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    //     ]);

    //     $user = User::find(Auth::id());

    //     if ($request->hasFile('image')) {
    //         $profileImagePath = uploadImage($request->file('image'), 'users', '0', 80);

    //         if ($user->image && file_exists(public_path($user->image))) {
    //             unlink(public_path($user->image));
    //         }
    //         $user->image = $profileImagePath;
    //     }
    //     if (! $user) {
    //         return response()->json(['message' => 'User not found.'], 404);
    //     }

    //     $user->update([
    //         'name'    => $request->name,
    //         'email'   => $request->email,
    //         'phone'   => $request->phone,
    //         'address' => $request->address,
    //     ]);

    //     return response()->json([
    //         'message' => 'Profile updated successfully!',
    //     ]);

    // }
    public function profileEdit(Request $request)
{
    $request->validate([
        'name'     => 'required|string|max:255',
        'email'    => 'required|email|max:255',
        'phone'    => 'required|string|max:15',
        'address'  => 'nullable|string|max:255',
        'password' => 'nullable|string|min:6|confirmed', 
        'image'    => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $user = User::find(Auth::id());

    if (! $user) {
        return response()->json(['message' => 'User not found.'], 404);
    }

    // Handle profile image update
    if ($request->hasFile('image')) {
        $profileImagePath = uploadImage($request->file('image'), 'users', '0', 80);

        // Delete old image if exists
        if ($user->image && file_exists(public_path($user->image))) {
            unlink(public_path($user->image));
        }

        $user->image = $profileImagePath;
    }

    // If the user provided a new password, update it
    if ($request->filled('password')) {
        $user->password = bcrypt($request->password);
    }

    // Update other profile details
    $user->update([
        'name'    => $request->name,
        'email'   => $request->email,
        'phone'   => $request->phone,
        'address' => $request->address,
    ]);

    return response()->json([
        'message' => 'Profile updated successfully!',
    ]);
}
}
