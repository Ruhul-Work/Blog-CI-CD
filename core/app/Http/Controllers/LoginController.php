<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Role;
use Illuminate\Support\Facades\Session;
use App\Models\Firewall;

class LoginController extends Controller
{

    public function login(Request $request)
    {

        //Login Attempt Count
        $attempt = Session::get("login", 0);
        $RateLimit = get_option('max_login_attempt') ?? 5; //max attempt is 5


        $validated = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);


        $credentials = [
            'username' => $validated['username'],
            'password' => $request['password'],
        ];
        $credentials2 = [
            'email' => $validated['username'],
            'password' => $request['password'],
        ];
        $credentials3 = [
            'phone' => $validated['username'],
            'password' => $request['password'],
        ];

        if (Auth::attempt($credentials) || Auth::attempt($credentials2) || Auth::attempt($credentials3)) {

            $role = Auth::user()->is_admin;

            if ($role == 1) {

                // Update last_login timestamp
                $user=User::find(Auth::user()->id);
                $user->last_login = now(); // Assuming `last_login` is a datetime field
                $user->save();

                $role = $user->user_role;

                return json_encode([
                    "login" => true,
                    "message" => "Login Successfully"
                ]);
            } else {
                $attempt++;
                Auth::logout();
                return json_encode([
                    "login" => false,
                    "message" => "Forbidden"
                ]);
            }
        } else {

            $attempt++;
            Session::put("login", $attempt);
            Session::save();

            if ($attempt >= $RateLimit) {
                $userIp = get_client_ip();
                $existingFirewallEntry = Firewall::where('ip_address', $userIp)->first();
                if (!$existingFirewallEntry) {//problem detected here
                    $firewall = new Firewall;
                    $firewall->ip_address = $userIp;
                    $firewall->type = 'Black_listed';
                    $firewall->comments = 'IP Blocked due to multiple failed login attempts';
                    $firewall->save();

                }
                Session::forget("login");
                Session::save();
                //check if white listed here
               if( $existingFirewallEntry->type !="White_listed" )
                return json_encode([
                    "login" => false,
                    "message" => "You ip has been blocked due to multiple failed login attempts"
                ]);

            }

            return json_encode([
                "login" => false,
                "message" => "Invalid Username Or Password"
            ]);
        }
    }


    public function logout(Request $request)
    {
        return redirect(route("backend.login"))->with(Auth::logout());
    }


    //  public function forget(Request $request)
    // {

    //     $validated = $request->validate([
    //         'username' => 'required',
    //     ]);

    //     $user = User::where('email', '=', $request->username)->orWhere('username', '=', $request->username)->orWhere('phone', '=', $request->username)->first();

    //     if ($user) {

    //         $code = rand(1111, 9999);
    //         session(['otp_code' => $code]);
    //         session(['otp_user_id' => $user->id]);
    //         $mail = sendMail($code, $user->email, $user->name);
    //         $success_msg = "A 4 digit code has been sent successfully";
    //         $otpSend = true;

    //         return json_encode([
    //             "login" => true,
    //             "message" => $success_msg
    //         ]);

    //     } else {
    //         return json_encode([
    //             "login" => false,
    //             "message" => "Invalid User"
    //         ]);
    //     }
    // }


    // public function verify_forget(Request $request)
    // {

    //     $id = $request->session()->get('otp_user_id');
    //     if ($request->session()->has('otp_code') && $request->session()->get('otp_code') == $request->otp) {
    //         if (strlen($request->new_password) < 4) {
    //             return json_encode([
    //                 "login" => false,
    //                 "message" => "Invalid Password"
    //             ]);
    //         }
    //         $credentials = [
    //             'password' => Hash::make($request->new_password),
    //         ];

    //         User::whereId($id)->update($credentials);
    //         $request->session()->forget('otp_code');
    //         $request->session()->forget('otp_user_id');
    //         return json_encode([
    //             "login" => true,
    //             "message" => "Password Updated"
    //         ]);
    //     } else {
           
    //         return json_encode([
    //             "login" => false,
    //             "message" => "Invalid Otp"
    //         ]);
           
    //     }
    // }
    
    
      public function forget(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required',
        ]);

        // Check rate limiting for OTP requests
        $otpAttempts = session('otp_attempts', 0);

        // Limit to 2 attempts
        if ($otpAttempts >= 2) {
            return json_encode([
                "login" => false,
                "message" => "Too many OTP requests. Please contact the admin of the website."
            ]);
        }

        // Check if the user exists based on email, username, or phone
        $user = User::where('email', '=', $request->username)
            ->orWhere('username', '=', $request->username)
            ->orWhere('phone', '=', $request->username)
            ->first();

        if ($user) {
            // Generate a 4-digit OTP code
            $code = rand(1111, 9999);
            session(['otp_code' => $code]);
            session(['otp_user_id' => $user->id]);
            session(['otp_generated_at' => now()]); // Store the timestamp of OTP generation
            session(['otp_attempts' => $otpAttempts + 1]); // Increment attempts on successful OTP send

            // Send the OTP code to the user's email
            sendMail($code, $user->email, $user->name);

            return json_encode([
                "login" => true,
                "message" => "A 4 digit code has been sent to your mail successfully. Please try to enter it."
            ]);
        } else {
            Log::warning('Invalid OTP request', ['username' => $request->username]);
            return json_encode([
                "login" => false,
                "message" => "Invalid User"
            ]);
        }
    }
    
    public function verify_forget(Request $request)
    {
        $id = $request->session()->get('otp_user_id');

        // Verify OTP and check expiration
        if ($request->session()->has('otp_code') &&
            $request->session()->get('otp_code') == $request->otp) {

            // Check if OTP has expired (e.g., 3 minutes)
            $otpGeneratedAt = $request->session()->get('otp_generated_at');
            if ($otpGeneratedAt && now()->diffInMinutes($otpGeneratedAt) > 2) {
                // OTP expired
                $request->session()->forget('otp_code');
                $request->session()->forget('otp_user_id');
                Log::warning('Expired OTP attempt', ['user_id' => $id]);
                return json_encode([
                    "login" => false,
                    "message" => "OTP has expired. Please request a new one."
                ]);
            }

            if (strlen($request->new_password) < 4) {
                return json_encode([
                    "login" => false,
                    "message" => "Invalid Password"
                ]);
            }

            // Check if the user still exists before updating the password
            $user = User::find($id);
            if (!$user) {
                Log::warning('Password update attempt for non-existing user', ['user_id' => $id]);
                return json_encode([
                    "login" => false,
                    "message" => "User not found"
                ]);
            }

            // Update the user's password
            $credentials = [
                'password' => Hash::make($request->new_password),
            ];
            $user->update($credentials);

            // Clear the OTP and user ID from the session
            $request->session()->forget('otp_code');
            $request->session()->forget('otp_user_id');

            return json_encode([
                "login" => true,
                "message" => "Password Updated"
            ]);
        } else {
            // Increment the OTP attempts count
            $request->session()->increment('otp_attempts');

            Log::warning('Invalid OTP attempt', ['otp' => $request->otp, 'user_id' => $id]);

            return json_encode([
                "login" => false,
                "message" => "Invalid OTP"
            ]);
        }
    }

}
