<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExtraColumnsForSoldPriceInPropertyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('property', function (Blueprint $table) {
          $table->string('sold_price_currency')->nullable();
          $table->string('sold_price')->nullable();
          $table->tinyInteger('mark_as_sold')->default(0);
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
          $table->dropColumn('sold_price_currency');
          $table->dropColumn('sold_price');
          $table->dropColumn('mark_as_sold');
      });
    }
}
