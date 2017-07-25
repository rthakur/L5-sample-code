<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExtraColumnLangInShareLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('share_links', function (Blueprint $table) {
        $table->string('lang')->nullable()->after('short_link_name');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('share_links', function (Blueprint $table) {
        $table->dropColumn('lang');
      });
    }
}
