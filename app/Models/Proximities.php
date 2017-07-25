<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cache;

class Proximities extends Model
{
    protected $table = 'proximities';
    public $timestamps = false;
    protected $guarded = [];
    
    public function langName()
    {
      $name  = trans('viewproperty.'.str_replace(' ','',$this->name));
      $name = str_replace(["\'",'\"'], "'",$name);
      $name = str_replace(["->'",'->"'], "",$name);
      return $name;   
    }
    
    public static function getAllProximities()
    {
      if (Cache::has('getAllProximities'))
        return Cache::get('getAllProximities');
        
        
      $getAllProximities = self::orderBy('name')->get();
      Cache::put('getAllProximities', $getAllProximities, 24*60*3);
      return $getAllProximities;
    }
    
}
