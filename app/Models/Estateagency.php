<?php

namespace App\Models;

use App;
use App\Models\Country;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\CommonHelper;

class Estateagency extends Model
{
    use SoftDeletes;
    
    protected $table = 'estate_agencies';
    protected $guarded = [];
    
    protected $dates = ['deleted_at'];

    public function user()
    {
        return $this->hasOne('App\User', 'agency_id', 'id')->where('role_id', '4');
    }

    public function agents()
    {
        return $this->hasMany('App\User', 'agency_id', 'id')->where('role_id', '3');
    }
    
    public function agentsWithProperties()
    {
        return $this->selectRaw('estate_agencies.*, users.*, count(property.id) as propertiesCount')->join('users', function($join){
                                            $join->on('estate_agencies.id', '=', 'users.agency_id')
                                                ->where('role_id', '3');
                                        })->join('property',function($join){
                                            $join->on('users.id', '=', 'property.agent_id')
                                                 ->where('property.deleted_at',null);
                                        })->groupBy('users.id');
    }

    public function contactPerson()
    {
        return $this->hasOne('App\Models\Estateagencycontactperson', 'estate_agency_id');
    }

    public function properties()
    {
        return $this->hasMany('App\Models\Property', 'agency_id', 'id');
    }

    public function country()
    {
        return $this->hasOne('App\Models\Country', 'id', 'country_id');
    }

    public function getCountry($id)
    {
        return Country::find($id);
    }

    public function state()
    {
        return $this->hasOne('App\Models\State', 'id', 'state_id');
    }

    public function api_errors()
    {
        return $this->hasMany('App\Models\ApiErrorReporting', 'agency_id', 'id');
    }
    
    public function campaign()
    {
        return $this->hasOne('App\Models\Campaign', 'id', 'campaign_id');
    }

    public function formatPhone()
    {
        if (empty($this->phone)) return '';
        $phone = str_replace(' ', '', $this->phone);

        if (strpos($this->phone, '(') !== false) return $this->phone;

        if (substr($phone, 0, 2) == '91' || substr($phone, 0, 3) == '+91')
            return substr($phone, 0, 2) . ' (0) ' . substr($phone, 2, 3) . ' ' . substr($phone, 5, 3) . ' ' . substr($phone, 8, 6);


        return substr($phone, 0, 3) . ' (0) ' . substr($phone, 3, 3) . ' ' . substr($phone, 6, 3) . ' ' . substr($phone, 9, 6);
    }

    public function detailPageURL($data = NULL)
    { 
        if ($data == 'agent') {
            $urlData =  [
                '/'. App::getLocale(),
                trans('seolinks.realestateagent'),
                $this->id,
                CommonHelper::cleanString($this->public_name),
                CommonHelper::cleanString($this->name)
            ]; 
        } else {
            $urlData = [
                '/'. App::getLocale(),
                trans('countries.' . $this->country_id),
                CommonHelper::cleanString(trans('states.' . $this->state_id)),
                CommonHelper::cleanString(trans('seolinks.realestateagency')),
                $this->id,
                CommonHelper::cleanString($this->public_name)
            ];
        }
        
        return implode('/', $urlData); // combine url parts into a string and return
    }
    
    public function messages()
    {
        return $this->hasMany('App\Models\Message', 'agency_id', 'id')->where('type', 'agency_contact');
    }
    
    public function getUnreadMessages()
    {
        $unreadMessages = $this->messages()->where('status', '0');

        return $unreadMessages->count() ? $unreadMessages : false; 
    }
    
    public function markAllMessagesAsRead()
    {
      $messages = $this->getUnreadMessages();
      if (isset($messages) && count($messages) && $messages) {
        $messages->update([ 'status' => '1' ]);
      }
    }
}
