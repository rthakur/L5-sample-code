<?php
namespace App\Helpers;

use App\User;
use App\Models\Package;

class UsersSubscriptionHelper
{

    public static function sendEmail(User $user)
    {
        $emailText = '';
        
            if($user->package_id == '1')
                $emailText = str_replace('<useremail>',$user->agency->public_name,trans('emails.FreePackageSubscription'));
                
            if($user->package_id == '2')
                $emailText = str_replace('<useremail>',$user->agency->public_name,trans('emails.ProfessionalPackageSubscription'));
                    
            if($user->package_id == '3')
                $emailText = str_replace('<useremail>',$user->agency->public_name,trans('emails.ProfessionalWithApiPackageSubscription'));
                    
            if($user->package_id == '4')
                $emailText = str_replace('<useremail>',$user->agency->public_name,trans('emails.ProfessionalSyncronizePackageSubscription'));
                    
            if($user->package_id == '5' && $user->add_property_plan == '1')
                $emailText = str_replace('<useremail>',$user->agency->public_name,trans('emails.CampaignPackageSubscription'));
            
            if($user->package_id == '5' && $user->add_property_plan == '2')
                $emailText = str_replace('<useremail>',$user->agency->public_name,trans('emails.CampaignApiPackageSubscription'));

            if($user->package_id == '5' && $user->add_property_plan == '3')
                $emailText = str_replace('<useremail>',$user->agency->public_name,trans('emails.CampaignSyncronizePackageSubscription'));
                
            
                
        \Mail::send('emails.subscription_detail',['userDetail' => $user,'emailText' => $emailText], 
        function ($message) use ($user) {
            $message->to($user->email)->subject(trans('emails.subscription_detail'));
        });
        
        return $user;
    }
    
    public static function sendSyncronizeMessageEmail(User $user)
    {
        $emailText = '';
        
            if($user->syncronize_plan_request == '1')
              $emailText = str_replace('<useremail>',$user->agency->public_name,trans('emails.SyncronizeRequestSentMail'));
              
            if($user->syncronize_plan_request == '3')
              $emailText = str_replace('<useremail>',$user->agency->public_name,trans('emails.SyncronizeDenialMail'));
            
        \Mail::send('emails.subscription_detail',['userDetail' => $user,'emailText' => $emailText], 
        function ($message) use ($user) {
            $message->to($user->email)->subject(trans('emails.subscription_detail'));
        });
        
        return $user;
    }
    
}