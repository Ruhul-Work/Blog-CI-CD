<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Mail\AuthOtpEmail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OtpController extends Controller
{

    public function showOtpForm($email)
    {
        try {
            $decodedEmail = urldecode($email);
            $decryptedEmail = decrypt($decodedEmail);

            $user = User::where('email', $decryptedEmail)->first();

            if (!$user) {
                return redirect()->route('register')->withErrors(['error' => 'Invalid user. Please register again.']);
            }
            return view('frontend.modules.auth.otp_verify', ['email' => $decryptedEmail, 'isAuthPage' => true]);

        } catch (DecryptException $e) {
            return redirect()->route('register')->withErrors(['error' => 'Invalid email.']);
        }
    }

    public function validateOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|array|min:4',
            'otp.*' => 'required|digits:1',
            'email' => 'required|string',
        ]);

        $otp = implode('', $request->otp);
        $email = decrypt($request->email);

        $user = User::where('email', $email)->first();

        // Check if OTP has expired
        if ($user->last_otp_send && $user->last_otp_send->diffInMinutes(now()) > 10) {
            return response()->json([
                'success' => false,
                'message' => 'OTP has expired. Please request a new one.',
            ], 422);
        }

        if ((string) $user->otp_code !== (string) $otp) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid OTP. Please try again.',
            ], 422);
        }

        $user->otp_code = null;
        $user->status = 1;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Your account has been successfully verified!',
        ]);
    }

    public function resendOtp(Request $request)
    {
        $email = decrypt($request->email);
        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.',
            ], 404);
        }

        if ($user->last_otp_send && $user->last_otp_send->diffInMinutes(now()) < 2) {
            $remainingTime = 2 - $user->last_otp_send->diffInMinutes(now());
            return response()->json([
                'success' => false,
                'message' => "Please wait {$remainingTime} minutes before requesting a new OTP.",
            ], 429);
        }

        $user->otp_code = rand(1000, 9999);
        $user->last_otp_send = now();
        $user->save();

        // Send OTP via email
        Mail::to($user->email)->send(new AuthOtpEmail($user->otp_code));

        return response()->json([
            'success' => true,
            'message' => 'A new OTP has been sent to your email.',
        ]);
    }

}
