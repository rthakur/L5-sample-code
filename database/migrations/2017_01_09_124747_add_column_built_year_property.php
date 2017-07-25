<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnBuiltYearProperty extends Migration
{
    public function up()
    {
        Schema::table('property', function (Blueprint $table) {
            $table->smallInteger('build_year')->nullable();
        });
    }

    public function down()
    {
        Schema::table('property', function (Blueprint $table) {
          $table->dropColumn('build_year');
        });
    }
}
