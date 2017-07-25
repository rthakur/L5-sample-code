<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExtraColumnPriceYearlyInPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('packages', function (Blueprint $table) {
          $table->string('price_yearly')->after('price')->nullable();
          $table->string('currency')->after('price_yearly')->nullable();
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('packages', function (Blueprint $table) {
          $table->dropColumn('price_yearly');
          $table->dropColumn('currency');
      });
    }
}
