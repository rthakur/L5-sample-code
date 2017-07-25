<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeNullableUsersTable extends Migration
{
    public function up()
    {
      Schema::table('users', function ($table) {
        $table->string('profile_picture')->nullable()->change();
        $table->string('phone')->nullable()->change();
        $table->string('skype')->nullable()->change();
        $table->text('about_me')->nullable()->change();
        $table->string('twitter')->nullable()->change();
        $table->string('facebook')->nullable()->change();
        $table->string('pinterest')->nullable()->change();
      });
    }

    public function down()
    {
      Schema::table('users', function ($table) {
        $table->string('profile_picture')->change();
        $table->string('phone')->change();
        $table->string('skype')->change();
        $table->text('about_me')->change();
        $table->string('twitter')->change();
        $table->string('facebook')->change();
        $table->string('pinterest')->change();
      });
    }
}
