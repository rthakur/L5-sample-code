<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\Language;

class Geolocation_languages_import extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'localisation:geolocation_import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command should import geolocation data from google sheet.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $tables = ['countries', 'states', 'cities'];
        $languages = Language::getTranslatableNoEnglish();

        foreach ($tables as $t) {

            if ($t == 'countries') {
                $entities = Country::where('search_key', '<>', null)->get();
            } elseif ($t == 'states') {
                $entities = State::where('search_key', '<>', null)->get();
            } elseif ($t == 'cities') {
                $entities = City::where('search_key', '<>', null)->get();
            } else {
                continue;
            }

            foreach ($entities as $e) {
                foreach ($languages as $l) {
                    $column = 'name_' . $l['country_code'];
                    $e->$column = $e->lang_sk($e, $l['country_code']);
                }
                $e->save();
            }
        }
        echo 'Done!' . PHP_EOL;
        return;
    }

    /*public function handle()
    {
        $client = new Google_Client();
        putenv("GOOGLE_APPLICATION_CREDENTIALS=service-account-credentials.json");
        $application_creds = 'service-account-credentials.json';

        $credentials_file = file_exists($application_creds) ? $application_creds : false;
        if ($credentials_file) {
            // set the location manually
            $client->setAuthConfig($credentials_file);
        } elseif (getenv('GOOGLE_APPLICATION_CREDENTIALS')) {
            // use the application default credentials
            $client->useApplicationDefaultCredentials();
        } else {
            die('service account credentials are missing');
        }

        $client->setApplicationName("Import Geolocation From Translation Sheet");
        $client->setScopes(['https://spreadsheets.google.com/feeds']);

        $fileId = '1IJNJ1DaxbUzvfUI3iO_r_Tx0NPg1oJwDIjLecdBTuEY';

        $tokenArray = $client->fetchAccessTokenWithAssertion();
        $accessToken = $tokenArray["access_token"];

        $client->setAccessToken($accessToken);
        $service = new \Google_Service_Sheets($client);

        foreach (['countries', 'states', 'cities'] as $sheet) {

            $result = $service->spreadsheets_values->get($fileId, $sheet . "!B4:ZZ1000000");

            foreach ($result->values as $translation) {

                if (!empty($translation) && is_array($translation)) {
                    if ($sheet == 'countries') {
                        $entity = Country::whereRaw('id = ? or search_key = ? or name = ?', [$translation[0], $translation[1], $translation[1]])->first();
                        if (!$entity) {
                            $entity = new Country();
                        }
                    } elseif ($sheet == 'states') {
                        $entity = State::whereRaw('id = ? or search_key = ? or name = ?', [$translation[0], $translation[1], $translation[1]])->first();
                        if (!$entity) {
                            $entity = new State();
                            if (isset($translation[2]) && $translation[2] != NULL && $translation[2] != '') {
                                $country = Country::where('name', $translation[2])->first();
                                if (!$country) {
                                    continue;
                                } else {
                                    $entity->country_id = $country->id;
                                }
                            } else {
                                continue;
                            }
                        }
                    } elseif ($sheet == 'cities') {
                        $entity = City::whereRaw('id = ? or search_key = ? or name = ?', [$translation[0], $translation[1], $translation[1]])->first();
                        if (!$entity) {
                            $entity = new City();
                            if (isset($translation[2]) && $translation[2] != NULL && $translation[2] != '') {
                                $desc_parts = explode(',', $translation[2]);
                                $country = Country::where('name', trim($desc_parts[0]))->first();
                                $state = State::where('name', trim($desc_parts[1]))->first();
                                if (!$country || !$state) {
                                    continue;
                                } else {
                                    $entity->state_id = $state->id;
                                    $entity->country_id = $country->id;
                                }
                            } else {
                                continue;
                            }
                        }
                    } else {
                        continue;
                    }

                    if (isset($translation[1]) && $translation[1] != "") {
                        $entity->name = $translation[1];
                        $entity->search_key = $translation[1];
                    } else {
                        continue;
                    }
                    $entity->name_fr = (isset($translation[4]) && $translation[4] != "") ? $translation[4] : $entity->name_fr;
                    $entity->name_de = (isset($translation[5]) && $translation[5] != "") ? $translation[5] : $entity->name_de;
                    $entity->name_sv = (isset($translation[6]) && $translation[6] != "") ? $translation[6] : $entity->name_sv;
                    $entity->name_ar = (isset($translation[7]) && $translation[7] != "") ? $translation[7] : $entity->name_ar;
                    $entity->name_no = (isset($translation[8]) && $translation[8] != "") ? $translation[8] : $entity->name_no;
                    $entity->name_es = (isset($translation[9]) && $translation[9] != "") ? $translation[9] : $entity->name_es;
                    $entity->name_ma = (isset($translation[10]) && $translation[10] != "") ? $translation[10] : $entity->name_ma;
                    $entity->name_da = (isset($translation[11]) && $translation[11] != "") ? $translation[11] : $entity->name_da;
                    $entity->name_fi = (isset($translation[12]) && $translation[12] != "") ? $translation[12] : $entity->name_fi;
                    $entity->name_hi = (isset($translation[13]) && $translation[13] != "") ? $translation[13] : $entity->name_hi;
                    $entity->name_pt = (isset($translation[14]) && $translation[14] != "") ? $translation[14] : $entity->name_pt;
                    $entity->name_ru = (isset($translation[15]) && $translation[15] != "") ? $translation[15] : $entity->name_ru;
                    $entity->name_ja = (isset($translation[16]) && $translation[16] != "") ? $translation[16] : $entity->name_ja;
                    $entity->name_uk = (isset($translation[17]) && $translation[17] != "") ? $translation[17] : $entity->name_uk;
                    $entity->name_it = (isset($translation[18]) && $translation[18] != "") ? $translation[18] : $entity->name_it;
                    $entity->translation_requested = 1;
                    $entity->save();
                }
            }

            // look for new addition to the database and check if it already exists
            if ($sheet == 'countries') {
                $additions = Country::where('translation_requested', 0)->get();
            } elseif ($sheet == 'states') {
                $additions = State::where('translation_requested', 0)->get();
            } elseif ($sheet == 'cities') {
                $additions = City::where('translation_requested', 0)->get();
            } else {
                continue;
            }

            if (count($additions)) {
                foreach ($additions as $a) {
                    if ($sheet == 'countries') {
                        $query = Country::where('id', '<>', $a->id);
                        $column = 'country_id';
                    } elseif ($sheet == 'states') {
                        $query = State::where('id', '<>', $a->id);
                        $column = 'state_id';
                    } elseif ($sheet == 'cities') {
                        $query = City::where('id', '<>', $a->id);
                        $column = 'city_id';
                    } else {
                        continue;
                    }

                    $check = $query->orWhereRaw('(name = ? or name_fr = ? or name_de = ? or name_sv = ? or name_ar = ? or name_no = ? or name_es = ? 
                     or name_ma = ? or name_da = ? or name_fi = ? or name_hi = ? or name_pt = ? or name_ru = ? or name_ja = ? or name_uk = ? or name_it = ?)',
                        [$a->search_key,$a->search_key,$a->search_key,$a->search_key,$a->search_key,$a->search_key,$a->search_key,$a->search_key,$a->search_key,
                            $a->search_key,$a->search_key,$a->search_key,$a->search_key,$a->search_key,$a->search_key,$a->search_key])->first();
                    // update all relations if duplication been found
                    if ($check) {
                        $props = Property::where($column, $a->id)->get();
                        foreach ($props as $p) {
                            $p->$column = $check->id;
                            $p->save();
                        }
                        $agencies = Estateagency::where($column, $a->id)->get();
                        foreach ($agencies as $a) {
                            $a->$column = $check->id;
                            $a->save();
                        }

                        if ($sheet == 'countries') {
                            $states = State::where($column, $a->id)->get();
                            foreach ($states as $s) {
                                $s->country_id = $check->id;
                                $s->save();
                            }
                            $cities = City::where($column, $a->id)->get();
                            foreach ($cities as $c) {
                                $c->country_id = $check->id;
                                $c->save();
                            }
                        } elseif ($sheet == 'states') {
                            $cities = City::where($column, $a->id)->get();
                            foreach ($cities as $c) {
                                $c->state_id = $check->id;
                                $c->save();
                            }
                        }

                        if ($sheet == 'countries') {
                            Country::where('id', $a->id)->delete();
                        } elseif ($sheet == 'states') {
                            State::where('id', $a->id)->delete();
                        } elseif ($sheet == 'cities') {
                            City::where('id', $a->id)->delete();
                        } else {
                            continue;
                        }


                    }

                }
            }

            if ($sheet == 'countries') {
                $entities = Country::get();
            } elseif ($sheet == 'states') {
                $entities = State::get();
            } elseif ($sheet == 'cities') {
                $entities = City::get();
            } else {
                continue;
            }

            foreach ($entities as $e) {
                $e->translation_requested = 0;
                $e->save();
            }
        }


        echo 'Done!' . PHP_EOL;

        return true;
    }*/
}
