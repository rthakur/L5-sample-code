<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Estateagency as Agency;
use App\Models\Message;
use App\User;
use App\Models\Estateagencycontactperson;
use App\Models\Property;
use Auth, App;
use App\Helpers\CommonHelper;

class AgencyController extends Controller
{
    /**
     * Gets Agency list page.
     *
     * @return view
     */
    public function getIndex()
    {
        $agencies = Agency::paginate(6);
        return view('front.agency.index', [ 'agencies' => $agencies ]);
    }
  
    /**
     * Gets Agency detail page.
     *
     * @return view
     */
    public function getDetail($country, $state, $realestateagency, $agencyId, $agencyName)
    {
        $agency = Agency::find($agencyId);
        
        if(!$agency) return redirect(SITE_LANG.'/404');
        
        $data['agencyUser'] = $agency->user;
        
        if (empty($agency) || CommonHelper::cleanString($agency->public_name) != $agencyName) {
            return App::abort('404');
        }
        
        ## Gets Agency's all properties
        $agentsproperties = Property::select('property.*')->where('property.agency_id',$agencyId);
        
        ## Gets Agency's all agents
        $data['agents'] = $agency->agentsWithProperties()->where('estate_agencies.id',$agency->id)->get();
        
        $data['agency'] = $agency;
        $data['with_search_bar'] = true;
        $data['agentsproperties'] = $agentsproperties->paginate(20);
        return view('front.agency.detail', $data);
    }
   
    /**
     * Send contact message to Agency.
     *
     * @return response
     */
    public function postSendmessage(Request $request)
    {
        $validate = validator($request->all(),[
            'agency_id' => 'required|exists:estate_agencies,id',
            'sender_name' => 'required',
            'sender_email' => 'required|email',
            'message' => 'required',
            'g-recaptcha-response' => 'required|recaptcha',
        ]);
     
        if ($validate->fails()) {
            return response(['errors' => $validate->getMessageBag()], 403);
        }
        $message = new Message;
        $estateagency = Agency::select('id', 'info_email')->find($request->agency_id);
         
        $message->entry_by = Auth::check() ? Auth::id() : null;
        $message->sender_name = $request->sender_name;
        $message->sender_email = $request->sender_email;
        $message->to_email = $estateagency->info_email;
        $message->message_text = $request->message;
        $message->agency_id = $estateagency->id;
        $message->type = 'agency_contact';
        $message->save();
        
        $data['message'] = trans('common.ThanksContacting');
         
        return $data;
     }
     
     /**
      * Creates New Agency.
      *
      * @return response
      */
     public function postCreateAgency(Request $request)
     {
         $user = Auth::user();
         $agency = $user->agency;
         
         $tab = $request->form_type ? $request->form_type : 'contact_info'; 
         
         if((isset($request->form_type) && $request->form_type == 'contact_info'))
         {
             $validate = validator($request->all(),[
                 'agency_title' => 'required',
                 'phone' => 'phonenumber',
                //  'phone' => 'max:13|regex:/^[0-9\+]+$/',
                 'address_1' => 'required',
                 'city' => 'required',
                 'contact_person' => 'required',
                 'zip' => 'required|min:4|max:10|regex:/^[ A-Z0-9]+$/',
                 'email' => 'required|email|unique:virtual_assistants,email|unique:users,email,'.$user->id.'|unique:estate_agencies,info_email,'.$agency->id,
                 'logo' => 'image',
             ]);
             if ($validate->fails())
                return back()->withErrors($validate)->withInput()->with('tab',$tab);
         }
         
         if((isset($request->form_type) && $request->form_type == 'invoice_info'))
         {
             $validate = validator($request->all(),[
                 'agency_title' => 'required',
                 'invoice_address' => 'required',
                 'contact_person' => 'required',
                 'invoice_zip_code' => 'required|min:4|max:10|regex:/^[ A-Z0-9]+$/',
                 'invoice_city' => 'required',
                 'logo' => 'image',
                //  'vat_number' => 'vat'
             ]);
             
             if ($validate->fails())
                return back()->withErrors($validate)->withInput()->with('tab',$tab);
         }
         
         $agencyData = [
             'public_name' => $request->agency_title,
             'legal_companyname' => $request->agency_title,
             'description' => $request->agency_description,
             'address_line_1' => $request->address_1,
             'city' => $request->city,
             'zip_code' => $request->zip,
             'info_email' => $request->email,
             'phone' => $request->phone ?: '',
             'website' => $request->website ?: '',
             'country_id' => $request->country_id,
         ];
      
         if ($agency) {
             
             if ($request->hasFile('logo')) 
             {    //update logo
                 $uploadFile = $request->file('logo');
                 $fileNameWithPath = 'agency/logo/'.$agency->public_name.'-'.time();
                 $fileNameWithPath = $fileNameWithPath.'.'.$uploadFile->getClientOriginalExtension();
                 $agencyData['logo'] = $this->uploadFileToS3($uploadFile, $fileNameWithPath, $agency->logo);
             } 
             
             if((isset($request->form_type) && $request->form_type == 'invoice_info'))
             {
                 $agency->vat_number = $request->vat_number;
                 $agency->invoice_address  = $request->invoice_address;
                 $agency->invoice_country  = $request->invoice_country;
                 $agency->invoice_city  = $request->invoice_city;
                 $agency->invoice_zip_code  = $request->invoice_zip_code;
                 $agencyData = [
                     'public_name' => $request->agency_title,
                     'legal_companyname' => $request->agency_title,
                 ];
             }
             
      
             if ($request->contact_person) 
             {     //update agency contact person
                 $estateAgencyContact = Estateagencycontactperson::firstorNew(['estate_agency_id' => $agency->id]);
                 $estateAgencyContact->first_name = $request->contact_person;
                 $estateAgencyContact->save();
             }
          
             $agency->update($agencyData);   
            return redirect(SITE_LANG.'/account/agency/details')->with('tab',$tab)->with('success', trans('common.SuccessfullyUpdated')); 
         }
      
         $agency = Agency::create($agencyData);
         $agency->save();
      
         $user->agency_id = $agency->id; //update agency user
         $user->save();
      
         return redirect(SITE_LANG.'/account/agency/details')->with('tab', 'contact_info'); 
     }
     
     
     public function postAgencyLogo(Request $request)
     {
         $agency = Auth::user()->agency;
         
         if ($request->hasFile('logo')) {    //update logo
             $uploadFile = $request->file('logo');
             $fileNameWithPath = 'agency/logo/'.$agency->public_name.'-'.time();
             $fileNameWithPath = $fileNameWithPath.'.'.$uploadFile->getClientOriginalExtension();
             $agencyData['logo'] = $this->uploadFileToS3($uploadFile, $fileNameWithPath, $agency->logo);
         }
         
         $agency->update($agencyData);
         
         return $agency->logo;
     }
     
     public function removeLogo($user_id)
     {
         if (Auth::id() != base64_decode($user_id)) {
             return response(['errors' => ['profile_pic' => 'something went wrong']], 403);
         }
         
          $user = Auth::user();
          
          if($user->agency)
             $this->s3Delete($user->agency->logo);
          
          $agencyData['logo'] = null;
          
          $user->agency->update($agencyData);
          
          
          return $user->getLogo();
         
     }
}