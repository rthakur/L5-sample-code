<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnSymbolLanguages extends Migration
{
    public function up()
    {
      Schema::table('languages', function (Blueprint $table) {
        $table->string('symbol');
      });
    }

    public function down()
    {
      Schema::table('languages', function (Blueprint $table) {
        $table->dropColumn('symbol');
      });
    }
}
