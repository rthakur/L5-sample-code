<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyColumnsSearchStatistics extends Migration
{
    public function up()
    {
        Schema::table('search_statistics', function (Blueprint $table) {
            $table->renameColumn('property_services', 'property_views');
        });
    }

    public function down()
    {
        Schema::table('search_statistics', function (Blueprint $table) {
            $table->renameColumn('property_views', 'property_services');
        });
    }
}
