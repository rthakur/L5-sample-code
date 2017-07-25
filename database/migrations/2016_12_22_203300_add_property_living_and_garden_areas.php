<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPropertyLivingAndGardenAreas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('property', function (Blueprint $table) {
            $table->double('total_living_area')->after('personal_property_tax_currency')->default(0);
            $table->enum('total_living_area_type', ['sq.m.', 'sq.ft.'])->after('total_living_area')->default('sq.m.');
            $table->double('total_garden_area')->after('total_living_area_type')->default(0);
            $table->enum('total_garden_area_type', ['sq.m.', 'sq.ft.'])->after('total_garden_area')->default('sq.m.');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('property', function (Blueprint $table) {
            $table->dropColumn('total_living_area');
            $table->dropColumn('total_living_area_type');
            $table->dropColumn('total_garden_area');
            $table->dropColumn('total_garden_area_type');
        });
    }
}
