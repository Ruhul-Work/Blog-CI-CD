<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Mail\authOtpEmail;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthOtpController extends Controller
{
    public function showOtpForm($email)
    {
        try {
           
            $decodedEmail = urldecode($email);
            
            $decryptedEmail = decrypt($decodedEmail);
            $customer = Customer::where('email', $decryptedEmail)->first();

            if (!$customer) {

                return redirect()->route('register')->withErrors(['error' => 'অবৈধ গ্রাহক। আবার নিবন্ধন করুন.']);
            }

            return view('frontend.modules.auth.otp_verify', ['email' => $decryptedEmail]);

        } catch (DecryptException $e) {

            return redirect()->route('register')->withErrors(['error' => 'অবৈধ ইমেল.']);
        }
    }
    

    public function verifyOtp(Request $request)
    {


        $request->validate([
            'otp' => 'required|array',
            'email' => 'required|email'
        ]);

        $otp = implode('', $request->otp);

        $customer = Customer::where('email', $request->email)->first();

        if ($customer && $customer->otp_code === $otp) {
            // OTP is valid, mark user as verified (if needed)
            $customer->status = 1;
            $customer->save();

            return redirect()->route('login')->with('success', 'আপনার অ্যাকাউন্ট সফলভাবে যাচাই করা হয়েছে  |এখন লগ ইন করুন!');
        }

        return back()->withErrors(['otp' => 'OTP ভুল। অনুগ্রহ করে আবার চেষ্টা করুন।']);
    }


    public function resendOtp(Request $request)
    {
        // Validate the email
        $request->validate([
            'email' => 'required|email'
        ]);

        // Retrieve the customer
        $customer = Customer::where('email', $request->email)->first();

        if ($customer) {

            $otp = rand(1000, 9999);
            $customer->otp_code = $otp;
            $customer->save();

            // Send OTP to email
            Mail::to($customer->email)->send(new authOtpEmail($otp));

            return response()->json(['success' => 'OTP পুনরায় পাঠানো হয়েছে। আপনার ইমেইল চেক করুন।']);
        }

        return response()->json(['errors' => ['email' => 'এই ইমেইল ঠিকানা দিয়ে কোন ব্যবহারকারী পাওয়া যায়নি।']], 404);
    }



}
