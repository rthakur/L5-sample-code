<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Country;

class Get_Countries_Geolocation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'countries:geolocation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command has been created for feeling geo_lat nad geo_lng fields in countries table.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $countries = Country::get();

        foreach ($countries as $c) {
            $geocode_url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . urlencode($c->name_en) . "&key=AIzaSyAr2PDr0fhwN5po1CjPpWbb6j0fJtDs4-4";
            $test_result = file_get_contents($geocode_url);
            $test_result = json_decode($test_result);
            $geo_location = array();

            if ($test_result->status == 'OK') {
                if (isset($test_result->results[0]->geometry) && isset($test_result->results[0]->geometry->location)
                    && isset($test_result->results[0]->geometry->location->lat)
                    && isset($test_result->results[0]->geometry->location->lng)
                ) {
                    $geo_location['geo_lat'] = $test_result->results[0]->geometry->location->lat;
                    $geo_location['geo_lng'] = $test_result->results[0]->geometry->location->lng;
                } else {
                    $geo_location['geo_lat'] = NULL;
                    $geo_location['geo_lng'] = NULL;
                }
            } else {
                $geo_location['geo_lat'] = NULL;
                $geo_location['geo_lng'] = NULL;
            }

            $c->geo_lat = $geo_location['geo_lat'];
            $c->geo_lng = $geo_location['geo_lng'];

            $c->save();
        }

        echo 'Done!' . PHP_EOL;

    }
}
