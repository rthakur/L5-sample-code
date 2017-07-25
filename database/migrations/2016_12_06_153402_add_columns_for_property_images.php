<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsForPropertyImages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('property_images', function (Blueprint $table) {
            $table->string('url_checksum')->after('image_url')->nullable()->default(NULL);
            $table->string('s3_url')->after('url_checksum')->nullable()->default(NULL);
            $table->string('s3_path')->after('s3_url')->nullable()->default(NULL);
            $table->integer('marked_to_delete')->after('downloaded')->default(0);
            $table->integer('api_lock')->after('marked_to_delete')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('property_images', function (Blueprint $table) {
            $table->dropColumn('url_checksum');
            $table->dropColumn('s3_url');
            $table->dropColumn('s3_path');
            $table->dropColumn('marked_to_delete');
            $table->dropColumn('api_lock');
        });
    }
}
