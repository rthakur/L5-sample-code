<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePropertyStatisticsTable extends Migration
{
    public function up()
    {
      Schema::create('property_statistics', function ($table) {
        $table->increments('id');
        $table->integer('property_id');
        $table->integer('user_id')->nullable();
        $table->string('ip');
        $table->string('geo_city')->nullable();
        $table->string('geo_country')->nullable();
        $table->string('geo_region')->nullable();
        $table->timestamps();
      });
    }

    public function down()
    {
      Schema::drop('property_statistics');
    }
}
