<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateObjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('objects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('street_address', 64);
            $table->string('zipcode', 64);
            $table->string('city_id', 64);
            $table->string('area_id', 64);
            $table->string('state_id', 64);
            $table->string('countryid', 64);
            $table->string('housenumber', 64);
            $table->string('geo_latitude', 64);
            $table->string('geo_longitude', 64);
            $table->string('built_year', 64);
            $table->string('bathroom', 64);
            $table->string('jacuzzi', 64);
            $table->string('bathtub', 64);
            $table->string('heating_system', 64);
            $table->string('cooling_system', 64);
            $table->string('balcony', 64);
            $table->string('balcony_size', 64);
            $table->string('basement', 64);
            $table->string('basement_size', 64);
            $table->string('fence', 64);
            $table->string('petpolicy_type', 64);
            $table->string('kitchent_ype', 64);
            $table->string('fireplace', 64);
            $table->string('parking_type', 64);
            $table->string('roof_deck', 64);
            $table->string('washing_machine', 64);
            $table->string('shopping_centerdistance_id', 64);
            $table->string('shopping_centerminutes', 64);
            $table->string('towncenter_distance_id', 64);
            $table->string('towncenter_minutes', 64);
            $table->string('hospital_id', 64);
            $table->string('hospital_minutes', 64);
            $table->string('trainstation_distance_id', 64);
            $table->string('trainstation_minutes', 64);
            $table->string('busstation_distance_id', 64);
            $table->string('busstation_minutes', 64);
            $table->string('airport_distance_id', 64);
            $table->string('airport_minutes', 64);
            $table->string('coffeshop_distance_id', 64);
            $table->string('coffeshop_minutes', 64);
            $table->string('beach_distance_id', 64);
            $table->string('beach_minutes', 64);
            $table->string('cinema_distance_id', 64);
            $table->string('cinema_minutes', 64);
            $table->string('park_distance_id', 64);
            $table->string('park_minutes', 64);
            $table->string('university_distance_id', 64);
            $table->string('university_minutes', 64);
            $table->string('exhibition_distance_id', 64);
            $table->string('exhibition_minutes', 64);
            $table->string('furnished_id', 64);
            $table->string('rooms', 64);
            $table->string('living_sqm', 64);
            $table->string('indoorextra_sqm', 64);
            $table->string('balcony_sqm', 64);
            $table->string('floor', 64);
            $table->string('floormax', 64);
            $table->string('elevator', 64);
            $table->string('accessentrance_readydate', 64);
            $table->string('gardensqm', 64);
            $table->string('monthlyrent', 64);
            $table->string('yearly_operating_cost', 64);
            $table->string('exhibitiondate_time1', 64);
            $table->string('exhibitiondate_time2', 64);
            $table->string('estateagent1_id', 64);
            $table->string('estateagent2_id', 64);
            $table->string('assistant1_id', 64);
            $table->string('assistant2_id', 64);
            $table->string('price', 64);
            $table->string('price_brokerfee', 64);
            $table->string('price_persqm', 64);
            $table->string('pool', 64);
            $table->string('seaview', 64);
            $table->string('distance_to_water', 64);
            $table->string('near_sea', 64);
            $table->string('new_production', 64);
            $table->string('typeofliving_id', 64);
            $table->string('biding_status_id', 64);
            $table->string('price_status', 64);
            $table->string('price_changed_datetime', 64);
            $table->string('executiveaction', 64);
            $table->string('biddinghasstarted', 64);
            $table->string('soldprice', 64);
            $table->string('link_to_realestate_homepage', 64);
            $table->string('added_viatype', 64);
            $table->string('updated_viatype', 64);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('objects');
    }
}
