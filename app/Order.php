<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
  protected $fillable = ['user_id', 'billing_email', 'billing_name', 'billing_address',
                        'billing_city', 'billing_state', 'billing_zipcode', 'billing_phone',
                        'billing_name_on_card', 'billing_discount','billing_discount_code',
                        'billing_subtotal','billing_tax','billing_total','error', 'payment_gateway'];
                        
    public function user()
    {
      return $this->belongsTo('App\User');
    }

    public function products()
    {
      return $this->belongsToMany('App\Product')->withPivot('quantity');
    }
}
