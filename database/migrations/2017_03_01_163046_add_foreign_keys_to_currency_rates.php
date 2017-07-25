<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToCurrencyRates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('currency_rates', function (Blueprint $table) {
            $table->integer('base_currency_id')->unsigned()->nullable()->after('base_currency');
            $table->foreign('base_currency_id')->references('id')->on('currencies')->onUpdate('cascade')->onDelete('cascade');

            $table->integer('convert_currency_id')->unsigned()->nullable()->after('convert_currency');
            $table->foreign('convert_currency_id')->references('id')->on('currencies')->onUpdate('cascade')->onDelete('cascade');

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
            $table->dropForeign('currency_rates_base_currency_id_foreign');
            $table->dropColumn('base_currency_id');
            $table->dropForeign('currency_rates_convert_currency_id_foreign');
            $table->dropColumn('convert_currency_id');
        });
    }
}
