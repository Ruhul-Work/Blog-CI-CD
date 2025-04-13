<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Models\Role;
use App\Models\Permission;

class HasAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        $currentPath = Route::getFacadeRoot()->current()->uri(); //current route
        $user_role = Auth::user()->user_role; //user role

        //super
        if ($user_role == 1) {
            //abort(403);
            return $next($request);
        }

        //$role = Role::whereId($user_role)->first();
        $permission = explode(",", Auth::user()->user_permission); //user_permission

        foreach ($permission as $single) {
            
            $slug = Permission::whereId($single)->first();
            if ($slug) {

                if (in_array($currentPath, explode(",", $slug->slug))) {

                    return $next($request);

                }

            }
        }

        abort(403);
    }
}
