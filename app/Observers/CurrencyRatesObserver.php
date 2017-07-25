<?php

namespace App\Observers;

use App\Models\Currency;
use App\Models\CurrencyRates;
use App\Helpers\PropertyPricesHelper;
use App\Models\Property;


class CurrencyRatesObserver
{

    public function saved(CurrencyRates $rate)
    {
        if ($rate->isDirty() && !$rate::$DISABLED_OBSERVER) {
            $rate::$DISABLED_OBSERVER = true;

            $updating = $rate->getDirty();
            $eur = Currency::get_eur();

            if (isset($updating['exchange_rate']) && $rate->convert_currency_id == $eur->id) {
                $props = Property::where('price_currency_id', $rate->base_currency_id)
                    ->orWhere('monthly_fee_currency_id', $rate->base_currency_id)->get();
                if (count($props)) {
                    foreach ($props as $prop) {
                        $prop::$DISABLED_OBSERVER = true;
                        PropertyPricesHelper::reCount($prop);
                        $prop::$DISABLED_OBSERVER = false;
                    }
                }
            }
        }
        return;
    }

}