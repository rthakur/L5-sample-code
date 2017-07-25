<?php

namespace App\Http\Middleware;

use Closure;
use Session, Auth;

class AdminAccountCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (in_array(Auth::user()->role_id , ['1','5'])) {
            return $next($request);
        } else if (Session::has('auth_access')) {
            Auth::loginUsingId(Session::get('auth_access'));
            Session::forget('auth_access');
            return $next($request);
        }
        
        return \App::abort('404');
    }
}
