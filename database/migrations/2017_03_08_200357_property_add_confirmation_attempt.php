<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PropertyAddConfirmationAttempt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('property', function (Blueprint $table) {
            $table->tinyInteger('location_confirm_attempt')->default(0)->after('city');
            $table->tinyInteger('location_confirmed')->default(0)->after('location_confirm_attempt');
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
            $table->dropColumn('location_confirm_attempt');
            $table->dropColumn('location_confirmed');
        });
    }
}
