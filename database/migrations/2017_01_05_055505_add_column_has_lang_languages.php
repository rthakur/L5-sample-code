<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnHasLangLanguages extends Migration
{
    public function up()
    {
      Schema::table('languages', function(Blueprint $table) {
        $table->tinyInteger('has_lang');
      });
    }

    public function down()
    {
      Schema::table('languages', function(Blueprint $table) {
        $table->dropColumn('has_lang');
      });
    }
}
