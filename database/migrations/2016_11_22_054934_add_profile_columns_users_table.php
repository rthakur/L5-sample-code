<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProfileColumnsUsersTable extends Migration
{
    public function up()
    {
      Schema::table('users', function (Blueprint $table) {
        $table->string('profile_picture');
        $table->string('phone');
        $table->string('skype');
        $table->text('about_me');
        $table->string('twitter');
        $table->string('facebook');
        $table->string('pinterest');
      });
    }

    public function down()
    {
      Schema::table('users', function (Blueprint $table) {
        $table->dropColumn('phone');
        $table->dropColumn('skype');
        $table->dropColumn('about_me');
        $table->dropColumn('twitter_id');
        $table->dropColumn('facebook_id');
        $table->dropColumn('pinterest_id');
      });
    }
}
