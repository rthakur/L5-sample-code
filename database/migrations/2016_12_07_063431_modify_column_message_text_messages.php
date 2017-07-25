<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyColumnMessageTextMessages extends Migration
{
    public function up()
    {
      Schema::table('messages', function ($table) {
          $table->text('message_text')->change();
      });
    }

    public function down()
    {
      
    }
}
