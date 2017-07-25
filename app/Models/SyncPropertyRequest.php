<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class SyncPropertyRequest extends Model
{
    protected $guarded = [];
    
    public function updatedTime()
    {
        $updated = Carbon::createFromFormat('Y-m-d H:i:s', $this->updated_at);
        return $updated->diffForHumans(Carbon::now());
    }
    
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
}
