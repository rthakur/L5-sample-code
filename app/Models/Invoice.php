<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class Invoice extends Model
{
    public $timestamps = false;
    
    protected $guarded = [];
    
    public function getFormattedDate()
    {
      $carbonDate = Carbon::createFromTimestamp($this->period_start);
      return $carbonDate->format('Y-m-d');
    }
    
    public function getEndDate()
    {
      $carbonDate = Carbon::createFromTimestamp($this->period_start);
      $carbonAddMonth = $carbonDate->copy()->addMonth();
      return $carbonAddMonth->format('Y-m-d');
    }
    
    public function getPaymentPeriod()
    {
      $carbonDate = Carbon::createFromTimestamp($this->period_start);
      $carbonAddMonth = $carbonDate->copy()->addMonth();
      return $carbonDate->format('Y-m-d') .' to '. $carbonAddMonth->format('Y-m-d');
    }

}
