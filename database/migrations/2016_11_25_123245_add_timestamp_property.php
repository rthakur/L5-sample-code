<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTimestampProperty extends Migration
{
    public function up()
    {
      Schema::table('property', function ($table) {
        $table->timestamps();
      });
    }

    public function down()
    {
      Schema::table('property', function ($table) {
        $table->dropTimestamps();
      });
    }
}
