<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PropertyFeatures extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $table = 'property_features';
    public $timestamps = false;
    protected $guarded = [];
    
    public function feature()
    {
      return $this->belongsTo('App\Models\Features', 'feature_id', 'id');
    }
    
}
