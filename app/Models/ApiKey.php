<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiKey extends Model
{
  public $timestamps = false;
  protected $table = 'api_keys';
}
