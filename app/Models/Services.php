<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Services extends Model
{
    protected $table = 'services';
    public $timestamps = false;
    
    public function langName(){
      $name  = trans('viewproperty.'.str_replace(' ','',$this->search_key));
      $name = str_replace(["\'",'\"'], "'",$name);
      $name = str_replace(["->'",'->"'], "",$name);
      return $name;   
    }
}
