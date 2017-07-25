<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShareLinksTable extends Migration
{
    public function up()
    {
        Schema::create('share_links', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable();
            $table->string('list_name')->nullable();
            $table->string('place')->nullable();
            $table->float('geo_lat')->nullable();
            $table->float('geo_lng')->nullable();
            $table->tinyInteger('zoom')->nullable();
            $table->text('search_filter_json')->nullable();
            $table->string('view_type', 20);
            $table->integer('property_id')->nullable();
            $table->string('short_link_name', 6);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('share_links');
    }
}
