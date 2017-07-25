<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnCityPropertyTable extends Migration
{
  public function up()
  {
      Schema::table('property', function (Blueprint $table) {
        $table->string('city')->after('city_id')->nullable();
      });
  }

  public function down()
  {
      Schema::table('property', function (Blueprint $table) {
        $table->dropColumn('city');
      });
  }
}
