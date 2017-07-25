<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Message extends Model
{
    use SoftDeletes;
    
    protected $guarded = array();
    
    public function user()
    {
      return $this->hasOne('App\User', 'id', 'user_id');
    }
    
    public function agency()
    {
      return $this->hasOne('App\Models\Estateagency', 'id', 'agency_id');
    }
    
    public function typeName()
    {
      $type = $this->type;
      
      if($type == 'agent_contact' && $this->user)
        return '<a target="_new" href="'.$this->user->detailPageURL().'">'.$this->user->name.'</a>';
        
      if($type == 'agency_contact' && $this->agency)
        return '<a target="_new" href="'.$this->agency->detailPageURL().'">'.$this->agency->public_name.'</a>';
      
      return '';
    }
    
    public function addedDate()
    {
      return isset($this->created_at) ? date( 'd/m/Y', strtotime($this->created_at) ) : '';
    }
}
