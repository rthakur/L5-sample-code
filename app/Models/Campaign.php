<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Campaign extends Model
{
    use SoftDeletes;
    
    protected $dates = ['deleted_at'];
    
    protected $guarded = [];
    
    public function checkCampaignExpiry($user)
    {
        $createdAt = Carbon::createFromFormat('Y-m-d H:i:s', $user->agency->created_at);
        
        //adds trial months to the account created date
        $createdAt = $createdAt->addMonths($this->trial_months);
        
        if (Carbon::now()->lte($createdAt)) {
            return false;
        }
        
        return true;
    }
}
