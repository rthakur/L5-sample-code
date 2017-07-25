<?php

namespace App\Observers;

use App\Models\Property;
Use App\Helpers\PropertyPricesHelper;
use App\Helpers\PropertyLocationsHelper;
use App\Helpers\PropertyZoomLevelHelper;
use Cache;

class PropertyObserver
{

    public function saving(Property $prop)
    {   
         if ($prop->isDirty() && !$prop::$DISABLED_OBSERVER) {
             
             Cache::flush();
             
            $updating = $prop->getDirty();

            if (isset($updating['price']) || isset($updating['price_currency_id']) || isset($updating['monthly_fee']) || isset($updating['monthly_fee_currency_id'])) {
                $prop::$DISABLED_OBSERVER = true;
                PropertyPricesHelper::reCount($prop);
                $prop::$DISABLED_OBSERVER = false;
            }
            
            if (!empty($updating['geo_lat']) && !empty($updating['geo_lng'])) {    
                PropertyZoomLevelHelper::setPropertyZoomLevel($prop);
            }

        }
        
        return;
    }

    public function saved(Property $prop)
    {
        if ($prop->api_updated_at == 0) {
            return;
        }
        
        if ($prop->isDirty() && !$prop::$DISABLED_OBSERVER) {
            $updating = $prop->getDirty();

            if ((isset($updating['geo_lat']) && $updating['geo_lat'] != null && $updating['geo_lat'] != '')
                || (isset($updating['geo_lng']) && $updating['geo_lng'] != null && $updating['geo_lng'] != '')
            ) {
                if ($prop->state_id == null || $prop->city_id == null || $prop->state_id == '' || $prop->city_id == '') {
                    $prop::$DISABLED_OBSERVER = true;
                    PropertyLocationsHelper::detect($prop);
                    $prop::$DISABLED_OBSERVER = false;
                }
            }
        }
    }

}