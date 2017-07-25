<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class GeolocationAddLanguages extends Migration
{

    public $tables = ['countries', 'states', 'cities'];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach ($this->tables as $t) {
            Schema::table($t, function (Blueprint $entity) {
                $entity->string('name_en')->after('name')->nullable()->default(null);
                $entity->string('name_fr')->after('name_en')->nullable()->default(null);
                $entity->string('name_de')->after('name_fr')->nullable()->default(null);
                $entity->string('name_sv')->after('name_de')->nullable()->default(null);
                $entity->string('name_ar')->after('name_sv')->nullable()->default(null);
                $entity->string('name_no')->after('name_ar')->nullable()->default(null);
                $entity->string('name_es')->after('name_no')->nullable()->default(null);
                $entity->string('name_ma')->after('name_es')->nullable()->default(null);
                $entity->string('name_da')->after('name_ma')->nullable()->default(null);
                $entity->string('name_fi')->after('name_da')->nullable()->default(null);
                $entity->string('name_hi')->after('name_fi')->nullable()->default(null);
                $entity->string('name_pt')->after('name_hi')->nullable()->default(null);
                $entity->string('name_ru')->after('name_pt')->nullable()->default(null);
                $entity->string('name_ja')->after('name_ru')->nullable()->default(null);
                $entity->string('name_uk')->after('name_ja')->nullable()->default(null);
                $entity->string('name_it')->after('name_uk')->nullable()->default(null);
            });

            DB::statement('Update ' . $t . ' set name_en = name');

            Schema::table($t, function (Blueprint $table) {
                $table->dropColumn('name');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        foreach ($this->tables as $t) {
            Schema::table($t, function (Blueprint $entity) {
                $entity->string('name')->after('language_id')->nullable()->default(null);
            });

            DB::statement('Update ' . $t . ' set name = name_en');

            Schema::table($t, function (Blueprint $entity) {
                $entity->dropColumn('name_en');
                $entity->dropColumn('name_fr');
                $entity->dropColumn('name_de');
                $entity->dropColumn('name_sv');
                $entity->dropColumn('name_ar');
                $entity->dropColumn('name_no');
                $entity->dropColumn('name_es');
                $entity->dropColumn('name_ma');
                $entity->dropColumn('name_da');
                $entity->dropColumn('name_fi');
                $entity->dropColumn('name_hi');
                $entity->dropColumn('name_pt');
                $entity->dropColumn('name_ru');
                $entity->dropColumn('name_ja');
                $entity->dropColumn('name_uk');
                $entity->dropColumn('name_it');
            });
        }
    }
}
