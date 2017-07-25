<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexPropertyIdGeoPropertyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('property', function (Blueprint $table) {
        DB::statement('ALTER TABLE  `property`  ADD INDEX `property_id_geo`(`geo_lat`, `geo_lng`)');
        $table->index('external_id');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('property', function (Blueprint $table) {
        $table->dropIndex('property_id_geo');
        $table->dropIndex('external_id');
      });
    }
}
