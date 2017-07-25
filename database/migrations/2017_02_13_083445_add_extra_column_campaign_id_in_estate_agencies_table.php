<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExtraColumnCampaignIdInEstateAgenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('estate_agencies', function (Blueprint $table) {
        $table->integer('campaign_id')->nullable()->after('streetaddresses_id');
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
        $table->dropColumn('campaign_id');
      });
    }
}
