<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIncrementStatementInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::table('invoices')->truncate();
        DB::update("ALTER TABLE invoices AUTO_INCREMENT = 5001;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('invoices')->truncate();
        DB::update("ALTER TABLE invoices AUTO_INCREMENT = 1;");
    }
}
