<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExtraColumnsInPropertyTableForZoomLevels extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('property', function (Blueprint $table) {
        for($i =1; $i != 16;  $i++)
          $table->smallInteger('zoom_level'.$i)->default(0);
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
        for($i =1; $i != 16;  $i++)
          $table->smallInteger('zoom_level'.$i);
      });
    }
}
