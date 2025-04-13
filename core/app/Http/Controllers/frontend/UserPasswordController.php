<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Nette\Schema\ValidationException;

class UserPasswordController extends Controller
{
    public function showChangeForm()
    {
        $user = Auth::user(); // Get the currently authenticated user
        return view('frontend.modules.dashboard.password_form', compact('user'));
    }


    public function updatePassword(Request $request)
    {
//        $request->validate([
////            'current_password' => 'required',
//            'new_password' => 'required|min:8|confirmed',
//        ]);


        //        if (!Hash::check($request->current_password, $user->password)) {
//            throw ValidationException::withMessages([
//                'current_password' => ['The provided password does not match your current password.'],
//            ]);
//        }



        $validator = Validator::make($request->all(), [
//            'current_password' => 'required',
            'new_password' => 'required|min:8',
            'confirm_password' => 'required|same:new_password',
        ], [
//            'current_password.required' => 'বর্তমান পাসওয়ার্ড প্রয়োজন।',
            'new_password.required' => 'নতুন পাসওয়ার্ড প্রয়োজন।',
            'new_password.min' => 'নতুন পাসওয়ার্ড অবশ্যই ৮ অক্ষরের হতে হবে।',
            'confirm_password.required' => 'নতুন পাসওয়ার্ড নিশ্চিতকরণ প্রয়োজন।',
            'confirm_password.same' => 'নতুন পাসওয়ার্ড এবং নিশ্চিতকরণ পাসওয়ার্ড মিলছে না।',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }


        $user = auth()->user();


        $user->password = Hash::make($request->new_password);

        $user->save();
        
         return response()->json(['message' => 'Password updated successfully.Login now with new password']);

    
    }

}
