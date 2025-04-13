<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{

    public function showLinkRequestForm()
    {
        return view('frontend.modules.auth.passwords.email_form',['isAuthPage' => true]);
    }





    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['success' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }
    
        public function showResetForm($token, Request $request)
  {
        $email=$request->email;
             
         return view('frontend.modules.auth.passwords.reset_form', ['token' => $token, 'email' => $email ,'isAuthPage' => true]);
     }
    




    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
            'token' => 'required',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
                Auth::login($user);
            }
        );

        return $status === Password::PASSWORD_RESET
//            ? redirect()->route('login')->with('status', __($status))
            ? redirect()->route('login')->with('success', 'আপনার পাসওয়ার্ড সফলভাবে পুনরায় সেট করা হয়েছে!')
            : back()->withErrors(['email' => [__($status)]]);
    }
}
