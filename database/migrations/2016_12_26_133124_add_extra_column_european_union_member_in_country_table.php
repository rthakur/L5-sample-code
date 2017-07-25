<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Country;

class AddExtraColumnEuropeanUnionMemberInCountryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('countries', function ($table) {
        $table->tinyInteger('european_union_member')->default('0');
      });
      
     $euroCountries = ['Austria','Belgium','Bulgaria','Croatia','Cyprus','Czechia','Denmark','Estonia','Finland','France','Germany','Greece','Hungary','Ireland','Italy','Latvia','Lithuania','Luxembourg','Malta','Netherlands','Poland','Portugal','Romania','Slovakia','Slovenia','Spain','Sweden','United Kingdom'];
     Country::select('search_key')->whereIn('search_key',$euroCountries)->update(['european_union_member'=>'1']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('countries', function ($table) {
        $table->dropColumn('european_union_member');
      });
    }
}
