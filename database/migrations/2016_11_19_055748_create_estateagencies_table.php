<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEstateagenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estate_agencies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('public_name');
            $table->string('legal_companyname');
            $table->integer('country_id');
            $table->integer('state_id')->nullable();
            $table->integer('city_id')->nullable();
            $table->integer('area_id')->nullable();
            $table->integer('streetaddresses_id')->nullable();
            $table->string('info_email');
            $table->string('allowedtoscrape')->nullable();
            $table->timestamp('first_contact_made_timestamp')->nullable();
            $table->string('api_key')->nullable();
            $table->string('secret_key')->nullable();
            $table->timestamp('last_scraped_timestamp')->nullable();
            $table->string('logo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('estate_agencies');
    }
}
