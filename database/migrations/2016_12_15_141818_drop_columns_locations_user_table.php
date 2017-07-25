<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropColumnsLocationsUserTable extends Migration
{
    public function up()
    {
      Schema::table('users', function (Blueprint $table) {
          $table->dropColumn('map_zoom');
          $table->dropColumn('map_geo_lat');
          $table->dropColumn('map_geo_lng');
      });
    }

}
