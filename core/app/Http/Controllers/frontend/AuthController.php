<?php
namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Mail\AuthOtpEmail;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    // public function showRegistrationForm()
    // {
    //     return view('frontend.modules.auth.register');
    // }

    // public function register(Request $request)
    // {
    //     // Validation rules
    //     $validatedData = $request->validate([
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|string|email|max:255|unique:users',
    //         'phone' => 'required|string|max:15|min:11|unique:users',
    //         'password' => 'required|string|min:6|confirmed',
    //     ]);

    //     // Create a new instance of the Customer model
    //     $customer = new Customer();
    //     $customer->name = $validatedData['name'];
    //     $customer->email = $validatedData['email'];
    //     $customer->phone = $validatedData['phone'];
    //     $customer->password = Hash::make($validatedData['password']);
    //     $customer->otp_code = rand(1000, 9999);

    //     $customer->save();

    //     Mail::to($customer->email)->send(new authOtpEmail($customer->otp_code));

    //     $encryptedEmail = encrypt($customer->email);

    //     return redirect()->route('auth.otp.verify', ['email' => urlencode($encryptedEmail)])

    //         ->with('success', 'নিবন্ধন সফল! OTP এর জন্য আপনার ইমেইল চেক করুন।');

    // }

    // public function showLoginForm(Request $request)
    // {

    //     return view('frontend.modules.auth.login');
    // }

    // public function login(Request $request)
    // {

    //     // Validate the request inputs
    //     $request->validate([
    //         'identifier' => 'required',
    //         'password' => 'required',
    //     ]);

    //     // Determine if the identifier is an email or phone number
    //     $field = filter_var($request->identifier, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

    //     // Prepare the credentials array
    //     $credentials = [
    //         $field => $request->identifier,
    //         'password' => $request->password,
    //     ];

    //     // Check if 'remember' checkbox was selected
    //     $remember = $request->has('remember');

    //     // Attempt to log the user in
    //     if (Auth::attempt($credentials, $remember)) {

    //         return redirect()->intended(route('home'));
    //     }

    //     // If login fails, return back with an error message
    //     return back()->withErrors([
    //         'identifier' => 'প্রদত্ত তথ্য আমাদের রেকর্ডের সাথে মেলেনি',

    //     ])->withInput($request->only('identifier', 'remember'));
    // }

    public function login(Request $request)
    {
        // Validate the request inputs
        $request->validate([
            'identifier' => 'required',
            'password'   => 'required',
        ]);

        // Determine if the identifier is an email or phone number
        $field = filter_var($request->identifier, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        $credentials = [
            $field     => $request->identifier,
            'password' => $request->password,
        ];

        $remember = $request->has('remember');

        // Attempt to log the user in
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Get today's date
            $today = date('Y-m-d');

            // Show modal only once per day
            if (! session()->has('login_modal_shown') || session('login_modal_shown') !== $today) {
                session()->put('login_modal_shown', $today); // Store today's date in session
                session()->flash('login_success', true);     // Flash for the frontend check
            }

            return response()->json([
                'success'      => true,
                'redirect_url' => route('home'),
            ]);
        }

        // If login fails, return errors
        return response()->json([
            'success' => false,
            'message' => 'প্রদত্ত তথ্য আমাদের রেকর্ডের সাথে মেলেনি।',
            'errors'  => [
                'identifier' => 'ইমেইল বা ফোন নম্বর সঠিক নয়।',
                'password'   => 'পাসওয়ার্ড ভুল।',
            ],
        ], 422);
    }
// public function login(Request $request)
// {
//     // Validate the request inputs
//     $request->validate([
//         'identifier' => 'required',
//         'password' => 'required',
//     ]);

//     $field = filter_var($request->identifier, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

//     $credentials = [
//         $field => $request->identifier,
//         'password' => $request->password,
//     ];

//     $remember = $request->has('remember');

//     if (Auth::attempt($credentials, $remember)) {
//         $request->session()->regenerate();

//         $redirectUrl = $request->input('redirect_url', route('home')); // Default to home if no redirect_url

//         return redirect($redirectUrl)->with('success', 'আপনার অ্যাকাউন্টে লগইন সফল হয়েছে!');
//     }

//     return back()->withErrors([
//         'identifier' => 'ইমেইল বা ফোন নম্বর সঠিক নয়।',
//         'password' => 'পাসওয়ার্ড ভুল।',
//     ])->withInput();
// }

    public function logout()
    {
        Auth::logout();

        return redirect()->route('login');
    }

//new code below for blog

    public function showRegistration()
    {
        return view('frontend.modules.auth.register', ['isAuthPage' => true]);
    }

    public function showLogin(Request $request)
    {

        return view('frontend.modules.auth.login', ['isAuthPage' => true]);
    }

    public function register(Request $request)
    {
        // Validation rules
        $validatedData = $request->validate([
            'fullName' => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'phone'    => 'required|string|max:15|min:11|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Create a new instance of the User model
        $user           = new User();
        $user->name     = $validatedData['fullName'];
        $user->email    = $validatedData['email'];
        $user->phone    = $validatedData['phone'];
        $user->password = Hash::make($validatedData['password']);
        $user->otp_code = rand(1000, 9999); // Generate OTP

        $user->save();

        Mail::to($user->email)->send(new AuthOtpEmail($user->otp_code));

        $encryptedEmail = encrypt($user->email);

        // Redirect to OTP verification page
        return response()->json([
            'success'      => true,
            'redirect_url' => route('auth.otp.verify', ['email' => urlencode(encrypt($user->email))]),

        ]);
    }

    //google login method
    public function redirectToGoogleProvider()
    {
        $scope            = 'email profile';
        $authorizationUrl = 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query([
            'scope'         => $scope,
            'redirect_uri'  => env('GOOGLE_REDIRECT_URL'),
            'response_type' => 'code',
            'client_id'     => env('GOOGLE_CLIENT_ID'),
            'access_type'   => 'offline',
            'prompt'        => 'consent',
        ]);

        return redirect($authorizationUrl);
    }

    public function handleGoogleCallback(Request $request)
    {
        $client = new Client();

        try {
            $response = $client->post('https://oauth2.googleapis.com/token', [
                'form_params' => [
                    'code'          => $request->input('code'),
                    'client_id'     => env('GOOGLE_CLIENT_ID'),
                    'client_secret' => env('GOOGLE_CLIENT_SECRET'),
                    'redirect_uri'  => env('GOOGLE_REDIRECT_URL'),
                    'grant_type'    => 'authorization_code',
                ],
            ]);

            $tokenData = json_decode($response->getBody()->getContents(), true);

            // Check for successful response
            if (isset($tokenData['error'])) {
                throw new Exception("Error fetching access token: " . $tokenData['error']);
            }

            // Use the access token to retrieve user information
            $response = $client->get('https://www.googleapis.com/oauth2/v3/userinfo', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $tokenData['access_token'],
                ],
            ]);

            $userData = json_decode($response->getBody()->getContents(), true);

            // Check if user exists in your database based on email
            $existingUser = User::where('email', $userData['email'])->first();

            if ($existingUser) {
                // User already exists, log them in
                Auth::login($existingUser);
            } else {
                // Create a new user
                $customer           = new User();
                $customer->name     = $userData['name'] ?? 'Unknown';
                $customer->email    = $userData['email'];
                $customer->password = '';
                $customer->save();

                Auth::login($customer);
            }
            // Get the previous URL from the session
            $previousUrl = session('previousUrl');

            // Redirect to the previous URL if it exists; otherwise, redirect to the default path
            if ($previousUrl) {
                session()->forget('previousUrl');
                return redirect()->to($previousUrl);
            }

            return redirect()->intended('/');

        } catch (Exception $e) {
            return redirect()->route('login')->with('error', $e->getMessage());
        }
    }

}
