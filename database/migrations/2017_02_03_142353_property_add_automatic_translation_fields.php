<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PropertyAddAutomaticTranslationFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('property', function (Blueprint $table) {
            $table->string('translation_source')->default('en')->after('main_image_url');
            $table->tinyInteger('translation_required')->default(1)->after('translation_source');
            $table->tinyInteger('translation_source_text_changed')->default(0)->after('translation_required');
            $table->tinyInteger('agent_translation_notified')->default(0)->after('agent_sold_notified');
            $table->tinyInteger('agent_translation_confirmed')->default(0)->after('agent_translation_notified');
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
            $table->dropColumn('translation_source');
            $table->dropColumn('translation_required');
            $table->dropColumn('translation_source_text_changed');
            $table->dropColumn('agent_translation_notified');
            $table->dropColumn('agent_translation_confirmed');
        });
    }
}
