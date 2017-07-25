<?php

namespace App\Observers;

use App\Models\PropertyTexts;
use App\Models\Language;
use App\User;
use App, View;


class PropertyTextsObserver
{

    protected $languages;

    public function __construct()
    {
        $this->languages = Language::getTranslatable();
    }

    public function saving(PropertyTexts $prop_texts)
    {
        if ($prop_texts->isDirty() && !$prop_texts::$DISABLED_OBSERVER) {
            $prop_texts::$DISABLED_OBSERVER = true;
            $prop_modified = false;

            $prop = $prop_texts->property()->first();

            //check for new property, set correct source language
            if ($prop->translation_source == 'en' && $prop_texts->subject_en == null && $prop_texts->description_en == null) {
                // handle new property look for existing source
                $prop->translation_source = $this->lang_source_lookup($prop_texts);
                $prop_modified = true;
            }

            $updating = $prop_texts->getDirty();
            $source_subject_col = 'subject_' . $prop->translation_source;
            $source_description_col = 'description_' . $prop->translation_source;

            // handle source updated properties
            if (isset($updating[$source_subject_col]) || isset($updating[$source_description_col])) {
                $prop->translation_source_text_changed = 1;
                //$prop->translation_required = 0;
                $prop_modified = true;
            }

            //check for missing source column
            if ($prop_texts->$source_subject_col == null || $prop_texts->$source_subject_col == '' || $prop_texts->$source_description_col == null || $prop_texts->$source_description_col == '') {
                $prop->translation_source_text_changed = 1;
                // $prop->translation_required = 0;
                $prop->translation_source = $this->lang_source_lookup($prop_texts);
                $prop_modified = true;
            }

            //check for empty translations, not source, if source is still the same. Wait for agent action otherwise
            if ($prop->translation_source_text_changed != 1) {
                foreach ($this->languages as $lang) {
                    $subject_col = 'subject_' . $lang->country_code;
                    $description_col = 'description_' . $lang->country_code;
                    if (($prop_texts->$subject_col == null || $prop_texts->$subject_col == '' || $prop_texts->$description_col == null || $prop_texts->$description_col == '')
                        && $prop->translation_source != $lang->country_code
                    ) {
                        $prop->translation_required = 1;
                        $prop_modified = true;
                        break;
                    }
                }
            }

            if ($prop_modified) {
                $prop->save();
            }
            $prop_texts::$DISABLED_OBSERVER = false;
        }

        return;
    }

    private function lang_source_lookup($prop_texts)
    {
        foreach ($this->languages as $lang) {
            $subject_col = 'subject_' . $lang->country_code;
            $description_col = 'description_' . $lang->country_code;
            if ($prop_texts->$subject_col != null && $prop_texts->$subject_col != '' && $prop_texts->$description_col != null && $prop_texts->$description_col != '') {
                return $lang->country_code;
                break;
            }

        }
    }

}