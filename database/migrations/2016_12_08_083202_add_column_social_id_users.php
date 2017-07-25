<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnSocialIdUsers extends Migration
{
    public function up()
    {
      Schema::table('users', function (Blueprint $table) {
          $table->string('social_id')->nullable();
      });
    }
    
    public function down()
    {
      Schema::table('users', function (Blueprint $table) {
          $table->dropColumn('social_id')->nullable();
      });
    }
}
