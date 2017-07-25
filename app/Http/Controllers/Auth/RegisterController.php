<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use App\Models\Estateagency;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\Package;
use App\Models\Estateagencycontactperson;
use App\Models\Campaign;
use Illuminate\Validation\Rule;
use Auth, Session, App, View;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Show the application registration form.
     *
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:virtual_assistants,email|unique:users,email|unique:estate_agencies,info_email',
            'password' => 'required|min:6|confirmed',
            'g-recaptcha-response' => 'required|recaptcha',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        $receiveNewsletter = NULL;
        if (isset($data['account-newsletter'])) $receiveNewsletter = '1';

        $newUserData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'role_id' => '2', //Regular User
            'receive_newsletter' => $receiveNewsletter,
            'access_token' => \Hash::make(str_random(6))
        ];

	    $user = User::create($newUserData);
        $this->sendWelcomeEmail($user);
        return $user;
    }

    public function registerAgency($token = null)
    {
        $agency = Estateagency::where('edit_token', $token)->first();

        if ($token && !$agency) {
            return redirect('/');
        }

        return view('auth.agency.register',['agency' => $agency]);
    }

    public function registerAgencyCampaign($key)
    {
        $campaign = Campaign::where('key', $key)->whereRaw('end_date >= CURDATE()')->first();

        if (!$campaign) {
            $data['error'] = trans('common.ExpiredCampaignCode');
        } else {
            $data['campaignKey'] = $campaign->key;
            $data['campaignMonths'] = $campaign->trial_months;
            $data['success'] = str_replace('{number}', $data['campaignMonths'], trans('common.CampaignSuccessMsg'));
        }

        return view('auth.agency.register', $data);
    }

    public function validateEmail(Request $request)
    {
        if ($request->ajax()){
            $validate = validator($request->all(),[
                'info_email' => 'required|unique:estate_agencies,info_email|unique:users,email'
            ]);

            if ($validate->fails()) {
                return response(['errors' => $validate->getMessageBag()], 403);
            }

            return 'Success';
        }
    }

    public function storeAgency(Request $request)
    {
        $rules = [
            'agency_title' => 'required',
            // 'info_email' => 'required',
            'invoice_address' => 'required',
            'contact_person' => 'required',
            'invoice_zip_code' => 'required',
            'invoice_city' => 'required',
            // 'zip' => 'min:4|max:10|regex:/^[ A-Z0-9]+$/',
            'zip' => 'min:3|max:10|regex:/(?i)^[a-z0-9][a-z0-9\- ]{0,10}[a-z0-9]$/',
            // 'phone' => 'max:13|regex:/^[0-9\+]+$/',
            'phone' => 'phonenumber',
            'website' => 'regex:[(http(s)?://)?([\w-]+\.)+[\w-]+(/[\w- ;,./?%&=]*)?]',
            'logo' => 'image',
            'campaign' => 'exists:campaigns,key',
            'vat_number' => 'vat',
            'g-recaptcha-response' => 'required|recaptcha',
        ];

        if ($request->agency_id)
            $rules['info_email'] = ['required', Rule::unique('estate_agencies')->ignore($request->agency_id) ];

        $validate = validator($request->all(), $rules);

        if ($validate->fails())
            return back()->withErrors($validate)->withInput();


        $agency = new Estateagency;

        if ($request->agency_id) {
            $agency = Estateagency::find($request->agency_id);
            $agency->edit_token = '';
        }

        $countryState = $this->getCountryStateCity($request);

        if ($request->campaign)
        {
            $campaign = Campaign::where('key', $request->campaign)->first();

            if (!$campaign)
                return back()->with('error', trans('common.ExpiredCampaignCode'));

            $agency->campaign_id = $campaign->id;
        }

        $agency->public_name = $request->agency_title;
        $agency->description = $request->agency_description;
        $agency->legal_companyname = $request->agency_title;
        $agency->info_email = $request->info_email;
        $agency->address_line_1 = $request->address_1;
        $agency->country_id = $countryState['countryId'];
        $agency->state_id = $countryState['stateId'];
        $agency->city_id = $countryState['cityId'];
        $agency->city = $request->city;
        $agency->zip_code = $request->zip;
        $agency->phone = $request->phone;
        $agency->website = $request->website;

        $agency->vat_number = $request->vat_number;
        $agency->invoice_address  = $request->invoice_address;
        $agency->invoice_country  = $request->invoice_country;
        $agency->invoice_city  = $request->invoice_city;
        $agency->invoice_zip_code  = $request->invoice_zip_code;
        $agency->logo = $request->hasFile('logo') ? $this->storeLogoImage($request, $agency) : null;
        $agency->save();

        if ($request->contact_person)
            Estateagencycontactperson::create(['estate_agency_id' => $agency->id, 'first_name' => $request->contact_person]);

        if ($agency->user) Auth::login($agency->user);

        if($request->package_select == '1' && !$request->campaign)
        {
            // $agency->user->package_id = '1';
            $agency->user->add_property_plan = '0';
            $agency->user->save();
        }

        if ($request->package_select > 1)
            return redirect(SITE_LANG.'/payment');

        return redirect(SITE_LANG.'/account/profile')->with('success',trans('common.CheckYourMailForLoginDetails'));
    }

    private function storeLogoImage($request, $agency)
    {
        $uploadFile = $request->file('logo');
        $fileNameWithPath = 'agency/logo/'.$agency->public_name.'-'.time();
        $fileNameWithPath = $fileNameWithPath.'.'.$uploadFile->getClientOriginalExtension();
        return $this->uploadFileToS3($uploadFile, $fileNameWithPath);
    }

   private function sendWelcomeEmail($request)
   {
       $userEmail = $request->email;
       \Mail::send('emails.welcome', ['user' => $request], function($message) use ($userEmail) {
           $message->to($userEmail)->subject( trans('emails.welcome_email_subject'));
       });
   }

   private function getCountryStateCity($request)
   {
       $country = Country::where('iso',$request->country)->first();
       $state   = State::where('name_en',$request->state)->first();
       $city    = City::where('name_en',$request->city)->first();

       $data['countryId'] = $country ? $country->id : ($state ? $state->country_id : ($city ? $city->country_id : ''));
       $data['stateId']   = $state ? $state->id : ($city ? $city->state_id : '');
       $data['cityId']    = $city ? $city->id : '';
       return $data;
   }
}
