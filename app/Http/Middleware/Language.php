<?php

namespace App\Http\Middleware;

use Closure, App, Session;
use App\Models\Language as getLanguage;

class Language
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->segment(1) == 'cdn' || $request->segment(1) == 'json')
            return $next($request);

        $appLanguages = getLanguage::getAllLanguages()->toArray();
        $appLanguages = array_flip($appLanguages);

        //chek in cookie
        $cookieLang = $request->cookie('miparoLANG');

        if (isset($appLanguages[$cookieLang]))
            $locale = $cookieLang;
        else
            $locale = App::getLocale();

        if (empty($locale)) $locale = 'en';

        define('SITE_LANG', '/' . $locale);
        define('APP_LANG', $locale);

        //Change language
        if ($request->setlang && isset($appLanguages[$request->setlang])) {
            Session::forget("currency");
            return Redirect($this->createURL($request))->cookie('miparoLANG', $request->setlang, 60 * 24 * 365);
        }

        $ignorePaths = ['auth/google/callback', 'auth/facebook/callback'];

        //ignore localization
        if ($locale == 'api' || !$request->isMethod('get') || $request->ajax() || in_array($request->path(), $ignorePaths))
            return $next($request);

        if (isset($appLanguages[$locale]) && isset($appLanguages[$request->segment(1)]))
            return $next($request);

        //check for uppercase country code
        if (isset($appLanguages[strtolower($request->segment(1))])) {
            return Redirect($this->createURL($request));
        }

        //Added default language //Mikael W
        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) $browserLang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
        else $browserLang = "en";

        if (isset($appLanguages[$browserLang]) && empty($cookieLang))
            return Redirect($browserLang);


        if (isset($appLanguages[$locale]))
            return Redirect($locale);


        //default language
        return Redirect('/en');
    }


    private function createURL($request)
    {
        $url = explode('/', $request->path());
        $url[0] = ($request->setlang) ? $request->setlang : strtolower($request->segment(1));
        return implode('/', $url);
    }

}
