<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExtraColumnsForContactDetailsEstateAgencyContactPersonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('estate_agencies', function (Blueprint $table) {
          $table->integer('invoice_country')->nullable();
          $table->string('invoice_city')->nullable();
          $table->string('invoice_zip_code')->nullable();
          $table->tinyInteger('same_as_contact_info')->default(0);
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('estate_agencies', function (Blueprint $table) {
        $table->dropColumn('invoice_country');
        $table->dropColumn('invoice_city');
        $table->dropColumn('invoice_zip_code');
        $table->dropColumn('same_as_contact_info');
      });
    }
}
