<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ApiErrorReporting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_error_reporting', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('agency_id')->unsigned();
            $table->foreign('agency_id')->references('id')->on('estate_agencies')->onDelete('cascade')->onUpdate('cascade');

            $table->string('app_key')->default(null);
            $table->string('app_secret')->default(null);
            $table->text('sdk_config')->default(null);
            $table->text('server_response')->default(null);
            $table->text('server_env')->default(null);
            $table->text('php_info')->default(null);
            $table->string('sdk_exception')->default(null);
            $table->text('sdk_response')->default(null);
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
        Schema::dropIfExists('api_error_reporting');
    }
}
