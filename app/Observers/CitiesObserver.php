<?php
/**
 * Created by PhpStorm.
 * User: master
 * Date: 4/6/17
 * Time: 8:57 PM
 */

namespace App\Observers;

use App\Models\City;
use App\Helpers\LocationsSKHelper;


class CitiesObserver
{

    public function saved(City $city)
    {
        if ($city->isDirty() && !$city::$DISABLED_OBSERVER) {
            $updating = $city->getDirty();
            if (isset($updating['name_en'])) {
                $city::$DISABLED_OBSERVER = true;
                $city = LocationsSKHelper::generate($city, 'cities');
                $city->save();
                $city::$DISABLED_OBSERVER = false;
            }
        }
    }

}