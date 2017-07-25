<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToProperty extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('property', function (Blueprint $table) {
            $table->integer('agency_id')->unsigned()->nullable();
            $table->foreign('agency_id')->references('id')->on('estate_agencies')->onDelete('cascade')->onUpdate('cascade');

            $table->integer('agent_id')->unsigned()->nullable();
            $table->foreign('agent_id')->references('id')->on('estate_agency_contact_person')->onDelete('cascade')->onUpdate('cascade');

            $table->text('subject');

            $table->double('price');

            $table->double('geo_lat');
            $table->double('geo_lng');

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
            $table->dropForeign('property_agency_id_foreign');
            $table->dropForeign('property_agent_id_foreign');
            $table->dropColumn('agency_id');
            $table->dropColumn('agent_id');
            $table->dropColumn('subject');
            $table->dropColumn('price');
            $table->dropColumn('geo_lat');
            $table->dropColumn('geo_lng');
        });
    }
}
