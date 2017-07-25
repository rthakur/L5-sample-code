<?php

namespace App\Http\Middleware;

use Closure;

class SendHeaders
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
        // return $next($request)->header('Cache-Control', 'no-cache max-age=3600');
        //$cacheKey = md5($request->zoom . "_" . $request->lng . "_" . $request->lat);
    \Config::set('session.driver', 'array');
    $cacheKey = md5(json_encode($request->all()));    
		$fileKey = sys_get_temp_dir() . "/" . $cacheKey;
		
		@file_put_contents( sys_get_temp_dir() .  "/requestheaders.log",  print_r(getallheaders(),1) . "\n" , FILE_APPEND); 
		@file_put_contents( sys_get_temp_dir() .  "/requestheaders.log",  print_r($_GET,1) . "\n\n\n" , FILE_APPEND); 
		
		if (isset($_SERVER["HTTP_IF_NONE_MATCH"]) && $_SERVER["HTTP_IF_NONE_MATCH"] == $cacheKey && file_exists($fileKey))
		{
			http_response_code(304);
			die;
		}
		elseif (isset($_SERVER["HTTP_IF_MATCH"]) && $_SERVER["HTTP_IF_MATCH"] == $cacheKey && file_exists($fileKey))
		{
			http_response_code(304);
			die;
		}
		else
		{
			file_put_contents($fileKey,json_encode($request->all()));
			return $next($request)->header('Cache-Control','max-age=3600, s-maxage=3600, public')
				->header('Last-Modified',gmdate("D, d M Y H:i:s", filemtime($fileKey))." GMT")
				->header('Set-Cookie', array())
				->header('expires', array())
				->header('pragma', array())
				->header('ETag', $cacheKey);
		}
    }
}
