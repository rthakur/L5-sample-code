<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameZipcodeDataStructure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::update("ALTER TABLE estate_agencies CHANGE zip_code zip_code VARCHAR(255) NULL DEFAULT NULL;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::update("ALTER TABLE estate_agencies CHANGE zip_code zip_code INT(11) NULL DEFAULT NULL;");
    }
}
