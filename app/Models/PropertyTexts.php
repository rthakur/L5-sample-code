<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Helpers\CommonHelper;
use App;

class PropertyTexts extends Model
{
    use SoftDeletes;

    public static $DISABLED_OBSERVER = false;
    protected $guarded = [];
    protected $dates = ['deleted_at'];
    public $table = 'property_texts';
    public $timestamps = true;


    public function property()
    {
        return $this->hasOne('App\Models\Property', 'id', 'property_id');
    }

    public function langSubject()
    {
      $lang = App::getLocale();
      $col = 'subject_' . ($this->{'subject_' . $lang} ? $lang : 'en');
      return CommonHelper::filterString($this->$col);
    }
    
    public function langDescription()
    {
        $lang = App::getLocale();
        $col = 'description_' . ($this->{'description_' . $lang} ? $lang : 'en');
        return CommonHelper::filterString($this->$col);
    }
    
    public function getLangSubject($lang)
    {
      $col = 'subject_' . $lang;
      return $this->$col ? CommonHelper::filterString($this->$col) : '';
    }
    
    public function getLangDescription($lang)
    {
        $col = 'description_' . $lang;
        return $this->$col ? CommonHelper::filterString($this->$col) : '';
    }
    
    public function checkTextExists($column, $siteLang = false)
    {
        if ($siteLang && !empty($this->{$column. $siteLang})) {
            return $siteLang;
        }
        
        foreach(Language::$languages as $lang) {
            if (!empty($this->{$column. $lang})) {
                return $lang;
            }
        }
        
        return false;
    }

}
