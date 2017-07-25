<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExtraColumnEditTokenInEstateAgencies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('estate_agencies', function (Blueprint $table) {
          $table->string('edit_token');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('estate_agencies', function (Blueprint $table) {
          $table->dropColumn('edit_token');
      });
    }
}
