<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\Type;
use App\Models\Package;
use App\User;
use App\Models\Language;
use App\Models\Message;
use App\Models\SyncronizePlanRequest;
use App\Helpers\PropertyViewsHelper;
use Auth, Hash, App, View, Carbon\Carbon, Exception;
use Dompdf\Dompdf;
use App\Models\Invoice;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $role = Auth::user()->role_id;

        Auth::user()->checkCampaignExpiry();

        if ($role == '4') return redirect(SITE_LANG . '/account/agency/details');

        //redirect admin and VA Admin
        if ($role == 1 || $role == '5')
            return redirect(SITE_LANG . '/admin');

        return view('profile.index');
    }

    public function getRandomAgentPassword()
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $password = substr(str_shuffle($chars),0,6);

        return $password;
    }

    public function getMyProperties(Request $request, $type = null)
    {
        if (Auth::user()->role_id != 3 || !Auth::user()->agency) {
            return redirect('/403');
        }

        $selectQuery = ['property.*'];

        foreach (Language::$languages as $lng) {
            $selectQuery[] = 'property_texts.subject_'. $lng .' as text_subject_'. $lng;
        }

        $selectQuery = implode(', ', $selectQuery);
        $properties = Property::selectRaw($selectQuery)
            ->join('property_texts', 'property.id', '=', 'property_texts.property_id');

        $properties = $this->getPropertiesByType($properties, $type)
            ->orderBy('property.created_at', 'desc')
            ->with('stats')
            ->with('texts')
            ->paginate(6);

        $data['new'] = $type;
        $data['properties'] = $properties;

        return view('profile.agent.property.index', $data);
    }

    private function getPropertiesByType($properties, $type)
    {
        if ($type == 'new') {

            $properties = $properties
                ->where('property.created_at', '<', Carbon::now()->subHours(1))
                ->where('agent_id', Auth::id())
                ->where('agent_checked', 0);

        } elseif ($type == 'confirm_sold') {

            $properties = $properties
                ->where('property.agent_id', Auth::id())
                ->where('agent_sold_notified', 1);

        } else {
            $properties = $properties->where('property.agency_id', Auth::user()->agency->id);
        }

        return $properties;
    }

    public function getSyncStatus()
    {
        $data['lastSyncStatus'] = Auth::user()->lastSyncStatus();

        if (Auth::user()->package->id == 1)
        {
            $data['selectedPackage'] = Auth::user()->package;
            $data['noProfessionalPlan'] = '1';
            $data['message'] = trans('common.AuthSyncMsg');
            return view('profile.agency.sync_status',$data);
        }
        return view('profile.agency.sync_status', $data);
    }

    public function getBookmarked()
    {
        $data['bookmarkedProperties'] = Auth::user()->bookmarkedProperties()->with('features')->paginate(12);
        return view('profile.bookmarked', $data);
    }

    public function getBills(Request $request)
    {
        $this->importStripeInvoices();
        $invoices = Invoice::where('user_id', Auth::id())->where('paid_status', '1')->orderBy('id','asc')->get();
        return view('profile.bills', ['invoices' => $invoices]);
    }

    public function downloadInvoice(Request $request, $invoiceId)
    {
        $data['invoice'] = Invoice::where('stripe_invoice_id', $invoiceId)->first();
        $data['agency'] = $request->user()->agency;
        $view = view('invoice.receipt', $data);
        $dompdf = new Dompdf();
        $dompdf->loadHtml($view);

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser
        $dompdf->stream('invoice');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $validate = validator($request->all(), [
            'form_account_name' => 'required',
            'email' => 'email|required|unique:virtual_assistants,email|unique:users,email,' . Auth::id().'|unique:estate_agencies,info_email',
            'form_account_phone' => 'max:13|regex:/^[0-9\+]+$/',
            'form_account_mobile' => 'max:13|regex:/^[0-9\+]+$/',
            // 'profile_picture' => 'image'
        ]);

        if($user->role_id == '4')
        {
            $validate = validator($request->all(), [
                'account_agency' => 'exists:estate_agencies,id',
                'form_account_name' => 'required',
                'email' => 'email|required|unique:virtual_assistants,email|unique:users,email,' . Auth::id().'|unique:estate_agencies,info_email,'.$user->agency->id,
            ]);
        }

        if ($validate->fails()) return back()->withErrors($validate)->withInput();

        $user->name = $request->form_account_name;
        $user->email = $request->email;
        $user->phone = $request->form_account_phone;
        $user->mobile = $request->form_account_mobile;
        $user->receive_newsletter = isset($request->receiveNewsletter) ? '1' : '';
        $user->save();

        if (Auth::user()->role_id == 4)
            return redirect(SITE_LANG . '/account/agency/details')->with('success', trans('common.SuccessfullyUpdated'))->with('tab', 'basic_info');
        else
            return redirect(SITE_LANG . '/account/profile')->with('success', trans('common.SuccessfullyUpdated'));
    }

    /**
     * Change Password.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $passwordPromptMsg = null;

        $user = Auth::user();

        if($request->has('form_type') && $request->form_type == 'social_info')
        {
            $validate = validator($request->all(), [
                'skype' => 'regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/',//validate_fld("skype"),
                'twitter' => 'regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/',//validate_fld("twitter"),
                'facebook' => 'regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/',//validate_fld("facebook"),
                'pinterest' => 'regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/',//validate_fld("pinterest"),
            ]);

            if ($validate->fails())
                return back()->withErrors($validate)->with('tab', 'social_info');


            $user->skype = $request->skype;
            $user->twitter = $request->twitter;
            $user->pinterest = $request->pinterest;
            $user->facebook = $request->facebook;
            $user->save();

            return back()->with('success', trans('common.SuccessfullyUpdated'))->with('tab', 'social_info');
        }

        $validate = validator($request->all(), [
            'password' => 'required|confirmed',
            'password_confirmation' => 'required'
        ]);

        if ($validate->fails())
            return back()->withErrors($validate)->with('tab', 'change_password');


        if (isset($request->current_password) && !Hash::check($request->current_password, $user->password)) {
            $validate->errors()->add('current_password', 'Current password not match');
            return back()->withErrors($validate)->with('tab', 'change_password');
        }

        $user->password = Hash::make($request->password);
        $user->remember_token = \Hash::make(str_random(6));
        $user->save();

        $reset_password_link = str_replace('<reset_password_link>', url(SITE_LANG.'/reset-password/'.base64_encode($user->id).'?token='.$user->remember_token), trans('emails.ChangePasswordMsg'));

        $passwordPromptMsg = str_replace('<user_name>',$user->name,trans('emails.passwordPromptMsg'));

        \Mail::send('emails.password_verification',['userDetail' => $user, 'reset_password_link' => $reset_password_link,'passwordPromptMsg' => $passwordPromptMsg],
        function ($message) use ($user) {
            $message->to($user->email)->subject(trans('emails.agency_profile_password_prompt'));
        });

        return back()->with('success', trans('common.SuccessfullyUpdated'))->with('tab', 'change_password');
    }

    public function getAgencyDetails()
    {
        $data['agency'] = Auth::user()->agency;
        $data['sidebar'] = true;
        return view('profile.agency.form', $data);
    }

    public function getProperties(Request $request)
    {
        $agency = Auth::user()->agency;
        if (!$agency) return view('errors.404');

        $selectQuery = ['property.*'];
        foreach (Language::$languages as $lng)
            $selectQuery[] = 'property_texts.subject_'. $lng .' as text_subject_'. $lng;

        $selectQuery = implode(', ', $selectQuery);
        $agencyProperties = Property::selectRaw($selectQuery)->where('agency_id',$agency->id)
                                    ->join('property_texts', 'property.id', '=', 'property_texts.property_id');

        if ($request->s && $agencyProperties)
            $agencyProperties = $this->getSearchedAgencyProperties($agencyProperties, $request->s);

        if ($request->sort) {
            if (App::getLocale() == 'en')
                $agencyProperties = $agencyProperties->orderBy('text_subject_en', $request->sort);
            else
                $agencyProperties = $agencyProperties->orderBy('text_subject_'.App::getLocale(), $request->sort)
                                                     ->orderBy('text_subject_en', $request->sort);

            $data['sort'] = $request->sort == 'asc' ? 'desc' : 'asc';
        }

        $data['agencyProperties'] =  $agencyProperties->with('stats')->paginate(21);
        return view('profile.agency.property.index', $data);
    }

    private function getSearchedAgencyProperties($agencyProperties, $searchString)
    {
        $agencyProperties = $agencyProperties->where(function($query) use($searchString) {
            foreach (Language::$languages as $key => $lang) {
                if ($key) {
                    $query->Orwhere('property_texts.subject_'. $lang, 'like','%'. $searchString .'%');
                } else {
                    $query->where('property_texts.subject_'. $lang, 'like','%'. $searchString .'%');
                }
            }
        });

        return $agencyProperties;
    }

    public function getAgents(Request $request)
    {
        if (Auth::user()->role_id != 4)
            return redirect('/403');

        $agency = Auth::user()->agency;
        $agencyAgents = $agency->agents();

        if($request->s)
            $agencyAgents = $agencyAgents->where('name','like','%'.$request->s.'%');

        if ($request->sort) {
            $agencyAgents = $agencyAgents->orderBy('name', $request->sort);
            $data['sort'] = $request->sort == 'asc' ? 'desc' : 'asc';
        }

        $data['agencyAgents'] = !empty($agency) && !empty($agency->agents) ? $agencyAgents->paginate(10) : [];
        return view('profile.agency.agents', $data);
    }

    public function getNewAgent(Request $request)
    {
        $data['types'] = Type::all();
        return view('profile.agency.agent_new', $data);
    }

    public function getMyMessages(Request $request)
    {
        if (Auth::user()->role_id != 3)
            return redirect('/403');

        Auth::user()->markAllMessagesAsRead();
        $messages = Auth::user()->messages()->orderBy('id', 'desc')->paginate(6);
        return view('profile.agent.my_messages', ['messages' => $messages]);
    }

    public function getAgencyMessages(Request $request)
    {
        if (Auth::user()->role_id != 4)
            return redirect('/403');

        Auth::user()->agency->markAllMessagesAsRead();
        $messages = Auth::user()->agency->messages()->orderBy('id', 'desc')->paginate(6);
        return view('profile.agent.my_messages', ['messages' => $messages]);
    }

    public function getEditAgent($agentId)
    {
        $data['agent'] = User::find($agentId);
        $data['types'] = Type::all();

        return view('profile.agency.agent_edit', $data);
    }

    public function postSaveAgent(Request $request, $agentId = null)
    {
        $agent = User::find($agentId);
        $errorMessages = [
            'password.confirmed' => 'Confirmed Password does not match.',
            'password_confirmation.required_with' => 'Confirmed password is required'
        ];

        $validate = validator($request->all(), [
            'form_account_name' => 'required',
            'account_agency' => 'exists:estate_agencies,id',
            'email' => 'required|email|unique:virtual_assistants,email|unique:users,email,'.$agentId.'|unique:estate_agencies,info_email',
            'password' => ((!$agent) ? 'required|' : '').'min:6|confirmed',
            'password_confirmation' => 'required_with:password',
            'profile_picture' => 'image',
            'type_id' => 'required'
        ], $errorMessages);

        if ($validate->fails()) {

            if ($request->ajax) {
                return $validate->getMessageBag();
            }

            return back()->withErrors($validate)->withInput();
        }

        if (!$agent) $agent = new User;

        $agent->name = $request->form_account_name;
        $agent->email = $request->email;
        $agent->phone = $request->form_account_phone;
        $agent->mobile = $request->form_account_mobile;

        if ($request->password)
            $agent->password = bcrypt($request->password);

        if ($request->hasFile('profile_picture')) {
            $uploadFile = $request->file('profile_picture');
            $fileNameWithPath = 'profile/picture/' . $agent->name . '-' . $agent->id . '-' . time();
            $fileNameWithPath = $fileNameWithPath . '.' . $uploadFile->getClientOriginalExtension();
            $agent->profile_picture = $this->uploadFileToS3($uploadFile, $fileNameWithPath, $agent->profile_picture);
        }

        $agent->role_id = 3;

        if (Auth::user()->role_id == 4)
            $agent->agency_id = Auth::user()->agency_id;

        $agent->type_id = $request->type_id;
        $agent->allow_to_add = ($request->has('allow_to_add')) ? '1' : '';
        $agent->allow_to_edit = ($request->has('allow_to_edit')) ? '1' : '';
        $agent->allow_to_delete = ($request->has('allow_to_delete')) ? '1' : '';
        $agent->remember_token = \Hash::make(str_random(6));
        $agent->save();

        if ($request->send_mail)
            $this->sendAccountInfoMail($agent,$request);

        if (!$request->ajax()) {
            return redirect(SITE_LANG . '/account/agency/agents');
        }
    }

    private function sendAccountInfoMail($user,$request)
    {
        // $emailStr = trans('emails.agent_profile');
        // $emailStr = str_replace('<name>', $user->name, $emailStr);
        // $emailStr = str_replace('<reset_password_link>', url(SITE_LANG.'/reset-password/'.base64_encode($user->id).'?token='.$token), $emailStr);
        // $emailStr = nl2br($emailStr);

        $password = null;
        $agentPermissionsMessage = null;

        if($request->password)
            $password = $request->password;

        $reset_password_link = str_replace('<reset_password_link>', url(SITE_LANG.'/reset-password/'.base64_encode($user->id).'?token='.$user->remember_token), trans('emails.ChangePasswordMsg'));

        if(isset($request->allow_to_add) || isset($request->allow_to_edit) || isset($request->allow_to_delete))
            $agentPermissionsMessage = trans('emails.youcan').' '.(isset($request->allow_to_add) ? trans('emails.add'): '').(isset($request->allow_to_edit) ? ', '.trans('emails.edit'): '').(isset($request->allow_to_delete) ?', '. trans('emails.delete'): '').' '.trans('emails.property');


        \Mail::send('emails.profile.agent',['userDetail' => $user, 'password' => $password, 'reset_password_link' => $reset_password_link, 'agentPermissionsMessage' => $agentPermissionsMessage],
        function ($message) use ($user) {
            $message->to($user->email)->subject(trans('emails.agent_profile_subject'));
        });
    }

    public function resetPassword(Request $request, $id)
    {
        $user = User::find(base64_decode($id));

        if (!$user)
            return redirect(SITE_LANG);

        if($request->token != $user->remember_token)
            return redirect(SITE_LANG.'/login')->with('message', trans('common.ResetPswrdTokenExpired'));

        if($user)
            Auth::logout();

        return view('profile.agent.reset_password', compact(['id', 'user']));
    }

    public function savePassword(Request $request)
    {
        $customRequest = $request->toArray();
        $customRequest['id'] = base64_decode($request->id);

        $validate = validator($customRequest, [
            'id' => 'required|exists:users,id',
            'form_account_name' => 'required',
            'password' => 'required|min:6|confirmed'
        ]);

        if ($validate->fails())
            return back()->withErrors($validate);

        $user = User::find($customRequest['id']);
        $user->name = $request->form_account_name;
        $user->password = bcrypt($request->password);
        $user->remember_token = \Hash::make(str_random(6));
        $user->save();

        Auth::logout();
        Auth::loginUsingId($user->id);

        return redirect(SITE_LANG.'/account/profile');
    }

    public function getSubscriptionPlan()
    {

       if(Auth::user()->role_id != '4')
        return redirect(SITE_LANG.'/');

        $data['selectedPackage'] = isset(Auth::user()->package) ? Auth::user()->package : Package::find(1);
        return view('profile.agency.subscription', $data);
    }

    public function getPropertyDetails($propertyId)
    {
        if ((Auth::user()->role_id != 4 && Auth::user()->role_id != 3) || Auth::user()->package_id != 2)
            return back();

        $property = Property::where('agency_id', Auth::user()->agency_id)->find($propertyId);

        if (empty($property))
            $property = Property::where('agent_id', Auth::id())->find($propertyId);

        if ($property)
            return view('profile.agency.property.statistics', compact('property'));

        return back();
    }

    public function getPropertyStatData(Request $request)
    {
        $property = Property::where('agency_id', Auth::user()->agency_id)->orWhere('agent_id', Auth::id())->find($request->property_id);
        if ($property) {

            if (isset($request->month))
                $chartArray = PropertyViewsHelper::getTimeArray($request->property_id);
            else
                $chartArray = PropertyViewsHelper::getCountriesArray($request->property_id);

            return $chartArray;

        } else {
            return \Response::json(array(
                'success' => false,
                'errors' => ['Not Authorized' => 'Cannot Access This Property']
            ), 400);
        }
    }

    public function getApiCredential()
    {
        $data['agency'] = $agency = Auth::user()->agency;
        $package = Auth::user()->package;
        if (in_array($package->id , ['1','2'])) {
            $data['selectedPackage'] = $package;
            $data['noProfessionalPlan'] = $package->id;
            $data['message'] = trans('common.ApiAuthMsg');
            return view('profile.agency.api_credential',$data);
        }

        if ($agency->api_key == '' && $agency->secret_key == '')
            $this->generateAPIkeys();

        return view('profile.agency.api_credential', $data);
    }

    public function resetApiCredential($agency_id)
    {
        $this->generateAPIkeys();
        return back();
    }

    private function generateAPIkeys()
    {
        $agency = Auth::user()->agency;
        $agency->api_key = md5($agency->email . time());
        $agency->secret_key = sha1($agency->email . time() . microtime());
        $agency->save();
    }

    private function importStripeInvoices()
    {
        try {
            $invoices = Auth::user()->invoices();
            foreach ($invoices as $getInvoice) {
                $invoice = Invoice::firstOrCreate(['stripe_invoice_id' => $getInvoice->id]);
                $invoice->user_id = Auth::id();
                $invoice->period_start = $getInvoice->period_start;
                $invoice->period_end = $getInvoice->period_end;
                $invoice->subtotal = number_format(($getInvoice->subtotal / 100), 2);
                $invoice->tax = number_format(($getInvoice->tax / 100), 2);
                $invoice->tax_percent = $getInvoice->tax_percent;
                $invoice->total = number_format(($getInvoice->total / 100), 2);
                $invoice->paid_status = $getInvoice->paid;
                $invoice->save();
            }

        } catch (Exception $e) {
            \Log::error($e);
        }
    }

    public function getStatistics()
    {
        if (Auth::user()->role_id != 4 && Auth::user()->package_id < 2)
            return back();

        return view('profile.agency.statistics');
    }

    public function getStatisticsData(Request $request)
    {
        return PropertyViewsHelper::getAgencyMonthlyStats(Auth::user()->agency_id, $request->month);
    }

    public function confirmPropertyStatus($propertyId, $status)
    {
      $property = Property::where('agent_id', Auth::id())->find($propertyId);

      if (!$property)
        return back();

      if ($status == 'sold')
          $property->mark_as_sold = 1;
      else if ($status == 'keep') {
          $property->agent_sold_notified = 0;
          $property->api_updated_at = Carbon::now()->timestamp;
      }

      $property->save();
      return redirect(SITE_LANG.'/account/agent/myproperties/confirm_sold');
    }

    public function deleteMessage($messageId)
    {
        $message = Message::find($messageId);

        if (!$message) {
            return redirect(SITE_LANG .'/403');
        }

        $agentCheck = $message->user == Auth::user();
        $agencyCheck = $message->agency == Auth::user()->agency;

        if ($agentCheck || $agencyCheck) {
            $message->delete();
        } else {
            return redirect(SITE_LANG .'/403');
        }

        return back();
    }

    public function postAgentProfile (Request $request)
    {
        $user = Auth::user();

        if ($request->hasFile('profile_picture')) {
            $uploadFile = $request->file('profile_picture');
            $fileNameWithPath = 'profile/picture/' . $user->name . '-' . $user->id . '-' . time();
            $fileNameWithPath = $fileNameWithPath . '.' . $uploadFile->getClientOriginalExtension();
            $user->profile_picture = $this->uploadFileToS3($uploadFile, $fileNameWithPath, $user->profile_picture);
        }

        $user->save();
        return $user->profile_picture;
    }

    public function removeProfilePic($user_id)
    {
        if (Auth::id() != base64_decode($user_id)) {
            return response(['errors' => ['profile_pic' => 'something went wrong']], 403);
        }

         $user = Auth::user();

         if($user->profile_picture)
            $this->s3Delete($user->profile_picture);

         $user->profile_picture = null;
         $user->save();


         return $user->getProfilePicture();

    }

    public function unsubscribe($user_id, Request $request)
    {
        $user_id = base64_decode($user_id);
        $user = User::find($user_id);
        if($user && $user->access_token == $request->token && $user->role_id == 2)
        {
            $user->update(['access_token' => \Hash::make(str_random(6)),'receive_newsletter' => null]);
            Auth::logout();
            Auth::loginUsingId($user_id);

            return redirect(SITE_LANG.'/account/profile');
        }
        else
            return redirect(SITE_LANG.'/login')->with('message', trans('common.UnsubscribeTokenExpired'));
    }

}
