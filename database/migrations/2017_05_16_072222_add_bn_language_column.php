<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBnLanguageColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('property_texts', function (Blueprint $table) {
            $table->text('subject_bn')->after('subject_en')->nullable()->default(null);
            $table->text('description_bn')->after('description_en')->nullable()->default(null);
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
            $table->dropColumn('subject_bn');
            $table->dropColumn('description_bn');
        });
    }
}
