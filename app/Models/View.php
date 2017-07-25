<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class View extends Model
{
    protected $guarded = [];
    public $timestamps = false;

    public function langName()
    {
        return self::getLangName($this->name);
    }

    protected static function getLangName($langName)
    {
        $name = trans('viewproperty.' . str_replace(' ', '', $langName));
        $name = str_replace(["\'", '\"'], "'", $name);
        $name = str_replace(["->'", '->"'], "", $name);
        return $name;
    }
}
