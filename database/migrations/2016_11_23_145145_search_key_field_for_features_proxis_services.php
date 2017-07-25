<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SearchKeyFieldForFeaturesProxisServices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('features', function (Blueprint $table) {
            $table->string('search_key');
        });

        Schema::table('services', function (Blueprint $table) {
            $table->string('search_key');
        });

        Schema::table('proximities', function (Blueprint $table) {
            $table->string('search_key');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('features', function (Blueprint $table) {
            $table->dropColumn('search_key');
        });

        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn('search_key');
        });

        Schema::table('proximities', function (Blueprint $table) {
            $table->dropColumn('search_key');
        });
    }
}
