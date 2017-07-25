<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cache;

class Features extends Model
{
    protected $table = 'features';
    public $timestamps = false;
    
    public function langName(){
      $name  = trans('viewproperty.'.str_replace(' ','',$this->name));
      $name = str_replace(["\'",'\"'], "'",$name);
      $name = str_replace(["->'",'->"'], "",$name);
      return $name;  
    }
    
    public static function getAllFeatures()
    {
      if (Cache::has('getAllFeatures'))
        return Cache::get('getAllFeatures');
        
      $allFeatures = self::orderBy('name')->get();
      Cache::put('getAllFeatures', $allFeatures, 24*60*3);
      return $allFeatures;
    }
    
}
