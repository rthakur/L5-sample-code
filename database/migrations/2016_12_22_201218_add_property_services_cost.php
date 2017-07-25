<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPropertyServicesCost extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('property_services', function (Blueprint $table) {
            $table->double('price')->default(0)->after('service_id');
            $table->string('price_currency')->default('EUR')->after('price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('property_services', function (Blueprint $table) {
            $table->dropColumn('price');
            $table->dropColumn('price_currency');
        });
    }
}
