<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $guarded = [];
    public $timestamps = false;

    public function convert_rates()
    {
        return $this->hasMany('App\Models\CurrencyRates', 'convert_currency_id', 'id');
    }

    public function base_rates()
    {
        return $this->hasMany('App\Models\CurrencyRates', 'base_currency_id', 'id');
    }

    public static function get_eur()
    {
        return self::where('currency', 'EUR')->first();
    }

    public static function get_no_eur()
    {
        return self::where('currency', '<>', 'EUR')->get();
    }

    public static function get_no_eur_exchangeable()
    {
        return self::where('currency', '<>', 'EUR')->where('exchangeable', 1)->get();
    }

    public static function get_exchangeable()
    {
        return self::where('exchangeable', 1)->get();
    }
    
    public static function allCurrenciesWithCountries()
    {
      return self::select('currencies.id', 'currencies.currency', 'currencies.symbol', 'countries.iso as country_code')
      ->join('countries', 'countries.currency_id', '=', 'currencies.id')
      ->where('currencies.currency','!=','(none)')
      ->where('exchangeable','!=', 0)
      ->groupBy('currency')
      ->orderBy('currency_order', 'asc')
      ->get();
    }

}
