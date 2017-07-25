<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyColumnGeoLatlngShareLinks extends Migration
{
    public function up()
    {
        Schema::table('share_links', function (Blueprint $table) {
            $table->string('geo_lat')->change();
            $table->string('geo_lng')->change();
        });
    }

    public function down()
    {
        Schema::table('share_links', function (Blueprint $table) {
          $table->float('geo_lat')->change();
          $table->float('geo_lng')->change();
        });
    }
}
