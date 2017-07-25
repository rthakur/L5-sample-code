<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPropertyCurrencyKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('property', function (Blueprint $table) {
            $table->integer('price_currency_id')->unsigned()->nullable()->after('price_currency');
            $table->integer('monthly_fee_currency_id')->unsigned()->nullable()->after('monthly_fee_currency');
            $table->integer('property_tax_currency_id')->unsigned()->nullable()->after('property_tax_currency');
            $table->integer('personal_property_tax_currency_id')->unsigned()->nullable()->after('personal_property_tax_currency');
            $table->integer('sold_price_currency_id')->unsigned()->nullable()->after('sold_price_currency');

            $table->foreign('price_currency_id')->references('id')->on('currencies')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('monthly_fee_currency_id')->references('id')->on('currencies')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('property_tax_currency_id')->references('id')->on('currencies')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('personal_property_tax_currency_id')->references('id')->on('currencies')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('sold_price_currency_id')->references('id')->on('currencies')->onUpdate('cascade')->onDelete('restrict');
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
            $table->dropForeign('property_price_currency_id_foreign');
            $table->dropForeign('property_monthly_fee_currency_id_foreign');
            $table->dropForeign('property_property_tax_currency_id_foreign');
            $table->dropForeign('property_personal_property_tax_currency_id_foreign');
            $table->dropForeign('property_sold_price_currency_id_foreign');

            $table->dropColumn('price_currency_id');
            $table->dropColumn('monthly_fee_currency_id');
            $table->dropColumn('property_tax_currency_id');
            $table->dropColumn('personal_property_tax_currency_id');
            $table->dropColumn('sold_price_currency_id');
        });
    }
}
