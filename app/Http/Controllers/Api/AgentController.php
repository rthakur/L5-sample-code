<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Controller;
use App\User;

class AgentController extends Controller
{

    private $successes = array();
    private $failures = array();
    private $general_failures = array();

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function createUpdate(Request $request)
    {
        $agency_id = $request->get('agency_id');
        $input = json_decode($request->getContent(), true);
        $agents = $input['payload'];
        if (count($agents) < 11) {
            foreach ($agents as $a) {
                $updated = true;
                if (isset($a['email']) && preg_match('/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/', $a['email'])) {
                    $agent = User::whereRaw('email = ? and agency_id = ?', [$a['email'], $agency_id])->first();

                    if (!$agent) {
                        $agent = new User();
                        $agent->email = $a['email'];
                        $agent->role_id = 3;
                        $agent->password = bcrypt('123456');
                        $agent->agency_id = $agency_id;
                        $updated = false;
                    }

                    if (isset($a['name'])) {
                        $agent->name = $a['name'];
                    } else {
                        $this->failures[] = [
                            'status' => 'error',
                            'message' => 'Agent\'s name has not been set!',
                            'details' => [
                                'email' => $a['email']
                            ]
                        ];
                        continue;
                    }

                    $agent->phone = isset($a['phone']) ? $a['phone'] : '';
                    $agent->skype = isset($a['skype']) ? $a['skype'] : '';
                    $agent->about_me = isset($a['about_me']) ? $a['about_me'] : '';
                    $agent->twitter = isset($a['twitter']) ? $a['twitter'] : '';
                    $agent->facebook = isset($a['facebook']) ? $a['facebook'] : '';
                    $agent->pinterest = isset($a['pinterest']) ? $a['pinterest'] : '';
                    $agent->mobile = isset($a['mobile']) ? $a['mobile'] : '';
                    $agent->save();

                    //process profile_picture
                    if (isset($a['profile_picture'])) {
                        if ($a['profile_picture'] != Null && $a['profile_picture'] != '') {
                            $image_check = getimagesize($a['profile_picture']);
                            if (is_array($image_check) && !empty($image_check)) {
                                $file_name = 'profile/picture/' . $agent->name . '-' . $agent->id . '-' . time();
                                $filename_parts = explode('.', $a['profile_picture']);
                                $file_name = $file_name . '.' . end($filename_parts);
                                if ($updated) {
                                    $agent->profile_picture = $this->uploadFileToS3($a['profile_picture'], $file_name, $agent->profile_picture);
                                } else {
                                    $agent->profile_picture = $this->uploadFileToS3($a['profile_picture'], $file_name);
                                }
                            } else {
                                $agent->profile_picture = Null;
                            }
                            $agent->save();
                        }
                    }

                    $this->successes[] = [
                        'status' => 'success',
                        'message' => 'Data has been saved!',
                        'details' => [
                            'email' => $agent->email,
                            'action' => ($updated) ? 'updated' : 'created'
                        ]
                    ];
                } else {
                    $this->failures[] = [
                        'status' => 'error',
                        'message' => 'Email address does not seam like real email address!',
                        'details' => [
                            'email' => (isset($e['email'])) ? $e['email'] : NULL
                        ]
                    ];
                    continue;
                }
            }
        } else {
            $this->general_failures[] = 'Agent Api only accepts requests with number of agents less or equal to ten!';
        }

        return $this->_generate_result();

    }

    public function show(Request $request)
    {
        $agency_id = $request->get('agency_id');
        $input = json_decode($request->getContent(), true);
        $emails = $input['payload'];
        if (count($emails) < 11) {
            foreach ($emails as $e) {
                if (preg_match('/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/', $e['email'])) {
                    $agent = User::whereRaw('email = ? and agency_id = ?', [$e['email'], $agency_id])->first();
                    if ($agent) {
                        $this->successes[] = $agent->api_format();
                    } else {
                        $this->failures[] = [
                            'status' => 'error',
                            'message' => 'Agent has not been found!',
                            'details' => [
                                'email' => $e['email']
                            ]
                        ];
                        continue;
                    }
                } else {
                    $this->failures[] = [
                        'status' => 'error',
                        'message' => 'Email address does not seam like real email address!',
                        'details' => [
                            'email' => $e['email']
                        ]
                    ];
                    continue;
                }
            }
        } else {
            $this->general_failures[] = 'Agent Api only accepts requests with number of agents less or equal to ten!';
        }
        return $this->_generate_result();
    }

    public function all(Request $request)
    {
        $agency_id = $request->get('agency_id');
        $input = json_decode($request->getContent(), true);

        if (isset($input['payload']) && !empty($input['payload'])) {
            if (isset($input['payload']['page']) && $input['payload']['page'] && is_integer($input['payload']['page'])) {
                $page = $input['payload']['page'];
            } else {
                $page = 1;
            }
        } else {
            $page = 1;
        }
        $skip = ($page - 1) * 10;

        $agents = User::where('agency_id', '=', $agency_id)->skip($skip)->take(10)->get();
        if (count($agents)) {
            foreach ($agents as $a) {
                $this->successes[] = $a->api_format();
            }
        } else {
            $this->failures[] = [
                'status' => 'error',
                'message' => 'No agents were found!'
            ];
        }
        return $this->_generate_result();
    }

    public function destroy(Request $request)
    {
        $agency_id = $request->get('agency_id');
        $input = json_decode($request->getContent(), true);
        $emails = $input['payload'];
        if (count($emails) < 11) {
            foreach ($emails as $e) {
                if (preg_match('/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/', $e['email'])) {
                    $agent = User::whereRaw('email = ? and agency_id = ?', [$e['email'], $agency_id])->first();
                    if ($agent) {
                        User::destroy($agent->id);
                        $this->successes[] = [
                            'status' => 'success',
                            'message' => 'Agent ' . $e['email'] . ' has been deleted!',
                            'details' => [
                                'email' => $e['email'],
                                'action' => 'delete'
                            ]
                        ];
                    } else {
                        $this->failures[] = [
                            'status' => 'error',
                            'message' => 'Agent has not been found!',
                            'details' => [
                                'email' => $e['email']
                            ]
                        ];
                        continue;
                    }
                } else {
                    $this->failures[] = [
                        'status' => 'error',
                        'message' => 'Email address does not seam like real email address!',
                        'details' => [
                            'email' => $e['email']
                        ]
                    ];
                    continue;
                }
            }
        } else {
            $this->general_failures[] = 'Agent Api only accepts requests with number of agents less or equal to ten!';
        }
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
