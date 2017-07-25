<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeZonesTableColumnsTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('zones', function (Blueprint $table) {
        $table->Integer('property_count')->change();
        $table->Integer('zoomlevel')->change();
        $table->Integer('summarize_or_show_unique_properties')->change();
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('zones', function (Blueprint $table) {
        $table->string('property_count')->change();
        $table->string('zoomlevel')->change();
        $table->string('summarize_or_show_unique_properties')->change();
      });
    }
}
