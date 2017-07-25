<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PropertyScrapperChanges extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('property', function (Blueprint $table) {
            $table->string('property_url');
            $table->string('property_local_id');
            $table->string('ingress');
            $table->text('description');
            $table->integer('price_on_request')->default(0);
            $table->string('price_currency')->default('EUR');
            $table->double('property_tax')->default(0);
            $table->string('property_tax_currency')->default('EUR');
            $table->double('personal_property_tax')->default(0);
            $table->string('personal_property_tax_currency')->default('EUR');
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
            $table->dropColumn('property_url');
            $table->dropColumn('property_local_id');
            $table->dropColumn('ingress');
            $table->dropColumn('description');
            $table->dropColumn('price_on_request');
            $table->dropColumn('price_currency');
            $table->dropColumn('property_tax');
            $table->dropColumn('property_tax_currency');
            $table->dropColumn('personal_property_tax');
            $table->dropColumn('personal_property_tax_currency');
        });
    }
}
