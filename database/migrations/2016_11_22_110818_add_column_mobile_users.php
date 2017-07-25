<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnMobileUsers extends Migration
{
    public function up()
    {
      Schema::table('users', function ($table) {
        $table->string('mobile')->nullable();
      });
    }

    public function down()
    {
      Schema::table('users', function ($table) {
        $table->dropColumn('mobile');
      });
    }
}
