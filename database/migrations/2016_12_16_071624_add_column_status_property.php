<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnStatusProperty extends Migration
{
    public function up()
    {
      Schema::table('property', function (Blueprint $table) {
          $table->string('status')->default('sale');
          $table->tinyInteger('email_notify')->default(0);
      });
    }

    public function down()
    {
      Schema::table('property', function (Blueprint $table) {
          $table->dropColumn('status');
          $table->dropColumn('email_notify');
      });
    }
}
