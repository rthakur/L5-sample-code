<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixPropertyColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('property', function (Blueprint $table) {
            $table->string('external_id')->after('id');
            $table->text('subject_en')->after('subject')->nullable();
            $table->text('description_en')->after('description')->nullable();
        });

        DB::statement("Update property set external_id = property_local_id");
        DB::statement("Update property set subject_en = subject");
        DB::statement("Update property set description_en = description");

        Schema::table('property', function (Blueprint $table) {
            $table->dropColumn('property_local_id');
            $table->dropColumn('ingress');
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
            $table->string('property_local_id')->after('id');
            $table->string('ingress')->nullable()->after('main_image_url');
        });

        DB::statement("Update property set property_local_id = external_id");
        DB::statement("Update property set subject = subject_en");
        DB::statement("Update property set description = description_en");

        Schema::table('property', function (Blueprint $table) {
            $table->dropColumn('external_id');
            $table->dropColumn('subject_en');
            $table->dropColumn('description_en');
        });
    }
}
