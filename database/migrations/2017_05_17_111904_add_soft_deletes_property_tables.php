<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSoftDeletesPropertyTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

      public $tables = [
          'property_texts','property_views','property_features',
          'property_images','property_proximity','bookmarked_properties','searches','share_links'
      ];

          /**
           * Run the migrations.
           *
           * @return void
           */
      public function up()
      {
          foreach ($this->tables as $t) {
              Schema::table($t, function (Blueprint $entity) {
                  $entity->softDeletes();
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
                  $entity->dropSoftDeletes();
              });
          }
      }
}
