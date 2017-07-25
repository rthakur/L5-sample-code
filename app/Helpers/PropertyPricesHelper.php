<?php
namespace App\Helpers;

use App\Models\Property;
use App\Models\Currency;
use App\Models\CurrencyRates;

class PropertyPricesHelper
{

    public static function reCount(Property $prop)
    {
        $base_cur_price = Currency::where('id', $prop->price_currency_id)->first();
        $base_cur_fee = Currency::where('id', $prop->monthly_fee_currency_id)->first();
        $convert_cur = Currency::get_eur();
        if ($base_cur_price) {
            $rate = CurrencyRates::where('base_currency_id', $base_cur_price->id)
                ->where('convert_currency_id', $convert_cur->id)->first();
            $prop->eur_price = $prop->price * $rate->exchange_rate;
        }

        if ($base_cur_fee) {
            $rate = CurrencyRates::where('base_currency_id', $base_cur_fee->id)
                ->where('convert_currency_id', $convert_cur->id)->first();
            $prop->eur_monthly_fee = $prop->monthly_fee * $rate->exchange_rate;
        }

        $prop->save();

        return $prop;
    }
    
}