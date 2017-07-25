<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableStripeUserInvoices extends Migration
{
    public function up()
    {
      Schema::create('stripe_user_invoices', function($table){
        $table->increments('id');
        $table->integer('user_id');
        $table->string('stripe_invoice_id');
        $table->string('amount');
        $table->dateTime('date');
      });
    }

    public function down()
    {
      Schema::dropIfExists('stripe_user_invoices');
    }
}
