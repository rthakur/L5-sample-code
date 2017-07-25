<?php

namespace App\Http\Middleware;

use Closure, Auth;

class ACLMiddleware
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
        $adminIgnorePaths = ['admin','logout'];
        
        //Redirect to admin prefix if user role admin
        if(Auth::check() && Auth::user()->role_id == '1' && ! in_array($request->segment(1),$adminIgnorePaths))
        {
          //return redirect('admin');
        }
      
        return $next($request);
    }
}
