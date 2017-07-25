<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyServices extends Model
{
    protected $table = "property_services";
    public $timestamps = false;
    protected $guarded = [];

    public function service()
    {
        return $this->belongsTo('App\Models\Services', 'service_id', 'id');
    }
}
