<?php

namespace App\Observers;

Use App\Models\Country;
use App\Helpers\LocationsSKHelper;


class CountriesObserver
{


    public function saved(Country $country)
    {
        if ($country->isDirty() && !$country::$DISABLED_OBSERVER) {
            $updating = $country->getDirty();
            if (isset($updating['name_en'])) {
                $country::$DISABLED_OBSERVER = true;
                $country = LocationsSKHelper::generate($country, 'countries');
                $country->save();
                $country::$DISABLED_OBSERVER = false;
            }
        }
    }

    public function deleted(Country $country)
    {
        $states = $country->states()->get();
        if (count($states)) {
            foreach ($states as $state) {
                $state->delete();
            }
        }
    }
}