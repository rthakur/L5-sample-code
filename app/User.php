<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Database\Eloquent\SoftDeletes;
use App, Carbon\Carbon;
use Laravel\Cashier\Billable;
use App\Models\MapMovement;
use App\Helpers\CommonHelper;
use App\Models\Campaign;
use App\Models\Estateagency as Agency;
use Auth;

class User extends Authenticatable
{
    use SoftDeletes;
    use Notifiable;
    use Billable;
    
    public static $DISABLED_OBSERVER = false;
    

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role_id', 'agency_id', 'receive_newsletter', 'profile_picture', 'phone', 'mobile', 'skype', 'about_me', 'twitter', 'facebook', 'pinterest', 'social_id','access_token'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public function role()
    {
        return $this->hasOne('App\Models\Role','id','role_id');
    }

    public function agency()
    {
        return $this->belongsTo('App\Models\Estateagency', 'agency_id', 'id');
    }

    public function is_agent($field)
    {
        if ($this->role_id == 3)
            return $this->$field;
        else
            return false;
    }

    public function properties()
    {
        return $this->hasMany('App\Models\Property', 'agent_id', 'id');
    }

    public function boobkmarked()
    {
        return $this->hasOne('App\Models\BookmarkedProperty', 'user_id', 'id');
    }

    public function country()
    {
        return $this->hasOne('App\Models\Country', 'id', 'country_id');
    }

    public function package()
    {
        return $this->hasOne('App\Models\Package', 'id', 'package_id');
    }

    public function formatPhone()
    {
        if (empty($this->phone)) return "";

        $phone = str_replace(' ', '', $this->phone);
        return substr($phone, 0, 3) . ' (0) ' . substr($phone, 3, 3) . ' ' . substr($phone, 6, 3) . ' ' . substr($phone, 9, 3);
    }
    
    public static function getAllAgents()
    {
        return self::where('role_id','3')->get();
    }

    public function detailPageURL()
    {
      //  $country = isset($agent->country) ? CommonHelper::cleanString($agent->country->name_en) : trans('seolinks.unknown');
        $agentAgency = isset($this->agency) ? CommonHelper::cleanString($this->agency->public_name) : trans('seolinks.unknown');
        $agentUrl = SITE_LANG . '/' . trans('seolinks.realestateagent') . '/' . $this->id . '/' . $agentAgency . '/' . CommonHelper::cleanString($this->name);
        return $agentUrl;
    }

    public function getProfilePicture()
    {
        return isset($this->profile_picture) ? $this->profile_picture : '/assets/img/agent-01.jpg';
    }
    
    public function getLogo()
    {
        return isset($this->agency->logo) ? $this->agency->logo : '/assets/img/agent-01.jpg';
    }

    public function api_format()
    {
        $agent = [
            'email' => $this->email,
            'name' => $this->name,
            'profile_picture' => $this->getProfilePicture(),
            'phone' => $this->phone,
            'about_me' => $this->about_me,
            'skype' => $this->skype,
            'twitter' => $this->twitter,
            'facebook' => $this->facebook,
            'pinterest' => $this->pinterest,
            'mobile' => $this->mobile
        ];

        return $agent;
    }

    /**
     * Send the password reset notification.
     *
     * @param  string $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    public function getLastMapMovement()
    {
        return MapMovement::where('user_id', $this->id)->get()->last();
    }
    
    public function taxPercentage()
    {
        $country = $this->agency->country;
        if ($country && $country->search_key == 'Sweden')
            return 25;
        
        return 0;
    }
    
    public function vatText()
    {
      $country = $this->agency->country;
      
      if ($country && $country->search_key == 'Sweden')
          return trans('invoice.SwedenVATText');

      if ($country && $country->european_union_member)
          return trans('invoice.OutsideEuropeanUnionVATText');

      return trans('invoice.OutsideEuropeanUnionVATText');  
    }
    
    public function agencyCountry()
    {
        return $this->agency->country;
    }
    
    
    public function checkAllowToAddProperty()
    {
        if (
            ($this->role_id == '3' && $this->allow_to_add) ||
            $this->role_id == '4'
        ) {
            return $this->checkAgencySubscription($this->agency_id);
        } 
        
        return false;
    }
    
    private function checkAgencySubscription($agency_id)
    {
        $agency = Agency::find($agency_id);
        
        if (!$agency) {
            return false;
        }
        
        if ($this->role_id == 3) {
            $user = User::where('agency_id', $agency->id)->where('role_id', '4')->first();
        } else {
            $user = $this;
        }
        
        if (empty($user)) {
            return false;
        }
        
        if (
            in_array($user->package_id, [2, 3, 4]) &&
            $user->subscription('main') &&
            !$user->subscription('main')->cancelled()
        ) {
            return true;
        }
        
        $campaign = Campaign::find($agency->campaign_id);
            
        if (!empty($campaign) && $user->package_id == 5) {
            
            $createdAt = Carbon::createFromFormat('Y-m-d H:i:s', $agency->created_at);
            
            //adds trial months to the account created date
            $createdAt = $createdAt->addMonths($campaign->trial_months);
            
            if (Carbon::now()->lte($createdAt)) {
                return true;
            }
              
        }
        
        return false;
    }
    
    public function checkPropertyAccess($property_id)
    {
        if (
            $this->role_id == 1 ||
            ( $this->role_id == 3 && 
                ($this->properties->find($property_id) || 
                ( $this->allow_to_edit && $this->agency->properties->find($property_id) ) ) 
            ) ||
            ( $this->role_id == 4 && $this->agency && $this->agency->properties()->find($property_id) )
        ) {
            return true;
        }
        
        return false;
    }
    
    public function messages()
    {
        if ($this->role_id == 1)
            return $this->hasMany('App\Models\Message')->where('type', 'contact_us');
        
        return $this->hasMany('App\Models\Message')->where('type', 'agent_contact');
    }
    
    public function getUnreadMessages()
    {
        if ($this->role_id != 3 && $this->role_id != 1)
            return false;
        
        $unreadMessages = $this->messages()->where('status', '0');
        return count($unreadMessages) ? $unreadMessages : false; 
    }
    
    public function markAllMessagesAsRead()
    {
        $messages = $this->getUnreadMessages();
        if (isset($messages) && count($messages))
            $messages->update([ 'status' => '1' ]);
    }
    
    public function bookmarkedProperties()
    {
        return $this->belongsToMany('App\Models\Property', 'bookmarked_properties', 'user_id', 'property_id');
    }
    
    public function checkCampaignExpiry()
    {
        $thisAgency = $this->agency;
        
        if (($this->role_id == 3 || $this->role_id == 4) && !empty($thisAgency->campaign)) {
            $agencyUser = ($this->role_id == 4) ? $this : ($thisAgency ? $thisAgency->user : null);
            
            if (
                $agencyUser && 
                $thisAgency->campaign && 
                $agencyUser->package_id == 5 && 
                $thisAgency->campaign->checkCampaignExpiry($agencyUser)
            ) {
                $agencyUser->package_id = 1;
                $agencyUser->save();
            }
            
        }
        
    }
    
    public function allowToDeleteProperty($property)
    {
        $user = Auth::user();
        
        $adminCheck = $user->role_id == '1';
        $agentCheck = $user->role_id == '3' && ($property->agent_id == $user->id || $user->allow_to_delete == 1);
        $agencyCheck = $user->role_id == '4' && $property->agency_id == $user->agency_id;
        
        return ($adminCheck || $agentCheck || $agencyCheck);
    }
    
    
    public function getAPIPackage()
    {
      return $this->add_property_plan;    
    }

    public function lastSyncStatus()
    {
        return $this->hasMany('App\Models\SyncPropertyRequest', 'user_id', 'id')->orderBy('created_at', 'desc')->first();
    }
    
}
