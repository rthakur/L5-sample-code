<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExtraColumnTypeIdInUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('users', function (Blueprint $table) {
        $table->tinyInteger('type_id')->nullable()->default(1);
        $table->tinyInteger('allow_to_add')->nullable()->default(1);
        $table->tinyInteger('allow_to_edit')->nullable()->default(1);
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('users', function (Blueprint $table) {
        $table->dropColumn('type_id');
        $table->dropColumn('allow_to_add');
        $table->dropColumn('allow_to_edit');
      });
    }
}
