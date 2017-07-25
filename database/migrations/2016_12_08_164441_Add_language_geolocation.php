<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddLanguageGeolocation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('countries', function (Blueprint $table) {
            $table->text('search_key')->after('language_id')->nullable();
            $table->text('name_fr')->after('name')->nullable();
            $table->text('name_de')->after('name_fr')->nullable();
            $table->text('name_sv')->after('name_de')->nullable();
            $table->text('name_ar')->after('name_sv')->nullable();
            $table->text('name_no')->after('name_ar')->nullable();
            $table->text('name_es')->after('name_no')->nullable();
            $table->text('name_ma')->after('name_es')->nullable();
            $table->text('name_da')->after('name_ma')->nullable();
            $table->text('name_fi')->after('name_da')->nullable();
            $table->text('name_hi')->after('name_fi')->nullable();
            $table->text('name_pt')->after('name_hi')->nullable();
            $table->text('name_ru')->after('name_pt')->nullable();
            $table->text('name_ja')->after('name_ru')->nullable();
            $table->text('name_uk')->after('name_ja')->nullable();
            $table->integer('translation_requested')->after('name_uk')->default(0);
        });

        DB::statement('Update countries set search_key = name');

        Schema::table('states', function (Blueprint $table) {
            $table->text('search_key')->after('language_id')->nullable();
            $table->text('name_fr')->after('name')->nullable();
            $table->text('name_de')->after('name_fr')->nullable();
            $table->text('name_sv')->after('name_de')->nullable();
            $table->text('name_ar')->after('name_sv')->nullable();
            $table->text('name_no')->after('name_ar')->nullable();
            $table->text('name_es')->after('name_no')->nullable();
            $table->text('name_ma')->after('name_es')->nullable();
            $table->text('name_da')->after('name_ma')->nullable();
            $table->text('name_fi')->after('name_da')->nullable();
            $table->text('name_hi')->after('name_fi')->nullable();
            $table->text('name_pt')->after('name_hi')->nullable();
            $table->text('name_ru')->after('name_pt')->nullable();
            $table->text('name_ja')->after('name_ru')->nullable();
            $table->text('name_uk')->after('name_ja')->nullable();
            $table->integer('translation_requested')->after('name_uk')->default(0);
        });

        DB::statement('Update states set search_key = name');

        Schema::table('cities', function (Blueprint $table) {
            $table->text('search_key')->after('language_id')->nullable();
            $table->text('name_fr')->after('name')->nullable();
            $table->text('name_de')->after('name_fr')->nullable();
            $table->text('name_sv')->after('name_de')->nullable();
            $table->text('name_ar')->after('name_sv')->nullable();
            $table->text('name_no')->after('name_ar')->nullable();
            $table->text('name_es')->after('name_no')->nullable();
            $table->text('name_ma')->after('name_es')->nullable();
            $table->text('name_da')->after('name_ma')->nullable();
            $table->text('name_fi')->after('name_da')->nullable();
            $table->text('name_hi')->after('name_fi')->nullable();
            $table->text('name_pt')->after('name_hi')->nullable();
            $table->text('name_ru')->after('name_pt')->nullable();
            $table->text('name_ja')->after('name_ru')->nullable();
            $table->text('name_uk')->after('name_ja')->nullable();
            $table->integer('translation_requested')->after('name_uk')->default(0);
        });

        DB::statement('Update cities set search_key = name');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('countries', function (Blueprint $table) {
            $table->dropColumn('search_key');
            $table->dropColumn('name_fr');
            $table->dropColumn('name_de');
            $table->dropColumn('name_sv');
            $table->dropColumn('name_ar');
            $table->dropColumn('name_no');
            $table->dropColumn('name_es');
            $table->dropColumn('name_ma');
            $table->dropColumn('name_da');
            $table->dropColumn('name_fi');
            $table->dropColumn('name_hi');
            $table->dropColumn('name_pt');
            $table->dropColumn('name_ru');
            $table->dropColumn('name_ja');
            $table->dropColumn('name_uk');
            $table->dropColumn('translation_requested');
        });

        Schema::table('states', function (Blueprint $table) {
            $table->dropColumn('search_key');
            $table->dropColumn('name_fr');
            $table->dropColumn('name_de');
            $table->dropColumn('name_sv');
            $table->dropColumn('name_ar');
            $table->dropColumn('name_no');
            $table->dropColumn('name_es');
            $table->dropColumn('name_ma');
            $table->dropColumn('name_da');
            $table->dropColumn('name_fi');
            $table->dropColumn('name_hi');
            $table->dropColumn('name_pt');
            $table->dropColumn('name_ru');
            $table->dropColumn('name_ja');
            $table->dropColumn('name_uk');
            $table->dropColumn('translation_requested');
        });

        Schema::table('cities', function (Blueprint $table) {
            $table->dropColumn('search_key');
            $table->dropColumn('name_fr');
            $table->dropColumn('name_de');
            $table->dropColumn('name_sv');
            $table->dropColumn('name_ar');
            $table->dropColumn('name_no');
            $table->dropColumn('name_es');
            $table->dropColumn('name_ma');
            $table->dropColumn('name_da');
            $table->dropColumn('name_fi');
            $table->dropColumn('name_hi');
            $table->dropColumn('name_pt');
            $table->dropColumn('name_ru');
            $table->dropColumn('name_ja');
            $table->dropColumn('name_uk');
            $table->dropColumn('translation_requested');
        });

    }
}
