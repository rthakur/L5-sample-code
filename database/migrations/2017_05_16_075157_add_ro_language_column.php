<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRoLanguageColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('property_texts', function (Blueprint $table) {
            $table->text('subject_ro')->after('subject_bn')->nullable()->default(null);
            $table->text('description_ro')->after('description_bn')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('property_texts', function (Blueprint $table) {
            $table->dropColumn('subject_ro');
            $table->dropColumn('description_ro');
        });
    }
}
