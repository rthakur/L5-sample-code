<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddItalianLanguage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('property', function (Blueprint $table) {
            $table->text('subject_it')->after('subject_uk')->nullable();
            $table->text('description_it')->after('description_uk')->nullable();
        });

        Schema::table('countries', function (Blueprint $table) {
            $table->string('name_it')->after('name_uk')->nullable();
        });

        Schema::table('states', function (Blueprint $table) {
            $table->string('name_it')->after('name_uk')->nullable();
        });

        Schema::table('cities', function (Blueprint $table) {
            $table->string('name_it')->after('name_uk')->nullable();
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
            $table->dropColumn('subject_it');
            $table->dropColumn('description_it');
        });

        Schema::table('countries', function (Blueprint $table) {
            $table->dropColumn('name_it');
        });

        Schema::table('states', function (Blueprint $table) {
            $table->dropColumn('name_it');
        });

        Schema::table('cities', function (Blueprint $table) {
            $table->dropColumn('name_it');
        });

    }
}
