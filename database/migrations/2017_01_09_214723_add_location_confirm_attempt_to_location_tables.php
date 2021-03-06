<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLocationConfirmAttemptToLocationTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tables = ['countries', 'states', 'cities'];
        foreach ($tables as $t) {
            Schema::table($t, function (Blueprint $table) {
                $table->tinyInteger('confirm_attempt')->after('confirmed')->default(0);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $tables = ['countries', 'states', 'cities'];
        foreach ($tables as $t) {
            Schema::table($t, function (Blueprint $table) {
                $table->dropColumn('confirm_attempt');
            });
        }
    }
}
