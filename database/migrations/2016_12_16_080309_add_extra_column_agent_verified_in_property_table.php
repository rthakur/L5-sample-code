<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExtraColumnAgentVerifiedInPropertyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('property', function (Blueprint $table) {
          $table->tinyInteger('agent_verified')->default(0);
          $table->tinyInteger('agent_checked')->default(0);
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('property', function (Blueprint $table) {
          $table->dropColumn('agent_verified');
          $table->dropColumn('agent_checked');
      });
    }
}
