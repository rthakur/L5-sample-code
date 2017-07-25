<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Lang;

class City extends Model
{
    use SoftDeletes;
    public static $DISABLED_OBSERVER = false;
    public $timestamps = true;

    public static function lang_sk(City $city, $lang = APP_LANG)
    {
        if (Lang::has('cities.' . $city->search_key, [], $lang)) {
            return Lang::get('cities.' . $city->search_key, [], $lang);
        } else {
            return $city->name_en;
        }
    }

    public function country()
    {
        return $this->hasOne('App\Models\Country', 'id', 'country_id');
    }

    public function state()
    {
        return $this->hasOne('App\Models\State', 'id', 'state_id');
    }

    public function weather()
    {
        return $this->hasMany('App\Models\Weather', 'city_id', 'id');
    }

    public static function findCityByName($name, $countryId)
    {
        $lwrName = strtolower($name);
        $city = self::selectRaw('*, LCASE(name_en) as name_lower')->where('country_id', $countryId)->having('name_lower', '=', $lwrName)->first();
        return $city ? $city->id : null;
    }

}
