<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameZonesTableColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('zones', function ($table) {
        $table->renameColumn('nw', 'ne_lat');
        $table->renameColumn('ne', 'ne_lng');
        $table->renameColumn('sw', 'sw_lat');
        $table->renameColumn('se', 'sw_lng');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('zones', function ($table) {
        $table->renameColumn('ne_lat', 'nw');
        $table->renameColumn('ne_lng', 'ne');
        $table->renameColumn('sw_lat', 'sw');
        $table->renameColumn('sw_lng', 'se');
      });
    }
}
