<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PropertyImages extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $table = "property_images";
    
    public function property()
    {
        return $this->hasOne('App\Models\Property', 'id', 'property_id');
    }
}
