<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
  protected $guarded = [];

  public function getField($field)
  {
    if($this->$field == '-1')
      return 'Unlimited';
    return $this->$field;
  }

  public function getFieldHTML($field)
  {
      if ($this->$field == 1) {
          return '<td class="available"><i class="fa fa-check"></i></td>';
      }

      if ($this->$field == 0) {
          return '<td class="not-available"> -- </td>';
      }

      if ($this->$field == -1) {
          return '<td class="available"><i class="fa fa-check"></i></td>';
      }

      if ($this->$field == -2) {
          return '<td class="plan-option">'. trans('common.Optional') .'</td>';
      }

  }

}
