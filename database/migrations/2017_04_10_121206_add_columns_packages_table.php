<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->integer('logotype');
            $table->integer('agent_accounts');
            $table->integer('objects');
            $table->integer('api_access');
            $table->integer('synchronization');
            $table->integer('search_list_presentation');
            $table->integer('first_on_search_list');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn('logotype');
            $table->dropColumn('agent_accounts');
            $table->dropColumn('objects');
            $table->dropColumn('api_access');
            $table->dropColumn('synchronization');
            $table->dropColumn('search_list_presentation');
            $table->dropColumn('first_on_search_list');
        });
    }
}
