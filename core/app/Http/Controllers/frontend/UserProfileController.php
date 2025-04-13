<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \Illuminate\Support\Facades\Auth;

class UserProfileController extends Controller
{

    public function show()
    {
        $user = Auth::user(); // Get the currently authenticated user
        return view('frontend.modules.dashboard.profile.show', compact('user'));
    }


    public function edit()
    {
        $user = Auth::user(); // Get the currently authenticated user
        return view('frontend.modules.dashboard.profile.edit', compact('user'));
    }

    // Update user profile
    public function update(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'alternate_phone' => 'nullable|string|max:20',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
        ]);

        $user = Auth::user();

        // Update user profile
        $user->name = $request->input('name');
        $user->phone = $request->input('phone');
        $user->alternate_phone = $request->input('alternate_phone');
        $user->email = $request->input('email');
        $user->save();

        return redirect()->route('profile.show')->with('success', 'Profile updated successfully.');
    }
}
