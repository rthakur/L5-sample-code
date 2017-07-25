<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropViewLogTableAddColumnTypePropertyStatistics extends Migration
{
    public function up()
    {
      Schema::dropIfExists('property_view_logs');
      
      Schema::table('property_statistics', function($table){
        $table->smallInteger('type')->default(1);
      });
    }
    
    public function down()
    {
      Schema::create('property_view_logs', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('property_id');
          $table->integer('user_id')->nullable();
          $table->string('ip_address');
          $table->timestamps();
      });
      
      Schema::table('property_statistics', function($table){
        $table->dropColumn('type');
      });
    }
}
