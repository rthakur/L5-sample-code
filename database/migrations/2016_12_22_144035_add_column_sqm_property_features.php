<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnSqmPropertyFeatures extends Migration
{
    public function up()
    {
      Schema::table('property_features', function($table){
        $table->integer('sqm')->nullable();
      });
    }

    public function down()
    {
      Schema::table('property_features', function($table){
        $table->dropColumn('sqm');
      });
    }
}
