<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
   /*
      - get coupon code from the front-end and find it from the database

   */

    public static function findByCode($code)
    {
      return self::where('code', $code)->first();
    }

    public function discount($total)
    {
      if($this->type == 'fixed')
      {
        return $this->value;
      }
      elseif($this->type == 'percent') {
        return round(($this->percent_off / 100) * $total);
      }
      else
      {
        return 0;
      }
    }
}
