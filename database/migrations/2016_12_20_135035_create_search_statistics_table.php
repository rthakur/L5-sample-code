<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSearchStatisticsTable extends Migration
{
    public function up()
    {
        Schema::create('search_statistics', function (Blueprint $table) {
            $table->increments('id');
            $table->text('property_type')->nullable();
            $table->text('property_price')->nullable();
            $table->text('property_size')->nullable();
            $table->text('property_services')->nullable();
            $table->text('property_proximities')->nullable();
            $table->text('property_features')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('search_statistics');
    }
}
