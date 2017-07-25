<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cache;

class CurrencyRates extends Model
{
    protected $guarded = [];
    public static $DISABLED_OBSERVER = false;


    public static function getCurrencyRates($currency)
    {
        if (Cache::has('exchangeRates_' . $currency)) return Cache::get('exchangeRates_' . $currency);

        $exchangeRates = CurrencyRates::where('convert_currency_id', $currency)->get();
        $exchangeRates = array_pluck($exchangeRates, 'exchange_rate', 'base_currency_id');

        if ($exchangeRates)
            Cache::put('exchangeRates_' . $currency, $exchangeRates, 60 * 6);

        return $exchangeRates;

    }

    public function base_currency()
    {
        return $this->hasOne('App\Models\Currency', 'id', 'base_currency_id');
    }

    public function convert_currency()
    {
        return $this->hasOne('App\Models\Currency', 'id', 'convert_currency_id');
    }

}
