<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Controller;
Use App\Models\Estateagency;

class AgencyController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function createUpdate(Request $request)
    {
        $input = json_decode($request->getContent(), true);

        $results = [
            'updated' => 0,
            'created' => 0,
            'ignored' => 0
        ];

        $agencies = $input['agencies'];
        foreach ($agencies as $a) {
            if (isset($a['contactemail']) && preg_match('/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/', $a['contactemail'])) {
                $check = Estateagency::whereRaw('info_email = ?', array($a['contactemail']))->first();

                if ($check) {
                    $agency = $check;
                    $results['updated']++;
                } else {
                    $agency = new Estateagency();
                    $results['created']++;
                }

                if (isset($a['realestatename'])) {
                    $agency->public_name = $a['realestatename'];
                }

                if (isset($a['website'])) {
                    $agency->website = $a['website'];
                }

                if (isset($a['contactemail'])) {
                    $agency->info_email = $a['contactemail'];
                    $agency->api_key = md5($a['contactemail'] . time());
                    $agency->secret_key = sha1($a['contactemail'] . time() . microtime());
                }

                if (isset($a['contacttelenumber'])) {
                    $agency->phone = $a['contacttelenumber'];
                }

                if (isset($a['companyname'])) {
                    $agency->legal_companyname = $a['companyname'];
                }

                $agency->save();
            } else {
                $results['ignored']++;
            }
        }

        return $this->_set_success($results['created'] . ' Agencies were created. ' . $results['updated'] . ' Agencies were updated. ' . $results['ignored'] . ' Agencies were ignored.', []);

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $agency = Estateagency::whereRaw('id = ?', [$id])->get();

        if (count($agency)) {
            return $this->_set_success('Agency has been Found!', $agency);
        } else {
            return $this->_set_error('Property has not been found!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Estateagency::destroy($id)) {
            return $this->_set_success('Agency ' . $id . ' has been deleted!', []);
        } else {
            return $this->_set_error('Error occurred while deletion attempt');
        }
    }


    private function _set_success($msg, $data)
    {
        return Response::json(['result' => 'success', 'msg' => $msg, 'data' => $data]);

    }

    private function _set_error($msg, $data = [])
    {
        return Response::json(['result' => 'error', 'msg' => $msg, 'data' => $data]);
    }

}
