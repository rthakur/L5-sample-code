<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixPropertyLatAndLng extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('property', function (Blueprint $table) {
            $table->double('tmp_geo_lat')->nullable()->default(null)->after('geo_lat');
            $table->double('tmp_geo_lng')->nullable()->default(null)->after('geo_lng');
        });

        DB::statement('UPDATE property SET tmp_geo_lat = geo_lat, tmp_geo_lng = geo_lng;');

        Schema::table('property', function (Blueprint $table) {
            $table->dropColumn('geo_lat');
            $table->dropColumn('geo_lng');
        });

        Schema::table('property', function (Blueprint $table) {
            $table->double('geo_lat')->nullable()->default(null)->after('price');
            $table->double('geo_lng')->nullable()->default(null)->after('geo_lat');
        });

        DB::statement('UPDATE property SET geo_lng = tmp_geo_lat, geo_lng = tmp_geo_lng;');

        Schema::table('property', function (Blueprint $table) {
            $table->dropColumn('tmp_geo_lat');
            $table->dropColumn('tmp_geo_lng');
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
            $table->double('tmp_geo_lat')->nullable()->default(null)->after('geo_lat');
            $table->double('tmp_geo_lng')->nullable()->default(null)->after('geo_lng');
        });

        DB::statement('UPDATE property SET tmp_geo_lat = geo_lat, tmp_geo_lng = geo_lng;');

        Schema::table('property', function (Blueprint $table) {
            $table->dropColumn('geo_lat');
            $table->dropColumn('geo_lng');
        });

        Schema::table('property', function (Blueprint $table) {
            $table->double('geo_lat')->default(0)->after('price');
            $table->double('geo_lng')->default(0)->after('geo_lat');
        });

        DB::statement('UPDATE property SET geo_lng = tmp_geo_lat, geo_lng = tmp_geo_lng;');

        Schema::table('property', function (Blueprint $table) {
            $table->dropColumn('tmp_geo_lat');
            $table->dropColumn('tmp_geo_lng');
        });

    }
}
