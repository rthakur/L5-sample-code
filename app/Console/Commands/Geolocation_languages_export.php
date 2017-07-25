<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\Language;

class Geolocation_languages_export extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'localisation:geolocation_export';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command should export geolocation data to google sheet.';

    /**
     * Create a new command instance.
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
        $languages = Language::getTranslatable();

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


            foreach ($languages as $lang) {
                $lang_path = app()->resourcePath() . '/lang/' . $lang->country_code;
                $column = 'name_' . $lang->country_code;

                if (!is_dir($lang_path)) {
                    mkdir($lang_path);
                }

                if (!is_file($lang_path . '/' . $t . '.php')) {
                    touch($lang_path . '/' . $t . '.php');
                }

                $file_content = "<?php" . PHP_EOL . PHP_EOL;
                $file_content .= "return [" . PHP_EOL;

                foreach ($entities as $e) {

                    $translation = ($e->$column != null && $e->$column != '') ? $e->$column : $e->name_en;

                    $file_content .= '"' . $e->search_key . '" => "' . $translation . '",' . PHP_EOL;
                }

                $file_content .= '];';

                file_put_contents($lang_path . '/' . $t . '.php', $file_content);
            }

        }

        echo 'Done!' . PHP_EOL;
        return;

    }


}

/* public function handle()
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

     $client->setApplicationName("Export Geolocation to Translation Sheet");
     $client->setScopes(['https://spreadsheets.google.com/feeds']);

     $fileId = '1D2X6omXKjLB2cT3Tcl35D-DXlzGXaChwg_J3HfJtsk8';

     $tokenArray = $client->fetchAccessTokenWithAssertion();
     $accessToken = $tokenArray["access_token"];

     $client->setAccessToken($accessToken);
     $service = new \Google_Service_Sheets($client);

     $test_env = env('USE_TEST_LOCATIONS_TRANSLATION_SHEETS');

     if ($test_env) {
         $sheets = ['countries_test', 'states_test', 'cities_test'];
     } else {
         $sheets = ['countries', 'states', 'cities'];
     }


     foreach ($sheets as $sheet) {
         $get_country = false;
         $get_state = false;
         $count_to_add = 0;
         $data = [];

         if ($sheet == 'countries' || $sheet == 'countries_test') {
             $entities = Country::where('search_key', '<>', null)->where('confirmed', 1)->get();
         } elseif ($sheet == 'states' || $sheet == 'states_test') {
             $entities = State::where('search_key', '<>', null)->where('confirmed', 1)->get();
             $get_country = true;
         } elseif ($sheet == 'cities' || $sheet == 'cities_test') {
             $entities = City::where('search_key', '<>', null)->where('confirmed', 1)->get();
             $get_country = true;
             $get_state = true;
         } else {
             continue;
         }

         $search_keys = $service->spreadsheets_values->get($fileId, $sheet . "!B4:B2000");
         $existing = [];
         foreach ($search_keys->values as $search_key) {
             $existing[] = $search_key[0];
         }

         if (count($entities)) {
             foreach ($entities as $k => $entity) {
                 // check if entity is new

                 if (in_array($entity->search_key, $existing)) {
                     // skip already existing entities
                     continue;
                 }

                 // add new data to sheet
                 $description = '';
                 if ($get_country) {
                     $country = Country::where('id', $entity->country_id)->first();
                     if ($country) {
                         $description .= $country->name;
                     } else {
                         continue;
                     }
                 }
                 if ($get_state) {
                     $state = State::where('id', $entity->state_id)->first();
                     if ($state) {
                         $description .= ', ' . $state->name;
                     } else {
                         continue;
                     }
                 }

                 $data[] = new \Google_Service_Sheets_ValueRange([
                     'range' => $sheet . "!B" . (count($existing) + $count_to_add + 4) . ":D" . (count($existing) + $k + 4),
                     'values' => [[$entity->search_key, $entity->name, $description]]
                 ]);

                 $data[] = new \Google_Service_Sheets_ValueRange([
                     "range" => $sheet . "!F" . (count($existing) + $count_to_add + 4) . ":T" . (count($existing) + $k + 4),
                     "values" => [[
                         $entity::lang_sk($entity, 'fr'),
                         $entity::lang_sk($entity, 'de'),
                         $entity::lang_sk($entity, 'sv'),
                         $entity::lang_sk($entity, 'ar'),
                         $entity::lang_sk($entity, 'no'),
                         $entity::lang_sk($entity, 'es'),
                         $entity::lang_sk($entity, 'ma'),
                         $entity::lang_sk($entity, 'da'),
                         $entity::lang_sk($entity, 'fi'),
                         $entity::lang_sk($entity, 'hi'),
                         $entity::lang_sk($entity, 'pt'),
                         $entity::lang_sk($entity, 'ru'),
                         $entity::lang_sk($entity, 'ja'),
                         $entity::lang_sk($entity, 'uk'),
                         $entity::lang_sk($entity, 'it'),
                     ]]
                 ]);
                 $count_to_add++;
             }
         }

         echo $sheet . ' to be add ' . (count($data) / 2) . ' search_keys' . PHP_EOL;

         if (count($data)) {
             $body = new \Google_Service_Sheets_BatchUpdateValuesRequest(array(
                 'valueInputOption' => "USER_ENTERED",
                 'data' => $data
             ));
             $result = $service->spreadsheets_values->batchUpdate($fileId, $body);
         }

     }

     echo PHP_EOL;
     echo 'Done!';
     echo PHP_EOL;


     return true;
 }
*/

