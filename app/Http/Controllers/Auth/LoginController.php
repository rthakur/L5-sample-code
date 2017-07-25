<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Session, Auth;
use App\User;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = SITE_LANG. '/account/profile';
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }
    
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            $user = User::where('email', $request->email)->first();
            
            if (($user->role_id == 3 || $user->role_id == 4) && empty($user->agency)) {
                Auth::logout();
                return back()->with('message', trans('common.AgencyExistMsg'));
            }
            
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }
    
    public function logout()
    {
        Auth::logout();
        if (Session::has('auth_access')) Session::forget('auth_access');
        
        return redirect('/');
    }
    
    public function resetPassword(Request $request, $id)
    {
        $user = User::find(base64_decode($id));
        return view('auth.passwords.social.reset',compact('user'));
    }
    
    public function savePassword(Request $request)
    {
        $validate = \validator($request->all(), [
            'password' => 'required|min:6|confirmed'
        ]);
        if ($validate->fails()){
            $errors = $validate->getMessageBag()->all();
            
            
        foreach ($errors as $error){
            $errorMessage =  $error .'<br>';
        }
        $errorMessage = $errorMessage . trans('common.SocialLoginFailMsg');
            
        return redirect(SITE_LANG.'/register')->with('message', $errorMessage);
        }
        
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role_id = $request->role_id;
        $user->social_id = $request->social_id;
        $user->password = \Hash::make($request->password);
        $user->remember_token = \Hash::make(str_random(6));
        $user->save();
        
        Auth::login($user);
        
        $userEmail = $user->email;
        \Mail::send('emails.welcome', ['user' => $user], function($message) use ($userEmail) {
            $message->to($userEmail)->subject( trans('emails.welcome_email_subject'));
        });
        
        return redirect(SITE_LANG.'/account/profile');
    }
}
