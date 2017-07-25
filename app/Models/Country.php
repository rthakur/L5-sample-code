<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Lang;

class Country extends Model
{
    use SoftDeletes;
    public static $DISABLED_OBSERVER = false;
    public $timestamps = true;

    public static function lang_sk(Country $country, $lang = APP_LANG)
    {
        if (Lang::has('countries.' . $country->search_key, [], $lang)) {
            return Lang::get('countries.' . $country->search_key, [], $lang);
        } else {
            return $country->name_en;
        }
    }

    public static function onlyCountryHaveStateCity()
    {
        return self::select('countries.name', 'countries.id')
            ->join('states', 'countries.id', '=', 'states.country_id')
            ->join('cities', 'countries.id', '=', 'cities.country_id')
            ->groupBy('countries.id')
            ->get();
    }

    public function states()
    {
        return $this->hasMany('App\Models\State', 'country_id', 'id');
    }

    public function cities()
    {
        return $this->hasMany('App\Models\City', 'country_id', 'id');
    }

    public function agencies()
    {
        return $this->hasMany('App\Models\Estateagency', 'country_id', 'id');
    }

    public function currency()
    {
        return $this->hasOne('App\Models\Currency', 'id', 'currency_id');
    }
}
