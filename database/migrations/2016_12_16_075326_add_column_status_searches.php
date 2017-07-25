<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnStatusSearches extends Migration
{
    public function up()
    {
      Schema::table('searches', function (Blueprint $table) {
          $table->string('status')->nullable()->after('property_id');
      });
    }

    public function down()
    {
      Schema::table('searches', function (Blueprint $table) {
          $table->dropColumn('status');
      });
    }
}
