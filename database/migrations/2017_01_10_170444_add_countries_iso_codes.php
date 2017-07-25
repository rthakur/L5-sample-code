<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Country;

class AddCountriesIsoCodes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('countries', function (Blueprint $table) {
            $table->string('iso')->after('name')->default(null);
        });

        $iso_codes = file_get_contents('http://data.okfn.org/data/core/country-list/r/data.json');
        $iso_codes = json_decode($iso_codes, true);

        Country::whereRaw('name = ?', ['Saint-Barthélemy'])->update(['name' => 'Saint Barthélemy']);
        Country::whereRaw('name = ?', ['Brunei'])->update(['name' => 'Brunei Darussalam']);
        Country::whereRaw('name = ?', ['Bolivia'])->update(['name' => 'Plurinational State of Bolivia']);
        Country::whereRaw('name = ?', ['Bonaire'])->update(['name' => 'Sint Eustatius and Saba Bonaire']);
        Country::whereRaw('name = ?', ['The Bahamas'])->update(['name' => 'Bahamas']);
        Country::whereRaw('name = ?', ['Democratic Republic of the Congo'])->update(['name' => 'the Democratic Republic of the Congo']);
        Country::whereRaw('name = ?', ['Republic of the Congo'])->update(['name' => 'Congo']);
        Country::whereRaw('name = ?', ['Falkland Islands (Islas Malvinas)'])->update(['name' => 'Falkland Islands (Malvinas)']);
        Country::whereRaw('name = ?', ['Federated States of Micronesia'])->update(['name' => 'Federated States of Micronesia']);
        Country::whereRaw('name = ?', ['The Gambia'])->update(['name' => 'Gambia']);
        Country::whereRaw('name = ?', ['Iran'])->update(['name' => 'Islamic Republic of Iran']);
        Country::whereRaw('name = ?', ['North Korea'])->update(['name' => "Democratic People's Republic of Korea"]);
        Country::whereRaw('name = ?', ['South Korea'])->update(['name' => 'Republic of Korea']);
        Country::whereRaw('name = ?', ['Laos'])->update(['name' => "Lao People's Democratic Republic"]);
        Country::whereRaw('name = ?', ['Moldova'])->update(['name' => 'Republic of Moldova']);
        Country::whereRaw('name = ?', ['Saint Martin'])->update(['name' => 'Saint Martin (French part)']);
        Country::whereRaw('name = ?', ['Macedonia (FYROM)'])->update(['name' => 'the Former Yugoslav Republic of Macedonia']);
        Country::whereRaw('name = ?', ['Myanmar (Burma)'])->update(['name' => 'Myanmar']);
        Country::whereRaw('name = ?', ['Macau'])->update(['name' => 'Macao']);
        Country::whereRaw('name = ?', ['Pitcairn Islands'])->update(['name' => 'Pitcairn']);
        Country::whereRaw('name = ?', ['Palestine'])->update(['name' => 'State of Palestine']);
        Country::whereRaw('name = ?', ['Russia'])->update(['name' => 'Russian Federation']);
        Country::whereRaw('name = ?', ['Saint Helena'])->update(['name' => 'Ascension and Tristan da Cunha Saint Helena']);
        Country::whereRaw('name = ?', ['Sint Maarten'])->update(['name' => 'Sint Maarten (Dutch part)']);
        Country::whereRaw('name = ?', ['Syria'])->update(['name' => 'Syrian Arab Republic']);
        Country::whereRaw('name = ?', ['French Southern and Antarctic Lands'])->update(['name' => 'French Southern Territories']);
        Country::whereRaw('name = ?', ['Taiwan'])->update(['name' => 'Province of China Taiwan']);
        Country::whereRaw('name = ?', ['Tanzania'])->update(['name' => 'United Republic of Tanzania']);
        Country::whereRaw('name = ?', ['Vatican City'])->update(['name' => 'Holy See (Vatican City State)']);
        Country::whereRaw('name = ?', ['Venezuela'])->update(['name' => 'Bolivarian Republic of Venezuela']);
        Country::whereRaw('name = ?', ['British Virgin Islands'])->update(['name' => 'British Virgin Islands']);
        Country::whereRaw('name = ?', ['U.S. Virgin Islands'])->update(['name' => 'U.S. Virgin Islands']);
        Country::whereRaw('name = ?', ['Vietnam'])->update(['name' => 'Viet Nam']);
        Country::whereRaw('name = ?', ['Kosovo'])->delete();
        Country::whereRaw('name = ?', ['Czechia'])->update(['name' => 'Czech Republic']);

        foreach ($iso_codes as $code) {
            if (strpos($code['Name'], ',')) {
                $name_parts = explode(',', $code['Name']);
                $name = trim($name_parts[1]) . ' ' . trim($name_parts[0]);
            } else {
                $name = trim($code['Name']);
            }

            $country = Country::where('name', $name)->first();
            if ($country) {
                $country->iso = $code['Code'];
                $country->save();
            }
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('countries', function (Blueprint $table) {
            $table->dropColumn('iso');
        });
    }
}
