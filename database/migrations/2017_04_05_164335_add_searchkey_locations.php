<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSearchkeyLocations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        $arr = ['countries', 'states', 'cities'];

        foreach ($arr as $entity){
            Schema::table($entity, function (Blueprint $table) {
                $table->string('search_key')->after('name')->nullable()->default(null);
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
        $arr = ['countries', 'states', 'cities'];

        foreach ($arr as $entity){
            Schema::table($entity, function (Blueprint $table) {
                $table->dropColumn('search_key');
            });
        }
    }
}
