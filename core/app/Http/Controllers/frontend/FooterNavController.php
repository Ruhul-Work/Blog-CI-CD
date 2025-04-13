<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class FooterNavController extends Controller
{

    public function aboutUs()
    {
        return view('frontend.modules.footer_nav.about_us');
    }

    public function privacyPolicy()
    {
        return view('frontend.modules.footer_nav.privacy_policy');
    }

    public function termsCondition()
    {
        return view('frontend.modules.footer_nav.terms_conditions');
    }

    public function contactUs()
    {
        return view('frontend.modules.footer_nav.contact_us');
    }

    public function storeContact(Request $request)
    {

        $validatedData = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'number' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'message_details' => 'required|string|max:1000',
        ]);


        // Retrieve the email address from options
        $contactEmail = get_option('email');


        Mail::send('mail.contact', $validatedData, function ($message) use ($contactEmail) {
            $message->to($contactEmail)
                ->subject('New Contact Form Submission');
        });


//        Mail::send('mail.contact', $validatedData, function ($data) use ($validatedData) {
//            $data->to('englishmoja.yt@gmail.com')
//                ->subject('New Contact Form Submission');
//        });


        return response()->json(['success' => true, 'message' => 'ধন্যবাদ আপনার বার্তার জন্য! আমরা শীঘ্রই আপনার সাথে যোগাযোগ করবো।']);


    }

    public function storeSubscribe(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email|max:255',
        ]);


         Subscription::create($validatedData);

        // Return a success response
        return response()->json(['success' => true, 'message' => 'সাবস্ক্রিপশনের জন্য ধন্যবাদ!']);
    }
}
