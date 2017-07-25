<?php

namespace App\Helpers;

use App\Models\Property;
use App\Models\Zones;

class PropertyZoomLevelHelper
{

    public static function setPropertyZoomLevel(Property $prop)
    {
        for($zoomlevel = 3; $zoomlevel <= 10; $zoomlevel++) {
            
            $zone = Zones::selectRaw("id,
                (6764 
                * acos(
                    cos( radians(". $prop->geo_lng .") )
                    * cos( radians( geo_lat ) )
                    * cos( radians( geo_lng )
                    - radians(". $prop->geo_lat .") )
                    + sin( radians(". $prop->geo_lng .") )
                    * sin( radians( geo_lat ) )
                ) ) AS distance"
            )
            ->where('zoomlevel', $zoomlevel)
            ->orderBy('distance', 'asc')
            ->first();
            
            if ($zone) {
                $prop->{'zoom_level'. $zoomlevel} = $zone->id;
            }
        }
            
        return;
    }
    
}