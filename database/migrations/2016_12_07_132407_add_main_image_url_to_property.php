<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMainImageUrlToProperty extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('property', function (Blueprint $table) {
            $table->string('main_image_url')->after('agent_id')->nullable()->default(NULL);
        });

        DB::statement("Update property set main_image_url = (select (case when s3_url is not null then s3_url ELSE image_url end) as img_url from property_images where property_id = property.id and main_image = 1)");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('property', function (Blueprint $table) {
            $table->dropColumn('main_image_url');
        });
    }
}
