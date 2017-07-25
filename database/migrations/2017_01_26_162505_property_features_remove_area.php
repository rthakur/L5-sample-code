<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PropertyFeaturesRemoveArea extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('property_features', function (Blueprint $table) {
            $table->dropColumn('area');
            $table->dropColumn('area_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('property_features', function (Blueprint $table) {
            $table->integer('area')->default(null)->after('number');
            $table->enum('area_type', ['sq.m.', 'sq.ft.'])->default('sq.m.')->after('area');
        });
    }
}
