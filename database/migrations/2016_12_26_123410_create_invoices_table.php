<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('stripe_invoice_id')->nullable();
            $table->string('subtotal');
            $table->string('tax')->nullable();
            $table->string('tax_percent')->nullable();
            $table->string('total');
            $table->string('period_start');
            $table->string('period_end');
            $table->tinyInteger('paid_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
