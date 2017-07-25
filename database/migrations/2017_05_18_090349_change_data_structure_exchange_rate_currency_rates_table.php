<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeDataStructureExchangeRateCurrencyRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
     {
         DB::update("ALTER TABLE currency_rates CHANGE exchange_rate exchange_rate DOUBLE( 10, 5 ) NOT NULL ;");
     }

     /**
      * Reverse the migrations.
      *
      * @return void
      */
     public function down()
     {
         DB::update("ALTER TABLE currency_rates CHANGE exchange_rate exchange_rate DOUBLE( 8, 2 ) NOT NULL ;");
     }
}
