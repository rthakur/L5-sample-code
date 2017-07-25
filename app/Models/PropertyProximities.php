<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PropertyProximities extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = "property_proximity";
    public $timestamps = false;
    protected $guarded = [];

    public function proximity()
    {
        return $this->belongsTo('App\Models\Proximities', 'proximity_id', 'id');
    }
    
}
