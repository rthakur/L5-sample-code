<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiErrorReporting extends Model
{
    protected $table = 'api_error_reporting';
    public $timestamps = true;

    public function agency()
    {
        $this->hasOne('App\Models\Estateagency', 'id', 'agency_id');
    }
}
