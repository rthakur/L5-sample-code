<?php

namespace App\Http\Middleware;

use Closure;

use App\Models\ApiKey;
use App\Models\Estateagency;

use Illuminate\Support\Facades\Response;

class VerifyAppAgencyKey
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
		
		file_put_contents(sys_get_temp_dir().  "/api.log","request1:" .  print_r($_REQUEST,1) . "\n\n", FILE_APPEND);
		file_put_contents(sys_get_temp_dir().  "/api.log","input1:" . print_r($input,1) . "\n\n", FILE_APPEND);
		file_put_contents(sys_get_temp_dir().  "/api.log","input2:" . file_get_contents("php://input")  . "\n\n", FILE_APPEND);
		
		if (!isset($input['app_key']) && !isset($input['app_key'])) return $this->throw_error("api_key and secret_key was not provided, please check api client");
		elseif (!isset($input['app_key'])) return $this->throw_error("api_key was not provided");
		elseif (!isset($input['app_secret'])) return $this->throw_error("secret_key was not provided");
		elseif (strlen($input['app_secret'])<5 ) return $this->throw_error("secret_key is too short: " . $input['app_secret']);
		elseif (strlen($input['app_api'])<5 ) return $this->throw_error("api_key is too short: " . $input['app_api']);
		
		
        if (isset ($input['app_key']) && isset ($input['app_secret'])) {
            $check = Estateagency::whereRaw('api_key = ? and secret_key = ?', array($input['app_key'], $input['app_secret']))->first();
			file_put_contents(sys_get_temp_dir().  "/api.log","Check->". print_r($check,1).  "<-\n" , FILE_APPEND);
            if ($check) {
                $request->attributes->add(['agency_id' => $check->id]);
				file_put_contents(sys_get_temp_dir().  "/api.log","check 2" , FILE_APPEND);
                return $next($request);
            } else {
				file_put_contents(sys_get_temp_dir().  "/api.log","failed 2" , FILE_APPEND);
                return $this->throw_error();
            }
        } else {
			file_put_contents(sys_get_temp_dir().  "/api.log","failed 10" , FILE_APPEND);
            return $this->throw_error();
        }

    }


    public function throw_error($message = "")
    {
        return Response::json([
            'result' => 'General Failure',
            'message' => 'Request cant be completed!',
            'details' => ['The provided app_key and app_secret could not be validated or match none of Agencies! Message: ' . $message]
        ]);
    }

}
