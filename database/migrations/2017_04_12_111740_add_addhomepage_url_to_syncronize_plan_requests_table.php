<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAddhomepageUrlToSyncronizePlanRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('syncronize_plan_requests', function (Blueprint $table) {
            $table->string('homepage_url');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('syncronize_plan_requests', function (Blueprint $table) {
            $table->dropColumn('homepage_url');
        });
    }
}
