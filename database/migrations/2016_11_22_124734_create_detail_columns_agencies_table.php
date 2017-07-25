<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetailColumnsAgenciesTable extends Migration
{
    public function up()
    {
      Schema::table('estate_agencies', function ($table) {
        $table->string('skype')->nullable();
        $table->text('description')->nullable();
        $table->text('address_line_1')->nullable();
        $table->text('address_line_2')->nullable();
        $table->string('city')->nullable();
        $table->integer('zip_code')->nullable();
        $table->string('phone')->nullable();
        $table->string('mobile')->nullable();
        $table->string('website')->nullable();
        $table->double('geo_lat')->nullable();
        $table->double('geo_lng')->nullable();
      });
    }

    public function down()
    {
      Schema::table('estate_agencies', function ($table) {
        $table->dropColumn('phone');
        $table->dropColumn('mobile');
        $table->dropColumn('skype');
        $table->dropColumn('description');
        $table->dropColumn('address_line_1');
        $table->dropColumn('address_line_2');
        $table->dropColumn('city');
        $table->dropColumn('zip_code');
        $table->dropColumn('website');
        $table->dropColumn('geo_lat');
        $table->dropColumn('geo_lng');
      });
    }
}
