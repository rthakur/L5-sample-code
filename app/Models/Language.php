<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cache;

class Language extends Model
{
    public $timestamps = false;
    public static $languages = ['en','fr','de','sv','ar','no','es','ma','da','fi','hi','pt','ru','ja','uk','it'];

    public static function getAllLanguages()
    {
        if (Cache::has('all_languages'))
            return Cache::get('all_languages');

        $allLanguages = self::where('has_lang', '1')->orderBy('name')->get()->pluck('country_code', 'name') ?: [];

        Cache::put('all_languages', $allLanguages, 60 * 24);
        return $allLanguages;
    }

    public static function getAllCurrencies()
    {
        if (Cache::has('allCurrencies'))
            return Cache::get('allCurrencies');

        $allCurrencies = self::all();

        Cache::put('allCurrencies', $allCurrencies, 60 * 24);

        return $allCurrencies;
    }

    public static function getLangByCountryCode($countryCode)
    {
        if (Cache::has('langByCountryCode_' . $countryCode)) {
            $lang = Cache::get('langByCountryCode_' . $countryCode);
        } else {
            $lang = self::where('country_code', $countryCode)->first();
            Cache::put('langByCountryCode_' . $countryCode, $lang, 60 * 24);
        }

        return $lang ? $lang->name : '';
    }

    public static function getAllCurrenciesByCountryCode()
    {
        return self::groupBy('currency')->orderBy('currency')->get()->pluck('currency', 'country_code');
    }

    public static function getAllNoEnglish()
    {
        return self::where('country_code', '<>', 'en')->get();
    }

    public static function getTranslatable()
    {
        return self::whereIn('country_code', self::$languages)->where('google_translate_code', '<>', null)->where('google_translate_code', '<>', '')->where('has_lang', '1')->get();
    }

    public static function getTranslatableNoEnglish()
    {
        return self::where('google_translate_code', '<>', null)
            ->where('google_translate_code', '<>', '')
            ->where('country_code', '<>', 'en')
            ->where('has_lang', '1')
            ->get();
    }

}
