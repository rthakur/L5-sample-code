<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PropertyProximityRelations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('property_proximity',function(Blueprint $table){
            $table->increments('id');

            $table->integer('property_id')->unsigned();
            $table->foreign('property_id')->references('id')->on('property')->onDelete('cascade')->onUpdate('cascade');

            $table->integer('proximity_id')->unsigned();
            $table->foreign('proximity_id')->references('id')->on('proximities')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('property_proximity');
    }
}
