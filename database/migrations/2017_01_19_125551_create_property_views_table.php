<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePropertyViewsTable extends Migration
{
    public function up()
    {
        Schema::create('property_views', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('property_id');
            $table->integer('view_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('property_views');
    }
}
