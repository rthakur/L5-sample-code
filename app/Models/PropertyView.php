<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PropertyView extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $guarded = [];
    public $timestamps = false;

    public function view()
    {
        return $this->belongsTo('App\Models\View', 'view_id', 'id');
    }

}
