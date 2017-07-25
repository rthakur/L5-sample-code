<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Property;
use App\Models\Language;

class Property_translation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translate:property';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This Command has been made for automatic property texts translations to multiple languages.';

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

        $languages = Language::getTranslatableNoEnglish();
        $properties = Property::where('translation_required', 1)->inRandomOrder()->limit(50)->get();
        $cnt = 0;
		
        foreach ($properties as $prop) {
			
            $prop_texts = $prop->texts()->first();
            $prop_texts::$DISABLED_OBSERVER = true;
            //  echo '----------------------------------' . PHP_EOL;
            //  echo 'Processing property ' . $prop->id . PHP_EOL;

            $source_subject_col = 'subject_' . $prop->translation_source;
            $source_description_col = 'description_' . $prop->translation_source;

            //  echo 'Sources are: ' . $source_subject_col . ' and ' . $source_description_col . PHP_EOL;

            //check for english translation available, add if not
            if ($prop->translation_source != 'en'
                && (($prop_texts->subject_en == null || $prop_texts->subject_en == '' || $prop_texts->description_en == null || $prop_texts->description_en == '')
                    || $prop->translation_override_all == 1)
            ) {
                //  echo 'Sources are not English!' . PHP_EOL;
                //  echo 'Tranaslating ' . $prop->$source_subject_col . ' (' . $source_subject_col . ') to English' . PHP_EOL;
                $en_subj = self::translate($prop->translation_source, 'en', $prop_texts->$source_subject_col);
                //  print_r($en_subj);
                //  echo PHP_EOL;
                $prop_texts->subject_en = $en_subj;

                //  echo 'Tranaslating ' . $prop->$source_description_col . ' (' . $source_description_col . ') to English' . PHP_EOL;
                $en_desc = self::translate($prop->translation_source, 'en', $prop_texts->$source_description_col);
                //  print_r($en_desc);
                //  echo PHP_EOL;
                $prop_texts->description_en = $en_desc;
            }

            // translate all the rest from english
            foreach ($languages as $lang) {
				print ".";
                $subject_col = 'subject_' . $lang->country_code;
                $description_col = 'description_' . $lang->country_code;
                if ((($prop_texts->$subject_col == null || $prop_texts->$subject_col == '' || $prop_texts->$description_col == null || $prop_texts->$description_col == '')
                        || $prop->translation_override_all == 1) && $prop->translation_source != $lang->country_code
                ) {
                    //  echo 'Tranaslating ' . $prop->subject_en . ' (subject_en) to ' . $lang->name . PHP_EOL;
                    $subj = self::translate('en', $lang->google_translate_code, $prop_texts->subject_en);
                    //  print_r($subj);
                    //  echo PHP_EOL;
                    $prop_texts->$subject_col = $subj;

                    //  echo 'Tranaslating ' . $prop->description_en . ' (description_en) to ' . $lang->name . PHP_EOL;
                    $desc = self::translate('en', $lang->google_translate_code, $prop_texts->description_en);
                    //  print_r($desc);
                    //  echo PHP_EOL;
                    $prop_texts->$description_col = $desc;
                }
            }

            $prop_texts->save();

            $prop->translation_required = 0;
            $prop->agent_translation_notified = 0;
            $prop->translation_source_text_changed = 0;
            $prop->translation_override_all = 0;
            $prop->save();
			$cnt++;
			print "cnt: $cnt \n";
        }

        echo 'Done!' . PHP_EOL;
    }

    private static function translate($source, $target, $text)
    {

        // temporary unsupported langs here
        if ($target == null || $target == '') {
            return $text;
        }

        $apiKey = 'AIzaSyAEQXMgADE7welF--5HpLi4_r_UCzGtc4E';
        $url = 'https://translation.googleapis.com/language/translate/v2';
        $params = '?key=' . $apiKey . '&q=' . urlencode($text) . '&target=' . trim($target) . '&source=' . trim($source);

        $response = file_get_contents($url . $params);
        $result = json_decode($response, true);

        if (isset($result['error']) && !empty($result['error'])) {
            return $text;
        } else {
            return $result['data']['translations'][0]['translatedText'];
        }
    }

}
