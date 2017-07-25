<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemovePropertyImagesDeprecatedColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('property_images', function (Blueprint $table) {
            $table->dropColumn('marked_to_delete');
            $table->dropColumn('downloaded');
            $table->dropColumn('upload_lock');
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
            $table->tinyInteger('downloaded')->default(0)->after('main_image');
            $table->tinyInteger('marked_to_delete')->default(0)->after('downloaded');
            $table->tinyInteger('upload_lock')->default(0)->after('api_lock');
        });
    }
}
