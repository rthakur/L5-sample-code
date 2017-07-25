<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsLocationsUsersTable extends Migration
{
    public function up()
    {
      Schema::table('users', function (Blueprint $table) {
          $table->smallInteger('map_zoom')->nullable();
          $table->string('map_geo_lat')->nullable();
          $table->string('map_geo_lng')->nullable();
      });
    }

    public function down()
    {
      Schema::table('users', function (Blueprint $table) {
          $table->dropColumn('map_zoom');
          $table->dropColumn('map_geo_lat');
          $table->dropColumn('map_geo_lng');
      });
    }
}
