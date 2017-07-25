<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePropertyViewLogsTable extends Migration
{
    public function up()
    {
        Schema::create('property_view_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('property_id');
            $table->integer('user_id')->nullable();
            $table->string('ip_address');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('property_view_logs');
    }
}
