<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeColumnDistancePropertyProximities extends Migration
{
    public function up()
    {
        Schema::table('property_proximity', function (Blueprint $table) {
          $table->string('distance')->nullable()->change();
          $table->dropColumn('distance_type');
        });
    }

    public function down()
    {
        Schema::table('property_proximity', function (Blueprint $table) {
          $table->integer('distance')->nullable()->change();
          $table->string('distance_type')->nullable();
        });
    }
}
