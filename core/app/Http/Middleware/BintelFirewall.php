<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Firewall;
class BintelFirewall
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
     
     
        // Redirect from www to non-www
        if (strpos($request->getHost(), 'www.') === 0) {
            $nonWwwUrl = str_replace('www.', '', $request->getHost());
            $url = $request->getScheme() . '://' . $nonWwwUrl . $request->getRequestUri();
            return redirect()->to($url, 301);
        }
        
        //$firewall=get_option('firewall')??'No';
        $firewall='No';
        if($firewall =='Yes'){
            $userIp = get_client_ip();
            $check = Firewall::where('ip_address', $userIp)->where('type','=','Black_listed')->first();
            if($check){

                $view = view('firewall.blocked');
                $content = $view->render();
                //dd($check);
                echo $content;
                exit; 
            
    
            }

        }

        return $next($request);
        
        
       
        
   
    }
}
