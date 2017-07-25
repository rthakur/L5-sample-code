<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPropertyMultylanguage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('property', function (Blueprint $table) {

            $table->text('subject_fr')->after('subject')->nullable();
            $table->text('subject_de')->after('subject_fr')->nullable();
            $table->text('subject_sv')->after('subject_de')->nullable();
            $table->text('subject_ar')->after('subject_sv')->nullable();
            $table->text('subject_no')->after('subject_ar')->nullable();
            $table->text('subject_es')->after('subject_no')->nullable();
            $table->text('subject_ma')->after('subject_es')->nullable();
            $table->text('subject_da')->after('subject_ma')->nullable();
            $table->text('subject_fi')->after('subject_da')->nullable();
            $table->text('subject_hi')->after('subject_fi')->nullable();
            $table->text('subject_pt')->after('subject_hi')->nullable();
            $table->text('subject_ru')->after('subject_pt')->nullable();
            $table->text('subject_ja')->after('subject_ru')->nullable();
            $table->text('subject_uk')->after('subject_ja')->nullable();

            $table->text('description_fr')->after('description')->nullable();
            $table->text('description_de')->after('description_fr')->nullable();
            $table->text('description_sv')->after('description_de')->nullable();
            $table->text('description_ar')->after('description_sv')->nullable();
            $table->text('description_no')->after('description_ar')->nullable();
            $table->text('description_es')->after('description_no')->nullable();
            $table->text('description_ma')->after('description_es')->nullable();
            $table->text('description_da')->after('description_ma')->nullable();
            $table->text('description_fi')->after('description_da')->nullable();
            $table->text('description_hi')->after('description_fi')->nullable();
            $table->text('description_pt')->after('description_hi')->nullable();
            $table->text('description_ru')->after('description_pt')->nullable();
            $table->text('description_ja')->after('description_ru')->nullable();
            $table->text('description_uk')->after('description_ja')->nullable();
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
            $table->dropColumn('subject_fr');
            $table->dropColumn('subject_de');
            $table->dropColumn('subject_sv');
            $table->dropColumn('subject_ar');
            $table->dropColumn('subject_no');
            $table->dropColumn('subject_es');
            $table->dropColumn('subject_ma');
            $table->dropColumn('subject_da');
            $table->dropColumn('subject_fi');
            $table->dropColumn('subject_hi');
            $table->dropColumn('subject_pt');
            $table->dropColumn('subject_ru');
            $table->dropColumn('subject_ja');
            $table->dropColumn('subject_uk');

            $table->dropColumn('description_fr');
            $table->dropColumn('description_de');
            $table->dropColumn('description_sv');
            $table->dropColumn('description_ar');
            $table->dropColumn('description_no');
            $table->dropColumn('description_es');
            $table->dropColumn('description_ma');
            $table->dropColumn('description_da');
            $table->dropColumn('description_fi');
            $table->dropColumn('description_hi');
            $table->dropColumn('description_pt');
            $table->dropColumn('description_ru');
            $table->dropColumn('description_ja');
            $table->dropColumn('description_uk');
        });
    }
}
