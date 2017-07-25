<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class GeolocationAddExtraLanguages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
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
                 $entity->string('name_bn')->after('name_en')->nullable()->default(null);
                 $entity->string('name_ro')->after('name_bn')->nullable()->default(null);
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
                 $entity->dropColumn('name_bn');
                 $entity->dropColumn('name_ro');
             });
         }
     }
}
