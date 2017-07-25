<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsPropertyProximitiess extends Migration
{
    public function up()
    {
      Schema::table('property_proximity', function($table){
        $table->integer('distance')->nullable();
        $table->string('distance_type')->nullable();
      });
    }

    public function down()
    {
      Schema::table('property_proximity', function($table){
        $table->dropColumn('distance');
        $table->dropColumn('distance_type');
      });
    }
}
