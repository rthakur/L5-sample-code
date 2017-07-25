<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnAreaTypePropertyFeatures extends Migration
{
    public function up()
    {
      Schema::table('property_features', function ($table) {
        $table->renameColumn('sqm', 'area');
        $table->enum('area_type', ['sq.m.', 'sq.ft.'])->default('sq.m.');
      });
    }

    public function down()
    {
      Schema::table('property_features', function ($table) {
        $table->renameColumn('area', 'sqm');
        $table->dropColumn('area_type');
      });
    }
}
