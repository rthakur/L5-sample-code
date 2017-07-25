<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Package;
use App\User;
use Auth, Exception, Carbon\Carbon;

class PaymentController extends Controller
{
  public function getIndex()
  {
      $package = Package::find(2);
      if (!$package && Auth::check())
          return view('errors.403');
      
      return view('payment.index', [ 'package' => $package ]);
  }
  
  public function checkout(Request $request)
  {
      $user = Auth::user();
      $userActiveSubscription = $user->package_id;
      
      if (empty($user->agency)) 
          return redirect('/403');
      
      $user->newSubscription('main', 'professional')->create($request->stripeToken);
      $user->syncronize_plan_request = (in_array($user->syncronize_plan_request,['2','3'])) ? '0' : $user->syncronize_plan_request;
      $user->package_id = $request->package_id;
      $user->add_property_plan = 1;
      $user->save();  
      
      
      if($userActiveSubscription == '1')
        return redirect(SITE_LANG.'/property/plans')->with('success',trans('common.NowYouCanChoose'));
     
      return redirect(SITE_LANG.'/account/agency/subscription');
  }
  
    public function getUpdate($plan_id = null)
    {
        $user = Auth::user();
        
        try {
            if ($plan_id != 1)
            {
                if ($user->subscribed('main')) {
                    $user->add_property_plan = 1;
                    $user->subscription('main')->resume();
                } else {
                    $package = Package::find($plan_id);
                    return redirect(SITE_LANG.'/payment')->with('package',$package);
                }
                
            } elseif ($user->subscribed('main')) {
                $user->add_property_plan = 1;
                $user->subscription('main')->cancel();
            }elseif ($user->package_id == '5') {
                $user->add_property_plan = 1;
                $user->syncronize_plan_request =  '0';
            }
            
            $user->syncronize_plan_request = (in_array($user->syncronize_plan_request,['2','3'])) ? '0' : $user->syncronize_plan_request;
            $user->package_id = $plan_id;
            $user->save();
          
      } catch(Exception $e) {
          \Session::flash('warning', trans('common.TryAgain'));
          \Log::error($e);
          return back();
      } 
      
      return back()->with('success', trans('common.ChangePkgPlanSuccessMsg'));
  }
  
  
}
