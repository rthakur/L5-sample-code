<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnUserIdEstateAgencies extends Migration
{
    public function up()
    {
      Schema::table('estate_agencies', function (Blueprint $table) {
          $table->integer('user_id')->nullable();
      });
    }

    public function down()
    {
      Schema::table('estate_agencies', function (Blueprint $table) {
          $table->dropColumn('user_id');
      });
    }
}
