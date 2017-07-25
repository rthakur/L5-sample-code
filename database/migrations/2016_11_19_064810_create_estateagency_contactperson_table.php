<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEstateagencyContactpersonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('estate_agency_contact_person', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('estate_agency_id');
          $table->string('first_name');
          $table->string('last_name');
          $table->string('email');
          $table->string('password');
          $table->string('type_id');
          $table->text('temporary_auth_token')->nullable();
          $table->string('gmail_id')->nullable();
          $table->string('facebook_id')->nullable();
          $table->string('telephone')->nullable();
          $table->string('mobilephone')->nullable();
          $table->string('contact_email')->nullable();
          $table->string('skype')->nullable();
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
       Schema::dropIfExists('estate_agency_contact_person');
    }
}
