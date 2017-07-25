<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Lang;

class State extends Model
{
    use SoftDeletes;
    public static $DISABLED_OBSERVER = false;
    public $timestamps = true;

    public static function lang_sk(State $state, $lang = APP_LANG)
    {
        if (Lang::has('states.' . $state->search_key, [], $lang)) {
            return Lang::get('states.' . $state->search_key, [], $lang);
        } else {
            return $state->name_en;
        }
    }

    public static function createArrayByCountry()
    {
        $statesByCountry = [];
        foreach (self::orderBy('name')->get() as $state) {
            $statesByCountry[$state->country_id][] = ['id' => $state->id, 'name' => trans('states.' . $state->id)];
        }
        return json_encode($statesByCountry);
    }

    public function country()
    {
        return $this->hasOne('App\Models\Country', 'id', 'country_id');
    }

    public function cities()
    {
        return $this->hasMany('App\Models\City', 'state_id', 'id');
    }

}
