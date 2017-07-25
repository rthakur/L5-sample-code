<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cache;

class PropertyTypes extends Model
{
    protected $table = 'property_types';
    public $timestamps = false;
    public static $typeIcons = [
        'apartment' => 'flaticon-building',
        'family_town_house' => 'flaticon-home-1',
        'house' => 'flaticon-internet',
        'cottage' => 'flaticon-buildings-2',
        'commercial' => 'flaticon-front',
        'land' => 'flaticon-buildings-1',
        'farm_ranches' => 'flaticon-home',
        'parking' => 'flaticon-transport-2',
        'castle' => 'flaticon-monument',
        'island' => 'flaticon-beach',
        'timeshare' => 'flaticon-luxury',
    ];
    
    public static function getAllTypes()
    {
      return self::all()->pluck('name', 'search_key');
    }
    
    public static function getGroupedTypes()
    {
      $groupedTypes = [];
      $groupedTypes['living'][trans('common.WithMonthlyFees')] = ['apartment', 'family_town_house'];
      $groupedTypes['living'][trans('common.WithoutMonthlyFees')] = ['house', 'cottage'];
      $groupedTypes['others'] = ['commercial', 'land', 'farm_ranches', 'parking', 'castle', 'island', 'timeshare'];
      
      return $groupedTypes;
    }
    
}
