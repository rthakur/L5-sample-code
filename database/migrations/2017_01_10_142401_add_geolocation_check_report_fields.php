<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGeolocationCheckReportFields extends Migration
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
                $table->string('confirm_result')->after('confirm_attempt')->default(null);
                $table->text('confirm_response')->after('confirm_result')->default(null);
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
                $table->dropColumn('confirm_result');
                $table->dropColumn('confirm_response');
            });
        }
    }
}
