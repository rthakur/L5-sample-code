<?php

namespace App\Observers;

use App\Models\Estateagency;
use App\User;
use App\Models\Campaign;
use App, View;

class EstateagencyObserver
{
    protected $agency;
   
    /**
     *
     * Listen to the created event.
     * @return void
     */
    public function created(Estateagency $agency)
    {
        $this->agency = $agency;
        $this->createApiCredentials();
        $this->validateAndCreateUser();
    }

    /**
     * Listen to the deleting event.
     *
     * @return void
     */
    public function deleting(Estateagency $agency)
    {
        //
    }
    
    
    private function createApiCredentials(){
      $this->agency->api_key = md5($this->agency->info_email . time());
      $this->agency->secret_key = sha1($this->agency->info_email . time() . microtime());
      $this->agency->save();
    }
    
    
   private function validateAndCreateUser()
   {
      $user = User::where('email', $this->agency->info_email)->first();
      if($user)
      {
          //if user type agnecy and not assgin any agency to this user
          //if user type agnecy and user assigned agency not exists
          
          //if user has role_id as 3 or 4, its agency_id field cannot be null or user's agency can't be null.
          
          if($user->role_id == '4' && (empty($user->agency_id) || empty($user->agency)))
          {
            $user->agency_id = $this->agency->id;
            $user->save();
          }
          else
          {
            $this->createAccount($this->agency->id.time().'@miparo.com');
          }
      }
      else
      {
        $this->createAccount($this->agency->info_email);
      }
      
   }    
    
  
   private function createAccount($email)
   {
     $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
     $password = substr(str_shuffle($chars),0,6);
     $user = new User;
     $user->name = $this->agency->public_name;
     $user->email = $email;
     $user->password = \Hash::make($password);
     $user->role_id = '4';
     $user->phone = $this->agency->phone;
     $user->agency_id = $this->agency->id;
     
     if($user->agency->campaign_id)
     {
         $user->package_id = '5'; //package for campaign  
         $user->add_property_plan = '2';
     }

     $user->save();    
             
     $this->sendEmail($email, $password);
   }
     
   private function sendEmail($email,  $password)
   {
    $CampaignRegisterMessage = null;

    $user = User::where('email',$email)->first();
    
    if($user->package_id == '5'){
        $campaign = Campaign::where('id', $user->agency->campaign_id)->first();
        $campaignMonths = $campaign->trial_months ?: '';
        
        $CampaignRegisterMessage = str_replace(':attribute',$campaignMonths,trans('emails.CampaignWelcomeMsg'));  
    }
     
     \Mail::send('emails.register_email', ['userEmail' => $email, 'password' => $password , 'registerMessage' => $CampaignRegisterMessage], function($message) use ($email) {
       $message->to($email)->subject( trans('emails.register_email_subject') );
     });
  
   }  
}