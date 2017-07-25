<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PropertyAgentSoldCheckNotified extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('property', function (Blueprint $table) {
            $table->dropColumn('api_updated_at');
        });

        Schema::table('property', function (Blueprint $table) {
            $table->tinyInteger('agent_sold_notified')->default(0)->after('agent_checked');
            $table->bigInteger('api_updated_at')->after('updated_at')->default(0);
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
            $table->dropColumn('api_updated_at');
        });

        Schema::table('property', function (Blueprint $table) {
            $table->dropColumn('agent_sold_notified');
            $table->bigInteger('api_updated_at')->after('updated_at')->nullable()->default(NULL);
        });
    }
}
