<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Socialite, Auth, Session;

class SocialController extends Controller
{
    protected $redirectTo = SITE_LANG;
  
    public function redirectToFacebook()
    {
      return Socialite::driver('facebook')->redirect();
    }

    public function facebookCallback(Request $request)
    {
      try {
          $user = Socialite::driver('facebook')->user();
      } catch(Exception $ex) {
          return redirect('errors.403');
      }
      
      return $this->commonCallback($user);
    }

    public function redirectToGoogle()
    {
      return Socialite::driver('google')->redirect();
    }

    public function googleCallback(Request $request)
    {
      try {
          if(isset($request->error) && $request->error == 'access_denied'){
              $errorMessage = trans('common.AccessDeniedMsg').trans('common.SocialLoginFailMsg');
              
              return redirect(SITE_LANG.'/register')->with('message' , $errorMessage);
          }
            
          $user = Socialite::driver('google')->user();
      } catch(Exception $ex) {
        return redirect('errors.403');
      }
      
      return $this->commonCallback($user);
    }
    
    private function commonCallback($user)
    {
        $user_details = User::where('social_id', $user->id)->first();

        if (empty($user_details) && isset($user) && isset($user->email))
            $user_details = User::where('email', $user->email)->first();
      
        if (empty($user_details)) {
            
            if(isset($user) && !isset($user->email))
                return redirect(SITE_LANG.'/register')->with('message', 'Sorry!! We don\'t have access to your email.');
            
            
            $user = [
                'name' => $user->name?: '',
                'email' => $user->email,
                'role_id' => '2',
                'social_id' => $user->id
            ];
            
            return view('auth.passwords.social.reset',compact('user'));
            
            // return redirect(SITE_LANG.'/social/reset-password/'.base64_encode($user_details->id));
      }
     
      Auth::login($user_details);
      return redirect($this->redirectTo);
  }
}
