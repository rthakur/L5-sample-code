<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PropertyAddMonthlyFee extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('property', function (Blueprint $table) {
            $table->double('monthly_fee')->nullable()->default(null)->after('price_currency');
            $table->string('monthly_fee_currency')->default('EUR')->after('monthly_fee');
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
            $table->dropColumn('monthly_fee');
            $table->dropColumn('monthly_fee_currency');
        });
    }
}
