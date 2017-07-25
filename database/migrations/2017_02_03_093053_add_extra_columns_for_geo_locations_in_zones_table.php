<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExtraColumnsForGeoLocationsInZonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('zones', function (Blueprint $table) {
        $table->string('geo_lat')->nullable();
        $table->string('geo_lng')->nullable();
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('zones', function (Blueprint $table) {
        $table->dropColumn('geo_lat');
        $table->dropColumn('geo_lng');
      });
    }
}
