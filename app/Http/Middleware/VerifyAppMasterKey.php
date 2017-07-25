<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\ApiKey;
use Illuminate\Support\Facades\Response;

class VerifyAppMasterKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $input = json_decode($request->getContent(), true);
        if (isset ($input['app_key']) && isset ($input['app_secret'])) {
            $check = ApiKey::whereRaw('api_key = ? and secret_key = ?', array($input['app_key'], $input['app_secret']))->first();
            if ($check) {
                return $next($request);
            } else {
                return $this->throw_error();
            }
        } else {
            return $this->throw_error();
        }

    }


    public function throw_error()
    {
        return Response::json(['result' => 'failed', 'msg' => 'The provided apiKey and secretKey could not be validated']);
    }

}
