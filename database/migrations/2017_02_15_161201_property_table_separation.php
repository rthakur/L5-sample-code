<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PropertyTableSeparation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('property_texts', function (Blueprint $table) {
            $table->engine = 'MYISAM';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            $table->increments('id');
            $table->integer('property_id')->unsigned();

            $table->text('subject_en')->nullable()->default(null);
            $table->text('subject_fr')->nullable()->default(null);
            $table->text('subject_de')->nullable()->default(null);
            $table->text('subject_sv')->nullable()->default(null);
            $table->text('subject_ar')->nullable()->default(null);
            $table->text('subject_no')->nullable()->default(null);
            $table->text('subject_es')->nullable()->default(null);
            $table->text('subject_ma')->nullable()->default(null);
            $table->text('subject_da')->nullable()->default(null);
            $table->text('subject_fi')->nullable()->default(null);
            $table->text('subject_hi')->nullable()->default(null);
            $table->text('subject_pt')->nullable()->default(null);
            $table->text('subject_ru')->nullable()->default(null);
            $table->text('subject_ja')->nullable()->default(null);
            $table->text('subject_uk')->nullable()->default(null);
            $table->text('subject_it')->nullable()->default(null);

            $table->text('description_en')->nullable()->default(null);
            $table->text('description_fr')->nullable()->default(null);
            $table->text('description_de')->nullable()->default(null);
            $table->text('description_sv')->nullable()->default(null);
            $table->text('description_ar')->nullable()->default(null);
            $table->text('description_no')->nullable()->default(null);
            $table->text('description_es')->nullable()->default(null);
            $table->text('description_ma')->nullable()->default(null);
            $table->text('description_da')->nullable()->default(null);
            $table->text('description_fi')->nullable()->default(null);
            $table->text('description_hi')->nullable()->default(null);
            $table->text('description_pt')->nullable()->default(null);
            $table->text('description_ru')->nullable()->default(null);
            $table->text('description_ja')->nullable()->default(null);
            $table->text('description_uk')->nullable()->default(null);
            $table->text('description_it')->nullable()->default(null);

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
        Schema::dropIfExists('property_texts');
    }
}
