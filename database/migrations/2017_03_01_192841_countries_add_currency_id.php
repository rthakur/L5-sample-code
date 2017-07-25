<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CountriesAddCurrencyId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('countries', function (Blueprint $table) {
            $table->integer('currency_id')->unsigned()->nullable()->after('language_id');
            $table->foreign('currency_id')->references('id')->on('currencies')->onUpdate('restrict')->onDelete('restrict');

            $table->integer('language_id')->unsigned()->nullable()->change();
            $table->foreign('language_id')->references('id')->on('languages')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('countries', function (Blueprint $table) {
            $table->dropForeign('countries_language_id_foreign');
            $table->dropForeign('countries_currency_id_foreign');
            $table->dropColumn('currency_id');
        });


    }
}
