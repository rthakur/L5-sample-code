<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class StatesCitiesAddingSoftDeletes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('countries',function(Blueprint $table){
           $table->softDeletes();
        });

        Schema::table('states',function(Blueprint $table){
           $table->softDeletes();
        });

        Schema::table('cities',function(Blueprint $table){
           $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('countries',function(Blueprint $table){
            $table->dropSoftDeletes();
        });

        Schema::table('states',function(Blueprint $table){
            $table->dropSoftDeletes();
        });

        Schema::table('cities',function(Blueprint $table){
            $table->dropSoftDeletes();
        });
    }
}
