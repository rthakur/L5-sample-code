<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExtraColumnVatNumberEstateAgencyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('estate_agencies', function (Blueprint $table) {
          $table->dropColumn('same_as_contact_info');
          $table->string('vat_number')->nullable();
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
        $table->tinyInteger('same_as_contact_info')->default(0);
        $table->dropColumn('vat_number');
      });
    }
}
