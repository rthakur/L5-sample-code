<?php

use Illuminate\Database\Seeder;
use App\Models\Language;
use App\Models\Country;

class LanguagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('set foreign_key_checks = 0;');
        Language::truncate();

        $countries = Country::get();

        $translatable = ['en','bn','ro','fr', 'ar', 'de', 'no', 'es', 'ma', 'sv', 'da', 'fi', 'pt', 'ru', 'ja', 'it', 'uk', 'hi'];
        $exceptions = [
            'CN' => [
                'code' => 'ma',
                'name' => 'Mandarin',
                'google_translate_code' => 'zh-CN'
            ]
            
        ];

        foreach ($countries as $country) {
            if (isset($exceptions[$country->iso])) {

                $lang = Language::firstorNew([
                    'country_code' => $exceptions[$country->iso]['code']
                ]);

                $lang->name = $exceptions[$country->iso]['name'];
                $lang->google_translate_code = $exceptions[$country->iso]['google_translate_code'];
            } else {

                $result = file_get_contents('https://restcountries.eu/rest/v2/alpha/' . $country->iso);
                $result = json_decode($result);

                foreach ($result->languages as $l) {
                    if ($l->iso639_1 != null) {
                        $language = $l;
                        break;
                    }
                }

                $lang = Language::firstorNew([
                    'country_code' => $language->iso639_1
                ]);

                $lang->name = $language->name;
                $lang->google_translate_code = $language->iso639_1;
            }

            if (in_array($lang->country_code, $translatable)) {
                $lang->has_lang = 1;
            }

            $lang->save();

            $country->language_id = $lang->id;
            $country->save();
        }

    }
}
