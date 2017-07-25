<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Language;

class RemoveSubjectsDescriptionsColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('property', function (Blueprint $table) {
            foreach(Language::where('has_lang', 1)->get() as $lang) {
                
                if (Schema::hasColumn('property', 'subject_'. $lang->country_code)) {
                    $table->dropColumn('subject_'. $lang->country_code);
                }
                
                if (Schema::hasColumn('property', 'description_'. $lang->country_code)) {
                    $table->dropColumn('description_'. $lang->country_code);
                }
            }
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
            foreach(Language::where('has_lang', 1)->get() as $lang) {
                $table->text('subject_'.$lang->country_code)->nullable();
                $table->text('description_'.$lang->country_code)->nullable();
            }
        });
    }
}
