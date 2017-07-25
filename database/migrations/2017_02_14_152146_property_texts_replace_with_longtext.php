<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Language;

class PropertyTextsReplaceWithLongtext extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $languages = Language::getTranslatable();
        foreach ($languages as $language) {
            DB::statement('ALTER TABLE property MODIFY subject_' . $language->country_code . ' LONGTEXT;');
            DB::statement('ALTER TABLE property MODIFY description_' . $language->country_code . ' LONGTEXT;');
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $languages = Language::getTranslatable();
        foreach ($languages as $language) {
            DB::statement('ALTER TABLE property MODIFY subject_' . $language->country_code . ' TEXT;');
            DB::statement('ALTER TABLE property MODIFY description_' . $language->country_code . ' TEXT;');
        }
    }
}
