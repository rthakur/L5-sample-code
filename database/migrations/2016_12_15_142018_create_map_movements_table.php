<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMapMovementsTable extends Migration
{
    public function up()
    {
        Schema::create('map_movements', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->smallInteger('zoom');
            $table->string('geo_lat');
            $table->string('geo_lng');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('map_movements');
    }
}
