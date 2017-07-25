<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Controller;
use App\Models\ApiErrorReporting;

class ErrorReportingController extends Controller
{

    private $successes = array();
    private $failures = array();
    private $general_failures = array();

    public function report(Request $request)
    {
        $agency_id = $request->get('agency_id');
        $input = json_decode($request->getContent(), true);
        $error = $input['payload'];

        $reported = new ApiErrorReporting();
        $reported->agency_id = $agency_id;
        $reported->app_key = $error['app_key'];
        $reported->app_secret = $error['app_secret'];
        $reported->sdk_config = json_encode($error['sdk_config']);
        $reported->server_response = (is_array($error['server_response'])) ? json_encode($error['server_response']) : $error['server_response'];
        $reported->server_env = json_encode($error['server_env']);
        $reported->php_info = json_encode($error['php_info']);
        $reported->sdk_exception = $error['sdk_exception'];
        $reported->sdk_response = json_encode($error['sdk_response']);
        $reported->save();

        $this->successes[] = [
            'external_id' => $reported->id,
            'status' => 'success',
            'message' => 'Error has been logged!'
        ];

        return $this->_generate_result();


    }

    private function _generate_result()
    {
        if (empty($this->general_failures)) {
            $message = 'Data has been processed!';
            $details = [
                'successes' => $this->successes,
                'failures' => $this->failures
            ];
            if (!empty($this->successes) && !empty($this->failures)) {
                $result = 'Mixed';
            } elseif (!empty($this->successes)) {
                $result = 'Success';
            } else {
                $result = 'Error';
            }
        } else {
            $message = "Request cant be completed!";
            $result = 'General Failure';
            $details = $this->general_failures;
        }

        return Response::json([
            'result' => $result,
            'message' => $message,
            'details' => $details
        ]);
    }

}
