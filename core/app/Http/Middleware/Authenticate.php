<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        
        $routePath = url()->current();
        if (str_contains('backend', $routePath)){
            return $request->expectsJson() ? null : route('backend.login');
        }
        else{
            //public login route
            return $request->expectsJson() ? null : route('login');
        }
           
    }
}
