<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPropertyTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('property_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
        });

        Schema::table('property', function (Blueprint $table) {
            $table->unsignedInteger('property_type_id')->nullable()->default(NULL)->after('id');
            $table->foreign('property_type_id')->references('id')->on('property_types')->onDelete('cascade')->onUpdate('cascade');
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
            $table->dropForeign('property_property_type_id_foreign');
            $table->dropColumn('property_type_id');
        });

        Schema::dropIfExists('property_types');
    }
}
