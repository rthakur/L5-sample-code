<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyCurrencyRates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('currency_rates', function (Blueprint $table) {
            $table->dropColumn('base_currency');
            $table->dropColumn('convert_currency');
            $table->dropColumn('convert_currency_country');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('currency_rates', function (Blueprint $table) {
            $table->string('base_currency')->nullable();
            $table->string('convert_currency')->nullable();
            $table->string('convert_currency_country')->nullable();
        });
    }
}
