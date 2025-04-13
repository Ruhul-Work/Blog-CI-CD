<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SubscriptionOrder;
use Symfony\Component\HttpFoundation\Response;

class CheckActiveSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Check if user has an active subscription (Valid only if 'paid' and within valid date range)
            $hasActiveSubscription = SubscriptionOrder::where('user_id', $user->id)
                ->where('payment_status', 'paid')
                ->where('subscription_start_date', '<=', now()) 
                ->where('end_date', '>=', now()) 
                ->exists();

            if ($hasActiveSubscription) {
                return redirect()->route('subscriptions.index')
                    ->with('error', 'আপনার ইতিমধ্যেই একটি সক্রিয় সাবস্ক্রিপশন আছে। মেয়াদ শেষ না হওয়া পর্যন্ত আপনি অন্য কোনও সাবস্ক্রিপশন কিনতে পারবেন না।');
            }
        } else {
            // Redirect to login if not authenticated
            return redirect()->route('login')->with('error', 'Please log in to access this page.');
        }

        return $next($request);
    }
}
