<?php

namespace App\Helpers;

use App\Models\Country;
use App\Models\State;
use App\Models\City;


class LocationsSKHelper
{


    public static function generate($entity, $what)
    {
        if ($entity) {
            if ($what == 'countries') {
                $entity = self::generate_for_country($entity);

            } else if ($what == 'states') {
                $entity = self::generate_for_state($entity);

            } else if ($what == 'cities') {
                $entity = self::generate_for_city($entity);
            }
        }

        return $entity;
    }

    private static function generate_for_country(Country $country)
    {
        $country->search_key = md5($country->name_en);
        return $country;
    }

    private static function generate_for_state(State $state)
    {

        $country = $state->country()->first();

        if ($country) {
            $state->search_key = md5($country->name_en . ' ' . $state->name_en);
        }

        return $state;
    }

    private static function generate_for_city(City $city)
    {
        $country = $city->country()->first();

        if ($country) {
            $city->search_key = md5($country->name_en . ' ' . $city->name_en);
        }

        return $city;
    }


}