<?php
namespace App\Helpers;

use App\Models\Property;
use App\Models\Country;
use App\Models\State;
Use App\Models\City;

class PropertyLocationsHelper
{

    public static function detect(Property $prop)
    {
        $latlng = trim($prop->geo_lat) . ',' . trim($prop->geo_lng);
        $g_api_key = env('GOOGLE_BACKEND_API_KEY');
        $url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng=' . $latlng . '&language=en&key=' . $g_api_key;
       // echo 'Requesting URL --> ' . $url . PHP_EOL;
        $test_result = file_get_contents($url);
        $test_result = json_decode($test_result);

        if ($test_result->status == 'OK') {
            $addr_components = $test_result->results[0]->address_components;
            $country_comp = false;
            $state_comp = false;
            $city_comp = false;
            $street_comp = false;
            $street_num_comp = false;
            $zip_comp = false;
         //   echo 'Found location for: ' . $latlng . ' PROP_ID: ' . $prop->id . PHP_EOL;
            foreach ($addr_components as $component) {
                if (in_array('country', $component->types) && in_array('political', $component->types)) {
                    $country_comp = $component;
            //        echo 'Country: ' . $component->long_name . PHP_EOL;
                }

                if (in_array('administrative_area_level_1', $component->types) && in_array('political', $component->types)) {
                    $state_comp = $component;
         //           echo 'State: ' . $component->long_name . PHP_EOL;
                }

                if ((in_array('locality', $component->types) || in_array('sublocality', $component->types)) && in_array('political', $component->types)) {
                    $city_comp = $component;
           //         echo 'City: ' . $component->long_name . PHP_EOL;
                }

                if (in_array('route', $component->types)) {
                    $street_comp = $component;
         //           echo 'Street: ' . $component->long_name . PHP_EOL;
                }

                if (in_array('street_number', $component->types)) {
                    $street_num_comp = $component;
          //          echo 'Street num: ' . $component->long_name . PHP_EOL;
                }

                if (in_array('postal_code', $component->types)) {
                    $zip_comp = $component;
         //           echo 'ZIP: ' . $component->long_name . PHP_EOL;
                }
            }
         //   echo '----------------------------------' . PHP_EOL . PHP_EOL;

            if ($country_comp) {
                $country = Country::where('iso', $country_comp->short_name)->first();
                if (!$country) {
                    $country = new Country();
                    $country->iso = $country_comp->sort_name;
                    $country->name_en = $country_comp->long_name;
                    $country->save();
                }
                $prop->country_id = $country->id;

                $state = false;
                if ($state_comp) {
                    $state = State::where('country_id', $country->id)->where('name_en', $state_comp->long_name)->first();
                    if (!$state) {
                        $state = new State();
                        $state->country_id = $country->id;
                        $state->name_en = $state_comp->long_name;
                        $state->save();
                    }
                    $prop->state_id = $state->id;
                }

                if ($city_comp) {
                    $city = City::where('country_id', $country->id)->where('name_en', $city_comp->long_name)->first();
                    if (!$city) {
                        $city = new City();
                        $city->country_id = $country->id;
                        $city->state_id = ($state) ? $state->id : null;
                        $city->name_en = $city_comp->long_name;
                        $city->save();
                    }
                    $prop->city_id = $city->id;
                }

                if ($zip_comp) {
                    $prop->zip_code = $zip_comp->long_name;
                }

                if ($prop->street_address == null || $prop->street_address == '') {
                    $prop->street_address = '';
                    $prop->street_address .= ($street_num_comp) ? $street_num_comp->long_name . ' ' : '';
                    $prop->street_address .= ($street_comp) ? $street_comp->long_name : '';
                }

                $prop->location_confirmed = 1;

            }
        } else {
            // oops, something happened with lat lng, those not seam to be real
        }

        $prop->location_confirmed = 1;
        $prop->save();

        return $prop;
    }

}