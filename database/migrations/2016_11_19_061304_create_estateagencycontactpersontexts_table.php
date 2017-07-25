<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEstateagencycontactpersontextsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estate_agency_contact_person_texts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('estate_agency_contact_person_id');
            $table->integer('text_type_id');
            $table->integer('languange_id');
            $table->text('text');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('estateagencycontactpersontexts');
    }
}
