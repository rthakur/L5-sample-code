<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveGeolocationLanguagesFromTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tables = ['countries', 'states', 'cities'];
        $columns = ['search_key', 'name_fr', 'name_de', 'name_sv', 'name_ar', 'name_no', 'name_es', 'name_ma', 'name_da', 'name_fi',
            'name_hi', 'name_pt', 'name_ru', 'name_ja', 'name_uk', 'name_it', 'translation_requested'];
        foreach ($tables as $t) {
            Schema::table($t, function (Blueprint $table) use ($columns) {
                foreach ($columns as $c) {
                    $table->dropColumn($c);
                }
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $tables = ['countries', 'states', 'cities'];
        $columns = ['search_key', 'name_fr', 'name_de', 'name_sv', 'name_ar', 'name_no', 'name_es', 'name_ma', 'name_da', 'name_fi',
            'name_hi', 'name_pt', 'name_ru', 'name_ja', 'name_uk', 'name_it', 'translation_requested'];
        foreach ($tables as $t) {
            Schema::table($t, function (Blueprint $table) use ($columns) {
                foreach ($columns as $c) {
                    $table->string($c)->nullable()->default(null);
                }
                $table->dropTimestamps();
            });
        }
    }
}
