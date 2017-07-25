<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExtraColumnPropertiesNumberInEstateAgenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('estate_agencies', function (Blueprint $table) {
            $table->integer('number_of_properties')->nullable();
            $table->integer('virtual_assistant_id')->nullable();
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
            $table->dropColumn('number_of_properties');
            $table->dropColumn('virtual_assistant_id');
        });
    }
}
